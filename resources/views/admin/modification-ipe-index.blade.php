@extends('layouts.dashboard')

@section('title', 'Modification IPE Clearance')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
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
        .form-check .form-check-input {
            margin-left: 0;
        }
        .form-check-input, .form-check-label {
            cursor: pointer;
        }
        .modal-backdrop {
            z-index: 9998 !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class=" grid-margin stretch-card col-md-12   grid-margin stretch-card ">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 mb-3">
                            <div class="mb-2">
                                <h6 class="text-center text-uppercase text-muted fw-semibold mb-3"
                                    style="font-size: 0.85rem;">
                                    Total Modification IPE Clearance Requests
                                </h6>

                                <div class="row g-2 justify-content-center">
                                    @php
                                        $validationStats = [
                                            [
                                                'label' => 'All',
                                                'value' => $total_request,
                                                'bg' => '#f8f9fa',
                                                'text' => 'text-dark',
                                                'border' => 'border',
                                            ],
                                            [
                                                'label' => 'Pending',
                                                'value' => $pending,
                                                'bg' => '#fff3cd',
                                                'text' => 'text-dark',
                                                'border' => 'border-warning',
                                            ],
                                            [
                                                'label' => 'Processing',
                                                'value' => $processing,
                                                'bg' => '#cfe2ff',
                                                'text' => 'text-primary',
                                                'border' => 'border-primary',
                                            ],
                                            [
                                                'label' => 'Failed',
                                                'value' => $rejected,
                                                'bg' => '#f8d7da',
                                                'text' => 'text-danger',
                                                'border' => 'border-danger',
                                            ],
                                            [
                                                'label' => 'Successful',
                                                'value' => $resolved,
                                                'bg' => '#d1e7dd',
                                                'text' => 'text-success',
                                                'border' => 'border-success',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($validationStats as $stat)
                                        <div class="col-4 col-sm-3 col-lg-2">
                                            <div class="border rounded-3 text-center py-2 px-1 shadow-sm {{ $stat['text'] }}"
                                                style="background: {{ $stat['bg'] }}; font-size: 0.85rem;">
                                                <div class="small fw-light mb-1">{{ $stat['label'] }}</div>
                                                <div class="fw-bold" style="font-size: 1.1rem;">{{ $stat['value'] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title">Modification IPE Clearance</h4>
                        <p class="card-description">Modify the status of the modification request from this section.</p>

                        {{-- <div class="d-flex justify-content-end mb-3 gap-2 flex-wrap">
                            @if($refund_count > 0)
                                <a href="{{ route('admin.modification.ipe.refund') }}" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to refund all failed modification transactions?')">
                                    <i class="ri-refresh-line"></i> Refund All Failed ({{ $refund_count }})
                                </a>
                            @endif
                            <a href="{{ route('admin.modification.ipe.download-template') }}" class="btn btn-info btn-sm">
                                <i class="ri-download-line"></i> Download Pending Template
                            </a>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">
                                <i class="ri-file-excel-line"></i> Upload Resolved Excel
                            </button>
                        </div> --}}

                        <div class="row">

                          <div class="col-xl-12">

                                    <div class="card custom-card ">

                                        <div class="card-body">
                                            @if (session('success'))
                                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    {!! session('success') !!}
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
                                            <div class="col-12  mb-3">

                                                <form action="{{ route('admin.modification.ipe.index') }}" method="GET">
                                                    <div class="row g-2">
                                                        <div class="col-md-3">
                                                            <input type="text" name="search" class="form-control"
                                                                value="{{ request('search') }}"
                                                                placeholder="Search by Tracking ID "
                                                                autocomplete="off">
                                                        </div>

                                                        <div class="col-md-2">
                                                            <input type="date" name="date_from" class="form-control"
                                                                value="{{ request('date_from') }}"
                                                                placeholder="Start Date">
                                                        </div>

                                                        <div class="col-md-2">
                                                            <input type="date" name="date_to" class="form-control"
                                                                value="{{ request('date_to') }}" placeholder="End Date">
                                                        </div>

                                                        <div class="col-md-2">
                                                            <select name="per_page" class="form-select">
                                                                <option value="5"
                                                                    {{ request('per_page') == 5 ? 'selected' : '' }}>5 per
                                                                    page</option>
                                                                <option value="10"
                                                                    {{ request('per_page') == 10 ? 'selected' : '' }}>10
                                                                    per page</option>
                                                                <option value="25"
                                                                    {{ request('per_page') == 25 ? 'selected' : '' }}>25
                                                                    per page</option>
                                                                <option value="50"
                                                                    {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                                                    per page</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-primary w-100">Filter</button>
                                                        </div>
                                                    </div>
                                                </form>


                                            </div>

                                            @if (!$ipeRequests->isEmpty())
                                                @php
                                                    // Calculate serial number based on pagination
                                                    $currentPage = $ipeRequests->currentPage(); // Current page number
                                                    $perPage = $ipeRequests->perPage(); // Number of items per page
                                                    $serialNumber = ($currentPage - 1) * $perPage + 1; // Starting serial number for the current page
                                                @endphp

                                                <div class="table-responsive">
                                                    <table class="table"
                                                        style="background:#fafafc !important">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%" class="cust2"
                                                                    scope="col">
                                                                    ID</th>
 <th scope="col" class="cust2 ">Date</th>
                                                                <th class="cust2 ">Tracking ID</th>
                                                                <th scope="col" class="cust2 ">Reply
                                                                </th>

                                                                <th scope="col" class="text-center ">
                                                                    Status</th>
                                                                <th scope="col">Actions</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($ipeRequests as $data)
                                                                <tr>
                                                                    <th scope="row">{{ $serialNumber++ }}</th>
                                                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y h:i A') }}
                                                                    </td>
                                                                    <td>{{ $data->trackingId }}</td>
                                                                    <td>
                                                                        <div class="text-truncate" style="max-width: 200px;" title="{{ strip_tags($data->reply) }}">
                                                                            {!! strip_tags($data->reply) !!}
                                                                        </div>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        @if ($data->resp_code == '200')
                                                                            <span
                                                                                class="badge bg-success">Sucessful</span>
                                                                        @elseif ($data->resp_code == '400')
                                                                            <span
                                                                                class="badge bg-danger">Failed</span>
                                                                        @elseif ($data->resp_code == '100')
                                                                            <span
                                                                                class="badge bg-warning">Pending</span>
                                                                        @else
                                                                            <span class="badge bg-primary">
                                                                                Processing
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($data->resp_code != '400')
                                                                            <a href="{{ route('admin.modification.ipe.view-request', $data->id) }}"
                                                                                class="btn btn-primary btn-sm">
                                                                                <i class="ri-eye-line"></i> View
                                                                            </a>
                                                                            <button type="button" class="btn btn-light btn-sm"
                                                                                data-bs-toggle="modal" data-bs-target="#replyModal"
                                                                                data-id="{{ $data->id }}"
                                                                                data-tracking="{{ $data->trackingId }}"
                                                                                data-trxamount="{{ $data->transaction->amount ?? 0 }}">
                                                                                <i class="ri-reply-line"></i> Reply
                                                                            </button>
                                                                        @else
                                                                            <span class="badge bg-secondary"></span>
                                                                        @endif
                                                                    </td>


                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                    <!-- Pagination Links -->
                                                    <div class="d-flex justify-content-center">
                                                        {{ $ipeRequests->links('vendor.pagination.bootstrap-5') }}
                                                    </div>
                                                </div>

                                             @endif
                                         </div>

                                     </div>
                         </div>
                         </div>
                     </div>
                 </div>
             </div>

         </div>
     </div>

     <!-- Reply Modal moved to bottom for better stacking context -->
     <div class="modal shadow-lg" id="replyModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
         <div class="modal-dialog modal-dialog-centered">
             <div class="modal-content border-0 shadow-sm">
                 <div class="modal-header bg-primary text-white">
                     <h6 class="modal-title text-white">Reply Modification IPE Clearance (<span id="modalTrackingId"></span>)</h6>
                     <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <form method="POST" id="replyForm">
                         @csrf
                         <div class="mb-3">
                             <label class="form-label fw-semibold">Status</label>
                             <select name="status" id="statusSelect" class="form-select" style="color: black;" required>
                                 <option value="" disabled selected>-- Select Status --</option>
                                 <option value="200">Successful</option>
                                 <option value="101">Processing</option>
                                 <option value="400">Failed</option>
                                 <option value="100">Pending</option>
                             </select>
                         </div>

                         <div class="mb-3 d-none" id="refundSection">
                             <label class="form-label fw-semibold">Refund Options</label>
                             <div class="d-flex gap-3">
                                 @foreach([10, 20, 30, 50, 100] as $perc)
                                     <div class="form-check">
                                         <input class="form-check-input refund-perc" type="radio" name="refund_perc" id="perc{{$perc}}" value="{{$perc}}">
                                         <label class="form-check-label" for="perc{{$perc}}">{{$perc}}%</label>
                                     </div>
                                 @endforeach
                             </div>
                             <label class="form-label small">Refund Amount (₦)</label>
                             <input type="number" step="0.01" name="refund_amount" id="refundAmount" class="form-control">
                         </div>

                         <div class="mb-3">
                             <label class="form-label fw-semibold">Comment</label>
                             <div id="editor" style="height: 150px; background: #fff;"></div>
                             <input type="hidden" name="comment" id="commentInput">
                             <input type="hidden" name="id" id="requestId">
                             <input type="hidden" id="trxAmount">
                         </div>

                         <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Submit Reply</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Type your reply here...'
            });

            const replyModal = document.getElementById('replyModal');
            if (replyModal) {
                replyModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const tracking = button.getAttribute('data-tracking');
                    const trxAmount = button.getAttribute('data-trxamount');

                    document.getElementById('modalTrackingId').textContent = tracking;
                    document.getElementById('requestId').value = id;
                    document.getElementById('trxAmount').value = trxAmount;
                    document.getElementById('replyForm').setAttribute('action', '/admin/requests/modification-ipe/' + id + '/update-status');

                    quill.root.innerHTML = '';
                });
            }

            $('#statusSelect').on('change', function() {
                var status = $(this).val();
                if (status == '400') {
                    $('#refundSection').removeClass('d-none');
                    $('#refundAmount').attr('required', true);
                } else {
                    $('#refundSection').addClass('d-none');
                    $('#refundAmount').removeAttr('required');
                    if (status == '101') {
                         quill.root.innerHTML = "Thank you for reaching out. Your request has been received and is currently being processed. We will notify you promptly upon resolution.";
                    }
                }
            });

            $('.refund-perc').on('change', function() {
                var perc = $(this).val();
                var total = parseFloat($('#trxAmount').val()) || 0;
                var refund = (total * perc) / 100;
                $('#refundAmount').val(refund.toFixed(2));
            });

            $('#replyForm').on('submit', function() {
                $('#commentInput').val(quill.root.innerHTML);
                if (quill.getText().trim().length === 0) {
                    alert('Please enter a comment.');
                    return false;
                }
                return true;
            });
        });
    </script>
@endpush
