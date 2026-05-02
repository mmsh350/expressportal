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
                        @include('common.message')
                        <!-- Redesigned Modification Header & Form -->
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-12">
                                <div class="text-center mb-5 mt-3">
                                    <img class="img-fluid d-block mx-auto mb-3" src="{{ asset('assets/images/nimc.png') }}" style="width: 50%; max-width: 180px;">
                                    <h2 class="fw-bold text-primary mb-2">NIN Modification Portal</h2>

                                    <div class="d-flex justify-content-center mt-3">
                                        <div class="alert alert-primary border-0 shadow-sm px-4 py-2 rounded-4 d-inline-block text-wrap" style="background-color: rgba(13, 110, 253, 0.08); max-width: 90%;">
                                            <i class="mdi mdi-information-outline me-2 fs-18 align-middle"></i>
                                            <span class="small fw-semibold">
                                                @if($settings->nin_modification_notice)
                                                    {!! $settings->nin_modification_notice !!}
                                                @else
                                                    Standard Processing: 24-48 hours (DOB: 1-6 days)
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5" style="background: #ffffff; border: 1px solid #edf2f9 !important;">
                                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 px-md-5">
                                        <h5 class="card-title mb-0 fw-bold"><i class="mdi mdi-fountain-pen-tip me-2 text-primary"></i>New Modification Request</h5>
                                    </div>
                                    <div class="card-body p-4 p-md-5 pt-2">
                                        <form id="form" name="nin-request" method="POST" action="{{ route('user.nin.modifications.request') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold text-dark mb-2">Modification Category</label>
                                                    <div class="input-group shadow-sm rounded-3">

                                                        <select name="service_code" id="mod_type" class="form-select border-start-0 ps-0 text-dark py-3" required>
                                                            <option value="">-- Click here to choose modification type --</option>
                                                            @foreach ($services as $service)
                                                                <option value="{{ $service->service_code }}" data-type-name="{{ $service->name }}">
                                                                    {{ $service->name }} — ₦{{ number_format($service->amount, 0) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="dynamic-fields-wrapper">
                                                    <div id="dynamic-fields">
                                                        <!-- Fields injected by JS -->
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-4">
                                                    <h6 class="fw-bold text-dark mb-4 border-bottom pb-2">Support Details</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="form-floating shadow-sm rounded-3">
                                                                <input type="email" name="email" class="form-control" id="floatEmail" placeholder="name@example.com">
                                                                <label for="floatEmail">Self Service Email (Optional)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating shadow-sm rounded-3">
                                                                <input type="password" name="password" class="form-control" id="floatPass" placeholder="Password">
                                                                <label for="floatPass">Self Service Password (Optional)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 text-start mt-2">
                                                            <div class="alert alert-danger border-0 shadow-sm px-3 py-2 rounded-3 text-wrap d-inline-block w-100" style="background-color: rgba(220, 53, 69, 0.08);">
                                                                <i class="mdi mdi-alert me-1 fs-16"></i>
                                                                <small class="fw-bold">Note:</small> <small>Provide Email and password only if account has been created on self service and delinking has being done.</small>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 mt-4">
                                                            <div class="p-4 border rounded-4 bg-light shadow-sm border-dashed" style="border: 2px dashed #dee2e6 !important;">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-8 text-start">
                                                                        <label class="form-label fw-bold text-dark mb-2">Clear Picture</label>
                                                                        <input type="file" name="clear_picture" class="form-control py-2" accept="image/*">
                                                                        <div class="mt-3">
                                                                            <div class="alert alert-danger border-0 small py-2 mb-0">
                                                                                <i class="mdi mdi-information-outline me-1"></i> <strong>Note:</strong> You can verify the NIN and use the image if it is clear, otherwise snap a clear image of the customer showing their shoulders.
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 text-center mt-3 mt-md-0">
                                                                        <div class="bg-white p-2 rounded-3 border d-inline-block shadow-sm">
                                                                            <small class="text-muted d-block mb-2 fw-semibold">Upload Guide</small>
                                                                            <img src="{{ asset('assets/images/nin_mod_sample.jpg') }}" class="sample-img img-fluid rounded-2" style="max-height: 100px; width: auto;" alt="Sample">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 text-center mt-5">
                                                    <button type="submit" id="nin-request" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow fw-bold w-100 w-md-auto">
                                                        <i class="mdi mdi-check-circle me-2 fs-20"></i> Submit Request Now
                                                    </button>
                                                    <p class="text-muted small mt-3"><i class="mdi mdi-shield-check me-1"></i> Your data is processed securely via NIMC channels.</p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Summary Stats Section (Moved Below) -->
                                <div class="mt-4 pt-4 border-top">
                                    <h6 class="text-center text-uppercase text-muted fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">
                                        Your Request Statistics
                                    </h6>
                                    <div class="row g-3 justify-content-center">
                                        @php
                                            $validationStats = [
                                                ['label' => 'Total', 'value' => $totalAll, 'bg' => 'bg-white', 'text' => 'text-dark', 'icon' => 'mdi mdi-history', 'color' => 'primary'],
                                                ['label' => 'Pending', 'value' => $totalInProgress, 'bg' => 'bg-white', 'text' => 'text-warning', 'icon' => 'mdi mdi-timer-sand', 'color' => 'warning'],
                                                ['label' => 'Failed', 'value' => $totalFailed, 'bg' => 'bg-white', 'text' => 'text-danger', 'icon' => 'mdi mdi-close-circle', 'color' => 'danger'],
                                                ['label' => 'Success', 'value' => $totalSuccessful, 'bg' => 'bg-white', 'text' => 'text-success', 'icon' => 'mdi mdi-check-all', 'color' => 'success'],
                                            ];
                                        @endphp
                                        @foreach ($validationStats as $stat)
                                            <div class="col-6 col-sm-3 col-lg-2">
                                                <div class="card border shadow-sm text-center py-3 px-2 rounded-4 {{ $stat['bg'] }}">
                                                    <div class="avatar avatar-sm bg-light rounded-circle mx-auto mb-2 text-{{ $stat['color'] }} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="{{ $stat['icon'] }} fs-20"></i>
                                                    </div>
                                                    <div class="small text-muted mb-0">{{ $stat['label'] }}</div>
                                                    <div class="fw-bold fs-5 {{ $stat['text'] }}">{{ $stat['value'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search & Table Section -->
                        <div class="col-md-12 mt-5 pt-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h5 class="fw-bold mb-0">Modification History</h5>
                                <div class="badge bg-light text-dark border">Recent Entries</div>
                            </div>

                            <form method="GET" action="{{ route('user.nin.modifications') }}" class="row g-2 mb-4 align-items-end">
                                <div class="col-md-4 text-start">
                                    <label class="small fw-bold text-muted mb-1 ms-1">Search Requests</label>
                                    <div class="input-group shadow-sm rounded-3">

                                        <input type="text" name="search" class="form-control border-start-0 ps-0" value="{{ request('search') }}" placeholder="Search NIN or Ref...">
                                    </div>
                                </div>
                                <div class="col-md-3 text-start">
                                    <label class="small fw-bold text-muted mb-1 ms-1">From Date</label>
                                    <input type="date" name="date_from" class="form-control shadow-sm rounded-3" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3 text-start">
                                    <label class="small fw-bold text-muted mb-1 ms-1">To Date</label>
                                    <input type="date" name="date_to" class="form-control shadow-sm rounded-3" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-dark w-100 rounded-3 shadow-sm py-2">Filter</button>
                                </div>
                            </form>

                            @if (!$ninServices->isEmpty())
                                <div class="table-responsive rounded-4 shadow-sm border">
                                    <table class="table table-hover align-middle mb-0" style="background: #fff;">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">SN</th>
                                                <th>Date</th>
                                                <th>NIN Number</th>
                                                <th>Service</th>
                                                <th class="text-center">Status</th>
                                                <th class="pe-4">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $sn = ($ninServices->currentPage() - 1) * $ninServices->perPage() + 1; @endphp
                                            @foreach ($ninServices as $data)
                                                <tr>
                                                    <td class="ps-4 fw-semibold text-muted">{{ $sn++ }}</td>
                                                    <td class="small">{{ $data->created_at->format('d M Y, h:i A') }}</td>
                                                    <td class="fw-bold">{{ $data->nin }}</td>
                                                    <td><span class="badge bg-soft-info text-info border border-info">{{ $data->type }}</span></td>
                                                    <td class="text-center">
                                                        @if ($data->status == 'Successful')
                                                            <span class="badge rounded-pill bg-success px-3">Successful</span>
                                                        @elseif($data->status == 'Failed')
                                                            <span class="badge rounded-pill bg-danger px-3">Failed</span>
                                                        @elseif($data->status == 'In-Progress')
                                                            <span class="badge rounded-pill bg-primary px-3">In-Progress</span>
                                                        @else
                                                            <span class="badge rounded-pill bg-warning px-3 text-dark">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        @if ($data->reason)
                                                            <button class="btn btn-soft-primary btn-sm rounded-pill px-3 fw-bold shadow-md"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#reason"
                                                                    data-reason="{{ $data->reason }}">
                                                                <i class="mdi mdi-eye-outline me-1"></i> View Results
                                                            </button>
                                                        @else
                                                            <span class="small text-muted italic">No details yet</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $ninServices->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            @else
                                <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
                                    <img width="180" src="{{ asset('assets/images/no-transaction.gif') }}" alt="No Data">
                                    <p class="text-muted fw-semibold mt-3">No modification history found.</p>
                                </div>
                            @endif
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
                    <div class="modal-header bg-primary text-light border-0">
                        <h5 class="modal-title text-light fw-bold"><i class="mdi mdi-alert-octagon me-2"></i>Important Information</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-0 small">{!! $settings->nin_modification_popup !!}</div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-primary px-5 rounded-pill" data-bs-dismiss="modal">I Understand</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modals -->
    <div class="modal fade" id="reason" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white border-0">
                    <h6 class="modal-title fw-bold text-white" id="staticBackdropLabel2"><i class="mdi mdi-eye me-1"></i> Modification Results</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="message" class="text-dark fs-15">No Message Yet.</div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("#reason").on("show.bs.modal", function(event) {
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
                                    <select name="gender" class="form-select" style="color: #000" required>
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
