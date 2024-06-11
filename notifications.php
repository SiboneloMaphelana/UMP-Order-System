<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
  <!-- Bootstrap Icons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <!-- Header -->
  <header class="header d-flex justify-content-between align-items-center p-3">
    <a href="index.php" class="btn btn-custom-size align-self-center">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="logo">Notifications</h1>
    <div class="dropdown">
      <button class="btn btn-custom-size align-self-center dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle"></i>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Logout</a></li>
      </ul>
    </div>
  </header>

  <div class="container mt-5">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Notification List</h5>
        <ul class="list-group">
          <li class="list-group-item">Notification 1</li>
          <li class="list-group-item">Notification 2</li>
          <li class="list-group-item">Notification 3</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
