<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Service;
use App\Models\Wallet;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnrollmentController extends Controller
{
    protected $transactionService;

    protected $loginId;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        $this->loginId = auth()->user()->id;
    }

    public function bvnEnrollment()
    {
        $serviceCodes = ['110'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        // Extract specific service fees
        $ServiceFee = $services->get('110') ?? 0.00;

        $enrollments = Enrollment::where('user_id', $this->loginId)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('enrollments.bvn-enrollment', compact('ServiceFee', 'enrollments'));
    }

     public function enrollBVN(Request $request)
    {

        $data = $request->validate([
            'phone' => 'required|numeric|digits:11|unique:bvn_enrollments,phone_number',
            'agent_location' => 'required|string',
            'agent_bvn' => 'required|digits:11',
            'kegow_account' => 'required|numeric|digits:10',
            'account_name' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:bvn_enrollments,email',
            'dob' => 'required|date',
            'address' => 'required|string',
            'lga' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'geo_zone' => 'required|string',
            'username' => 'nullable|string|max:255',
        ]);

        // NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '110')
            ->where('status', 'enabled')
            ->first();

        if (! $ServiceFee) {
            return redirect()->route('user.bvn-enrollment')
                ->with('error', 'Service Error: Sorry Action not Allowed !');
        }

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        // Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return redirect()->route('user.bvn-enrollment')
                ->with('error', 'Wallet Error: Sorry Wallet Not Sufficient for Transaction !');
        } else {
            $responseurl = env('RESPONSE_URL');
            $submitData = [
                'fullname' => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'agent_location' => $request->agent_location,
                'agent_bvn' => $request->agent_bvn,
                'kegow_account' => $request->kegow_account,
                'account_name' => $request->account_name,
                'dob' => $request->dob,
                'geo_zone' => $request->geo_zone,
                'state' => $request->state,
                'lga' => $request->lga,
                'address' => $request->address,
                'city' => $request->city,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'username' => $request->username,
                'url' => $responseurl,
            ];

            try {


                $balance = $wallet->balance - $ServiceFee;

                Wallet::where('user_id', $loginUserId)
                    ->update(['balance' => $balance]);

                $serviceDesc = 'Wallet debitted with a service fee of ₦'.number_format($ServiceFee, 2);

                $trx_id = $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'BVN User Request', $serviceDesc, 'Wallet', 'Approved');

                // save the data
                $this->saveEnrollmentRecord($submitData, $trx_id);

                return redirect()->route('user.bvn-enrollment')
                    ->with('success', 'BVN user request successfully submitted');

            } catch (\Exception $e) {
                return redirect()->route('user.bvn-enrollment')
                    ->with('error', 'An error occurred while making the API request');
            }
        }
    }

    public function saveEnrollmentRecord($data, $trx_id)
    {
        try {
            // Create a new enrollment record
            $enrollment = new Enrollment;
            $enrollment->user_id = auth()->user()->id;
            $enrollment->refno = $this->transactionService->generateReferenceNumber();
            $enrollment->agent_location = $data['agent_location'];
            $enrollment->first_name = $data['first_name'];
            $enrollment->last_name = $data['last_name'];
            $enrollment->fullname = $data['fullname'];
            $enrollment->state = $data['state'];
            $enrollment->lga = $data['lga'];
            $enrollment->address = $data['address'];
            $enrollment->city = $data['city'];
            $enrollment->geo_zone = $data['geo_zone'];
            $enrollment->bvn = $data['agent_bvn'];
            $enrollment->kegow_account = $data['kegow_account'];
            $enrollment->account_number = $data['kegow_account']; // fallback
            $enrollment->account_name = $data['account_name'];
            $enrollment->email = $data['email'];
            $enrollment->dob = $data['dob'];
            $enrollment->phone_number = $data['phone_number'];
            $enrollment->username = $data['username'];
            $enrollment->tnx_id = $trx_id->id;

            // Save the record
            $enrollment->save();
        } catch (\Exception $e) {
            Log::error('Failed to save enrollment record: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to save enrollment record',
                'error' => $e->getMessage(),
            ];
        }
    }


    public function index(Request $request)
    {

        // Services
        $pending = Enrollment::whereIn('status', ['submitted', 'processing'])
            ->count();

        $resolved = Enrollment::where('status', 'successful')
            ->count();

        $rejected = Enrollment::where('status', 'rejected')
            ->count();

        $total_request = Enrollment::count();

        $query = Enrollment::with(['user', 'transactions']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%")
                    ->orWhere('bvn', 'like', "%{$searchTerm}%")
                    ->orWhere('fullname', 'like', "%{$searchTerm}%")
                    ->orWhere('status', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Check if date_from and date_to are provided and filter accordingly
        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $enrollmentList = $query
            ->orderByRaw("
                CASE
                    WHEN status = 'submitted' THEN 1
                    WHEN status = 'processing' THEN 2
                    ELSE 3
                END
            ")
            ->orderByDesc('id')
            ->paginate(10);

        $request_type = 'enrollment';

        return view('admin.enrollment-list', compact(
            'pending',
            'resolved',
            'rejected',
            'total_request',
            'enrollmentList',
            'request_type'
        ));
    }

    public function showRequests($request_id, $type, $requests = null)
    {

        switch ($type) {
            case 'enrollment':
                $requests = Enrollment::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'enrollment';
                break;

            default:
                $requests = Enrollment::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'enrollment';
        }

        if (strtolower($requests->status) == 'rejected') {
            abort(404, 'Kindly Submit a new request');
        }

        return view(
            'admin.view-request2',
            compact(
                'requests',
                'request_type'
            )
        );
    }

    public function updateRequestStatus(Request $request, $id, $type)
    {
        $request->validate([
            'status' => 'required|string',
            'comment' => 'required|string',
        ]);

        $requestDetails = Enrollment::findOrFail($id);
        $route = 'admin.enroll.index';
        $status = $request->status;

        $requestDetails->status = $status;
        $requestDetails->reason = $request->comment;

        if ($request->status === 'rejected') {

            $requestDetails->refunded_at = Carbon::now();

            $refundAmount = $request->refundAmount;

            $wallet = Wallet::where('user_id', $requestDetails->user_id)->first();

            $balance = $wallet->balance + $refundAmount;

            Wallet::where('user_id', $requestDetails->user_id)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet credited with a Request fee of ₦'.number_format($refundAmount, 2);

            $this->transactionService->createTransaction($requestDetails->user_id, $refundAmount, 'BVN User Request Refund', $serviceDesc, 'Wallet', 'Approved');
        }

        $requestDetails->save();

        return redirect()->route($route)->with('success', 'Request status updated successfully.');
    }
}
