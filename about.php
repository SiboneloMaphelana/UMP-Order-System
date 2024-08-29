<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/new.css">

</head>

<body>



  <div class="container-fluid overflow-hidden">
    <div class="row vh-100 overflow-auto">
      <?php include_once("partials/navigation.php"); ?>
      <div class="col d-flex flex-column h-sm-100">
        <main class="row overflow-auto main-content">
          <div class="col-md-6 mx-auto">
            <!-- About Us Section -->
            <section class="about-us">
              <div class="hero-image">
                <img src="images/about.png" alt="Hero" class="img-fluid">
              </div>
              <div class="container mt-4">
                <div class="row">
                  <div class="col-12 text-center">
                    <h1>About Us</h1>
                    <p class="lead">Welcome to our UMP Cafeteria!</p>
                    <i class="fas fa-question-circle fa-3x"></i>

                    <p class="lead">Who Are We?</p>
                    <p class="text-justify">
                      Well, we are the UMP Cafeteria. We provide a variety of delicious food items for our students and staff at an affordable price. From budget breakfasts, plates, beverages, to snacks and desserts. Meals are prepared by our top chefs and our hospitality students in training.
                    </p>
                  </div>
                </div>
              </div>
            </section>


          </div>
        </main>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>