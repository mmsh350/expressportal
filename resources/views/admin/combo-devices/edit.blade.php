@extends('layouts.dashboard')

@section('title', 'Edit Combo Device')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4 mt-2 d-flex align-items-center">
            <a href="{{ route('admin.combo-devices.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3">
                <i class="mdi mdi-arrow-left"></i>
            </a>
            <h3 class="fw-bold mb-0">Edit Combo Device</h3>
        </div>

        @include('common.message')

        <div class="card border-0 shadow-sm" style="border-radius: 1.5rem;">
            <div class="card-body p-4">
                <form action="{{ route('admin.combo-devices.update', $device->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Device Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $device->title }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Price (₦)</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="{{ $device->price }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Condition</label>
                            <select name="condition" class="form-select" style="color:#000" required>
                                <option value="New" {{ $device->condition == 'New' ? 'selected' : '' }}>Brand New</option>
                                <option value="Refurbished" {{ $device->condition == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                                <option value="Used" {{ $device->condition == 'Used' ? 'selected' : '' }}>Used / Pre-owned</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ $device->description }}</textarea>
                        </div>

                        <div class="col-md-12 py-3">
                            <div class="d-flex align-items-center bg-light p-3 rounded-4 border">
                                <div class="form-check form-switch ps-0 mb-0">
                                    <input class="form-check-input ms-0" type="checkbox" name="is_active" id="isActive" {{ $device->is_active ? 'checked' : '' }} style="width: 3.5em; height: 1.75em; cursor: pointer; float: none;">
                                </div>
                                <label class="form-check-label fw-bold mb-0" for="isActive" style="cursor: pointer; margin-left: 4em; font-size: 1.05rem;">Listing Active</label>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 text-uppercase small text-muted" style="letter-spacing: 0.1em;">Current Gallery</h6>
                                <span class="badge rounded-pill {{ count($device->images ?? []) >= 5 ? 'bg-soft-danger text-danger' : 'bg-soft-primary text-primary' }}" style="font-weight: 700; padding: 6px 12px; border: 1px solid currentColor;">
                                    <i class="mdi mdi-image-multiple me-1"></i> {{ count($device->images ?? []) }} / 5 Slots Used
                                </span>
                            </div>
                            <div class="row g-3">
                                @if($device->images)
                                    @foreach($device->images as $index => $path)
                                    <div class="col-4 col-md-3 col-lg-2">
                                        <div class="position-relative shadow-sm rounded-3 overflow-hidden border" style="height: 120px;">
                                            @if($index == 0)
                                                <span class="position-absolute bottom-0 start-0 bg-primary text-white px-2 py-1 small fw-bold" style="font-size: 0.65rem; z-index: 2; border-radius: 0 4px 0 0;">MAIN</span>
                                            @endif
                                            <img src="{{ asset('storage/' . $path) }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                            
                                            <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center shadow position-absolute" 
                                                    style="top: 5px; right: 5px; z-index: 5; width: 28px !important; height: 28px !important; border-radius: 50% !important; padding: 0 !important; border: 2px solid #fff !important; min-width: 28px !important;" 
                                                    onclick="deleteGalleryImage('{{ $path }}')">
                                                <i class="mdi mdi-close" style="font-size: 14px; line-height: 1;"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="bg-light rounded-3 p-4 text-center border border-dashed">
                                            <i class="mdi mdi-image-off outline text-muted fs-3 d-block mb-2"></i>
                                            <p class="text-muted small mb-0">Your gallery is currently empty.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="bg-white p-3 rounded-4 border shadow-sm">
                                <label class="form-label fw-bold mb-2">Add New Photos</label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*" {{ count($device->images ?? []) >= 5 ? 'disabled' : '' }}>
                                @if(count($device->images ?? []) >= 5)
                                    <div class="alert alert-soft-danger d-flex align-items-center mt-3 mb-0 py-2 border-0">
                                        <i class="mdi mdi-alert-circle me-2"></i>
                                        <span class="small fw-bold">Maximum capacity reached. Remove photos to add more.</span>
                                    </div>
                                @else
                                    <small class="text-muted mt-2 d-block">
                                        <i class="mdi mdi-information-outline me-1"></i> You can select up to {{ 5 - count($device->images ?? []) }} more files.
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                            <i class="mdi mdi-check-circle me-1"></i> Update Listing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form for individual image deletion --}}
<form id="delete-image-form" action="{{ route('admin.combo-devices.delete-image', $device->id) }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="image_path" id="delete-image-path">
</form>

@endsection

@push('scripts')
<script>
    function deleteGalleryImage(path) {
        if (confirm('Are you sure you want to delete this image?')) {
            document.getElementById('delete-image-path').value = path;
            document.getElementById('delete-image-form').submit();
        }
    }
</script>
@endpush
