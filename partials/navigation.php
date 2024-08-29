<div class="header text-white" style="background-color: #353D55;">
    <div class="container-fluid">
        <div class="row align-items-center py-2">
            <div class="col-12 d-md-none d-flex justify-content-between align-items-center">
                <!-- Mobile Navbar Brand and Search Bar -->
                <a href="#" class="navbar-brand text-white mx-auto mx-md-0">
                    <img src="images/logo.png" class="me-2" alt="logo" style="height: 36px;">
                </a>
                <form action="search.php" method="POST" class="w-75 mx-auto">
                    <div class="input-group">
                        <input type="text" class="form-control py-2 px-3" placeholder="Search" name="search">
                        <button class="btn btn-outline-light" type="submit"><i class="fas fa-search border-0"></i></button>
                    </div>
                </form>

            </div>

            <div class="col-12 d-none d-md-block">
                <!-- Desktop Navbar Brand, Search Bar, Cart, and Account Dropdown -->
                <div class="row align-items-center">
                    <div class="col-md-3 d-flex align-items-center">
                        <a href="#" class="navbar-brand text-white mx-auto mx-md-0">
                            <img src="images/logo.png" alt="logo" class="me-2 logo">
                        </a>
                    </div>
                    <div class="col-md-9 d-flex justify-content-end align-items-center">
                        <form action="search.php" method="POST" class="me-3" style="width: 300px;" id="search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" name="search" style="height: 36px;">
                                <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                        <a href="cart.php" class="text-white me-3 text-decoration-none d-flex align-items-center">
                            <i class="fas fa-shopping-cart"></i> <span class="d-none d-md-inline ms-2">Cart</span>
                        </a>
                        <div class="dropdown">
                            <a href="#" class="text-white text-decoration-none d-flex align-items-center dropdown-toggle" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <span class="d-none d-md-inline ms-2">Account</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="model/logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- Desktop Navigation -->
<nav class="navbar navbar-expand-md navbar-dark d-none d-md-block desktop">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item me-4">
                    <a class="nav-link text-dark fs-4" href="index.php">Menu</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link text-dark fs-4" href="orders.php">Track Orders</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link text-dark fs-4" href="about.php">About Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<nav class="navbar navbar-dark d-md-none fixed-bottom">
    <div class="container-fluid d-flex justify-content-around">
        <a class="nav-link text-white" href="index.php"><i class="fas fa-utensils"></i><br>Menu</a>
        <a class="nav-link text-white" href="cart.php"><i class="fas fa-shopping-cart"></i><br>Cart</a>
        <a class="nav-link text-white" href="orders.php"><i class="fas fa-box"></i><br>Orders</a>
        <div class="dropup">
            <a class="nav-link text-white dropdown-toggle" href="#" id="mobileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user"></i><br>More
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileDropdown">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="about.php">About Us</a></li>
                <li><a class="dropdown-item" href="model/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>