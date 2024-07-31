<div class="col-12 col-sm-3 col-xl-2 px-sm-2 px-0 sidebar-bg d-flex sticky-top">
    <div class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-3 pt-2 text-white">
        <a href="dashboard.php" class="d-flex align-items-center pb-sm-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5"><img src="partials/logo.png" alt="logo" class="img-fluid h-25"></span>
        </a>
        <ul class="nav nav-pills flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto mb-0 justify-content-center align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link px-sm-0 px-2">
                    <i class="fs-5 fas fa-home"></i><span class="ms-1 d-none d-sm-inline">Home</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php" data-bs-toggle="collapse" class="nav-link px-sm-0 px-2">
                    <i class="fs-5 fas fa-tachometer-alt"></i><span class="ms-1 d-none d-sm-inline">Dashboard</span> </a>
            </li>
            <li>
                <a href="orders.php" class="nav-link px-sm-0 px-2">
                    <i class="fs-5 fas fa-table"></i><span class="ms-1 d-none d-sm-inline">Orders</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link dropdown-toggle px-sm-0 px-1" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fs-5 fas fa-bars"></i><span class="ms-1 d-none d-sm-inline">Menu</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdown">
                    <li><a class="dropdown-item" href="all_categories.php">All categories</a></li>
                    <li><a class="dropdown-item" href="add_category.php">Add category</a></li>
                    <li><a class="dropdown-item" href="add_menu.php">Add menu</a></li>
                    <li><a class="dropdown-item" href="all_menus.php">All Menu</a></li>
                </ul>
            </li>
            <li>
                <a href="customers.php" class="nav-link px-sm-0 px-2">
                    <i class="fs-5 fas fa-users"></i><span class="ms-1 d-none d-sm-inline">Customers</span> </a>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link dropdown-toggle px-sm-0 px-1" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fs-5 fas fa-bell"></i><span class="ms-1 d-none d-sm-inline">Notifications</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="notificationsDropdown">
                    <li><a class="dropdown-item" href="order_notifications.php">Order Notifications</a></li>
                    <li><a class="dropdown-item" href="password_reset_notifications.php">Password Reset Notifications</a></li>
                    <li><a class="dropdown-item" href="account_update_notifications.php">Account Update Notifications</a></li>
                    <!-- Add more notification types as needed -->
                </ul>
            </li>
        </ul>
        <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="" alt="hugenerd" width="28" height="28" class="rounded-circle">
                <span class="d-none d-sm-inline mx-1">Admin</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="model/logout.php">Log out</a></li>
            </ul>
        </div>
    </div>
</div>
