@extends('layouts.dashboard')

@section('title', 'Dashboard')
@push('styles')
    <style>
        /* Default style (for larger screens) */
        .price {
            font-size: 2rem;
            /* Default font size for larger screens */
            white-space: normal;
            /* Allow wrapping on larger screens */
            overflow: visible;
            /* Allow content to overflow if necessary */
            text-overflow: unset;
            /* Reset ellipsis */
            line-height: 1.2;
            /* Standard line height */
        }

        /* Style for smaller screens (e.g., mobile or tablet) */
        @media (max-width: 767px) {
            .price {
                font-size: 1.2rem;
                /* Adjust font size for smaller screens */
                white-space: nowrap;
                /* Prevent text from wrapping */
                overflow: hidden;
                /* Hide overflow */
                text-overflow: ellipsis;
                /* Show ellipsis if text overflows */
            }
        }

        /* General Styles for Service Cards */
        .service-card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .icon-box {
            margin-bottom: 1.5rem;
        }

        .icon-box-media {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1e40af;
            border-radius: 50%;
            width: 70px;
            height: 70px;
        }

        .icon-box-title {
            font-weight: bolder;
            font-size: 1rem;
            color: #333;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .icon-box-media {
                width: 60px;
                height: 60px;
            }

            .icon-box-title {
                font-size: 1rem;
            }
        }

        /* Ensures 2 items per row on mobile (smaller than 576px) */
        @media (max-width: 576px) {
            .col-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .icon-box-media {
                width: 50px;
                height: 50px;
            }

            .icon-box-title {
                font-size: 0.9rem;
            }
        }

        /* Custom CSS for icon box */
        .icon-box-media {
            transition: transform 0.3s ease;
        }

        .icon-box-media:hover {
            transform: scale(1.1);
        }

        /* Custom CSS for cards */
        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .copy-btn-wrap .btn {
            padding: 4px 12px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background-color: #1e40af;
            /* Modern primary blue */
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .copy-btn-wrap .btn:hover {
            background-color: #1e3a8a;
            /* Darker blue on hover */
        }

        /* Advanced Premium Design System */
        :root {
            --primary-blue: #1e40af;
            --primary-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --card-shadow-hover: 0 25px 50px -12px rgba(30, 64, 175, 0.15);
        }

        .service-card {
            border-radius: 1.5rem !important;
            border: none !important;
            background: var(--glass-bg) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            height: 100%;
            overflow: hidden;
            box-shadow: var(--card-shadow) !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .service-card:hover {
            transform: translateY(-10px) scale(1.02) !important;
            box-shadow: var(--card-shadow-hover) !important;
            border-color: rgba(30, 64, 175, 0.3) !important;
            background: #ffffff !important;
        }

        .service-card.disabled {
            opacity: 0.55;
            cursor: not-allowed;
            filter: grayscale(0.7);
        }

        .service-card.disabled:hover {
            transform: none !important;
            box-shadow: var(--card-shadow) !important;
        }

        .service-icon-wrapper {
            width: 72px;
            height: 72px;
            background: #f8fafc;
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem auto;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .service-card:hover .service-icon-wrapper {
            transform: scale(1.15) rotate(8deg);
            background: white;
            box-shadow: 0 10px 20px rgba(30, 64, 175, 0.1);
        }

        .service-icon-wrapper img {
            max-width: 38px;
            max-height: 38px;
            object-fit: contain;
        }

        .service-card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.25rem;
            letter-spacing: -0.01em;
        }

        /* Premium Version Pills (Squared) */
        .version-pills {
            background: #ffffff;
            padding: 5px;
            border-radius: 4px;
            display: inline-flex;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .version-pills .nav-link {
            border-radius: 4px !important;
            padding: 10px 28px !important;
            font-weight: 700;
            font-size: 0.75rem;
            font-family: 'Inter', sans-serif;
            color: #64748b !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none !important;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            position: relative;
        }

        .version-pills .nav-link:hover:not(.active) {
            color: #1e40af !important;
            background: #f8fafc;
            transform: scale(1.02);
        }

        .version-pills .nav-link.active {
            background: #1e40af !important;
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(30, 64, 175, 0.25);
        }

        /* Status Badges */
        .coming-soon-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 0.6rem;
            font-weight: 800;
            background: #e2e8f0;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            z-index: 2;
        }

        .card-new-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 0.6rem;
            font-weight: 800;
            background: #fef3c7;
            color: #d97706;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            z-index: 2;
        }

        /* Animations */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tab-pane.active .col-6 {
            animation: slideUpFade 0.5s ease forwards;
        }

        .tab-pane.active .col-6:nth-child(1) { animation-delay: 0.05s; }
        .tab-pane.active .col-6:nth-child(2) { animation-delay: 0.1s; }
        .tab-pane.active .col-6:nth-child(3) { animation-delay: 0.15s; }
        .tab-pane.active .col-6:nth-child(4) { animation-delay: 0.2s; }
        .tab-pane.active .col-6:nth-child(5) { animation-delay: 0.25s; }

        @media (max-width: 576px) {
            .service-icon-wrapper {
                width: 65px;
                height: 65px;
            }
            .service-card-title {
                font-size: 0.9rem;
            }
            .service-card {
                padding: 1.25rem !important;
            }
        }

        /* Remove border from tab content */
        .tab-content {
            border: none !important;
            padding: 0 !important;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
            <p class="mb-0">Here’s a quick look at your dashboard.</p>
        </div>
        @if ($status == 'Pending')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                We're excited to have you on board! However, we need to verify your identity before activating your
                account. Simply click the link below to complete the verification process<br>
            </div>
        @endif
        @include('common.message')
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class="row">
                <div class="col-md-6 col-6 grid-margin stretch-card">
                    <div class="card hover-shadow">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="mdi mdi-wallet-outline mdi-36px"></i>
                                <p class="fw-medium mt-3">Main Wallet</p>
                            </div>
                            <h1 class="fw-light price">
                                ₦{{ auth()->user()->wallet ? number_format(auth()->user()->wallet->balance, 2) : '0.00' }}
                            </h1>

                            <a href="#" data-bs-toggle="modal" data-bs-target="#walletModal"
                                class="btn btn-sm btn-outline-primary mt-3">
                                Add Fund
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-6 grid-margin stretch-card">
                    <div class="card hover-shadow">
                        <div class="card-body text-center">
                            <div class="text-danger mb-2">
                                <i class="mdi mdi-gift-outline mdi-36px"></i>
                                <p class="fw-medium mt-3">Bonus Wallet</p>
                            </div>
                            <h1 class="fw-light price">
                                ₦{{ auth()->user()->wallet ? number_format(auth()->user()->wallet->bonus, 2) : '0.00' }}
                            </h1>

                            <a href="{{ route('user.wallet') }}" class="btn btn-sm btn-outline-danger mt-3">
                                Claim Bonus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                 @if (auth()->user()->role == 'super_admin')
                <div class="row g-3 g-sm-4 mb-4">
                    @foreach ($metrics as $metric)
                        <div class="col-6 col-sm-5 col-md-4">
                            <x-dashboard.metric :title="$metric['title']"
                                                :value="$metric['value']"
                                                :icon="$metric['icon']"
                                                :bg="$metric['bg']"
                                                :href="$metric['href']" />
                        </div>
                    @endforeach
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card mb-2">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Daily Charts</h5>
                            </div>
                            <div class="card-body">
                                <div style="max-height: 300px;">
                                    <canvas id="depositBreakdownChart"
                                            style="height: 100%; max-height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top Funding</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="fundingChart"
                                        width="600"
                                        height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <!-- Left side column containing the icons -->
                <div class="col-lg-12 col-12">
                    <div class="container py-4" style="max-width: 100%">

                        <!-- NIN Services Section -->
                        <div class="mb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">NIN Services</h4>
                                </div>
                            </div>

                            <!-- Sub-category: Verification -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
                                    <div class="d-flex align-items-center">
                                        <h6 class="text-primary fw-bold text-uppercase mb-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">Verification Services</h6>
                                    </div>

                                    <!-- Version Toggle -->
                                    <ul class="nav nav-pills version-pills mt-3 mt-md-0" id="verificationTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="v1-tab" data-bs-toggle="pill" data-bs-target="#v1-content" type="button" role="tab">Version 1</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="v2-tab" data-bs-toggle="pill" data-bs-target="#v2-content" type="button" role="tab">Version 2 <small class="ms-1 opacity-75">(Soon)</small></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="v3-tab" data-bs-toggle="pill" data-bs-target="#v3-content" type="button" role="tab">Version 3 <small class="ms-1 opacity-75">(Soon)</small></button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content" id="verificationTabsContent">
                                    <!-- Version 1 Content -->
                                    <div class="tab-pane fade show active" id="v1-content" role="tabpanel">
                                        <div class="row g-3 g-md-4">
                                            <!-- Verify NIN (v1) -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card position-relative p-4 text-center">
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">NIN Verify</h5>
                                                    <p class="text-muted small mb-0">Instant NIN details</p>
                                                    <a href="{{ route('user.verify-nin') }}" class="stretched-link"></a>
                                                </div>
                                            </div>

                                            <!-- NIN Phone (v1) -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card position-relative p-4 text-center">
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">NIN Phone</h5>
                                                    <p class="text-muted small mb-0">Instant NIN Search by phone</p>
                                                    <a href="{{ route('user.verify-nin-phone') }}" class="stretched-link"></a>
                                                </div>
                                            </div>

                                            <!-- Demographics (v1) -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card position-relative p-4 text-center">
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">Demographics</h5>
                                                    <p class="text-muted small mb-0">Instant Demographic Search</p>
                                                    <a href="{{ route('user.verify-demo') }}" class="stretched-link"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Version 2 Content -->
                                    <div class="tab-pane fade" id="v2-content" role="tabpanel">
                                        <div class="row g-3 g-md-4">
                                            <!-- NIN Verify v2 -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card disabled position-relative p-4 text-center">
                                                    <span class="coming-soon-badge">Soon</span>
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">NIN Verify</h5>
                                                    <p class="text-muted small mb-0">Instant NIN details</p>
                                                    <a href="javascript:void(0)" class="stretched-link"></a>
                                                </div>
                                            </div>

                                            <!-- NIN Phone v2 -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card disabled position-relative p-4 text-center">
                                                    <span class="coming-soon-badge">Soon</span>
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">NIN Phone</h5>
                                                    <p class="text-muted small mb-0">Instant NIN Search by phone</p>
                                                    <a href="javascript:void(0)" class="stretched-link"></a>
                                                </div>
                                            </div>

                                            <!-- Demographics v2 -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card disabled position-relative p-4 text-center">
                                                    <span class="coming-soon-badge">Soon</span>
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">Demographics</h5>
                                                    <p class="text-muted small mb-0">Instant Demographics Search</p>
                                                    <a href="javascript:void(0)" class="stretched-link"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Version 3 Content -->
                                    <div class="tab-pane fade" id="v3-content" role="tabpanel">
                                        <div class="row g-3 g-md-4">
                                            <!-- NIN Verify v2 -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card disabled position-relative p-4 text-center">
                                                    <span class="coming-soon-badge">Soon</span>
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">NIN Verify</h5>
                                                    <p class="text-muted small mb-0">Instant NIN details</p>
                                                    <a href="javascript:void(0)" class="stretched-link"></a>
                                                </div>
                                            </div>

                                            <!-- NIN Phone v2 -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card disabled position-relative p-4 text-center">
                                                    <span class="coming-soon-badge">Soon</span>
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">NIN Phone</h5>
                                                    <p class="text-muted small mb-0">Instant NIN Search by phone</p>
                                                    <a href="javascript:void(0)" class="stretched-link"></a>
                                                </div>
                                            </div>

                                            <!-- Demographics v2 -->
                                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                                <div class="card service-card disabled position-relative p-4 text-center">
                                                    <span class="coming-soon-badge">Soon</span>
                                                    <div class="service-icon-wrapper">
                                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                                    </div>
                                                    <h5 class="service-card-title">Demographics</h5>
                                                    <p class="text-muted small mb-0">Instant Demographics Search</p>
                                                    <a href="javascript:void(0)" class="stretched-link"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sub-category: Management & Clearance -->
                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="text-primary fw-bold text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.05em;">Other NIN Services</h6>
                                    <div class="ms-3 flex-grow-1 border-bottom opacity-5"></div>
                                </div>
                                <div class="row g-3 g-md-4">
                                    <!-- IPE Clearance -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                            </div>
                                            <h5 class="service-card-title">IPE Clearance</h5>
                                            <p class="text-muted small mb-0">Process IPE Clearance</p>
                                            <a href="{{ route('user.ipe') }}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- Modification IPE -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                            </div>
                                            <h5 class="service-card-title">Modification IPE</h5>
                                            <p class="text-muted small mb-0">Process Modification IPE</p>
                                            <a href="{{ route('user.modification-ipe') }}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- NIN Validation -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                            </div>
                                            <h5 class="service-card-title">Validation</h5>
                                            <p class="text-muted small mb-0">NIN Validation Request</p>
                                            <a href="{{ route('user.nin-validation')}}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- NIN Delinking -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <span class="card-new-badge">Hot</span>
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                            </div>
                                            <h5 class="service-card-title">NIN Delinking</h5>
                                            <p class="text-muted small mb-0">Unlink Device</p>
                                            <a href="{{ route('user.nin.delink')}}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- Email Retrieval -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <span class="card-new-badge">New</span>
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                            </div>
                                            <h5 class="service-card-title">Email Retrieval</h5>
                                            <p class="text-muted small mb-0">Recover linked email</p>
                                            <a href="{{ route('user.email.retrive')}}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- NIMC License -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <span class="card-new-badge" style="background: #dcfce7; color: #166534;">Price {{ $settings->nimc_license_price ?? '180,000' }}</span>
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                            </div>
                                            <h5 class="service-card-title">NIMC License</h5>
                                            <p class="text-muted small mb-0">Enrollment License</p>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#nimcLicenseModal" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- NIN Modifications -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <span class="card-new-badge">Mod</span>
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                            </div>
                                            <h5 class="service-card-title">NIN Modifications</h5>
                                            <p class="text-muted small mb-0">Update NIN details</p>
                                            <a href="{{ route('user.nin.modifications')}}" class="stretched-link"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BVN Services Section -->
                        <div class="pb-5">
                            <div class="section-header mb-4">
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">BVN Services</h4>
                                    <div class="flex-grow-1 ms-4 border-bottom opacity-10"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-4">
                                    <h6 class="text-success fw-bold text-uppercase mb-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">Bank Verification Number</h6>
                                    <div class="ms-3 flex-grow-1 border-bottom opacity-10"></div>
                                </div>

                                <div class="row g-3 g-md-4">
                                    <!-- Verify BVN -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/bvn.png') }}" alt="BVN">
                                            </div>
                                            <h5 class="service-card-title">Verify BVN</h5>
                                            <p class="text-muted small mb-0">Verify BVN details</p>
                                            <a href="{{ route('user.verify-bvn') }}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- BVN RETRIVAL -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/bvn.png') }}" alt="BVN">
                                            </div>
                                            <h5 class="service-card-title">BVN Retrieval</h5>
                                            <p class="text-muted small mb-0">Find BVN by phone</p>
                                            <a href="{{ route('user.bvn-phone-search') }}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- BVN ENROLLMENT -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card position-relative p-4 text-center">
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/bvn.png') }}" alt="BVN">
                                            </div>
                                            <h5 class="service-card-title">Enroll as BVN Agent</h5>
                                            <p class="text-muted small mb-0">Become a BVN Enrollment Agent</p>
                                            <a href="{{ route('user.bvn-enrollment') }}" class="stretched-link"></a>
                                        </div>
                                    </div>

                                    <!-- BVN MODIFICATION -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card disabled position-relative p-4 text-center">
                                            <span class="coming-soon-badge">Soon</span>
                                            <div class="service-icon-wrapper">
                                                <img src="{{ asset('assets/images/bvn.png') }}" alt="BVN">
                                            </div>
                                            <h5 class="service-card-title">BVN Modification</h5>
                                            <p class="text-muted small mb-0">Modify BVN Details</p>
                                            <a href="javascript:void(0)" class="stretched-link"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right side column for transaction table -->
            <div class="col-lg-12 stretch-card mt-2">
                    <div class="container py-3" style="max-width: 100%">
                        <h4 class="fw-light mb-4 text-center">Recent Transactions</h4>
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="table-responsive">
                                    @php
                                        $transactions = auth()->user()->transactions()->latest()->paginate(10);
                                        $serialNumber =
                                            ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                                    @endphp

                                    @forelse ($transactions as $data)
                                        @if ($loop->first)
                                            <table class="table text-nowrap" style="background: #fafafc !important;">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th width="5%">ID</th>
                                                        <th>Reference No.</th>
                                                        <th>Service Type</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Receipt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                        @endif

                                        <tr>
                                            <td>{{ $serialNumber++ }}</td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('user.reciept', $data->referenceId) }}">
                                                    {{ strtoupper($data->referenceId) }}
                                                </a>
                                            </td>
                                            <td>{{ $data->service_type }}</td>
                                            <td>{{ $data->service_description }}</td>
                                            <td>&#8358;{{ number_format($data->amount, 2) }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge
                                                    {{ $data->status == 'Approved' ? 'bg-success' : ($data->status == 'Rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ strtoupper($data->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a target="_blank" href="{{ route('user.reciept', $data->referenceId) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>

                                        @if ($loop->last)
                                            </tbody>
                                            </table>

                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $transactions->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center">
                                            <p class="fw-semibold fs-15 mt-2">No Transaction Available!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="kycModal" tabindex="-1" aria-labelledby="kycModal" data-bs-keyboard="true"
                data-bs-backdrop="static" data-bs-keyboard="false">

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="staticBackdropLabel2">Verify Account
                            </h6>
                        </div>
                        <div class="modal-body">
                            We're excited to have you on board! However, we need to verify your identity before activating
                            your
                            account. provide your Identification number below.
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="col-md-6 col-lg-6">
                                <form id="verify" name="verifyForm" method="POST"
                                    action="{{ route('user.verify-user') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <p class="mb-2 text-muted text-center">Enter your BVN No.</p>
                                        <input type="text" id="bvn" name="bvn"
                                            class="form-control text-center" maxlength="11" required />
                                    </div>
                                    <div class="text-center mb-3 d-flex justify-content-center gap-2">
                                        <button type="submit" id="submit" class="btn btn-primary">
                                            <i class="lar la-check-circle"></i> Verify Now
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('logout') }}" class="text-center mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="las la-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="walletModalLabel">Fund Wallet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <small class="fw-semibold">Fund your wallet instantly by depositing
                                into the virtual account number</small>
                            <ul class="list-unstyled virtual-account-list mt-3 mb-0">
                                @if (auth()->user()->virtualAccount != null)
                                    @foreach (auth()->user()->virtualAccount as $data)
                                        <li class="account-item mb-3 p-2">
                                            <div class="d-flex align-items-start">
                                                <div class="bank-logo me-3">
                                                    <img width="80px" height="80px" style="object-fit: contain;"
                                                        src="{{ asset('assets/images/' . strtolower($data->bankName) . '.png') }}"
                                                        alt="{{ $data->bankName }} logo">
                                                </div>
                                                <div class="flex-fill">
                                                    <p class="account-name mb-1">{{ $data->accountName }}</p>
                                                    <span class="account-number d-block">{{ $data->accountNo }}</span>
                                                    <small class="bank-name text-muted">{{ $data->bankName }}</small>
                                                </div>
                                                <div class="copy-btn-wrap ms-auto">
                                                    <button class="btn btn-outline-secondary btn-sm copy-account-number"
                                                        data-account="{{ $data->accountNo }}">
                                                        Copy
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            <hr>
                            <center>
                                <a style="text-decoration:none" class="mb-2" href="{{ route('user.support') }}">
                                    <small class="fw-semibol text-danger">If your funds is not
                                        received within 30mins.
                                        Please Contact Support
                                        <i class="mdi mdi-headphones mdi-12px" style="font-size:24px"></i>
                                    </small> </a>

                                <a style="text-decoration:none" href="{{ route('user.wallet') }}">
                                    <h4 class="fw-semibol text-danger">Go to wallet
                                        <i class="mdi mdi-wallet-outline mdi-36px" style="font-size:24px"></i>
                                    </h4>
                                </a>

                                @if (!auth()->user()->vwallet_is_created)
                                    <a href="{{ route('user.verify-user') }}">Genearte Virtual accounts</a>
                                @endif
                            </center>

                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @push('scripts')
            <script>
                @if ($kycPending)
                    const kycModal = new bootstrap.Modal(document.getElementById('kycModal'));
                    kycModal.show();
                @endif

                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('verify');
                    const submitButton = document.getElementById('submit');

                    if (form && submitButton) {
                        form.addEventListener('submit', function() {
                            submitButton.disabled = true;
                            submitButton.innerText = 'Verifying ...';
                        });
                    }
                });


                document.querySelectorAll('.copy-account-number').forEach(button => {
                    button.addEventListener('click', function() {
                        const acctNo = this.getAttribute('data-account');
                        navigator.clipboard.writeText(acctNo);
                        this.innerText = 'Copied!';
                        setTimeout(() => {
                            this.innerText = 'Copy';
                        }, 2000);
                    });
                });
            </script>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const data = @json($depositChartData);
                    const labels = Object.keys(data);
                    const values = Object.values(data);

                    const ctx = document.getElementById('depositBreakdownChart');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Deposits Breakdown',
                                    data: values,
                                    backgroundColor: [
                                        'rgba(25, 135, 84, 0.7)',
                                        'rgba(255, 193, 7, 0.7)',
                                        'rgba(220, 53, 69, 0.7)'
                                    ],
                                    borderColor: [
                                        'rgba(25, 135, 84, 1)',
                                        'rgba(255, 193, 7, 1)',
                                        'rgba(220, 53, 69, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: context =>
                                                `${context.label}: ₦${context.parsed.toLocaleString()}`
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
              @if (!empty($popup))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let popupDiv = document.createElement('div');
                        popupDiv.innerHTML = `
                                <div class="modal fade" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title text-white" id="popupModalLabel"> {{ $popup->title ?? 'Notice' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {!! nl2br(e(str_replace('{name}', auth()->user()->name, $popup->message))) !!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            `;
                        document.body.appendChild(popupDiv);
                        let modal = new bootstrap.Modal(document.getElementById('popupModal'));
                        modal.show();
                    });
                </script>
            @endif
            <script>
                const ctx = document.getElementById('fundingChart').getContext('2d');

                const data = {
                    labels: @json($topFunders->pluck('name')), // user names
                    datasets: [{
                        label: 'Top 5 Funders Today',
                        data: @json($topFunders->pluck('total_funding')), // funding amounts
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(153, 102, 255, 0.6)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1,
                    }]
                };

                new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let index = context.dataIndex;
                                        let email = @json($topFunders->pluck('email'))[index];
                                        let amount = context.formattedValue;
                                        return email + ': ₦' + amount;
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Top 5 Funders for Today'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₦' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }

                });
            </script>
        @endpush
