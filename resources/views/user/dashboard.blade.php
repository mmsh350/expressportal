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
        /* Combo Device Showcase Styles */
        .combo-card {
            border-radius: 1.5rem !important;
            border: none !important;
            background: #ffffff !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            overflow: hidden;
            height: 100%;
        }
        .combo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(30, 64, 175, 0.15) !important;
        }
        .combo-img-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        .combo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .combo-card:hover .combo-img {
            transform: scale(1.1);
        }
        .combo-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            z-index: 10;
        }
        .combo-price-tag {
            background: rgba(30, 64, 175, 0.9);
            backdrop-filter: blur(5px);
            color: white;
            padding: 8px 15px;
            border-radius: 0 1rem 0 0;
            position: absolute;
            bottom: 0;
            left: 0;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .gallery-indicator {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.65rem;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <!-- Welcome Header -->
        <div class="col-12">
            <div class="welcome-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @php
                            $hour = date('H');
                            $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            @if (auth()->user()->profile_pic)
                                <img src="{{ 'data:image/;base64,' . auth()->user()->profile_pic }}" class="rounded-circle border border-2 border-white me-3"
                                    alt="Profile" style="width: 60px; height: 60px; object-fit: cover;" />
                            @else
                                <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                    <i class="mdi mdi-account text-white fs-3"></i>
                                </div>
                            @endif
                            <h2 class="mb-0 text-white">{{ $greeting }}, {{ auth()->user()->name ?? 'User' }} 👋</h2>
                        </div>
                        <p class="mb-0 text-white-50 ms-md-5 ps-md-4">Welcome to your premium dashboard. Ready for some transactions today?</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="mdi mdi-shield-check-outline text-white-50" style="font-size: 4rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        @if ($status == 'Pending')
            <div class="col-12 mb-4">
                <div class="alert alert-danger d-flex align-items-center p-3" role="alert">
                    <i class="mdi mdi-alert-circle me-3 fs-4"></i>
                    <div>
                        <strong>Account Verification Required:</strong> We need to verify your identity before activating your account.
                        <a href="{{ route('user.kyc') }}" class="alert-link text-decoration-underline ms-2">Complete KYC now</a>
                    </div>
                </div>
            </div>
        @endif

        @include('common.message')

        <!-- Wallet Section -->
        <div class="col-12 mb-4">
            <div class="row g-4">
                <!-- Main Wallet -->
                <div class="col-md-6">
                    <div class="wallet-card main-wallet shadow-sm">
                        <div class="wallet-label text-primary">
                            <i class="mdi mdi-wallet-outline me-2"></i> Main Wallet
                        </div>
                        <div class="wallet-balance text-dark">
                            ₦{{ auth()->user()->wallet ? number_format(auth()->user()->wallet->balance, 2) : '0.00' }}
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#walletModal" class="btn btn-primary rounded-pill px-4">
                                <i class="mdi mdi-plus-circle-outline me-1"></i> Add Funds
                            </a>
                            <a href="#transactions-list" class="btn btn-outline-primary rounded-pill px-4">
                                <i class="mdi mdi-history me-1"></i> History
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bonus Wallet -->
                <div class="col-md-6">
                    <div class="wallet-card bonus-wallet shadow-sm">
                        <div class="wallet-label text-danger">
                            <i class="mdi mdi-gift-outline me-2"></i> Bonus Balance
                        </div>
                        <div class="wallet-balance text-dark">
                            ₦{{ auth()->user()->wallet ? number_format(auth()->user()->wallet->bonus, 2) : '0.00' }}
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('user.wallet') }}" class="btn btn-danger rounded-pill px-4">
                                <i class="mdi mdi-arrow-up-bold-circle-outline me-1"></i> Claim Bonus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 mb-4">
            <h6 class="text-uppercase fw-bold text-muted small mb-3" style="letter-spacing: 0.1em;">Quick Actions</h6>
            <div class="quick-actions-bar">
                <a href="{{ route('user.verify-nin') }}" class="quick-action-btn">
                    <i class="mdi mdi-account-search text-primary"></i> NIN Verify
                </a>
                <a href="{{ route('user.verify-bvn') }}" class="quick-action-btn">
                    <i class="mdi mdi-bank text-success"></i> BVN Verify
                </a>

                <a href="{{ route('user.support') }}" class="quick-action-btn">
                    <i class="mdi mdi-help-circle text-info"></i> Support
                </a>
            </div>
        </div>
    </div>

    <!-- Super Admin Section -->
    @if (auth()->user()->role == 'super_admin')
        <div class="row">
            <div class="col-12 mb-4">
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

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-transparent border-0 pt-4 px-4">
                                <h5 class="card-title mb-0 fw-bold">Daily Charts</h5>
                            </div>
                            <div class="card-body p-4">
                                <div style="height: 300px;">
                                    <canvas id="depositBreakdownChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-transparent border-0 pt-4 px-4">
                                <h5 class="card-title mb-0 fw-bold">Top Funding</h5>
                            </div>
                            <div class="card-body p-4">
                                <div style="height: 300px;">
                                    <canvas id="fundingChart"></canvas>
                                </div>
                            </div>
                        </div>
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

                                    <!-- Verification Service (Soon) -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card disabled position-relative p-4 text-center">
                                            <span class="coming-soon-badge">Soon</span>
                                            <div class="service-icon-wrapper">
                                                <i class="mdi mdi-shield-check text-primary fs-2"></i>
                                            </div>
                                            <h5 class="service-card-title">Verification As A Service</h5>
                                            <p class="text-muted small mb-0">Identity Verification</p>
                                            <a href="javascript:void(0)" class="stretched-link"></a>
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

                                    <!-- NIN Personalize (Soon) -->
                                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                        <div class="card service-card disabled position-relative p-4 text-center">
                                            <span class="coming-soon-badge">Soon</span>
                                            <div class="service-icon-wrapper">
                                                <i class="mdi mdi-magnify text-primary fs-2"></i>
                                            </div>
                                            <h5 class="service-card-title">NIN Personalize</h5>
                                            <p class="text-muted small mb-0">Personalize NIN</p>
                                            <a href="javascript:void(0)" class="stretched-link"></a>
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

            <!-- Combo Device Showcase Section -->
            @if(isset($comboDevices) && $comboDevices->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-4">
                        <div class="pe-4">
                            <h3 class="fw-bold mb-1">Premium Device Combos</h3>
                            <p class="text-muted small mb-0">Exclusive deals on high-quality devices and accessories</p>
                        </div>
                        <div class="flex-grow-1 border-bottom opacity-10"></div>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($comboDevices as $combo)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card combo-card">
                            <div class="combo-img-container">
                                <span class="combo-badge shadow-sm {{ $combo->condition == 'New' ? 'bg-success text-white' : ($combo->condition == 'Refurbished' ? 'bg-info text-white' : 'bg-warning text-dark') }}">
                                    {{ $combo->condition }}
                                </span>

                                @if($combo->images && count($combo->images) > 0)
                                    <img src="{{ asset('storage/' . $combo->images[0]) }}" class="combo-img" alt="{{ $combo->title }}">
                                    <div class="gallery-indicator">
                                        <i class="mdi mdi-image-multiple me-1"></i> {{ count($combo->images) }} Photos
                                    </div>
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="mdi mdi-image-off outline text-muted fs-1"></i>
                                    </div>
                                @endif

                                <div class="combo-price-tag">
                                    ₦{{ number_format($combo->price, 0) }}
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2">{{ $combo->title }}</h5>
                                <p class="text-muted small mb-4" style="height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $combo->description }}
                                </p>

                                <div class="d-flex gap-2">
                                    @if($combo->images && count($combo->images) > 0)
                                    <button class="btn btn-outline-primary btn-sm rounded-pill flex-fill" data-bs-toggle="modal" data-bs-target="#galleryModal{{ $combo->id }}">
                                        <i class="mdi mdi-eye-outline me-1"></i> View Gallery
                                    </button>
                                    @endif

                                    @php
                                        $waNumber = $settings->whatsapp_number ?? '';
                                        $waMessage = "Hello, I am interested in the " . $combo->title . " (₦" . number_format($combo->price) . ") listed on ExpressPortal.";
                                        if ($waNumber) {
                                            $finalWaUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waNumber) . "?text=" . urlencode($waMessage);
                                        } else {
                                            $waUrl = $settings->whatsapp_url ?? '#';
                                            $finalWaUrl = $waUrl . (str_contains($waUrl, '?') ? '&' : '?') . "text=" . urlencode($waMessage);
                                        }
                                    @endphp
                                    <a href="{{ $finalWaUrl }}" target="_blank" class="btn btn-success btn-sm rounded-pill flex-fill text-white shadow-sm">
                                        <i class="mdi mdi-whatsapp me-1"></i> Interested? Chat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Modal -->
                    <div class="modal fade" id="galleryModal{{ $combo->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem; background: #f8f9fa;">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="fw-bold mb-0">{{ $combo->title }} Gallery</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row g-3">
                                        @if($combo->images)
                                            @foreach($combo->images as $img)
                                            <div class="col-6 col-md-4">
                                                <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow-sm border" style="height: 200px; width: 100%; object-fit: cover;">
                                                </a>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="bg-white p-3 rounded-4 mt-4 border border-light shadow-sm">
                                        <h6 class="fw-bold small text-primary text-uppercase mb-2">Description</h6>
                                        <p class="mb-0 text-muted">{{ $combo->description }}</p>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <a href="{{ $finalWaUrl }}" target="_blank" class="btn btn-success rounded-pill px-4 text-white">
                                        <i class="mdi mdi-whatsapp me-1"></i> Order via WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Right side column for transaction table -->
            <div class="col-lg-12 stretch-card mt-2" id="transactions-list">
                    <div class="container py-3" style="max-width: 100%">
                        <div class="d-flex align-items-center justify-content-center mb-4">
                            <div class="flex-grow-1 border-bottom opacity-10"></div>
                            <h4 class="fw-bold px-4 mb-0 text-uppercase small text-muted" style="letter-spacing: 0.2em;">Recent Transactions</h4>
                            <div class="flex-grow-1 border-bottom opacity-10"></div>
                        </div>
                        <div class="card h-100 shadow-sm border-0" style="border-radius: 1.25rem;">
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
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="walletModalLabel">Fund Wallet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="text-center mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                    <i class="mdi mdi-bank-plus text-primary fs-3"></i>
                                </div>
                                <h5 class="fw-bold mb-1">Fund Your Wallet</h5>
                                <p class="text-muted small">Transfer to any of the accounts below for instant funding</p>
                            </div>
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
