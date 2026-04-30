<nav class="sidebar sidebar-offcanvas mt-0" id="sidebar">
    <!-- User Profile Section -->
    <div class="sidebar-profile text-center p-3">
        @if (auth()->user()->profile_pic)
            <img src="{{ 'data:image/jpeg;base64,' . auth()->user()->profile_pic }}" alt="Profile Picture"
                class="rounded-circle shadow" style="width: 80px; height: 80px; object-fit: cover;">
        @else
            <i class="bi bi-person-circle" style="font-size: 3rem; color: #fff;"></i>
        @endif

        <div class="sidebar-profile-info mt-2">
            <span class="sidebar-profile-name truncate-text">{{ auth()->user()->name }}</span>
            <span class="sidebar-profile-email text-light truncate-text">
                <small>{{ auth()->user()->email }}</small>
            </span>
        </div>
        <div class="d-block d-sm-none mt-1">
            <small>Referral Code:</small>
            <p class="badge bg-danger">{{ ucwords(auth()->user()->referral_code) }}</p>
        </div>
    </div>

    <ul class="nav">
        <!-- Main Section -->
        <li class="nav-item nav-category">Main Menu</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.wallet') ? 'active' : '' }}" href="{{ route('user.wallet') }}">
                <i class="mdi mdi-wallet menu-icon"></i>
                <span class="menu-title">Fund Wallet</span>
            </a>
        </li>

        <!-- Verification Section -->
        <li class="nav-item nav-category">Identity Verification</li>

        <li class="nav-item">
            <a href="#" class="nav-link" onclick="toggleSubmenu(event, 'ninSubmenu')">
                <i class="mdi mdi-fingerprint menu-icon"></i>
                <span class="menu-title">NIN Version 1</span>
                <i class="mdi mdi-chevron-down ms-auto"></i>
            </a>
            <ul class="sub-menu nav flex-column ps-4" id="ninSubmenu">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('user.verify-nin') ? 'active' : '' }}"
                        href="{{ route('user.verify-nin') }}">
                         Verify NIN
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('user.verify-nin-phone') ? 'active' : '' }}"
                        href="{{ route('user.verify-nin-phone') }}">
                         Verify NIN Phone
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('user.verify-demo') ? 'active' : '' }}"
                        href="{{ route('user.verify-demo') }}">
                         NIN Demographic
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.verify-bvn') ? 'active' : '' }}"
                href="{{ route('user.verify-bvn') }}">
                <i class="mdi mdi-fingerprint menu-icon"></i>
                <span class="menu-title">Verify BVN</span>
            </a>
        </li>

        <!-- Agent Section -->
        <li class="nav-item nav-category">Agent Services</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.bvn-enrollment') ? 'active' : '' }}"
                href="{{ route('user.bvn-enrollment') }}">
                <i class="mdi mdi-account-plus menu-icon"></i>
                <span class="menu-title">BVN Agent</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link opacity-50" href="javascript:void(0)">
                <i class="mdi mdi-account-edit menu-icon"></i>
                <span class="menu-title">BVN Modification</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.bvn-phone-search') ? 'active' : '' }}"
                href="{{ route('user.bvn-phone-search') }}">
                <i class="mdi mdi-account-search menu-icon"></i>
                <span class="menu-title">BVN RETRIVAL</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.nin.delink') ? 'active' : '' }}"
                href="{{ route('user.nin.delink') }}">
                <i class="mdi mdi-link-off menu-icon"></i>
                <span class="menu-title">NIN Delink</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.email.retrive') ? 'active' : '' }}"
                href="{{ route('user.email.retrive') }}">
                <i class="mdi mdi-email-search menu-icon"></i>
                <span class="menu-title">Email Retrive</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.nin.modifications') ? 'active' : '' }}"
                href="{{ route('user.nin.modifications') }}">
                <i class="mdi mdi-account-edit menu-icon"></i>
                <span class="menu-title">NIN Modifications</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#nimcLicenseModal">
                <i class="mdi mdi-card-account-details menu-icon"></i>
                <span class="menu-title">NIMC License</span>
            </a>
        </li>

        <!-- Processing Section -->
        <li class="nav-item nav-category">Advanced Processing</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.ipe') ? 'active' : '' }}" href="{{ route('user.ipe') }}">
                <i class="mdi mdi-sync menu-icon"></i>
                <span class="menu-title">IPE Clearance</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.modification-ipe') ? 'active' : '' }}" href="{{ route('user.modification-ipe') }}">
                <i class="mdi mdi-sync menu-icon"></i>
                <span class="menu-title">Modification IPE</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.nin-validation') ? 'active' : '' }}"
                href="{{ route('user.nin-validation') }}">
                <i class="mdi mdi-sync menu-icon"></i>
                <span class="menu-title">NIN Validation</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.personalize-nin') ? 'active' : '' }}"
                href="{{ route('user.personalize-nin') }}">
                <i class="mdi mdi-magnify menu-icon"></i>
                <span class="menu-title">NIN Personalize</span>
            </a>
        </li>

        <li class="nav-item nav-category">System</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.support') }}">
                <i class="mdi mdi-lifebuoy menu-icon"></i>
                <span class="menu-title">Support</span>
            </a>
        </li>

        <!-- Admin Section -->
        @if (in_array(auth()->user()->role, ['admin', 'super_admin']))
            <li class="nav-item nav-category">Administration</li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="toggleSubmenu(event, 'adminSubmenu')">
                    <i class="mdi mdi-cog-outline menu-icon"></i>
                    <span class="menu-title">Manage</span>
                    <i class="mdi mdi-chevron-down ms-auto"></i>
                </a>
                <ul class="sub-menu nav flex-column ps-4" id="adminSubmenu">

                    @if (auth()->user()->role == 'super_admin')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.services.index') ? 'active' : '' }}"
                                href="{{ route('admin.services.index') }}">
                                <i class="mdi mdi-pencil menu-icon"></i> Services
                            </a>
                        </li>
                    @endif

                    @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.users.index') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <i class="mdi mdi mdi-account-multiple menu-icon"></i> Users
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.nin.services.list') ? 'active' : '' }}"
                            href="{{ route('admin.nin.services.list') }}">
                            <i class="mdi mdi-tools menu-icon"></i>NIN Validation
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.bvn.services.list') ? 'active' : '' }}"
                            href="{{ route('admin.bvn.services.list') }}">
                            <i class="mdi mdi-tools menu-icon"></i>BVN Services
                        </a>
                    </li>

                      <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.enroll.index') ? 'active' : '' }}"
                            href="{{ route('admin.enroll.index') }}">
                            <i class="mdi mdi-pencil menu-icon"></i>BVN Agent
                        </a>
                    </li>
                    <!-- ipe clearance services -->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.ipe.index') ? 'active' : '' }}"
                            href="{{ route('admin.ipe.index') }}">
                            <i class="mdi mdi-tools menu-icon"></i>IPE Clearance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.modification.ipe.index') ? 'active' : '' }}"
                            href="{{ route('admin.modification.ipe.index') }}">
                            <i class="mdi mdi-tools menu-icon"></i>Modification IPE
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.delink.services.list') ? 'active' : '' }}"
                            href="{{ route('admin.delink.services.list') }}">
                            <i class="mdi mdi-link-off menu-icon"></i> NIN Delink
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.email.retrive.list') ? 'active' : '' }}"
                            href="{{ route('admin.email.retrive.list') }}">
                            <i class="mdi mdi-email-search menu-icon"></i> Email Retrive
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.nin.modifications.list') ? 'active' : '' }}"
                            href="{{ route('admin.nin.modifications.list') }}">
                            <i class="mdi mdi-account-edit menu-icon"></i> NIN Modifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.transactions') ? 'active' : '' }}"
                            href="{{ route('admin.transactions') }}">
                            <i class="mdi mdi-receipt-text-outline menu-icon"></i>
                            All Transactions
                        </a>
                    </li>
                      <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.popup.index') ? 'active' : '' }}"
                            href="{{ route('admin.popup.index') }}">
                            <i class="mdi mdi-window-restore menu-icon"></i> Popup
                        </a>
                    </li>
                      <li class="nav-item">
                        <a class="nav-link {{ Route::is('site-settings.edit') ? 'active' : '' }}"
                            href="{{ route('admin.site-settings.edit') }}">
                            <i class="mdi mdi-cog menu-icon"></i>
                            Site Settings
                        </a>
                    </li>
                </ul>

            </li>
        @endif
        <!-- Logout Section -->
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <a class="nav-link d-flex align-items-center" style="margin-left:14px;" href="#"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="mdi mdi-logout menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </a>
            </form>
        </li>
    </ul>
</nav>
