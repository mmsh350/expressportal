@extends('layouts.dashboard')

@section('title', 'View Modification Request')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .modification-img {
            max-width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Request Details</h4>
        </div>
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">NIN Modification: {{ $modification->type }}</h5>
                    <span>Ref: {{ $modification->refno }}</span>
                </div>
                <div class="card-body">
                    @include('common.message')

                    <div class="row">
                        <!-- Customer Info -->
                        <div class="col-md-6 mb-4">
                            <div class="p-3 border rounded bg-light h-100">
                                <h6 class="text-uppercase text-muted mb-3">Customer Information</h6>
                                <p><strong>Name:</strong> {{ $modification->user->name ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $modification->user->email ?? 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ $modification->user->phone_number ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Transaction Info -->
                        <div class="col-md-6 mb-4">
                            <div class="p-3 border rounded bg-light h-100">
                                <h6 class="text-uppercase text-muted mb-3">Transaction Details</h6>
                                <p><strong>Status:</strong>
                                    <span class="badge {{ $modification->status == 'Successful' ? 'bg-success' : ($modification->status == 'Failed' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $modification->status }}
                                    </span>
                                </p>
                                <p><strong>Amount Paid:</strong> ₦{{ number_format($modification->transactions->amount ?? 0, 2) }}</p>
                                <p><strong>Date:</strong> {{ $modification->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Modification Details -->
                        <div class="col-12 mb-4">
                            <div class="p-4 border rounded bg-white shadow-sm">
                                <h6 class="text-uppercase text-primary mb-4 border-bottom pb-2">Requested Modification Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <p class="text-muted mb-1">NIN Number</p>
                                        <h5 class="fw-bold">{{ $modification->nin }}</h5>
                                    </div>

                                    @if($modification->type == 'DOB MOD')
                                        <div class="col-md-4">
                                            <p class="text-muted mb-1">New DOB</p>
                                            <h5 class="fw-bold text-success">{{ $modification->dob }}</h5>
                                        </div>
                                    @endif

                                    @if(in_array($modification->type, ['NAME MOD', 'PHONE NO MOD']))
                                        <div class="col-md-4">
                                            <p class="text-muted mb-1">Surname</p>
                                            <h5 class="fw-bold">{{ $modification->surname }}</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="text-muted mb-1">First Name</p>
                                            <h5 class="fw-bold">{{ $modification->first_name }}</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="text-muted mb-1">Middle Name</p>
                                            <h5 class="fw-bold">{{ $modification->middle_name ?? '-' }}</h5>
                                        </div>
                                    @endif

                                    @if($modification->type == 'ADDRESS MOD')
                                        <div class="col-12">
                                            <p class="text-muted mb-1">New Address</p>
                                            <h5 class="fw-bold">{{ $modification->address }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="text-muted mb-1">Town</p>
                                            <h5 class="fw-bold">{{ $modification->town }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="text-muted mb-1">LGA Origin</p>
                                            <h5 class="fw-bold">{{ $modification->lga_origin }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="text-muted mb-1">State Origin</p>
                                            <h5 class="fw-bold">{{ $modification->state_origin }}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="text-muted mb-1">LGA Residence</p>
                                            <h5 class="fw-bold">{{ $modification->lga_residence }}</h5>
                                        </div>
                                    @endif

                                    @if($modification->type == 'GENDER MOD')
                                        <div class="col-md-4">
                                            <p class="text-muted mb-1">Correct Gender</p>
                                            <h5 class="fw-bold text-success">{{ $modification->gender }}</h5>
                                        </div>
                                    @endif

                                    @if($modification->type == 'OTHER MOD')
                                        <div class="col-md-4">
                                            <p class="text-muted mb-1">Mod Type Detail</p>
                                            <h5 class="fw-bold text-success">{{ $modification->modification_type_detail }}</h5>
                                        </div>
                                    @endif

                                    @if($modification->phone_number)
                                        <div class="col-md-4">
                                            <p class="text-muted mb-1">Phone Number</p>
                                            <h5 class="fw-bold">{{ $modification->phone_number }}</h5>
                                        </div>
                                    @endif

                                    @if($modification->email)
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1">Self Service Email</p>
                                            <h5 class="fw-bold text-primary">{{ $modification->email }}</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1">Self Service Password</p>
                                            <h5 class="fw-bold text-primary">{{ $modification->password }}</h5>
                                        </div>
                                    @endif
                                </div>

                                @if($modification->clear_picture)
                                    <div class="mt-4 pt-3 border-top">
                                        <h6 class="text-muted mb-3">Customer Clear Picture</h6>
                                        <a href="{{ asset('storage/' . $modification->clear_picture) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $modification->clear_picture) }}" class="modification-img img-fluid" alt="NIN Modification Picture">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Section -->
                        <div class="col-12">
                            <div class="p-4 border rounded bg-light shadow-sm">
                                <h6 class="text-uppercase text-muted mb-4">Process Request</h6>
                                <form action="{{ route('admin.nin.modification.update-status', $modification->id) }}" method="POST" id="statusForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Update Status</label>
                                            <select name="status" id="status" class="form-select" style="color: #000" required>
                                                <option value="Pending" {{ $modification->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="In-Progress" {{ $modification->status == 'In-Progress' ? 'selected' : '' }}>Processing</option>
                                                <option value="Successful" {{ $modification->status == 'Successful' ? 'selected' : '' }}>Resolved</option>
                                                <option value="Failed" {{ $modification->status == 'Failed' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3 d-none" id="refundSection">
                                            <label class="form-label fw-bold text-danger">Refund Amount (₦)</label>
                                            <div class="input-group">
                                                <input type="number" name="refundAmount" id="refundAmount" class="form-control" step="0.01" placeholder="0.00">
                                                <button type="button" class="btn btn-outline-dark" onclick="setRefund(100)">100%</button>
                                                <button type="button" class="btn btn-outline-dark" onclick="setRefund(50)">50%</button>
                                            </div>
                                            <small class="text-muted">Paid: ₦{{ number_format($modification->transactions->amount ?? 0, 2) }}</small>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Admin Comment / Response</label>
                                            <div id="editor" style="height: 200px; background: white;">{!! $modification->reason !!}</div>
                                            <input type="hidden" name="comment" id="commentInput">
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-lg w-100">Update Request Status</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        const transactionAmount = {{ $modification->transactions->amount ?? 0 }};

        function setRefund(pct) {
            document.getElementById('refundAmount').value = (transactionAmount * pct / 100).toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const quill = new Quill('#editor', { theme: 'snow', placeholder: 'Enter your response to the customer...' });
            const status = document.getElementById('status');
            const refundSection = document.getElementById('refundSection');

            function toggleRefund() {
                if (status.value === 'Failed') {
                    refundSection.classList.remove('d-none');
                } else {
                    refundSection.classList.add('d-none');
                }
            }

            status.addEventListener('change', toggleRefund);
            toggleRefund();

            document.getElementById('statusForm').addEventListener('submit', function(e) {
                document.getElementById('commentInput').value = quill.root.innerHTML;
                if (quill.getText().trim().length === 0) {
                    e.preventDefault();
                    alert('Please add a comment for the user.');
                }
            });
        });
    </script>
@endpush
