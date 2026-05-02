@extends('layouts.dashboard')

@section('title', 'BVN User Request')

@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class=" grid-margin stretch-card col-md-10   grid-margin stretch-card ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">BVN Agent Request</h4>
                        <p class="card-description">Apply for BVN Agent Request: Become
                            an
                            Authorized Agent for BVN Support</p>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="new-tab" data-bs-toggle="tab" href="#new-1" role="tab"
                                    aria-controls="new-1" aria-selected="true">New</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history-1" role="tab"
                                    aria-controls="history-1" aria-selected="false" tabindex="-1">Request History</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="new-1" role="tabpanel" aria-labelledby="new-tab">

                                <center>
                                    <img class="img-fluid" src="{{ asset('assets/images/bvn.jpg') }}" width="30%">
                                </center>
                                <center>
                                    <small class="font-italic text-danger"><i>
                                        @if(!empty($settings->bvn_enrollment_notice))
                                            {!! nl2br(e($settings->bvn_enrollment_notice)) !!}
                                        @else
                                            Please note that this request will be processed in the next 5 Working days.
                                            Kindly provide a valid email address and phone number. In addition the email
                                            address and phone number provided should be unique to this user and not
                                            already associated with another registered user.
                                        @endif
                                    </i></small>
                                </center>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <form name="enroll" id="enroll" method="POST" action="{{ route('user.enroll-bvn') }}">
                                            @csrf
                                            <div class="mb-4 text-start">
                                                <h6 class="text-primary fw-bold mb-3">Agent Credentials</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Agent BVN</label>
                                                        <input type="text" name="agent_bvn" maxlength="11" class="form-control" placeholder="11-digit BVN" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Agent Location</label>
                                                        <input type="text" name="agent_location" class="form-control" placeholder="Office/Shop Location" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Kegow Account</label>
                                                        <input type="text" name="kegow_account" maxlength="10" class="form-control" placeholder="10-digit Account No." required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Account Name</label>
                                                        <input type="text" name="account_name" class="form-control" placeholder="Bank Account Name" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4 text-start">
                                                <h6 class="text-primary fw-bold mb-3">Personal Details</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">First Name</label>
                                                        <input type="text" name="first_name" class="form-control" placeholder="Legal First Name" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" placeholder="Legal Last Name" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Email Address</label>
                                                        <input type="email" name="email" class="form-control" placeholder="example@mail.com" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="text" name="phone" maxlength="11" class="form-control" placeholder="080XXXXXXXX" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Date of Birth</label>
                                                        <input type="date" name="dob" class="form-control" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Username (Optional)</label>
                                                        <input type="text" name="username" class="form-control" placeholder="Preferred handle" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4 text-start">
                                                <h6 class="text-primary fw-bold mb-3">Location & Business Details</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">State</label>
                                                        <input type="text" name="state" class="form-control" placeholder="e.g. Lagos" required />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">City</label>
                                                        <input type="text" name="city" class="form-control" placeholder="e.g. Ikeja" required />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">LGA</label>
                                                        <input type="text" name="lga" class="form-control" placeholder="Local Govt Area" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Geo Zone</label>
                                                        <select name="geo_zone" class="form-select" style="color: #000;" required>
                                                            <option value="">Select Zone</option>
                                                            <option value="North Central">North Central</option>
                                                            <option value="North East">North East</option>
                                                            <option value="North West">North West</option>
                                                            <option value="South East">South East</option>
                                                            <option value="South South">South South</option>
                                                            <option value="South West">South West</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label">Full Business Address</label>
                                                        <textarea class="form-control" name="address" rows="2" placeholder="Complete office/business address" required></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-info py-2 small mb-4 text-start">
                                                <p class="mb-1"><i class="mdi mdi-information-outline"></i>

                                                    <span class="fw-bold">Enrollment Fee: &#x20A6;{{ number_format($ServiceFee->amount, 2) }}</span>

                                            </div>

                                            <div class="text-center mt-4">
                                                <button type="submit" id="submit" class="btn btn-primary btn-lg px-5">
                                                    <i class="mdi mdi-send me-2"></i>Submit Agent Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="history-1" role="tabpanel" aria-labelledby="history-tab">


                                @if (!$enrollments->isEmpty())
                                    @php
                                        $currentPage = $enrollments->currentPage(); // Current page number
                                        $perPage = $enrollments->perPage(); // Number of items per page
                                        $serialNumber = ($currentPage - 1) * $perPage + 1; // Starting serial number for current page
                                    @endphp
                                    <div class="table-responsive">
                                        <table class="table text-nowrap" style="background:#fafafc !important">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th width="5%" scope="col">ID</th>
                                                    <th scope="col">Reference No.</th>
                                                    <th scope="col">Fullname</th>
                                                    <th scope="col" class="text-center">Status
                                                    </th>
                                                    <th scope="col" class="text-center">Response</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach ($enrollments as $data)
                                                    <tr>
                                                        <th scope="row">{{ $serialNumber++ }}</th>
                                                        <td>{{ $data->refno }}</td>
                                                        <td>{{ $data->fullname }}</td>
                                                        <td class="text-center">

                                                            @if ($data->status == 'successful')
                                                                <span
                                                                    class="badge bg-success">{{ Str::upper($data->status) }}</span>
                                                            @elseif($data->status == 'rejected')
                                                                <span
                                                                    class="badge bg-danger">{{ Str::upper($data->status) }}</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-warning">{{ Str::upper($data->status) }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a type="button" data-bs-toggle="modal" data-id="2"
                                                                data-reason="{{ $data->reason }}"
                                                                data-bs-target="#reason">

                                                                <i class="ti-info-alt" style="font-size:24px"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @php $i++ @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Pagination Links -->
                                        <div class="d-flex justify-content-center">
                                            {{ $enrollments->links('vendor.pagination.bootstrap-4') }}
                                        </div>
                                    </div>
                                @else
                                    <center><img width="65%" src="{{ asset('assets/images/no-transaction.gif') }}"
                                            alt=""></center>
                                    <p class="text-center fw-semibold  fs-15"> No Request
                                        Available!</p>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>

    <!-- Modals -->
    <div class="modal fade" id="reason" tabindex="-1" aria-labelledby="reason" data-bs-keyboard="true"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel2">Support Query</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="message">No Message Yet.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('enroll');
            const submitButton = document.getElementById('submit');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerText = 'Please wait while we process your request...';
            });
        });

        $("#reason").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);

            var reason = button.data("reason");
            if (reason != "") $("#message").html(reason);
            else $("#message").html("No Message Yet.");
        });
    </script>
@endpush
