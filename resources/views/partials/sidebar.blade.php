<!-- Brand Logo -->
<a href="/" class="brand-link">
    <img src="/dist/img/ALFerp.png" alt="ALFerp Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.name', 'ALFerp') }}</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/dist/img/profile.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">    
                @auth
                    {{ Auth::user()->name }}
                @endauth
            </a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{route('home')}}" class="nav-link {{ (Request::is('home')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            @hasanyrole('admin')
            <li class="nav-item has-treeview  {{ (Request::is('users')) ? ' menu-open' : '' }}">
                <a href="#" class="nav-link {{ (Request::is('users')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Users Management
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('users.index')}}"
                            class="nav-link {{ (Request::is('users')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview  {{ (Request::is('ticket')) ? ' menu-open' : '' }}">
                <a href="#" class="nav-link {{ (Request::is('ticket')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-project-diagram"></i>
                    <p>
                        Projects Management
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('tickets.index')}}"
                            class="nav-link {{ (Request::is('ticket')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>
                                Tickets
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview  {{ (Request::is('permissions', 'roles')) ? ' menu-open' : '' }}">
                <a href="#" class="nav-link {{ (Request::is('permissions', 'roles')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        System
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('permissions.index')}}"
                            class="nav-link {{ (Request::is('permissions')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Permissions
                            </p>
                        </a>
                        <a href="{{route('roles.index')}}"
                            class="nav-link {{ (Request::is('roles')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Role Management
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            @endhasanyrole
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->