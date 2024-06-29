<header class="header fixed-top d-flex justify-content-between align-items-center px-2 py-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-9 order-md-2">
                <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="index.php"><i class="bi bi-house-fill"></i> Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="notifications.php"><i class="bi bi-bell-fill"></i> Notifications</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="orders.php"><i class="bi bi-list-check"></i> Orders</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="cart.php"><i class="bi bi-cart-fill"></i> Cart</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-md-3 order-md-1">
                <img src="images/logo.jpeg" alt="UMP logo" class="logo img-fluid navbar-brand">
            </div>
        </div>
    </div>
    <!-- Search Bar -->
    <div class="input-group search-bar">
        <input type="search" class="form-control" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="button">Search</button>
    </div>
    
    <!-- User dropdown -->
    <div class="dropdown ms-auto">
        <button class="btn btn-light border-0 dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-fill text-success" style="font-size: 1.5rem;"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
            <li><a class="dropdown-item" href="signup.php">Sign Up</a></li>
            <li><a class="dropdown-item text-danger" href="model/logout.php">Logout</a></li>
        </ul>
    </div>
</header>
