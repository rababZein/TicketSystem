<!-- Brand Logo -->
<a href="/" class="brand-link">
    <img src="{{ asset('assets/img/ALFerp.png') }}" alt="ALFerp Logo" class="brand-image img-circle elevation-3"
        style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.name', 'ALFerp') }}</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('assets/img/profile.png') }}" class="img-circle elevation-2" alt="User Image">
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
                <router-link to="/admin/dashboard" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </router-link>
            </li>
            @can('project-list')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-project-diagram"></i>
                    <p>
                        Projects Management
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <router-link to="/admin/projects" class="nav-link">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>
                                Projects
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/tickets" class="nav-link">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>
                                Tickets
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/tasks" class="nav-link">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Tasks
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/receipts" class="nav-link">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>
                                Receipts
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/clients" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Clients
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Reports
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                            <router-link to="/admin/time-report" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Time Report</p>
                              </router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endcan
            @can('permission-list')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        System
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <router-link to="/admin/permissions" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Permissions
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/roles" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Role Management
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/users" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Employees
                            </p>
                        </router-link>
                    </li>
                </ul>
            </li>
            @endcan
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->