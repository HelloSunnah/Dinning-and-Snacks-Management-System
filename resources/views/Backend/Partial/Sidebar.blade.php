<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manpower.index') }}">
                <i class="bi bi-people"></i>
                <span>Manpower</span>
            </a>
        </li>

                <!-- Menu -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('menu.index') }}">
                <i class="bi bi-card-list"></i>
                <span>Menu</span>
            </a>
        </li>
        <!-- Manpower -->
     

        <!-- Distribution -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('distribution.index') }}">
                <i class="bi bi-truck"></i>
                <span>Distribution</span>
            </a>
        </li>

        <!-- Predictions -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('predictions.index') }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Predictions</span>
            </a>
        </li>
    </ul>

</aside>
