<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EnrollmentSyncController;
use App\Http\Controllers\IpeController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WalletController;
use App\Http\Requests\LoginRequest;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $settings = SiteSetting::first();
    if ($settings && $settings->home_enabled) {
        return view('welcome');
    }
    return redirect()->route('auth.login');
})->name('welcome');


Route::post('/monnify/webhook', [PaymentWebhookController::class, 'handleWebhook']);

Route::post('/update-bvn-enrollment-status', [EnrollmentSyncController::class, 'updateStatus']);

Route::group(['as' => 'auth.', 'prefix' => 'auth', 'middleware' => 'guest'], function () {
    // Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    // Route::post('login', [AuthController::class, 'login']);
    // Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    // Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');


     $settings = SiteSetting::first();

    if ($settings && $settings->login_enabled) {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    } else {
        Route::match(['get', 'post'], 'login', function (Request $request) {

            if ($request->isMethod('post')) {
                $user = User::where('email', $request->input('email'))->first();

                if ($user && $user->role == 'admin' || $user->role == 'super_admin') {

                    $loginRequest = app(LoginRequest::class);

                    return app(AuthController::class)->login($loginRequest);
                }
            }

            if ($request->isMethod('get') && $request->query('admin') == 1) {
                return app(AuthController::class)->showLoginForm($request);
            }

            return redirect()->away('https://');
        })->name('login');
    }

    // REGISTER ROUTES
    if ($settings && $settings->register_enabled) {
        Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    } else {
        Route::any('register', function () {
            return redirect()->away('https://');
        })->name('register');
    }


    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// User Routes
Route::middleware(['auth', 'user.active'])->group(function () {
    // User dashboard
    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/verify-user', [VerificationController::class, 'createAccounts'])->name('verify-user');

        Route::middleware(['user.active', 'user.is_kyced'])->group(function () {
            Route::get('/verify-nin', [VerificationController::class, 'ninVerify'])->name('verify-nin');
            Route::get('/verify-nin-phone', [VerificationController::class, 'phoneVerify'])->name('verify-nin-phone');

            Route::get('/verify-bvn', [VerificationController::class, 'bvnVerify'])->name('verify-bvn');

            Route::get('/nin-personalize', [VerificationController::class, 'ninPersonalize'])->name('personalize-nin');
            Route::get('/nin-personalize-auto/{id}', [VerificationController::class, 'ninPersonalize'])->name('personalize-nin-auto');
            Route::get('/ipe', [VerificationController::class, 'showIpe'])->name('ipe');
            Route::get('/bvn-enrollment', [EnrollmentController::class, 'bvnEnrollment'])->name('bvn-enrollment');
            Route::get('/verify-demo', [VerificationController::class, 'demoVerify'])->name('verify-demo');

            //Ipe request
            Route::post('/ipe-request', [VerificationController::class, 'ipeRequest'])->name('ipe-request');
            Route::get('/ipeStatus/{id}', [VerificationController::class, 'ipeRequestStatus'])->name('ipeStatus');

            //NIN Validation
            Route::get('/nin-validation', [VerificationController::class, 'showNinValidation'])->name('nin-validation');
            Route::post('nin-validation-request', [VerificationController::class, 'ninValidation'])->name('nin-validation-request');

            //NIN Validation
            Route::get('/bvn-phone-search', [VerificationController::class, 'bvnPhoneSearch'])->name('bvn-phone-search');
            Route::post('bvn-phone-search', [VerificationController::class, 'bvnPhoneRequest'])->name('bvn-phone-request');


            //Enrollment-----------------------------------------------------------------------------------------------------
            Route::post('/bvn-enrollment', [EnrollmentController::class, 'enrollBVN'])->name('enroll-bvn');
            //Wallet
            Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
            Route::get('claim-bonus/{id}', [WalletController::class, 'claimBonus'])->name('claim-bonus');

            //Transactions -----------------------------------------------------------------------------------------------------
            Route::get('/receipt/{referenceId}', [TransactionController::class, 'reciept'])->name('reciept');

            //Verification-----------------------------------------------------------------------------------------------------
            //NIN
            Route::post('/nin-retrieve', [VerificationController::class, 'ninRetrieve'])->name('ninRetrieve');
            Route::post('/nin-demo-retrieve', [VerificationController::class, 'ninDemoRetrieve'])->name('nin-demo-Retrieve');
            Route::post('/nin-phone-retrieve', [VerificationController::class, 'ninPhoneRetrieve'])->name('ninPhoneRetrieve');
            Route::post('/nin-track-retrieve', [VerificationController::class, 'ninTrackRetrieve'])->name('ninTrackRetrieve');
            //BVN
            Route::post('/bvn-retrieve', [VerificationController::class, 'bvnRetrieve'])->name('bvnRetrieve');

            //PDF Downloads -----------------------------------------------------------------------------------------------------
            Route::get('/standardBVN/{id}', [VerificationController::class, 'standardBVN'])->name("standardBVN");
            Route::get('/premiumBVN/{id}', [VerificationController::class, 'premiumBVN'])->name("premiumBVN");
            Route::get('/plasticBVN/{id}', [VerificationController::class, 'plasticBVN'])->name("plasticBVN");

            Route::get('/regularSlip/{id}', [VerificationController::class, 'regularSlip'])->name("regularSlip");
            Route::get('/standardSlip/{id}', [VerificationController::class, 'standardSlip'])->name("standardSlip");
            Route::get('/premiumSlip/{id}', [VerificationController::class, 'premiumSlip'])->name("premiumSlip");
            Route::get('/basicSlip/{id}', [VerificationController::class, 'basicSlip'])->name("basicSlip");

            Route::get('/nin-delink', [ServicesController::class, 'ninDelink'])->name('nin.delink');
            Route::post('/nin-services/delink/request', [ServicesController::class, 'requestNinServiceDelink'])->name('nin.services.delink.request');

            Route::get('/email-retrive', [ServicesController::class, 'emailRetrive'])->name('email.retrive');
            Route::post('/email-retrive/request', [ServicesController::class, 'requestEmailRetrive'])->name('email.retrive.request');


            //Whatsapp API Support--------------------------------------------------------------------------
            Route::get('/support', function () {
                return redirect()->away(config('services.whatsapp.api_url'));
            })->name('support');
        });
    });
    // Logout Route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'user.active', 'user.admin']], function () {
    // Services
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/services/edit/{id}', [ServicesController::class, 'edit'])->name('services.edit');
    Route::put('/services/update/{id}', [ServicesController::class, 'update'])->name('services.update');

    Route::get('/receipt/{referenceId}', [TransactionController::class, 'recieptAdmin'])->name('reciept');

    Route::get('/transactions', [TransactionController::class, 'transactions'])->name('transactions');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('user.activate');

    Route::get('/nin-services', [ServicesController::class, 'ninServicesList'])->name('nin.services.list');
    Route::get('/view-ipe-request/{id}/edit', [IpeController::class, 'showIpeRequest'])->name('ipe.view-request');
    Route::post('/requests/ipe/{id}/update-status', [IpeController::class, 'updateIpeStatus'])->name('ipe.update-request-status');

    Route::post('/requests/{id}/{type}/update-status', [ServicesController::class, 'updateRequestStatus'])->name('update-request-status');
    Route::get('/view-request/{id}/{type}/edit', [ServicesController::class, 'showRequests'])->name('view-request');

    Route::get('/bvn-services', [ServicesController::class, 'bvnServicesList'])->name('bvn.services.list');
    Route::post('/requests/{id}/{type}/update-bvn-status', [ServicesController::class, 'updateBvnRequestStatus'])->name('bvn-update-request-status');
    Route::get('/view-bvn-request/{id}/{type}/edit', [ServicesController::class, 'showBvnRequests'])->name('bvn-view-request');

     Route::get('site-settings/edit', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
    Route::put('site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');

    Route::get('ipe-index', [IpeController::class, 'ipeIndex'])->name('ipe.index');
    Route::get('ipe/download-template', [IpeController::class, 'downloadTemplateIPE'])->name('ipe.download-template');
    Route::post('ipe/upload-excel', [IpeController::class, 'uploadExcelIPE'])->name('ipe.upload-excel');
    Route::get('/ipe/refund-failed', [IpeController::class, 'refundFailedTransactions'])->name('ipe.refund');

     // NIN Services
    Route::get('/delink-services', [ServicesController::class, 'delinkServicesList'])->name('delink.services.list');
    Route::get('/email-retrive-services', [ServicesController::class, 'emailRetriveList'])->name('email.retrive.list');

});
