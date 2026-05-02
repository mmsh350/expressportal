@extends('layouts.dashboard')

@section('title', 'Manage Combo Devices')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-bold mb-0">Combo Devices</h3>
            <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                <i class="mdi mdi-plus-circle me-1"></i> Add New Combo
            </button>
        </div>
    </div>

    @include('common.message')

    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Device Title</th>
                                <th>Price</th>
                                <th>Condition</th>
                                <th>Status</th>
                                <th class="text-center">Images</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($devices as $device)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $device->title }}</div>
                                    <small class="text-muted">{{ Str::limit($device->description, 50) }}</small>
                                </td>
                                <td>₦{{ number_format($device->price, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $device->condition == 'New' ? 'bg-success' : ($device->condition == 'Refurbished' ? 'bg-info' : 'bg-secondary') }}">
                                        {{ $device->condition }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $device->is_active ? 'bg-label-success' : 'bg-label-danger' }}" style="background: {{ $device->is_active ? 'rgba(40, 167, 69, 0.1)' : 'rgba(220, 53, 69, 0.1)' }}; color: {{ $device->is_active ? '#28a745' : '#dc3545' }};">
                                        {{ $device->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        @if($device->images && count($device->images) > 0)
                                            <span class="badge bg-light text-dark border">{{ count($device->images) }} Photos</span>
                                        @else
                                            <span class="text-muted small">No images</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.combo-devices.edit', $device->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.combo-devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this device?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">No combo devices found. Add your first one!</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $devices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Device Modal -->
<div class="modal fade" id="addDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem;">
            <div class="modal-header px-4 pt-4 border-0">
                <h5 class="fw-bold mb-0">Add New Combo Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.combo-devices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Device Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Combo Devices name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Price (₦)</label>
                            <input type="number" name="price" step="0.01" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Condition</label>
                            <select name="condition" class="form-select" style="color:#000" required>
                                <option value="New">Brand New</option>
                                <option value="Refurbished">Refurbished</option>
                                <option value="Used">Used / Pre-owned</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Enter device specs and combo details..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Upload Images (At least 5 recommended)</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                            <small class="text-muted mt-1 d-block">You can select multiple images at once.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Listing</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
