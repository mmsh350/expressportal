<?php

namespace App\Http\Controllers;

use App\Exports\IpeTemplateExport;
use App\Models\IpeRequest;
use App\Models\Wallet;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class IpeController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function ipeIndex(Request $request)
    {
        return $this->ipeIndexBase($request, null, 'admin.ipe-index');
    }

    public function modificationIpeIndex(Request $request)
    {
        return $this->ipeIndexBase($request, 'MODIFICATION', 'admin.modification-ipe-index');
    }

    private function ipeIndexBase(Request $request, $tag, $view)
    {
        $countsQuery = IpeRequest::where('tag', $tag);
        if (is_null($tag)) {
            $countsQuery = IpeRequest::whereNull('tag');
        }

        $counts = $countsQuery->selectRaw("
        COUNT(*) as total_request,
        SUM(CASE WHEN resp_code = '100' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN resp_code = '101' THEN 1 ELSE 0 END) as processing,
        SUM(CASE WHEN resp_code = '200' THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN resp_code = '400' THEN 1 ELSE 0 END) as rejected")
            ->first();

        $pending = $counts->pending ?? 0;
        $processing = $counts->processing ?? 0;
        $resolved = $counts->resolved ?? 0;
        $rejected = $counts->rejected ?? 0;
        $total_request = $counts->total_request ?? 0;

        // Filters
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $ipeRequestsQuery = IpeRequest::where('tag', $tag);
        if (is_null($tag)) {
            $ipeRequestsQuery = IpeRequest::whereNull('tag');
        }

        if ($search) {
            $ipeRequestsQuery->where(function ($query) use ($search) {
                $query->where('trackingid', 'like', "%{$search}%");
            });
        }

        if ($dateFrom) {
            $ipeRequestsQuery->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $ipeRequestsQuery->whereDate('created_at', '<=', $dateTo);
        }

        $ipeRequestsQuery->orderByRaw("
        CASE resp_code
            WHEN '100' THEN 0
            WHEN '101' THEN 1
            WHEN '200' THEN 2
            WHEN '400' THEN 3
            ELSE 4
        END")->orderByDesc('created_at');

        $ipeRequests = $ipeRequestsQuery->with(['transaction', 'user'])->select(
            'id',
            'user_id',
            'tnx_id',
            'trackingId',
            'resp_code',
            'reply',
            'status',
            'created_at',
            'updated_at'
        )->paginate($perPage)->withQueryString();

        $refund_count_query = IpeRequest::where('resp_code', '400')
            ->whereNull('refunded_at')
            ->where('tag', $tag);
        
        if (is_null($tag)) {
            $refund_count_query = IpeRequest::where('resp_code', '400')
                ->whereNull('refunded_at')
                ->whereNull('tag');
        }

        $refund_count = $refund_count_query->count();

        return view($view, compact(
            'pending',
            'processing',
            'resolved',
            'rejected',
            'total_request',
            'ipeRequests',
            'refund_count'
        ));
    }

    public function downloadTemplateIPE()
    {
        return $this->downloadTemplateBase(null);
    }

    public function downloadTemplateModificationIPE()
    {
        return $this->downloadTemplateBase('MODIFICATION');
    }

    private function downloadTemplateBase($tag)
    {
        $query = IpeRequest::whereIn('resp_code', ['100', '101'])
            ->where('tag', $tag);
        
        if (is_null($tag)) {
            $query = IpeRequest::whereIn('resp_code', ['100', '101'])
                ->whereNull('tag');
        }

        $records = $query->select('id', 'trackingId', 'resp_code', 'reply')
            ->get();

        if ($records->isEmpty()) {
            return back()->with('error', 'No pending records to export.');
        }

        $ids = $records->pluck('id')->toArray();

        IpeRequest::whereIn('id', $ids)
            ->update(['resp_code' => '101']);

        $filename = ($tag ? strtolower($tag).'_' : '').'ipe_requests_pending_'.now()->format('Y_m_d_His').'.xlsx';

        return Excel::download(
            new IpeTemplateExport($records),
            $filename
        );
    }

    public function uploadExcelIPE(Request $request)
    {
        return $this->uploadExcelBase($request, null);
    }

    public function uploadExcelModificationIPE(Request $request)
    {
        return $this->uploadExcelBase($request, 'MODIFICATION');
    }

    private function uploadExcelBase(Request $request, $tag)
    {
        try {
            // Validate uploaded file
            $validator = Validator::make($request->all(), [
                'excel_file' => 'required|file|mimes:xlsx,xls',
            ]);

            if ($validator->fails()) {
                return back()->with('error', 'The file field is required and must be an Excel file.');
            }

            $data = Excel::toArray([], $request->file('excel_file'))[0];

            if (count($data) < 2) {
                return back()->with('error', 'The uploaded file is empty or has no valid data.');
            }

            $header = array_map('strtolower', $data[0]);

            if (! in_array('tracking_id', $header) || ! in_array('resp_code', $header) || ! in_array('reply', $header)) {
                return back()->with('error', 'Invalid file format. Required headers: tracking_id, resp_code, reply.');
            }

            $successCount = 0;
            $failedRows = [];

            // Process each row
            for ($i = 1; $i < count($data); $i++) {
                $row = array_combine($header, $data[$i]);

                $trackingId = trim($row['tracking_id'] ?? '');
                $respCode = trim((string) ($row['resp_code'] ?? ''));
                $reply = trim($row['reply'] ?? '');

                $rowNumber = $i + 1;

                // Validation
                if (! $trackingId || ! $respCode || ! $reply) {
                    $failedRows[] = "Row $rowNumber: Missing tracking_id, resp_code or reply.";

                    continue;
                }

                if (! in_array($respCode, ['200', '400'])) {
                    $failedRows[] = "Row $rowNumber: Invalid resp_code '$respCode'. Only 200 and 400 are allowed.";

                    continue;
                }

                $respCode == '200' ? $st = 'successful' : $st = 'failed';
                
                $updateQuery = IpeRequest::where('trackingId', $trackingId)
                    ->where('tag', $tag)
                    ->where('resp_code', '101');
                
                if (is_null($tag)) {
                    $updateQuery = IpeRequest::where('trackingId', $trackingId)
                        ->whereNull('tag')
                        ->where('resp_code', '101');
                }

                // Perform update
                $updated = $updateQuery->update([
                        'resp_code' => $respCode,
                        'reply' => $reply,
                        'status' => $st,
                        'updated_at' => Carbon::now(),
                    ]);

                if ($updated) {
                    $successCount++;
                } else {
                    $failedRows[] = "Row $rowNumber: Tracking ID '$trackingId' not found in the database.";
                }
            }

            // Prepare response message
            $message = "$successCount rows updated successfully.";
            if (count($failedRows)) {
                $message .= ' Some rows failed: <br><ul>';
                foreach ($failedRows as $error) {
                    $message .= "<li>$error</li>";
                }
                $message .= '</ul>';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Excel upload error: '.$e->getMessage());

            return back()->with('error', 'An error occurred while processing the file: '.$e->getMessage());
        }
    }

    public function refundFailedTransactions()
    {
        return $this->refundFailedBase(null);
    }

    public function refundFailedModificationTransactions()
    {
        return $this->refundFailedBase('MODIFICATION');
    }

    private function refundFailedBase($tag)
    {
        $query = IpeRequest::where('resp_code', '400')
            ->whereNull('refunded_at')
            ->where('tag', $tag);
        
        if (is_null($tag)) {
            $query = IpeRequest::where('resp_code', '400')
                ->whereNull('refunded_at')
                ->whereNull('tag');
        }

        $failedRequests = $query->get();

        $refunded = 0;
        foreach ($failedRequests as $request) {

            $success = $this->processRefund($request);

            if ($success) {
                $refunded++;
            }
        }

        return back()->with('success', "Refunded {$refunded} transaction(s).");
    }
    private function processRefund($ipeRequest, $refundAmount = null): bool
    {
        try {
            $userId = $ipeRequest->user_id;

            if ($ipeRequest->resp_code == '400') {
                $ipeRequest->refunded_at = Carbon::now();
                $amount = $refundAmount ?? ($ipeRequest->transaction->amount ?? 0);

                DB::transaction(function () use ($userId, $ipeRequest, $amount) {
                    Wallet::where('user_id', $userId)
                        ->increment('balance', $amount);

                    $ipeRequest->update([
                        'refunded_at' => now(),
                    ]);

                    $this->transactionService->createTransaction(
                        $userId,
                        $amount,
                        'IPE Refund',
                        "IPE refund for Tracking ID: {$ipeRequest->trackingId}",
                        'Wallet',
                        'Approved'
                    );
                });
            }

            Log::info("Refund processed for Tracking ID: {$ipeRequest->trackingId} - USER ID: {$userId}");

            return true;
        } catch (\Exception $e) {
            Log::error("Refund failed for IPE Request {$ipeRequest->id}: " . $e->getMessage());
            return false;
        }
    }

    public function showIpeRequest($id)
    {
        $requests = IpeRequest::with(['transaction', 'user'])->findOrFail($id);
        return view('admin.view-ipe-request', [
            'requests' => $requests,
            'request_type' => 'IPE Services'
        ]);
    }

    public function showModificationIpeRequest($id)
    {
        $requests = IpeRequest::with(['transaction', 'user'])->findOrFail($id);
        return view('admin.view-modification-ipe-request', [
            'requests' => $requests,
            'request_type' => 'Modification IPE Services'
        ]);
    }

    public function updateIpeStatus(Request $request, $id)
    {
        return $this->updateIpeStatusBase($request, $id, 'admin.ipe.index');
    }

    public function updateModificationIpeStatus(Request $request, $id)
    {
        return $this->updateIpeStatusBase($request, $id, 'admin.modification.ipe.index');
    }

    private function updateIpeStatusBase(Request $request, $id, $redirectRoute)
    {
        $refundKey = $request->has('refundAmount') ? 'refundAmount' : 'refund_amount';

        $request->validate([
            'status' => 'required|in:200,400,100,101',
            'comment' => 'required|string',
            $refundKey => 'required_if:status,400|nullable|numeric|min:0',
        ], [
            $refundKey . '.required_if' => 'The refund amount is required when the status is Failed.',
        ]);

        $ipeRequest = IpeRequest::findOrFail($id);
        $oldStatus = $ipeRequest->resp_code;
        $newStatus = $request->status;

        $ipeRequest->resp_code = $newStatus;
        $ipeRequest->reply = $request->comment;
        $ipeRequest->status = ($newStatus == '200') ? 'successful' : (($newStatus == '400') ? 'failed' : 'pending');
        $ipeRequest->save();

        // Handle refund if rejected (resp_code 400) and not already refunded
        if ($newStatus == '400' && $oldStatus != '400' && is_null($ipeRequest->refunded_at)) {
            $amountToRefund = $request->input($refundKey);
            $this->processRefund($ipeRequest, $amountToRefund);
        }

        if ($newStatus == '400') {
            return redirect()->route($redirectRoute)->with('success', 'Status updated to Failed and refund processed successfully.');
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
