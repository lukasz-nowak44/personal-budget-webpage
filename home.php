<?php

session_start();

if (!(isset($_SESSION['logged_user_id']))) {
  header('location: index.php');
  exit();
}

if (isset($_SESSION['given_signin_username'])) {
    unset($_SESSION['given_signin_username']);
  }

if (isset($_SESSION['given_signin_password'])) {
    unset($_SESSION['given_signin_password']);
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal Budget app</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="styles-app.css"> 
  
</head>
<body>

  <header>

    <nav class="navbar navbar-expand-lg navbar-dark py-0">

      <a class="navbar-brand" href="home.php"><img src="./assets/images/logo-light-removebg-preview.png" class="logo" alt=""></a>

      <button class="navbar-toggler me-3" type="button"        data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="income.php">Add income</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="expense.php">Add expense</a>
        </li> 
        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Display balance
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="display-balance.php?period=current-month">Current month</a></li>
            <li><a class="dropdown-item" href="display-balance.php?period=previous-month">Previous month</a></li>
            <li><a class="dropdown-item" href="display-balance.php?period=current-year">Current year</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="balance.php">non-standard</a></li>
          </ul>

        </li> 
        <li class="nav-item">
          <a class="nav-link" href="settings.html">Settings</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="">Logged as <?php  echo htmlspecialchars($_SESSION['logged_user_username']) ?></a>
        </li>
      </ul>

      </div>

    </nav>
    
  </header>

  <main class="home">

      <img src="assets/images/alexanderstein-house-167734_1920.jpg" class="img-fluid photo" alt="">

      <header>
        <h1>Hello <?php echo '<span class="username" style="color: var(--button);">'.htmlspecialchars($_SESSION['logged_user_username']).'</span>' ?>! What do we do?</h1>
      </header>

      <section class="options">

          <a class="card" href="income.php">
            <img src="./assets/icons/moneybag-plus.png" class="card-img-top" alt="">
            <div class="card-body">
              <p class="card-text">Add income</p>
            </div>
          </a>

          <a class="card" href="expense.php">
            <img src="./assets/icons/moneybag-minus.png" class="card-img-top" alt="">
            <div class="card-body">
              <p class="card-text">Add expense</p>
            </div>
          </a>

          <a class="card current-month" href="display-balance.php?period=current-month">
            <img src="./assets/icons/calendar-month.png" class="card-img-top" alt="">
            <div class="card-body">
              <p class="card-text">Balance from current month</p>
            </div>
          </a>

          <button class="card px-0" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <img src="./assets/icons/scale.png" class="card-img-top" alt="">
            <div class="card-body">
              <p class="card-text">Balance from another period</p>
            </div>
          </button>

          <div class="modal fade" id="staticBackdrop" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                   <h2 class="modal-title" id="staticBackdropLabel">For which period would you like to see the balance?</h2>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <ul class="periods">
                    <li><a class="dropdown-item" href="display-balance.php?period=current-month">Current month</a></li>
                    <li><a class="dropdown-item" href="display-balance.php?period=previous-month">Previous month</a></li>
                    <li><a class="dropdown-item" href="display-balance.php?period=current-year">Current year</a></li>
                    <li><a class="dropdown-item" href="balance.php">non-standard</a></li>
                  </ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          
          <a class="card" href="settings.php">
            <img src="./assets/icons/settings-cog.png" class="card-img-top" alt="">
            <div class="card-body">
              <p class="card-text">Change settings</p>
            </div>
          </a>

          <a class="card" href="logout.php">
            <img src="./assets/icons/logout.png" class="card-img-top" alt="">
            <div class="card-body">
              <p class="card-text">Logout</p>
            </div>
          </a>

      </section>

  </main>

  <footer>
    <small>® Personal Budget 2026.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script src="navbar.js"></script>

  <script src="home-cards.js"></script>

</body>
</html>