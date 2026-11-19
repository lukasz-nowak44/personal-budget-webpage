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

<?php

  require_once 'head.php';

?>

<body>

  <header>

<?php

  $_SESSION['home_active'] = true;

  require_once 'navbar.php';

?>
    
  </header>

  <main class="home">

      <img src="assets/images/alexanderstein-house-167734_1920.jpg" class="img-fluid photo" alt="">

      <header>
        <h1>Hello <?php echo '<span class="username">'.htmlspecialchars($_SESSION['logged_user_username']).'</span>' ?>! What do we do?</h1>
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
                    <li><a class="dropdown-item" href="display-balance.php?period=non-standard">non-standard</a></li>
                  </ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          
          <a class="card" href="settings.php">
            <img src="./assets/icons/settings.png" class="card-img-top" alt="">
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

<?php

  require_once 'footer.php';

?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script src="navbar.js"></script>

  <script src="home-cards.js"></script>

</body>
</html>