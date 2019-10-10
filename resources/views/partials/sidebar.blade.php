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
                <router-link to="/dashboard" class="nav-link">
                    <p>
                        <i class="nav-icon fas fa-tachometer-alt"></i>
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
                        <router-link to="/projects" class="nav-link">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>
                                Projects
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/tickets" class="nav-link">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>
                                Tickets
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/tasks" class="nav-link">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Tasks
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/receipts" class="nav-link">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>
                                Receipts
                            </p>
                        </router-link>
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
                        <router-link to="/permissions" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Permissions
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/roles" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Role Management
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/users" class="nav-link">
                            <p>
                                <i class="nav-icon fas fa-list"></i>
                                Users
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