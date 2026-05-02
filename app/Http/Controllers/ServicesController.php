<?php

namespace App\Http\Controllers;

use App\Models\BvnPhoneSearch;
use App\Models\NinValidation;
use App\Models\Service;
use App\Models\Wallet;
use App\Services\TransactionService;
use Carbon\Carbon;
use App\Models\SiteSetting;
use App\Models\NinModification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    protected $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }


    public function userCheck()
    {

        if (auth()->user()->role != 'super_admin')
            abort(403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->userCheck();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Service::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $services = $query->paginate($perPage)->withQueryString();

        return view('services.index', compact('services'));
    }

    public function edit($id)
    {
        $this->userCheck();

        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $this->userCheck();
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable',
            'status' => 'required|in:enabled,disabled',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());
        return redirect()->route('admin.services.index')->with('success', 'Service Updated Successfully!');
    }

    public function ninServicesList(Request $request)
    {

       // Services
        $pending = NinValidation::whereIn('status', ['Pending', 'In-Progress'])->whereNull('tag')
            ->count();

        $resolved = NinValidation::where('status', 'Successful')->whereNull('tag')
            ->count();

        $rejected = NinValidation::where('status', 'Failed')->whereNull('tag')
            ->count();

        $total_request = NinValidation::whereNull('tag')->count();

        $query = NinValidation::with(['user', 'transactions'])->whereNull('tag');

        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%")
                    ->orWhere('nin_number', 'like', "%{$searchTerm}%")
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

        $nin_services = $query
            ->orderByRaw("
                CASE
                    WHEN status = 'Pending' THEN 1
                    WHEN status = 'In-Progress' THEN 2
                    ELSE 3
                END
            ")
            ->orderByDesc('id')
            ->paginate(10);


        $request_type = 'nin-services';

        return view('admin.nin-services-list', compact(
            'pending',
            'resolved',
            'rejected',
            'total_request',
            'nin_services',
            'request_type'
        ));
    }

    public function showRequests($request_id, $type, $requests = null)
    {

        switch ($type) {
            case 'bvn-enrollment':

                break;
            case 'bvn-modification':

                break;
            case 'upgrade':

                break;

            case 'nin-services':
                $requests = NinValidation::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
                break;

            case 'delink-services':
                $requests = NinValidation::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'delink-services';
                break;

            case 'email-retrive-services':
                $requests = NinValidation::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'email-retrive-services';
                break;

            case 'vnin-to-nibss':

                break;

            default:
                $requests = NinValidation::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
        }

       if (strtolower($requests->status) == 'Failed') {
            abort(404, 'Kindly Submit a new request');
        }

        return view(
            'admin.view-request',
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

        $requestDetails = NinValidation::findOrFail($id);
        $route = 'admin.nin.services.list';

        if ($type === 'delink-services') {
            $route = 'admin.delink.services.list';
        }

        if ($type === 'email-retrive-services') {
            $route = 'admin.email.retrive.list';
        }

        $status = $request->status;

        $requestDetails->status = $status;
        $requestDetails->reason = $request->comment;


        if ($request->status === 'Failed') {

            $requestDetails->refunded_at = Carbon::now();

            $refundAmount = $request->refundAmount;

            $wallet = Wallet::where('user_id', $requestDetails->user_id)->first();

            $balance = $wallet->balance + $refundAmount;

            Wallet::where('user_id', $requestDetails->user_id)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet credited with a Request fee of ₦' . number_format($refundAmount, 2);

            $this->transactionService->createTransaction($requestDetails->user_id, $refundAmount, 'NIN Service Refund', $serviceDesc,  'Wallet', 'Approved');
        }

        $requestDetails->save();

        return redirect()->route($route)->with('success', 'Request status updated successfully.');
    }

    public function bvnServicesList(Request $request)
    {

        // Services
        $pending = BvnPhoneSearch::whereIn('status', ['pending', 'processing'])
            ->count();

        $resolved = BvnPhoneSearch::where('status', 'resolved')
            ->count();

        $rejected = BvnPhoneSearch::where('status', 'rejected')
            ->count();

        $total_request = BvnPhoneSearch::count();

        $query = BvnPhoneSearch::with(['user', 'transactions']); // Load related data

        if ($request->filled('search')) { // Check if search input is provided
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%") // Search in Reference No.
                    ->orWhere('phone_number', 'like', "%{$searchTerm}%") // Search in BMS ID
                    ->orWhere('name', 'like', "%{$searchTerm}%") // Search in BMS ID
                    ->orWhere('status', 'like', "%{$searchTerm}%") // Search in Status
                    ->orWhereHas('user', function ($subQuery) use ($searchTerm) { // Search in User fields
                        $subQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Check if date_from and date_to are provided and filter accordingly
        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom); // Adjust 'created_at' to your date field
        }

        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo); // Adjust 'created_at' to your date field
        }

        $bvn_services = $query
            ->orderByRaw("
                CASE
                    WHEN status = 'pending' THEN 1
                    WHEN status = 'processing' THEN 2
                    ELSE 3
                END
            ") // Prioritize 'pending' first, then 'processing', and others last
            ->orderByDesc('id') // Sort by latest record within the same priority
            ->paginate(10);


        $request_type = 'bvn-services';

        return view('admin.bvn-services-list', compact(
            'pending',
            'resolved',
            'rejected',
            'total_request',
            'bvn_services',
            'request_type'
        ));
    }

    public function showBvnRequests($request_id, $type, $requests = null)
    {

        switch ($type) {
            case 'bvn-enrollment':

                break;
            case 'bvn-modification':

                break;
            case 'upgrade':

                break;

            case 'nin-services':
                $requests = BvnPhoneSearch::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
                break;

            case 'vnin-to-nibss':

                break;

            default:
                $requests = BvnPhoneSearch::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
        }

        if (strtolower($requests->status) == 'rejected') {
            abort(404, 'Kindly Submit a new request');
        }

        return view(
            'admin.view-bvn-request',
            compact(
                'requests',
                'request_type'
            )
        );
    }

    public function updateBvnRequestStatus(Request $request, $id, $type)
    {
        $request->validate([
            'status' => 'required|string',
            'comment' => 'required|string',
        ]);

        $requestDetails = BvnPhoneSearch::findOrFail($id);
        $route = 'admin.bvn.services.list';
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

            $serviceDesc = 'Wallet credited with a Request fee of ₦' . number_format($refundAmount, 2);

            $this->transactionService->createTransaction($requestDetails->user_id, $refundAmount, 'BVN Search Refund', $serviceDesc,  'Wallet', 'Approved');
        }

        $requestDetails->save();

        return redirect()->route($route)->with('success', 'Request status updated successfully.');
    }

     public function ninDelink(Request $request)
    {

        $services = Service::where('service_code', '131')
            ->where('type', 'nin_services_delink')
            ->where('status', 'enabled')->get();


        $query = NinValidation::where('user_id', auth()->id())->where('tag', 'DELINK');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nin_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $ninServices = $query->orderBy('id', 'desc')->paginate(10);

        // ✅ Status counts
        $statusCounts = NinValidation::selectRaw('status, COUNT(*) as count')
            ->where('user_id', auth()->id())
            ->where('tag', 'DELINK')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalAll = NinValidation::where('user_id', auth()->id())
            ->where('tag', 'DELINK')
            ->count();

        $totalPending = $statusCounts['Pending'] ?? 0;
        $totalFailed = $statusCounts['Failed'] ?? 0;
        $totalInProgress = $statusCounts['In-Progress'] ?? 0;
        $totalSuccessful = $statusCounts['Successful'] ?? 0;

        $settings = SiteSetting::first();

        return view('nin-mod-delink-services', compact(
            'services',
            'ninServices',
            'totalAll',
            'totalPending',
            'totalFailed',
            'totalInProgress',
            'totalSuccessful',
            'settings'
        ));
    }

    public function requestNinServiceDelink(Request $request)
    {
        $rules = [
            'service' => ['required', 'exists:services,service_code'],
        ];

        switch ($request->input('service')) {

            case '131':
                // NIN only
                $rules += [
                    'nin' => ['required', 'digits:11'],
                    'email' => ['nullable', 'email'],
                ];
                break;
        }

        $request->validate($rules);

        // NIN Services Fee
        $ServiceFee = 0;

        $Service = Service::where('service_code', $request->input('service'))
            ->where('status', 'enabled')
            ->first();

        if (! $Service) {
            return redirect()->back()->with('error', 'Sorry Action not Allowed !');
        }

        $ServiceFee = $Service->amount;
        $serviceType = 'Self Service '.$Service->name;
        // Check if wallet is funded
        $wallet = Wallet::where('user_id', auth()->id())->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return redirect()->back()->with('error', 'Sorry Wallet Not Sufficient for Transaction !');
        } else {

            try {


                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', auth()->id())
                        ->update(['balance' => $balance]);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦'.number_format($ServiceFee, 2);

                    $transaction = $this->transactionService->createTransaction(auth()->id(), $ServiceFee, $serviceType, $serviceDesc, 'Wallet', 'Approved');

                    $trx_id = $transaction->id;

                    NinValidation::create([
                        'user_id' => auth()->id(),
                        'tnx_id' => $trx_id,
                        'refno' => $transaction->referenceId,
                        'nin_number' => $request->nin,
                        'email'=> $request->email ?? 'Not provided',
                        'description' => $serviceType,
                        'tag' => 'DELINK',
                    ]);

                    return redirect()->back()->with('success', 'Self Service Delink request has been submitted');
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'An error occurred while making the API request');
                }
        }
    }

    public function emailRetrive(Request $request)
    {
        $services = Service::where('service_code', '132')
            ->where('type', 'email_retrive')
            ->where('status', 'enabled')->get();

        $query = NinValidation::where('user_id', auth()->id())->where('tag', 'EMAIL_RETRIVE');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nin_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $ninServices = $query->orderBy('id', 'desc')->paginate(10);

        $statusCounts = NinValidation::selectRaw('status, COUNT(*) as count')
            ->where('user_id', auth()->id())
            ->where('tag', 'EMAIL_RETRIVE')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalAll = NinValidation::where('user_id', auth()->id())
            ->where('tag', 'EMAIL_RETRIVE')
            ->count();

        $totalPending = $statusCounts['Pending'] ?? 0;
        $totalFailed = $statusCounts['Failed'] ?? 0;
        $totalInProgress = $statusCounts['In-Progress'] ?? 0;
        $totalSuccessful = $statusCounts['Successful'] ?? 0;

        $settings = SiteSetting::first();

        return view('email-retrive-services', compact(
            'services',
            'ninServices',
            'totalAll',
            'totalPending',
            'totalFailed',
            'totalInProgress',
            'totalSuccessful',
            'settings'
        ));
    }

    public function requestEmailRetrive(Request $request)
    {
        $rules = [
            'service' => ['required', 'exists:services,service_code'],
        ];

        switch ($request->input('service')) {
            case '132':
                $rules += [
                    'nin' => ['required', 'digits:11'],
                    'email' => ['nullable', 'email'],
                ];
                break;
        }

        $request->validate($rules);

        $Service = Service::where('service_code', $request->input('service'))
            ->where('status', 'enabled')
            ->first();

        if (!$Service) {
            return redirect()->back()->with('error', 'Sorry Action not Allowed !');
        }

        $ServiceFee = $Service->amount;
        $serviceType = 'Self Service ' . $Service->name;
        $wallet = Wallet::where('user_id', auth()->id())->first();

        if ($wallet->balance < $ServiceFee) {
            return redirect()->back()->with('error', 'Sorry Wallet Not Sufficient for Transaction !');
        }

        try {
            DB::transaction(function () use ($wallet, $ServiceFee, $serviceType, $request, $Service) {
                $wallet->balance -= $ServiceFee;
                $wallet->save();

                $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);
                $transaction = $this->transactionService->createTransaction(auth()->id(), $ServiceFee, $serviceType, $serviceDesc, 'Wallet', 'Approved');

                NinValidation::create([
                    'user_id' => auth()->id(),
                    'tnx_id' => $transaction->id,
                    'refno' => $transaction->referenceId,
                    'nin_number' => $request->nin,
                    'email' => $request->email ?? 'Not provided',
                    'description' => $serviceType,
                    'tag' => 'EMAIL_RETRIVE',
                ]);
            });

            return redirect()->back()->with('success', 'Self Service Email Retrieval request has been submitted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
        }
    }

    public function delinkServicesList(Request $request)
    {
        // Services
        $pending = NinValidation::whereIn('status', ['Pending', 'In-Progress'])->where('tag', 'DELINK')
            ->count();

        $resolved = NinValidation::where('status', 'Successful')->where('tag', 'DELINK')
            ->count();

        $rejected = NinValidation::where('status', 'Failed')->where('tag', 'DELINK')
            ->count();

        $total_request = NinValidation::where('tag', 'DELINK')->count();

        $query = NinValidation::with(['user', 'transactions'])->where('tag', 'DELINK');

        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%")
                    ->orWhere('nin_number', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('status', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $nin_services = $query
            ->orderByRaw("
                CASE
                    WHEN status = 'Pending' THEN 1
                    WHEN status = 'In-Progress' THEN 2
                    ELSE 3
                END
            ")
            ->orderByDesc('id')
            ->paginate(10);

        $request_type = 'delink-services';

        return view('admin.delink-services-list', compact(
            'pending',
            'resolved',
            'rejected',
            'total_request',
            'nin_services',
            'request_type'
        ));
    }

    public function emailRetriveList(Request $request)
    {
        $pending = NinValidation::whereIn('status', ['Pending', 'In-Progress'])->where('tag', 'EMAIL_RETRIVE')->count();
        $resolved = NinValidation::where('status', 'Successful')->where('tag', 'EMAIL_RETRIVE')->count();
        $rejected = NinValidation::where('status', 'Failed')->where('tag', 'EMAIL_RETRIVE')->count();
        $total_request = NinValidation::where('tag', 'EMAIL_RETRIVE')->count();

        $query = NinValidation::with(['user', 'transactions'])->where('tag', 'EMAIL_RETRIVE');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%")
                    ->orWhere('nin_number', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('status', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $nin_services = $query
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 1 WHEN status = 'In-Progress' THEN 2 ELSE 3 END")
            ->orderByDesc('id')
            ->paginate(10);

        $request_type = 'email-retrive-services';

        return view('admin.email-retrive-list', compact(
            'pending', 'resolved', 'rejected', 'total_request', 'nin_services', 'request_type'
        ));
    }

    public function ninModifications(Request $request)
    {
        $services = Service::where('type', 'nin_modification')
            ->where('status', 'enabled')->get();

        $query = NinModification::where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nin', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('refno', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        $ninServices = $query->orderBy('id', 'desc')->paginate(10);

        $statusCounts = NinModification::selectRaw('status, COUNT(*) as count')
            ->where('user_id', auth()->id())
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalAll = NinModification::where('user_id', auth()->id())->count();
        $totalPending = $statusCounts['Pending'] ?? 0;
        $totalFailed = $statusCounts['Failed'] ?? 0;
        $totalInProgress = $statusCounts['In-Progress'] ?? 0;
        $totalSuccessful = $statusCounts['Successful'] ?? 0;

        $settings = SiteSetting::first();

        return view('nin-modifications', compact(
            'services',
            'ninServices',
            'totalAll',
            'totalPending',
            'totalFailed',
            'totalInProgress',
            'totalSuccessful',
            'settings'
        ));
    }

    public function requestNinModification(Request $request)
    {
        $service = Service::where('service_code', $request->service_code)
            ->where('status', 'enabled')
            ->first();

        if (!$service) {
            return redirect()->back()->with('error', 'Sorry Action not Allowed !');
        }

        $typeName = $service->name; // e.g. "DOB MOD"

        // Dynamic Validation Rules based on Modification Type
        $rules = [
            'service_code'  => ['required', 'exists:services,service_code'],
            'nin'           => ['required', 'digits:11'],
            'clear_picture' => ['required', 'image', 'max:2048'], // Picture is mandatory
            'email'         => ['nullable', 'email'],
            'password'      => ['nullable', 'string'],
        ];

        // Apply type-specific rules
        if ($typeName == 'DOB MOD') {
            $rules['dob'] = ['required', 'date'];
            $rules['phone_number'] = ['required', 'string'];
        } elseif ($typeName == 'NAME MOD' || $typeName == 'PHONE NO MOD') {
            $rules['surname'] = ['required', 'string', 'max:100'];
            $rules['first_name'] = ['required', 'string', 'max:100'];
            $rules['middle_name'] = ['nullable', 'string', 'max:100'];
            $rules['phone_number'] = ['required', 'string'];
        } elseif ($typeName == 'ADDRESS MOD') {
            $rules['address'] = ['required', 'string'];
            $rules['town'] = ['required', 'string', 'max:100'];
            $rules['lga_origin'] = ['required', 'string', 'max:100'];
            $rules['state_origin'] = ['required', 'string', 'max:100'];
            $rules['lga_residence'] = ['required', 'string', 'max:100'];
            $rules['state_residence'] = ['required', 'string', 'max:100'];
        } elseif ($typeName == 'GENDER MOD') {
            $rules['gender'] = ['required', 'in:Male,Female'];
            $rules['phone_number'] = ['required', 'string'];
        } elseif ($typeName == 'OTHER MOD') {
            $rules['modification_type_detail'] = ['required', 'string', 'max:255'];
            $rules['phone_number'] = ['required', 'string'];
        }

        $request->validate($rules);

        $wallet = Wallet::where('user_id', auth()->id())->first();
        if ($wallet->balance < $service->amount) {
            return redirect()->back()->with('error', 'Sorry Wallet Not Sufficient for Transaction !');
        }

        try {
            DB::transaction(function () use ($wallet, $service, $request, $typeName) {
                $balance = $wallet->balance - $service->amount;
                Wallet::where('user_id', auth()->id())->update(['balance' => $balance]);

                $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($service->amount, 2);
                $transaction = $this->transactionService->createTransaction(auth()->id(), $service->amount, 'NIN Modification: ' . $typeName, $serviceDesc, 'Wallet', 'Approved');

                $picturePath = null;
                if ($request->hasFile('clear_picture')) {
                    $picturePath = $request->file('clear_picture')->store('nin_modifications', 'public');
                }

                NinModification::create([
                    'user_id' => auth()->id(),
                    'tnx_id' => $transaction->id,
                    'refno' => $transaction->referenceId,
                    'type' => $typeName,
                    'nin' => $request->nin,
                    'phone_number' => $request->phone_number,
                    'surname' => $request->surname,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'dob' => $request->dob,
                    'address' => $request->address,
                    'town' => $request->town,
                    'lga_origin' => $request->lga_origin,
                    'state_origin' => $request->state_origin,
                    'lga_residence' => $request->lga_residence,
                    'state_residence' => $request->state_residence,
                    'gender' => $request->gender,
                    'modification_type_detail' => $request->modification_type_detail,
                    'clear_picture' => $picturePath,
                    'email' => $request->email,
                    'password' => $request->password,
                ]);
            });

            return redirect()->back()->with('success', 'NIN Modification request has been submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
        }
    }

    public function adminNinModificationsList(Request $request)
    {
        $pending = NinModification::whereIn('status', ['Pending', 'In-Progress'])->count();
        $resolved = NinModification::where('status', 'Successful')->count();
        $rejected = NinModification::where('status', 'Failed')->count();
        $total_request = NinModification::count();

        $query = NinModification::with(['user', 'transactions']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%")
                    ->orWhere('nin', 'like', "%{$searchTerm}%")
                    ->orWhere('type', 'like', "%{$searchTerm}%")
                    ->orWhere('status', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $modifications = $query
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 1 WHEN status = 'In-Progress' THEN 2 ELSE 3 END")
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.nin-modifications-list', compact(
            'pending', 'resolved', 'rejected', 'total_request', 'modifications'
        ));
    }

    public function adminShowModification($id)
    {
        $modification = NinModification::with(['user', 'transactions'])->findOrFail($id);
        return view('admin.view-modification', compact('modification'));
    }

    public function adminUpdateModificationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In-Progress,Successful,Failed',
            'comment' => 'required|string',
        ]);

        $modification = NinModification::findOrFail($id);
        $modification->status = $request->status;
        $modification->reason = $request->comment;

        if ($request->status === 'Failed' && !$modification->refunded_at) {
            $modification->refunded_at = Carbon::now();
            $refundAmount = $request->refundAmount ?? 0;

            if ($refundAmount > 0) {
                $wallet = Wallet::where('user_id', $modification->user_id)->first();
                $wallet->balance += $refundAmount;
                $wallet->save();

                $serviceDesc = 'Wallet credited with a Request fee refund of ₦' . number_format($refundAmount, 2);
                $this->transactionService->createTransaction($modification->user_id, $refundAmount, 'NIN Modification Refund', $serviceDesc, 'Wallet', 'Approved');
            }
        }

        $modification->save();

        return redirect()->route('admin.nin.modifications.list')->with('success', 'Modification status updated successfully.');
    }
}
