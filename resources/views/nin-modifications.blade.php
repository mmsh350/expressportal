@extends('layouts.dashboard')

@section('title', 'NIN Modifications')
@push('styles')
    <style>
        .pagination .page-link {
            min-width: 36px;
            text-align: center;
        }

        @media (max-width: 576px) {
            .pagination {
                font-size: 0.75rem;
            }
        }

        .sample-img {
            max-width: 150px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class="grid-margin stretch-card col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 mb-3">
                            <div class="mb-2">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <h6 class="text-center text-uppercase text-muted fw-semibold mb-3"
                                    style="font-size: 0.85rem;">
                                    Total Modification Requests
                                </h6>

                                <div class="row g-2 justify-content-center">
                                    @php
                                        $validationStats = [
                                            [
                                                'label' => 'All',
                                                'value' => $totalAll,
                                                'bg' => '#f8f9fa',
                                                'text' => 'text-dark',
                                                'border' => 'border',
                                            ],
                                            [
                                                'label' => 'Pending',
                                                'value' => $totalInProgress,
                                                'bg' => '#fff3cd',
                                                'text' => 'text-dark',
                                                'border' => 'border-warning',
                                            ],
                                            [
                                                'label' => 'Failed',
                                                'value' => $totalFailed,
                                                'bg' => '#f8d7da',
                                                'text' => 'text-danger',
                                                'border' => 'border-danger',
                                            ],
                                            [
                                                'label' => 'Successful',
                                                'value' => $totalSuccessful,
                                                'bg' => '#d1e7dd',
                                                'text' => 'text-success',
                                                'border' => 'border-success',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($validationStats as $stat)
                                        <div class="col-6 col-sm-3 col-lg-2">
                                            <div class="border rounded-3 text-center py-2 px-1 shadow-sm {{ $stat['text'] }}"
                                                style="background: {{ $stat['bg'] }}; font-size: 0.85rem;">
                                                <div class="small fw-light mb-1">{{ $stat['label'] }}</div>
                                                <div class="fw-bold" style="font-size: 1.1rem;">{{ $stat['value'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title">NIN Modifications</h4>
                        <p class="card-description">Submit NIN Modification Request</p>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="text-center mb-4">
                                    <img class="img-fluid d-block mx-auto mb-2" src="{{ asset('assets/images/nimc.png') }}" style="width: 50%; max-width: 250px;">
                                    <div class="px-2">
                                        <small class="font-italic text-danger d-block">
                                            @if($settings->nin_modification_notice)
                                                {!! $settings->nin_modification_notice !!}
                                            @else
                                                All modifications take 24-48 hours except DOB which takes 1-6 days.
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <div class="col-md-12">
                                        <form id="form" name="nin-request" method="POST" action="{{ route('user.nin.modifications.request') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12 mt-3 mb-3">
                                                    <select name="service_code" id="mod_type" class="form-select text-dark" required>
                                                        <option value="">-- Choose Modification Type & Fee --</option>
                                                        @foreach ($services as $service)
                                                            <option value="{{ $service->service_code }}" data-type-name="{{ $service->name }}">
                                                                {{ $service->name }} - &#x20A6;{{ number_format($service->amount, 2) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12" id="dynamic-fields">
                                                    <!-- Dynamic fields injected via JS -->
                                                </div>

                                                <div class="col-md-12 mt-3 mb-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Email (Self Service Account)</label>
                                                            <input type="email" name="email" class="form-control" placeholder="Optional">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Password (Self Service Account)</label>
                                                            <input type="password" name="password" class="form-control" placeholder="Optional">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label d-block text-center">Clear Picture (Optional)</label>
                                                            <input type="file" name="clear_picture" class="form-control" accept="image/*">
                                                            <div class="mt-2">
                                                                <small class="text-muted d-block mb-1">Sample Guide</small>
                                                                <img src="{{ asset('assets/images/nin_mod_sample.jpg') }}" class="sample-img img-fluid mx-auto" alt="Sample Picture">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="submit" id="nin-request" class="btn btn-primary">
                                                    <i class="las la-share"></i> Submit Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <form method="GET" action="{{ route('user.nin.modifications') }}" class="row g-2 mb-3 mt-4 align-items-end">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label for="search" class="form-label d-block d-md-none">Search</label>
                                            <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search Here ...">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date_from" class="form-label d-block d-md-none">Start Date</label>
                                            <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date_to" class="form-label d-block d-md-none">End Date</label>
                                            <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                                        </div>
                                    </div>
                                </form>

                                @if (!$ninServices->isEmpty())
                                    @php
                                        $currentPage = $ninServices->currentPage();
                                        $perPage = $ninServices->perPage();
                                        $serialNumber = ($currentPage - 1) * $perPage + 1;
                                    @endphp

                                    <div class="table-responsive">
                                        <table class="table text-nowrap" style="background:#fafafc !important">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th width="5%">ID</th>
                                                    <th>Date</th>
                                                    <th>NIN Number</th>
                                                    <th>Service Type</th>
                                                    <th class="text-center">Status</th>
                                                    <th>Response</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ninServices as $data)
                                                    <tr>
                                                        <th>{{ $serialNumber++ }}</th>
                                                        <td>{{ $data->created_at->format('d M Y, h:i A') }}</td>
                                                        <td>{{ $data->nin }}</td>
                                                        <td>{{ $data->type }}</td>
                                                        <td class="text-center">
                                                            @if ($data->status == 'Successful')
                                                                <span class="badge bg-success">Successful</span>
                                                            @elseif($data->status == 'Failed')
                                                                <span class="badge bg-danger">FAILED</span>
                                                            @elseif($data->status == 'In-Progress')
                                                                <span class="badge bg-primary">IN-PROGRESS</span>
                                                            @else
                                                                <span class="badge bg-warning">PENDING</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($data->reason && strlen(strip_tags($data->reason)) > 50)
                                                                {!! Str::limit(strip_tags($data->reason), 50) !!}
                                                                <a href="javascript:void(0)" class="text-primary fw-bold ms-1" data-bs-toggle="modal" data-bs-target="#reason" data-reason="{{ $data->reason }}">
                                                                    Show All
                                                                </a>
                                                            @else
                                                                {!! $data->reason ?? '-' !!}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $ninServices->links('vendor.pagination.bootstrap-4') }}
                                        </div>
                                    </div>
                                @else
                                    <center>
                                        <img width="65%" src="{{ asset('assets/images/no-transaction.gif') }}" alt="">
                                    </center>
                                    <p class="text-center fw-semibold fs-15">No Request Available!</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reason Modal -->
            <div class="modal fade" id="reason" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Admin Response</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="message">No Message Yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($settings->nin_modification_popup)
        <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-warning text-dark border-0">
                        <h5 class="modal-title fw-bold"><i class="mdi mdi-alert-octagon me-2"></i>Important Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <p class="fs-5 mb-0">{!! nl2br(e($settings->nin_modification_popup)) !!}</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-primary px-5 rounded-pill" data-bs-dismiss="modal">I Understand</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $("#reason").on("shown.bs.modal", function(event) {
            var button = $(event.relatedTarget);
            var reason = button.data("reason");
            if (reason != "") $("#message").html(reason);
            else $("#message").html("No Message Yet.");
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form');
            const submitButton = document.getElementById('nin-request');

            if (form) {
                form.addEventListener('submit', function() {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Processing...';
                });
            }
        });

        $(document).ready(function() {
            @if($settings->nin_modification_popup)
                if ($('#infoModal').length > 0) {
                    try {
                        var myModal = new bootstrap.Modal(document.getElementById('infoModal'));
                        myModal.show();
                    } catch (e) {
                        if (typeof $.fn.modal !== 'undefined') {
                            $('#infoModal').modal('show');
                        }
                    }
                }
            @endif

            $('#mod_type').change(function() {
                const selectedOption = $(this).find('option:selected');
                const type = selectedOption.data('type-name');
                const container = $('#dynamic-fields');
                container.empty();

                if (!type) return;

                let fields = '';
                switch(type) {
                    case 'DOB MOD':
                        fields = `<div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">NIN Number</label>
                                    <input type="text" name="nin" class="form-control" required maxlength="11" pattern="\\d{11}" placeholder="Enter 11-digit NIN">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">New Date of Birth</label>
                                    <input type="date" name="dob" class="form-control" required>
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" required placeholder="Enter Phone Number">
                                  </div>`;
                        break;
                    case 'NAME MOD':
                    case 'PHONE NO MOD':
                        fields = `<div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">NIN Number</label>
                                    <input type="text" name="nin" class="form-control" required maxlength="11" pattern="\\d{11}" placeholder="Enter 11-digit NIN">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Surname</label>
                                    <input type="text" name="surname" class="form-control" required placeholder="Enter Surname">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required placeholder="Enter First Name">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Middle Name (Optional)</label>
                                    <input type="text" name="middle_name" class="form-control" placeholder="Enter Middle Name">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" required placeholder="Enter Phone Number">
                                  </div>`;
                        break;
                    case 'ADDRESS MOD':
                        fields = `<div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">NIN Number</label>
                                    <input type="text" name="nin" class="form-control" required maxlength="11" pattern="\\d{11}" placeholder="Enter 11-digit NIN">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">New Residential Address</label>
                                    <textarea name="address" class="form-control" required rows="2" placeholder="Enter Full Address"></textarea>
                                  </div>
                                  <div class="row g-2">
                                      <div class="col-6 mb-2 text-start">
                                        <label class="form-label small fw-bold">Town/City</label>
                                        <input type="text" name="town" class="form-control" required placeholder="City">
                                      </div>
                                      <div class="col-6 mb-2 text-start">
                                        <label class="form-label small fw-bold">LGA of Origin</label>
                                        <input type="text" name="lga_origin" class="form-control" required placeholder="LGA Origin">
                                      </div>
                                      <div class="col-6 mb-2 text-start">
                                        <label class="form-label small fw-bold">State of Origin</label>
                                        <input type="text" name="state_origin" class="form-control" required placeholder="State Origin">
                                      </div>
                                      <div class="col-6 mb-2 text-start">
                                        <label class="form-label small fw-bold">LGA of Residence</label>
                                        <input type="text" name="lga_residence" class="form-control" required placeholder="LGA Residence">
                                      </div>
                                      <div class="col-12 mb-2 text-start">
                                        <label class="form-label small fw-bold">State of Residence</label>
                                        <input type="text" name="state_residence" class="form-control" required placeholder="State Residence">
                                      </div>
                                  </div>`;
                        break;
                    case 'GENDER MOD':
                        fields = `<div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">NIN Number</label>
                                    <input type="text" name="nin" class="form-control" required maxlength="11" pattern="\\d{11}" placeholder="Enter 11-digit NIN">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Correct Gender</label>
                                    <select name="gender" class="form-select" required>
                                      <option value="Male">Male</option>
                                      <option value="Female">Female</option>
                                    </select>
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" required placeholder="Enter Phone Number">
                                  </div>`;
                        break;
                    case 'OTHER MOD':
                        fields = `<div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">NIN Number</label>
                                    <input type="text" name="nin" class="form-control" required maxlength="11" pattern="\\d{11}" placeholder="Enter 11-digit NIN">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Modification Detail</label>
                                    <input type="text" name="modification_type_detail" class="form-control" required placeholder="What are you modifying?">
                                  </div>
                                  <div class="mb-2 text-start">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" required placeholder="Enter Phone Number">
                                  </div>`;
                        break;
                }
                container.html(fields);
            });
        });
    </script>
@endpush
