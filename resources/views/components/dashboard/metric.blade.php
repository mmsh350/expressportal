@props(['title', 'value','icon' => 'bi-info-circle', 'bg' => 'primary','href'])
@if ($href == '#')
    @php $href = 'user.dashboard'; @endphp
@endif
<a href="{{ route($href) }}" style="text-decoration: none !important; color: inherit; display: block; height: 100%;">
<div class="card shadow-sm h-100 border-0">
    <div class="card-body p-3 p-sm-4 d-flex flex-column">
        <div class="d-flex align-items-center mb-3">
            <div class="d-flex align-items-center justify-content-center rounded me-3 bg-{{ $bg }}" style="width: 42px; height: 42px; min-width: 42px;">
                <i class="bi {{ $icon }} fs-5" style="color: #ffffff !important;"></i>
            </div>
            <h6 class="mb-0 fw-semibold text-muted" style="font-size: 0.85rem; line-height: 1.3; white-space: normal;">
                {{ $title }}
            </h6>
        </div>
        <h3 class="price fw-bold mb-0 text-dark mt-auto">{{ $value }}</h3>
    </div>
</div>
</a>
