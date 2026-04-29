@extends('layouts.dashboard')

@section('title', 'Modification IPE Request')

@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class=" grid-margin stretch-card col-md-12   grid-margin stretch-card ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Modification IPE Request</h4>
                        <p class="card-description">Send your modification ipe request to get your tracking number</p>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="new-tab" data-bs-toggle="tab" href="#new-1" role="tab"
                                    aria-controls="new-1" aria-selected="true">New</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="new-1" role="tabpanel" aria-labelledby="new-tab">

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
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <form name="ipe-form" id="ipe-form" method="POST"
                                            action="{{ route('user.modification-ipe-request') }}">
                                            @csrf
                                            <div class="mb-3 row">

                                                <div class="col-md-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-md-12  mt-2 mb-0">
                                                            <p class="form-label">Tracking Number</p>
                                                            <input type="text" id="trackingId" name="trackingId"
                                                                maxlength="15" class="form-control" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-1 mb-2">

                                                @php
                                                    $ipeNotice = \App\Models\SiteSetting::first()->modification_ipe_notice ?? "Our Modification IPE request process is fully automated. You can track the status of your request using the 'Check Status' button";
                                                @endphp
                                                <small class="text-danger">{{ $ipeNotice }}</small><br />

                                                <p class="fw-bold mt-2"> Service Fee:
                                                    &#x20A6;{{ number_format($ServiceFee->amount ?? 0, 2) }}</p>

                                            </div>
                                            <button type="submit" id="submit" name="submit" class="btn btn-primary"><i
                                                    class="las la-share"></i> Submit
                                                Request
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-8">
                                        @if (!$ipes->isEmpty())
                                            @php
                                                $currentPage = $ipes->currentPage();
                                                $perPage = $ipes->perPage();
                                                $serialNumber = ($currentPage - 1) * $perPage + 1;
                                            @endphp
                                            <div class="table-responsive">
                                                <table class="table" style="background:#fafafc !important">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th width="5%" scope="col">ID</th>
                                                            <th scope="col">Request Tracking No.</th>
                                                            <th scope="col">Reply</th>
                                                            <th scope="col" class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i = 1; @endphp
                                                        @foreach ($ipes as $data)
                                                            <tr>
                                                                <th scope="row">{{ $serialNumber++ }}</th>
                                                                <td>{{ $data->trackingId }}</td>
                                                                <td class="text-center"><center>
                                                                    <div id="reply-{{ $data->id }}" class="text-truncate" style="max-width: 250px;">
                                                                        {!! $data->reply ?? 'No reply yet' !!}
                                                                    </div></center>

                                                                    @if($data->reply)
                                                                        <a href="javascript:void(0)" onclick="toggleReply('{{ $data->id }}')" class="small text-primary" id="toggle-{{ $data->id }}">View Full</a>
                                                                    @endif
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
                                                            </tr>
                                                            @php $i++ @endphp
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                                <!-- Pagination Links -->
                                                <div class="d-flex justify-content-center">
                                                    {{ $ipes->links('vendor.pagination.bootstrap-4') }}
                                                </div>
                                            </div>
                                        @else
                                            <center><img width="65%"
                                                    src="{{ asset('assets/images/no-transaction.gif') }}" alt="">
                                            </center>
                                            <p class="text-center fw-semibold  fs-15"> No Request
                                                Available!</p>
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
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ipe-form');
            const submitButton = document.getElementById('submit');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerText = 'Please wait while we process your request...';
            });
        });

        function toggleReply(id) {
            const el = document.getElementById('reply-' + id);
            const btn = document.getElementById('toggle-' + id);
            if (el.classList.contains('text-truncate')) {
                el.classList.remove('text-truncate');
                el.style.maxWidth = 'none';
                el.style.whiteSpace = 'normal';
                btn.innerText = 'Show Less';
            } else {
                el.classList.add('text-truncate');
                el.style.maxWidth = '250px';
                el.style.whiteSpace = 'nowrap';
                btn.innerText = 'View Full';
            }
        }
    </script>
@endpush
