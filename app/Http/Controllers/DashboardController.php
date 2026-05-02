<?php

namespace App\Http\Controllers;

use App\Models\BvnPhoneSearch;
use App\Models\Enrollment;
use App\Models\IpeRequest;
use App\Models\NinValidation;
use App\Models\Popup;
use App\Models\ComboDevice;
use App\Models\NinModification;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {

        $status = auth()->user()->kyc_status;

        $kycPending = session('kyc_pending', false);

        if ($status == 'Pending') {
            $kycPending = true;
        }

        //Check is user old balance has moved
        // $check =  auth()->user()->has_moved;


        // if (!$check) {
        //     //check if wallet existed and update the wallet
        //     $exist = User::where('id', auth()->user()->id)
        //         ->where('wallet_is_created', 1)
        //         ->exists();
        //     if ($exist) {
        //         Wallet::where('user_id', auth()->id())->update([
        //             'balance' => auth()->user()->old_balance,
        //             'deposit' => auth()->user()->old_balance,
        //         ]);

        //         //create a transaction for moving balance
        //         $serviceDesc = 'Wallet balance migration with a fee of ₦' . number_format(auth()->user()->old_balance, 2);

        //         $this->transactionService->createTransaction(auth()->user()->id, auth()->user()->old_balance, 'Wallet Top Up',   $serviceDesc,  'Wallet', 'Approved');
        //         // Update user record
        //         User::where('id', auth()->id())->update(['has_moved' => 1]);
        //     }
        // }

          if (auth()->user()->role == 'super_admin') {
            $totalRevenue = Transaction::where('status', 'Approved')->sum(DB::raw('CAST(amount AS DECIMAL(15,2))'));

            $totalUsers = User::count();

            $approvedToday = DB::table('transactions')
                ->where('status', 'Approved')
                ->whereIn('service_type', ['Wallet Topup', 'Admin Top Up'])
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->selectRaw('SUM(CAST(amount AS DECIMAL(15,2))) as total')
                ->value('total');

            $bvnSearch = BvnPhoneSearch::whereIn('status', ['pending', 'processing'])->count();

            $validationPending = NinValidation::whereIn('status', ['Pending', 'In-Progress'])
                ->whereNull('tag')->count();

            $ninDelink = NinValidation::whereIn('status', ['Pending', 'In-Progress'])
                ->where('tag', 'DELINK')->count();

            $totalWalletBalance = DB::table('wallets')->selectRaw('SUM(balance) as total')->value('total');
            $totalBonusBalance = DB::table('bonus_histories')->selectRaw('SUM(amount) as total')->value('total');
            $ipePending = IpeRequest::whereIn('resp_code', ['100', '101'])
                ->whereNull('tag')->count();

            $modificationIpePending = IpeRequest::whereIn('resp_code', ['100', '101'])
                ->where('tag', 'MODIFICATION')->count();

            $bvnEnrollmentCount = Enrollment::whereIn('status', ['submitted', 'processing'])->count();
            $ninModificationCount = NinModification::whereIn('status', ['Pending', 'In-Progress'])->count();

            $metrics = [
                [
                    'title' => 'Total Revenue',
                    'value' => '₦'.number_format($totalRevenue, 2),
                    'icon' => 'bi-cash-stack',
                    'bg' => 'success',
                    'href' => '#',
                ],
                [
                    'title' => 'Total Wallet Balance',
                    'value' => '₦'.number_format($totalWalletBalance, 2),
                    'icon' => 'bi-wallet2',
                    'bg' => 'warning',
                    'href' => '#',
                ],
                 [
                    'title' => 'Funding Today',
                    'value' => '₦'.number_format($approvedToday, 2),
                    'icon' => 'bi-wallet2',
                    'bg' => 'primary',
                    'href' => '#',
                ],
                [
                    'title' => 'Total Bonus Balance',
                    'value' => '₦'.number_format($totalBonusBalance, 2),
                    'icon' => 'bi-wallet2',
                    'bg' => 'info',
                    'href' => '#',
                ],

                [
                    'title' => 'Total Users',
                    'value' => number_format($totalUsers),
                    'icon' => 'bi-people-fill',
                    'bg' => 'danger',
                    'href' => 'admin.users.index',
                ],

                [
                    'title' => 'BVN RETRIVAL',
                    'value' => number_format($bvnSearch),
                    'icon' => 'bi-search',
                    'bg' => 'primary',
                    'href' => 'admin.bvn.services.list',
                ],
                [
                    'title' => 'IPE Clearance',
                    'value' => number_format($ipePending),
                    'icon' => 'bi-check2-circle',
                    'bg' => 'primary',
                    'href' => 'admin.ipe.index',
                ],
                [
                    'title' => 'NIN Validation',
                    'value' => number_format($validationPending),
                    'icon' => 'bi-check2-circle',
                    'bg' => 'info',
                    'href' => 'admin.nin.services.list',
                ],
                [
                    'title' => 'NIN DELINK',
                    'value' => number_format($ninDelink),
                    'icon' => 'bi bi-hourglass-split',
                    'bg' => 'secondary',
                    'href' => 'admin.delink.services.list',
                ],
                [
                    'title' => 'Email Retrieval',
                    'value' => number_format(NinValidation::whereIn('status', ['Pending', 'In-Progress'])->where('tag', 'EMAIL_RETRIVE')->count()),
                    'icon' => 'bi bi-envelope-open',
                    'bg' => 'dark',
                    'href' => 'admin.email.retrive.list',
                ],
                [
                    'title' => 'Modification IPE',
                    'value' => number_format($modificationIpePending),
                    'icon' => 'bi-pencil-square',
                    'bg' => 'warning',
                    'href' => 'admin.modification.ipe.index',
                ],
                [
                    'title' => 'BVN Agent',
                    'value' => number_format($bvnEnrollmentCount),
                    'icon' => 'bi-person-plus-fill',
                    'bg' => 'success',
                    'href' => 'admin.enroll.index',
                ],
                [
                    'title' => 'NIN Modification',
                    'value' => number_format($ninModificationCount),
                    'icon' => 'bi-pencil-square',
                    'bg' => 'warning',
                    'href' => 'admin.nin.modifications.list',
                ],
            ];

            $depositChartData = [
                'Approved' => (float) Transaction::whereIn('service_type', ['Wallet Topup', 'Admin Top Up'])->where('status', 'Approved')->sum('amount'),
                'Pending' => (float) Transaction::whereIn('service_type', ['Wallet Topup', 'Admin Top Up'])->where('status', 'Pending')->sum('amount'),
                'Rejected' => (float) Transaction::whereIn('service_type', ['Wallet Topup', 'Admin Top Up'])->where('status', 'Rejected')->sum('amount'),
            ];

            $depositChartData = [
                'Funding' => DB::table('transactions')
                    ->where('status', 'Approved')
                    ->whereIn('service_type', ['Wallet Topup', 'Admin Top Up'])
                    ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                    ->selectRaw('SUM(CAST(amount AS DECIMAL(15,2))) as total')
                    ->value('total'),

                'Expenses' => DB::table('transactions')
                    ->where('status', 'Approved')
                    ->whereNotIn('service_type', ['Wallet Topup', 'Admin Top Up'])
                    ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                    ->selectRaw('SUM(CAST(amount AS DECIMAL(15,2))) as total')
                    ->value('total'),

            ];

            $topFunders = DB::table('transactions as t')
                ->join('users as u', 't.user_id', '=', 'u.id')
                ->where('t.status', 'Approved')
                ->whereIn('t.service_type', ['Wallet Topup', 'Admin Top Up'])
                ->whereBetween('t.created_at', [now()->startOfDay(), now()->endOfDay()])
                ->select(
                    'u.name',
                    'u.email',
                    DB::raw('SUM(CAST(t.amount AS DECIMAL(15,2))) as total_funding')
                )
                ->groupBy('u.id', 'u.name', 'u.email')
                ->orderByDesc('total_funding')
                ->limit(5)
                ->get();

        }
        $comboDevices = ComboDevice::where('is_active', true)->latest()->get();

        $popup = Popup::where('is_active', true)->first();
        return view('user.dashboard', [
            'kycPending' => $kycPending,
            'status' =>   $status,
            'metrics' => $metrics ?? null,
            'depositChartData' => $depositChartData ?? null,
            'topFunders' => $topFunders ?? collect(),
            'popup' => $popup,
            'comboDevices' => $comboDevices,
        ]);
    }
}
