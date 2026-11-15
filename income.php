<?php

  session_start();

  if (!(isset($_SESSION['logged_user_id']))) {
    header('location: index.php');
    exit();
  }

  $_SESSION['transaction'] = 'income';

  $user_id = $_SESSION['logged_user_id'];
  require_once 'database.php';

  $sql_user_categories = "SELECT id,name FROM incomes_category_assigned_to_users WHERE user_id=:user_id";
  $load_categories = $db->prepare($sql_user_categories);
  $load_categories->bindValue(":user_id", $user_id, PDO::PARAM_INT);
  $load_categories->execute();

  $user_categories = $load_categories->fetchAll();

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
          <a class="nav-link" aria-current="page" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="income.php">Add income</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="expense.php">Add expense</a>
        </li> 
        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Display balance
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="balance.php">Current month</a></li>
            <li><a class="dropdown-item" href="balance.php">Previous month</a></li>
            <li><a class="dropdown-item" href="balance.php">Current year</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="balance.php">non-standard</a></li>
          </ul>

        </li> 
        <li class="nav-item">
          <a class="nav-link" href="settings.php">Settings</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>

      </div>
      
    </nav>

  </header>

    <div class="container">

      <div class="row d-flex flex-column flex-md-row justify-content-between align-items-center">

        <main class="main-income col-12 col-md-7 col-lg-6 col-xl-5 my-5">

          <header>
            <h1 class="text-center text-md-start">Add new income:</h1>
          </header>

          <form id="income" action="add-transaction.php" method="post" novalidate>

            <div class="field-income">
              <label for="amount">Amount:</label>
              <input id="amount" type="number" name="amount" <?= isset($_SESSION['given_amount']) ? 'value="'.$_SESSION['given_amount'].'"' : '' ?> required>
            </div>

<?php

            if (isset($_SESSION['given_amount'])) {
              unset($_SESSION['given_amount']);
            }

?>

            <div class="field-income">
              <label for="date">Date:</label>
              <input id="date" type="date" name="date" <?= isset($_SESSION['given_date']) ? 'value="'.$_SESSION['given_date'].'"' : '' ?>
                required>
            </div>

<?php
          
            if (isset($_SESSION['given_date'])) {
              unset($_SESSION['given_date']);
            }

?>

            <div class="field-income">
              
              <fieldset form="income">

                <legend>Category:</legend>

<?php

    $isItFirstCategory = true;

    foreach ($user_categories as $category) {

      $checked_or_not = '';
      
      if (isset($_SESSION['given_category'])) {

        if ($_SESSION['given_category'] == $category['id']) {

          $checked_or_not = 'checked';
      
        }
   
      } else {

        if ($isItFirstCategory) {
          $checked_or_not = 'checked';
          $isItFirstCategory = false;
        }
      }
      
      echo '<label><input type="radio" name="category" value="'.$category['id'].'" '.$checked_or_not.'>'.$category['name'].'</label>';

    } 
    
    if (isset($_SESSION['given_category'])) {
      unset($_SESSION['given_category']);
    }
    
?>

              </fieldset>

            </div>

            <div class="field-income d-flex flex-sm-row flex-column align-items-start">
              <label for="comment" class="pe-md-2">Add comment:</label>
              <textarea id="comment" name="comment" rows="2" cols="30" maxlength="45"><?= isset($_SESSION['given_comment']) ? htmlspecialchars($_SESSION['given_comment']) : '' ?></textarea>
            </div>

<?php

            if (isset($_SESSION['given_comment'])) {
              unset($_SESSION['given_comment']);
            }

?>
            <div class="d-flex flex-column gap-3 flex-md-row justify-content-between">
              <button class="btn btn-primary cancel order-1 order-md-0" data-bs-toggle="modal" data-bs-target="#staticBackdrop" type="button">Cancel</button>
              <button class="btn btn-primary add"  type="submit">Add</button>
            </div>

            <div class="modal fade" id="staticBackdrop" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                      <h2 class="modal-title" id="staticBackdropLabel">Are you sure that you want to cancel this income? You will lose all data.</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body d-flex justify-content-between">
                    <a href="home.php" class="btn btn-primary cancel">Yes</a>
                    <button type="button" class="btn btn-secondary add" data-bs-dismiss="modal">No</button>   
                  </div>
                </div>
              </div>
            </div>

          </form>

<?php

      if (isset($_SESSION['income_added'])) {

        echo '<p class="username mt-3" style="color: var(--accent);">Income added successfully!</p>';
        unset($_SESSION['income_added']);

      } else if (isset($_SESSION['amount_error'])) {

        echo '<p class="username mt-3" style="color: var(--button);">'.$_SESSION['amount_error'].'</p>';
        unset($_SESSION['amount_error']);

      }

?>

        </main>

        <img src="assets/images/openclipart-vectors-chart-line-148256_1280.png" class="img-fluid illustration col-12 col-md-5 my-5"  alt="">

      </div>

    </div>

  <footer>
    <small>® Personal Budget 2026.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script src="navbar.js"></script>

</body>
</html>