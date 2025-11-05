<!-- resources/views/layouts/master.blade.php or relevant sidebar partial -->
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box" style="background-color: white;">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                {{-- <img src="{{ URL::asset('build/images/murangalogo.png') }}" alt="" height="100"> --}}
            </span>
            <span class="logo-lg">
                {{-- <img src="{{ URL::asset('build/images/murangalogo.png') }}" alt="" height="100"> --}}
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                {{-- <img src="{{ URL::asset('build/images/murangalogo.png') }}" alt="" height="100"> --}}
            </span>
            <span class="logo-lg">
                {{-- <img src="{{ URL::asset('build/images/murangalogo.png') }}" alt="" height="100"> --}}
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>

            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('mobilizers.dashboard') }}" aria-controls="sidebarDashboards">
                        <i class="mdi mdi-speedometer"></i> <span>Mobilizers Dashboard</span>
                    </a>
                </li>

                {{-- @canany(['canViewMobilizers', 'canManageMobilizers', 'isMobilizer']) --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#mobilizersNav" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="mobilizersNav">
                        <i class="mdi mdi-account-group-outline"></i> <span>Mobilizers</span>
                    </a>
                    <div class="collapse menu-dropdown" id="mobilizersNav">
                        <ul class="nav nav-sm flex-column">
                            {{-- @can('canManageMobilizers') --}}
                            <li class="nav-item">
                                <a href="{{ route('mobilizers.create') }}" class="nav-link">Add Mobilizer</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @canany(['canViewMobilizers', 'canManageMobilizers']) --}}
                            <li class="nav-item">
                                <a href="{{ route('mobilizers.index') }}" class="nav-link">All Mobilizers</a>
                            </li>
                            @foreach($mobilizerRoles as $role)
                            <li class="nav-item">
                                <a href="{{ route('mobilizers.dashboard.role', $role->id) }}" class="nav-link">{{ $role->name }} Dashboard</a>
                            </li>
                            @endforeach
                            {{-- @endcan --}}
                            @can('isMobilizer')
                            <li class="nav-item">
                                <a href="{{ route('mobilizers.index') }}" class="nav-link">My List</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                {{-- @endcanany --}}
                {{-- Dashboard --}}
                @can('add_poll')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('root') }}" aria-controls="sidebarDashboards">
                        <i class="mdi mdi-speedometer"></i> <span>Polls Dashboard</span>
                    </a>
                </li>

                
                {{-- Tenders --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('participant-answers.create') }}" role="button">
                        <i class="mdi mdi-account-circle-outline"></i> <span>Start Poll</span>
                    </a>
                </li>
                {{-- My Calls --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('user.agentCalls', auth()->user()->id) }}">
                        <i class="mdi mdi-account-circle-outline"></i> <span>My Calls</span>
                    </a>
                </li>
                @endcan

                @can('is_super_admin')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#reports" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="reports">
                        <i class="mdi mdi-account-circle-outline"></i> <span>Reports</span>
                    </a>
                    <div class="collapse menu-dropdown" id="reports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('user.participants') }}" class="nav-link">Participants</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.all2') }}" class="nav-link">All Contacts</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.agents') }}" class="nav-link">Agent</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- Users --}}
                @can('is_super_admin')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#usersNav" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="usersNav">
                        <i class="mdi mdi-account-circle-outline"></i> <span>Users</span>
                    </a>
                    <div class="collapse menu-dropdown" id="usersNav">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('users.create') }}" class="nav-link">Add Users</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link">All Users</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- Mobilizers --}}
              

                {{-- Configuration --}}
                @can('is_super_admin')
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#configuration" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="configuration">
                        <i class="mdi mdi-cog-outline"></i> <span>Configuration</span>
                    </a>
                    <div class="collapse menu-dropdown" id="configuration">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="auth-signin-basic" class="nav-link">Roles</a>
                            </li>
                            <li class="nav-item">
                                <a href="auth-signin-basic" class="nav-link">Permissions</a>
                            </li>
                            <li class="nav-item">
                                <a href="auth-signin-cover" class="nav-link">Payment</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                @endcan

            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>