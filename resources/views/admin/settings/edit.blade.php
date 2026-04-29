@extends('layouts.dashboard')

@section('title', 'Site Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="mb-4 mt-2">
            <h3 class="mb-1 fw-bold">Site Settings</h3>
            <p class="text-muted mb-0">Manage global configuration for your application.</p>
        </div>

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error messages --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <strong><i class="mdi mdi-alert-circle me-2"></i>There were some problems with your input:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.site-settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Access Control Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-bottom px-4 py-3">
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-shield-account-outline text-primary me-2 fs-4 align-middle"></i>Access Control</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <div>
                                    <h6 class="mb-1 fw-semibold">Enable Home Page</h6>
                                    <small class="text-muted">Allow users to visit the landing page.</small>
                                </div>
                                <div class="form-check form-switch fs-4 mb-0">
                                    <input class="form-check-input" style="cursor: pointer;" type="checkbox" name="home_enabled" id="home_enabled" value="1" {{ old('home_enabled', $settings->home_enabled ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <div class="pe-3">
                                    <h6 class="mb-1 fw-semibold">Enable Login Page</h6>
                                    <small class="text-muted d-block">If disabled, normal users cannot access the login page. Admins can still log in via: <code class="bg-light px-2 py-1 rounded ms-1 border">{{ url('auth/login?admin=1') }}</code></small>
                                </div>
                                <div class="form-check form-switch fs-4 mb-0">
                                    <input class="form-check-input" style="cursor: pointer;" type="checkbox" name="login_enabled" id="login_enabled" value="1" {{ old('login_enabled', $settings->login_enabled ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-semibold">Enable Register Page</h6>
                                    <small class="text-muted">Allow new users to sign up for accounts.</small>
                                </div>
                                <div class="form-check form-switch fs-4 mb-0">
                                    <input class="form-check-input" style="cursor: pointer;" type="checkbox" name="register_enabled" id="register_enabled" value="1" {{ old('register_enabled', $settings->register_enabled ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Notices Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-bottom px-4 py-3">
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-message-alert-outline text-danger me-2 fs-4 align-middle"></i>Service Notices</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label for="delink_notice" class="form-label fw-semibold">NIN Delink Service Notice</label>
                        <textarea name="delink_notice" id="delink_notice" class="form-control bg-light border-0 px-3 py-2" rows="3" placeholder="Enter the notice message to display on the delinking request page...">{{ old('delink_notice', $settings->delink_notice ?? '') }}</textarea>
                        <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline me-1 text-primary"></i>This message will be displayed at the top of the NIN Delinking request form for users.</small>
                    </div>

                    <div class="mb-4">
                        <label for="ipe_notice" class="form-label fw-semibold">IPE Service Notice</label>
                        <textarea name="ipe_notice" id="ipe_notice" class="form-control bg-light border-0 px-3 py-2" rows="3" placeholder="Enter the notice message to display on the IPE request page...">{{ old('ipe_notice', $settings->ipe_notice ?? '') }}</textarea>
                        <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline me-1 text-primary"></i>This message will be displayed below the tracking number on the IPE form for users.</small>
                    </div>

                    <div class="mb-4">
                        <label for="bvn_notice" class="form-label fw-semibold">BVN Retrieval Notice</label>
                        <textarea name="bvn_notice" id="bvn_notice" class="form-control bg-light border-0 px-3 py-2" rows="3" placeholder="Enter the notice message to display on the BVN Retrieval page...">{{ old('bvn_notice', $settings->bvn_notice ?? '') }}</textarea>
                        <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline me-1 text-primary"></i>This message will be displayed on the BVN Retrieval form for users.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="modification_ipe_notice" class="form-label fw-semibold">Modification IPE Service Notice</label>
                        <textarea name="modification_ipe_notice" id="modification_ipe_notice" class="form-control bg-light border-0 px-3 py-2" rows="3" placeholder="Enter the notice message to display on the Modification IPE request page...">{{ old('modification_ipe_notice', $settings->modification_ipe_notice ?? '') }}</textarea>
                        <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline me-1 text-primary"></i>This message will be displayed below the tracking number on the Modification IPE form for users.</small>
                    </div>

                    <div class="mb-4">
                        <label for="bvn_enrollment_notice" class="form-label fw-semibold">BVN Enrollment Service Notice</label>
                        <textarea name="bvn_enrollment_notice" id="bvn_enrollment_notice" class="form-control bg-light border-0 px-3 py-2" rows="3" placeholder="Enter the notice message to display on the BVN Enrollment request page...">{{ old('bvn_enrollment_notice', $settings->bvn_enrollment_notice ?? '') }}</textarea>
                        <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline me-1 text-primary"></i>This message will be displayed at the top of the BVN Enrollment request form for users.</small>
                    </div>

                    <div class="mb-4">
                        <label for="email_retrieval_notice" class="form-label fw-semibold">Email Retrieval Service Notice</label>
                        <textarea name="email_retrieval_notice" id="email_retrieval_notice" class="form-control bg-light border-0 px-3 py-2" rows="3" placeholder="Enter the notice message to display on the Email Retrieval request page...">{{ old('email_retrieval_notice', $settings->email_retrieval_notice ?? '') }}</textarea>
                        <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline me-1 text-primary"></i>This message will be displayed at the top of the Email Retrieval request form for users.</small>
                    </div>

                    <div>
                        <label for="nimc_license_price" class="form-label fw-semibold">NIMC License Price</label>
                        <input type="text" name="nimc_license_price" id="nimc_license_price" class="form-control bg-light border-0 px-3 py-2" placeholder="e.g. 180,000" value="{{ old('nimc_license_price', $settings->nimc_license_price ?? '180,000') }}">
                        <small class="text-muted mt-2 d-block"><i class="mdi mdi-currency-ngn me-1 text-success"></i>Enter the price for the NIMC License (including commas if desired). This will be shown on the dashboard and modal.</small>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill"><i class="mdi mdi-content-save me-2"></i>Save All Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
