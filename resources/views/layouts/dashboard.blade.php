<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', $settings->site_name ?? config('app.name'))</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.base.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->
    <link rel="shortcut icon"
        href="{{ asset('assets/images/' . $settings->favicon ?? 'assets/images/default_favicon.png') }}">
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- endinject -->
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    @stack('styles')
    <style>
        /* Sidebar base styling */
        .sidebar {
            background-color: #1e40af;
            /* Your brand navy */
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            /* Matching your main font */
            padding-top: 7px;
            padding-bottom: 10px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            /* Subtle divider */
            margin: 0 10px;
        }

        .sidebar .nav-link {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            font-weight: 400;
            transition: all 0.2s ease;
            border-radius: 4px;
            margin: 2px 0;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 500;
            border-left: 3px solid #ffffff;
        }

        .sidebar .nav .nav-item .nav-link .menu-title {
            color: inherit;
        }

        .sidebar .menu-icon {
            margin-right: 12px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .menu-title {
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        /* Sub-menu styling */
        .sidebar .sub-menu {
            display: none;
            padding-left: 1.5rem;
            background-color: rgba(30, 64, 175, 0.8);
            /* Darker navy */
            border-left: 2px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .sub-menu.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .sidebar .sub-menu .nav-link {
            padding: 12px 20px;
            font-size: 14px;
            text-transform: none;
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .sub-menu .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Profile section */
        .sidebar-profile {
            background-color: rgba(0, 0, 0, 0.15);
            padding: 15px;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-profile-info {
            color: #ffffff;
        }

        .sidebar-profile-name {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .sidebar-profile-email small {
            opacity: 0.8;
            font-size: 0.85rem;
        }

        /* Collapsed state */
        .sidebar-icon-only .sidebar .sidebar-profile-info,
        .sidebar-icon-only .sidebar .sidebar-profile {
            display: none !important;
        }

        .sidebar-icon-only .sidebar .nav-link {
            justify-content: center;
            padding: 15px 10px;
        }

        .sidebar-icon-only .sidebar .menu-title,
        .sidebar-icon-only .sidebar .nav-link i.ms-auto {
            display: none !important;
        }

        .sidebar-icon-only .sidebar .nav-item {
            position: relative;
        }

        /* Hide submenu by default when collapsed */
        .sidebar-icon-only .sidebar .sub-menu {
            display: none !important;
        }

        /* Show submenu as a floating dropdown when clicked while collapsed */
        .sidebar-icon-only .sidebar .sub-menu.show {
            display: block !important;
            position: absolute;
            left: 100%;
            top: 0;
            width: 200px;
            background-color: #1e3a8a; /* Slightly darker than sidebar to distinguish */
            border-radius: 0 8px 8px 0;
            box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.15);
            padding: 10px 0;
            z-index: 1050;
        }

        .sidebar-icon-only .sidebar .sub-menu.show .nav-link {
            justify-content: flex-start;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .sidebar-icon-only .sidebar .sub-menu.show .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-icon-only .sidebar .menu-icon {
            margin-right: 0;
            font-size: 20px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                padding-top: 5px;
            }

            .sidebar .nav-link {
                padding: 12px 15px;
                font-size: 14px;
            }
        }

        /* Modern UI Overrides */
        body {
            background-color: #f3f4f6 !important;
            font-family: 'Inter', sans-serif !important;
            color: #111827;
        }
        .main-panel {
            background-color: #f3f4f6 !important;
        }
        .content-wrapper {
            background-color: #f3f4f6 !important;
            padding: 2.5rem 2rem !important;
        }

        /* Modern Cards */
        .card {
            border: none !important;
            border-radius: 1rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.2s ease-in-out;
            background-color: #ffffff !important;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            transform: translateY(-2px);
        }
        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid #f3f4f6 !important;
            padding: 1.5rem !important;
            font-weight: 600;
        }
        .card-body {
            padding: 1.5rem !important;
        }

        /* Modern Inputs and Buttons */
        .form-control, .form-select {
            border-radius: 0.5rem !important;
            border-color: #e5e7eb !important;
            padding: 0.6rem 1rem !important;
            box-shadow: none !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1e40af !important;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1) !important;
        }
        .btn {
            border-radius: 0.5rem !important;
            padding: 0.5rem 1.25rem !important;
            font-weight: 500 !important;
            transition: all 0.2s !important;
            border: none !important;
        }
        .btn-primary {
            background-color: #1e40af !important;
            color: #ffffff !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }
        .btn-primary:hover {
            background-color: #1e3a8a !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }
        .btn-outline-primary {
            border: 1px solid #1e40af !important;
            color: #1e40af !important;
            background: transparent !important;
        }
        .btn-outline-primary:hover {
            background-color: #1e40af !important;
            color: #ffffff !important;
        }

        /* Navbar and Sidebar Adjustments */
        .navbar {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px) !important;
            border-bottom: 1px solid #f3f4f6 !important;
        }
        .sidebar {
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05) !important;
        }

        .navbar .nav-profile-name {
            font-weight: 400 !important;
        }

        /* Tables */
        .table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }
        .table thead th {
            background-color: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
            border-top: none !important;
            color: #4b5563 !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em !important;
            padding: 1rem !important;
        }
        .table tbody td {
            padding: 1rem !important;
            border-bottom: 1px solid #f3f4f6 !important;
            vertical-align: middle !important;
        }

        /* Alerts */
        .alert {
            border-radius: 0.75rem !important;
            border: none !important;
        }
        .alert-danger {
            background-color: #fef2f2 !important;
            color: #991b1b !important;
        }
        .alert-success {
            background-color: #f0fdf4 !important;
            color: #166534 !important;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            color: #111827 !important;
            font-weight: 600 !important;
        }
        .text-muted {
            color: #6b7280 !important;
        }

        /* Sidebar Category Headers */
        .sidebar .nav-category {
            padding: 1.5rem 1.5rem 0.5rem 1.5rem;
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 10px;
        }

        .sidebar-icon-only .sidebar .nav-category {
            display: none;
        }
    </style>
</head>

<body>
    <div class="page-loading" id="loader">
        <div class="page-loading-inner">



            <div class="loader-demo-box mb-5" style="height:0px; border:0px !important;">
                <div class="circle-loader"></div>
            </div>


            <h6 class="loader-text">
                {{ $settings->short_name ?? config('app.name') }}
            </h6>

        </div>

    </div>
    <div class="container-scroller">
        @include('partials.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('partials.sidebar')

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    @yield('content')

                </div>
                <!-- content-wrapper ends -->

                @include('partials.footer')
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- NIMC License Modal -->
    <div class="modal fade" id="nimcLicenseModal" tabindex="-1" aria-labelledby="nimcLicenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mb-4">
                        <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; border: 1px solid #f1f5f9;">
                            <img src="{{ asset('assets/images/nimc.png') }}" alt="NIMC" style="width: 50px;">
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">NIMC Enrollment License</h4>
                    <p class="text-muted mb-4 px-2">
                        Become a certified NIMC Enrollment Agent. This license grants you the official authority to enroll citizens for NIN, providing you with a specialized enrollment machine, software access, and technical support.
                    </p>
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <span class="d-block text-muted small text-uppercase fw-bold mb-1">Registration Fee</span>
                        <h3 class="fw-bold text-primary mb-0">₦{{ $settings->nimc_license_price ?? '180,000' }}</h3>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.support') }}" target="_blank" class="btn btn-primary btn-lg rounded-pill fw-bold">
                            Purchase Now
                        </a>
                        <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">
                            Maybe Later
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('assets/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        function toggleSubmenu(e, id) {
            e.preventDefault();
            const submenu = document.getElementById(id);
            submenu.classList.toggle('show');
        }
    </script>
    <!-- endinject -->

    <!-- Custom js for this page-->

    @stack('scripts')

</body>

</html>
