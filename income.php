<?php

  session_start();

  $user_id = $_SESSION['logged_user_id'];
  require_once 'database.php';

  $sql_user_categories = "SELECT id,name FROM incomes_category_assigned_to_users WHERE user_id=:user_id";
  $load_categories = $db->prepare($sql_user_categories);
  $load_categories->bindValue(":user_id", $user_id, PDO::PARAM_INT);
  $load_categories->execute();

  $user_categories = $load_categories->fetchAll();

  $isEverythingGood = true;

  if (empty($_POST['amount'])) {

    $_SESSION['amount_error'] = 'This field cannot be empty';
    $isEverythingGood = false;

  } else if (!(filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT))) {

      $_SESSION['amount_error'] = 'This field must be in correct format';
      $isEverythingGood = false;

  }


  if ($isEverythingGood) {

    $amount = round($_POST['amount'],2);
    $date = $_POST['date'];
    $id_category = $_POST['category'];
    $comment = $_POST['comment'];
    
    $sql_add = "INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) VALUES (:user_id, :id_category, :amount, :date, :comment)";
    $add_income = $db->prepare($sql_add);
    $add_income->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $add_income->bindValue(":id_category", $id_category, PDO::PARAM_INT);
    $add_income->bindValue(":amount", $amount, PDO::PARAM_STR);
    $add_income->bindValue(":date", $user_id, PDO::PARAM_STR);
    $add_income->bindValue(":comment", $comment, PDO::PARAM_STR);
    $add_income->execute();

    $_SESSION['income_added'] = true;

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

      <a class="navbar-brand" href="home.html"><img src="./assets/images/logo-light-removebg-preview.png" class="logo" alt=""></a>

      <button class="navbar-toggler me-3" type="button"        data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="home.html">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="income.html">Add income</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="expense.html">Add expense</a>
        </li> 
        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Display balance
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="balance.html">Current month</a></li>
            <li><a class="dropdown-item" href="balance.html">Previous month</a></li>
            <li><a class="dropdown-item" href="balance.html">Current year</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="balance.html">non-standard</a></li>
          </ul>

        </li> 
        <li class="nav-item">
          <a class="nav-link" href="settings.html">Settings</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="index.html">Logout</a>
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

          <form id="income" method="post" novalidate>

            <div class="field-income">
              <label for="amount">Amount:</label>
              <input id="amount" type="number" name="amount" required>
            </div>

            <div class="field-income">
              <label for="date">Date:</label>
              <input id="date" type="date" name="date" value=""
                required>
            </div>

            <div class="field-income">
              
              <fieldset form="income">

                <legend>Category:</legend>

<?php

    $isItFirstCategory = true;

    foreach ($user_categories as $category) {
 
        if ($isItFirstCategory) {
          echo '<label><input type="radio" name="category" value="'.$category['id'].'" checked>'.$category['name'].'</label>';
          $isItFirstCategory = false;

        } else {
          echo '<label><input type="radio" name="category" value="'.$category['id'].'">'.$category['name'].'</label>';
        }

    }

?>

              </fieldset>

            </div>

            <div class="field-income d-flex flex-sm-row flex-column align-items-start">
              <label for="comment" class="pe-md-2">Add comment:</label>
              <textarea id="comment" name="comment" rows="2" cols="30" maxlength="45">
              </textarea>
            </div>

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
                    <a href="home.html" class="btn btn-primary cancel">Yes</a>
                    <button type="button" class="btn btn-secondary add" data-bs-dismiss="modal">No</button>   
                  </div>
                </div>
              </div>
            </div>

          </form>

<?php

      if (isset($_SESSION['income_added'])) {

        echo '<p class="username">Income added successfully!</p>';
        unset($_SESSION['income_added']);
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