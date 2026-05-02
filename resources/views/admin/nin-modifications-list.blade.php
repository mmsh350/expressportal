@extends('layouts.dashboard')

@section('title', 'NIN Modifications Management')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .form-check .form-check-input {
            margin-left: 0;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title">NIN Modification Requests</h5>
                        </div>
                        <div class="card-body">
                            @include('common.message')

                            <div class="col-xl-12 mb-3">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent">
                                                            <i class="mdi mdi-clipboard-list"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                <p class="text-muted mb-0">All Request</p>
                                                                <h4 class="fw-semibold mt-1">{{ $total_request }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-success-transparent">
                                                            <i class="mdi mdi-check-all"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                <p class="text-muted mb-0">Resolved</p>
                                                                <h4 class="fw-semibold mt-1">{{ $resolved }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-warning-transparent">
                                                            <i class="mdi mdi-timer-sand"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                 <p class="text-muted mb-0">Pending</p>
                                                                 <h4 class="fw-semibold mt-1">{{ $pending }}</h4>
                                                             </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex align-items-top justify-content-between">
                                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-danger-transparent">
                                                            <i class="mdi mdi-close-circle"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-fill ms-3">
                                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                            <div>
                                                                <p class="text-muted mb-0">Failed</p>
                                                                <h4 class="fw-semibold mt-1">{{ $rejected }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('admin.nin.modifications.list') }}" class="row g-2 mb-3 align-items-end">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search NIN or Ref...">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Date</th>
                                            <th>Ref No</th>
                                            <th>User</th>
                                            <th>Type</th>
                                            <th>NIN</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($modifications as $mod)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $mod->created_at->format('d M Y') }}</td>
                                                <td>{{ $mod->refno }}</td>
                                                <td>{{ $mod->user->name ?? 'N/A' }}</td>
                                                <td><span>{{ $mod->type }}</span></td>
                                                <td>{{ $mod->nin }}</td>
                                                <td>
                                                    @if ($mod->status == 'Pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($mod->status == 'Successful')
                                                        <span class="badge bg-success">Resolved</span>
                                                    @elseif($mod->status == 'In-Progress')
                                                        <span class="badge bg-primary">Processing</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.nin.modification.view', $mod->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="mdi mdi-eye"></i> View
                                                    </a>
                                                    @if($mod->status != 'Failed')
                                                        <button type="button" data-bs-toggle="modal" data-id="{{ $mod->id }}" data-trxamount="{{ $mod->transactions->amount ?? 0 }}" data-bs-target="#reply" class="btn btn-light btn-sm">
                                                            <i class="mdi mdi-reply"></i> Reply
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $modifications->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
            </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="reply" tabindex="-1" aria-labelledby="reply" data-bs-keyboard="true"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Reply Modification (#<span id="sid"></span>)</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="statusForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label"><strong>Select Status</strong></label>
                            <select name="status" id="status" class="form-select" style="color: #000" required>
                                <option value="" disabled selected>-- Choose Status --</option>
                                <option value="Successful">Resolved</option>
                                <option value="In-Progress">Processing</option>
                                <option value="Failed">Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="refundOption">
                            <label class="form-label"><strong>Refund Options</strong></label>
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm refund-pct text-dark" data-pct="100">100%</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm refund-pct text-dark" data-pct="50">50%</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm refund-pct text-dark" data-pct="0">0%</button>
                            </div>
                            <label class="form-label small"><strong>Or Enter Amount (₦)</strong></label>
                            <input type="number" id="refundAmount" name="refundAmount" class="form-control" step="0.01">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Comment</strong></label>
                            <div id="editor" style="height: 150px;" class="form-control"></div>
                            <input type="hidden" name="comment" id="commentInput">
                            <input type="hidden" name="trxAmount" id="trxAmount">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        $(document).ready(function() {
            const quill = new Quill('#editor', { theme: 'snow', placeholder: 'Enter your comment...' });

            $('#reply').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const amount = button.data('trxamount');

                $('#sid').text(id);
                $('#trxAmount').val(amount);
                $('#statusForm').attr('action', `/admin/nin-modification/${id}/update-status`);
            });

            $('#status').change(function() {
                if ($(this).val() === 'Failed') {
                    $('#refundOption').removeClass('d-none');
                } else {
                    $('#refundOption').addClass('d-none');
                }
            });

            $('.refund-pct').click(function() {
                const pct = $(this).data('pct');
                const amount = $('#trxAmount').val();
                $('#refundAmount').val((amount * pct / 100).toFixed(2));
            });

            $('#statusForm').submit(function(e) {
                $('#commentInput').val(quill.root.innerHTML);
                if (quill.getText().trim().length === 0) {
                    e.preventDefault();
                    alert('Please add a comment.');
                }
            });
        });
    </script>
@endpush
