<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">Menu</li>
                <li class="nav-item">
                    <a href="{{ url('notes') }}" class="nav-link {{ Request::is('notes') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Non-Labeled Notes
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('labels') }}" class="nav-link {{ Request::is('labels') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Manage Labels
                        </p>
                    </a>
                </li>

                <li class="nav-header">Labels</li>
                <li class="nav-item  {{ Request::is('notes-by-label/*') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link  {{ Request::is('notes-by-label/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>
                            Labels
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($menuLabels as $menuLabel)
                            <li class="nav-item">
                                <a href="{{ url('notes-by-label') . '/' . $menuLabel['id'] }}"
                                    class="nav-link {{ Request::is('notes-by-label' . '/' . $menuLabel['id']) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tag"></i>
                                    <p>
                                        {{ $menuLabel['name'] }}
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
