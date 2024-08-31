<header id="header" class="header fixed-top d-flex align-items-center">
  <!-- Logo Section -->
  <div class="d-flex align-items-center justify-content-between">
    <a href="index.html" class="logo d-flex align-items-center">
      <span class="d-none d-lg-block logo-text">Dining Management System</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>
  <!-- End Logo Section -->

  <!-- Search Bar -->
  <div class="search-bar d-none d-md-block">
    <form class="search-form d-flex align-items-center" method="GET" action="#">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword" class="form-control">
      <button type="submit" title="Search" class="btn btn-search">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>
  <!-- End Search Bar -->

  <!-- Navigation -->
  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center mb-0">
      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle profile-img">
          <span class="d-none d-md-block ms-2 dropdown-toggle">User Name</span>
        </a>
        <!-- Dropdown Menu -->
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header text-center">
            <h6 class="mb-0">User Name</h6>
            <span>Web Designer</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a href="{{ route('logout') }}" class="dropdown-item" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
        <!-- End Dropdown Menu -->
      </li>
      <!-- End User Profile Dropdown -->
    </ul>
  </nav>
  <!-- End Navigation -->
</header>
