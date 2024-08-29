<div id="sidebar">
        <div>
            <a class="navbar-brand ms-auto text-light" href="#">
                <img src="partials/logo.png" alt="Logo">
            </a>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-light" href="home.php"><i class="fas fa-home me-2"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="orders.php"><i class="fas fa-shopping-cart me-2"></i> Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="customers.php"><i class="fas fa-users me-2"></i> Customers</a>
                </li>
                <!-- Menu Section -->
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="menuDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#menuSubmenu" aria-expanded="false">
                        <i class="fas fa-book me-2"></i> Menu
                    </a>
                    <ul class="collapse list-unstyled ps-3" id="menuSubmenu">
                        <li><a class="nav-link text-light" href="add_menu.php"><i class="fas fa-plus me-2"></i> Add Menu</a></li>
                        <li><a class="nav-link text-light" href="all_menus.php"><i class="fas fa-list me-2"></i> All Menu</a></li>
                    </ul>
                </li>
                <!-- Category Section -->
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="categoryDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#categorySubmenu" aria-expanded="false">
                        <i class="fas fa-tags me-2"></i> Categories
                    </a>
                    <ul class="collapse list-unstyled ps-3" id="categorySubmenu">
                        <li><a class="nav-link text-light" href="add_category.php"><i class="fas fa-plus me-2"></i> Add Category</a></li>
                        <li><a class="nav-link text-light" href="all_categories.php"><i class="fas fa-list me-2"></i> All Categories</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Profile Dropdown at the Bottom -->
        <div class="profile-dropdown">
            <button class="btn btn-dark dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user me-2"></i> Profile
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                <li><a class="dropdown-item text-danger" href="model/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
    