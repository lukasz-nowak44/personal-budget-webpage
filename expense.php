<?php

  session_start();

  if (!(isset($_SESSION['logged_user_id']))) {
    header('location: index.php');
    exit();
  }

  $_SESSION['transaction'] = 'expense';

  $user_id = $_SESSION['logged_user_id'];
  $types_of_options = ['expenses_category','payment_methods']; 
  $expenses_category = []; 
  $payment_methods = [];
  
  require_once 'database.php';

  foreach ($types_of_options as $type) {

    $sql_user_categories = "SELECT id,name FROM ".$type."_assigned_to_users WHERE user_id=:user_id";
    $load_categories = $db->prepare($sql_user_categories);
    $load_categories->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $load_categories->execute();

    if ($type === 'expenses_category') {
      $expenses_category = $load_categories->fetchAll();
    } else {
      $payment_methods = $load_categories->fetchAll();
    }
    
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

  <link rel="stylesheet" href="styles-root.css"> 
  <link rel="stylesheet" href="styles-app.css"> 
  
</head>
<body>

  <header>

<?php

  $_SESSION['expense_active'] = true;

  require_once 'navbar.php';

?>

  </header>

    <div class="container">

      <div class="row d-flex flex-column flex-md-row justify-content-between align-items-center">

        <main class="main-expense col-12 col-md-7 col-lg-6 col-xl-5 my-5">

          <header>
            <h1 class="text-center text-md-start">Add new expense:</h1>
          </header>

          <form action="add-transaction.php" id="expense" method="post" novalidate>

            <div class="field-expense">
              <label for="amount">Amount:</label>
              <input id="amount" type="number" name="amount" <?= isset($_SESSION['given_amount']) ? 'value="'.$_SESSION['given_amount'].'"' : '' ?> required>
            </div>

<?php

            if (isset($_SESSION['given_amount'])) {
              unset($_SESSION['given_amount']);
            }

?>

            <div class="field-expense">
              <label for="date">Date:</label>
              <input id="date" type="date" name="date" <?= isset($_SESSION['given_date']) ? 'value="'.$_SESSION['given_date'].'"' : '' ?>
                required>
            </div>

<?php

            if (isset($_SESSION['given_date'])) {
              unset($_SESSION['given_date']);
            }

?>

            <div class="field-expense">

<?php
   
    foreach ($types_of_options as $type) {

      if ($type === 'expenses_category') {
        echo '<fieldset form="expense"><legend>Category:</legend>';
      } else {
        echo '<fieldset form="payment" class="mt-4"><legend>Payment method:</legend>';
      }

      $isItFirstCategory = true;
      $given_type_name = 'given_'.$type.'';

      // Below variable variables mechanism!
      foreach ($$type as $category) {

        $checked_or_not = '';
        
        if (isset($_SESSION[$given_type_name])) {

          if ($_SESSION[$given_type_name] == $category['id']) {

            $checked_or_not = 'checked';
        
          }
    
        } else {

          if ($isItFirstCategory) {
            $checked_or_not = 'checked';
            $isItFirstCategory = false;
          }
        }

        echo '<label class="me-3"><input type="radio" name="'.$type.'" value="'.$category['id'].'" '.$checked_or_not.'>'.$category['name'].'</label>';

      } 
    
      if (isset($_SESSION[$given_type_name])) {
        unset($_SESSION[$given_type_name]);
      }

      echo '</fieldset>';
    }
          
?>
          
            </div>
            <div class="field-expense d-flex flex-sm-row flex-column align-items-start">
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

            <div class="modal fade" id="staticBackdrop" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                      <h2 class="modal-title" id="staticBackdropLabel">Are you sure that you want to cancel this expense? You will lose all data.</h2>
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

      if (isset($_SESSION['expense_added'])) {

        echo '<p class="transaction-added mt-3">Expense added successfully!</p>';
        unset($_SESSION['expense_added']);

      } else if (isset($_SESSION['amount_error'])) {

        echo '<p class="transaction-error mt-3">'.$_SESSION['amount_error'].'</p>';
        unset($_SESSION['amount_error']);

      }

?>

        </main>

        <img src="assets/images/expense.jpg.png" class="img-fluid illustration col-12 col-md-5 my-5"  alt="">

      </div>

    </div>

<?php

  require_once 'footer.php';

?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script src="navbar.js"></script>

  <script src="added-sound.js" defer></script>

</body>
</html>