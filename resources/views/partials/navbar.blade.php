<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
        <ul class="navbar-nav me-lg-2 d-none d-lg-flex">
            <li class="nav-item nav-toggler-item">
                <button class="navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
            </li>

        </ul>
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="background: transparent;">
            <a class="navbar-brand brand-logo" href="{{ route('user.dashboard') }}" style="font-weight: 600; font-size: 1.5rem; letter-spacing: -0.025em; color: #1e40af; text-decoration: none;">
                Lite<span style="color: #111827;">verifier</span>
            </a>

            <a class="navbar-brand brand-logo-mini" href="{{ route('user.dashboard') }}" style="font-weight: 600; font-size: 1.5rem; color: #1e40af; text-decoration: none;">
                L<span style="color: #111827;">v</span>
            </a>
        </div>
        <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item nav-profile dropdown">
                <a class="nav-link d-block d-sm-none" href="#" data-bs-toggle="dropdown" id="profileDropdownSm">
                    @if (auth()->user()->profile_pic)
                        <img src="{{ 'data:image/;base64,' . auth()->user()->profile_pic }}" class="rounded-circle me-1"
                            alt="Profile Image" style="width: 32px; height: 32px; object-fit: cover;" />
                    @else
                        <i class="bi bi-person-circle" style="font-size: 2rem; color: #1e40af;"></i>
                    @endif
                    <span class="nav-profile-name">{{ auth()->user()->name }}</span>
                </a>

                <div class="d-none d-sm-flex align-items-center">
                    <div class="d-flex align-items-center rounded-pill px-3 py-1" style="background-color: #f3f4f6; border: 1px solid #e5e7eb;">
                        <span class="text-muted small me-2" style="font-weight: 500;">Referral Code:</span>
                        <span class="fw-bold" style="color: #1e40af; letter-spacing: 0.5px;" id="navReferralCode">{{ auth()->user()->referral_code ? strtoupper(auth()->user()->referral_code) : 'N/A' }}</span>
                        @if(auth()->user()->referral_code)
                        <button onclick="copyNavReferral(this)" class="btn p-0 ms-2" style="text-decoration: none; border: none; background: transparent; outline: none; line-height: 1;" title="Copy Code">
                            <i class="mdi mdi-content-copy" style="font-size: 1rem; color: #6b7280; transition: color 0.2s;" onmouseover="this.style.color='#1e40af'" onmouseout="this.style.color='#6b7280'"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </li>
            
            <li class="nav-item d-none d-lg-flex align-items-center ms-3">
                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm d-flex align-items-center" style="background-color: #fee2e2; color: #991b1b; border-radius: 8px; font-weight: 600; padding: 0.4rem 1rem; border: none; transition: all 0.2s;">
                        <i class="mdi mdi-logout me-1" style="font-size: 1.1rem;"></i> Logout
                    </button>
                </form>
            </li>
            <li class="nav-item nav-toggler-item-right d-lg-none">
                <button class="navbar-toggler align-self-center" type="button" data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </li>
        </ul>
    </div>

</nav>

@push('scripts')
<script>
    function copyNavReferral(btn) {
        var text = document.getElementById("navReferralCode").innerText;
        navigator.clipboard.writeText(text).then(function() {
            var icon = btn.querySelector('i');
            var oldClass = icon.className;
            var oldColor = icon.style.color;
            
            icon.className = 'mdi mdi-check';
            icon.style.color = '#166534'; // Success green
            
            setTimeout(function() {
                icon.className = oldClass;
                icon.style.color = oldColor;
            }, 2000);
        });
    }
</script>
@endpush
