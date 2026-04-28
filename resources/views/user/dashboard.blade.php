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

        /* Premium Service Cards Redesign */
        .service-card {
            border-radius: 1.25rem !important;
            border: 1px solid rgba(0,0,0,0.03) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            background: #ffffff;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 15px 30px -5px rgba(30, 64, 175, 0.1), 0 10px 15px -5px rgba(0, 0, 0, 0.04) !important;
            border-color: rgba(30, 64, 175, 0.15) !important;
        }
        .service-icon-wrapper {
            width: 72px;
            height: 72px;
            background: #f8fafc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem auto;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.02);
        }
        .service-card:hover .service-icon-wrapper {
            background: #eff6ff;
            transform: scale(1.08);
            box-shadow: 0 0 0 6px rgba(239, 246, 255, 0.5);
        }
        .service-icon-wrapper img {
            max-width: 38px;
            max-height: 38px;
            object-fit: contain;
            transition: all 0.3s ease;
        }
        .service-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0;
            line-height: 1.3;
            transition: color 0.3s ease;
        }
        .service-card:hover .service-card-title {
            color: #1e40af;
        }
        
        @media (max-width: 576px) {
            .service-icon-wrapper {
                width: 60px;
                height: 60px;
                margin-bottom: 0.75rem;
            }
            .service-icon-wrapper img {
                max-width: 32px;
                max-height: 32px;
            }
            .service-card-title {
                font-size: 0.85rem;
            }
            .service-card {
                padding: 1rem !important;
            }
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
                <div class="col-lg-12 col-12 col-md-6">
                    <div class="container py-3" style="max-width: 100%">
                        <h4 class="fw-light mb-4 text-center">Our Services</h4>
                        <div class="row g-3 g-md-4 justify-content-center">

                            <!-- Verify NIN -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">Verify NIN</h5>
                                    <a href="{{ route('user.verify-nin') }}" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- Verify NIN Phone -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">Verify NIN Phone</h5>
                                    <a href="{{ route('user.verify-nin-phone') }}" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- Verify NIN Demographic -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">Verify NIN Demographic</h5>
                                    <a href="{{ route('user.verify-demo') }}" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- IPE Clearance -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">IPE Clearance</h5>
                                    <a href="{{ route('user.ipe') }}" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- Verify BVN -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/bvn.png') }}" alt="BVN">
                                    </div>
                                    <h5 class="service-card-title text-center">Verify BVN</h5>
                                    <a href="{{ route('user.verify-bvn') }}" class="stretched-link"></a>
                                </div>
                            </div>

                             <!-- BVN RETRIVAL -->
                             <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/bvn.png') }}" alt="BVN">
                                    </div>
                                    <h5 class="service-card-title text-center">BVN RETRIVAL</h5>
                                    <a href="{{ route('user.bvn-phone-search') }}" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- Personalize -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">Personalize</h5>
                                    <a href="{{ route('user.personalize-nin') }}" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- Instant Validation -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">Instant Validation</h5>
                                    <a href="#" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- NIN Validation -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">NIN Validation</h5>
                                    <a href="{{ route('user.nin-validation')}}" class="stretched-link"></a>
                                </div>
                            </div>
                            
                            <!-- NIN Delinking -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">NIN Delinking</h5>
                                    <a href="{{ route('user.nin.delink')}}" class="stretched-link"></a>
                                </div>
                            </div>

                            <!-- Email Retrieval -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="card service-card position-relative p-4">
                                    <div class="service-icon-wrapper">
                                        <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC">
                                    </div>
                                    <h5 class="service-card-title text-center">Email Retrieval</h5>
                                    <a href="{{ route('user.email.retrive')}}" class="stretched-link"></a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right side column for transaction table -->
                <div class="col-lg-12 stretch-card mt-">
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
