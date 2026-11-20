<?php

  session_start();

  require_once 'helpers.php';

  if (!isset($_SESSION['logged_user_id'])) {
    header('Location: index.php');
    exit();
  }

  $transaction = $_GET['transaction'] ?? '';
  
  
  if (!in_array($transaction, ['income', 'expense'], true)) {
      header('Location: home.php');
      exit();
  }

  $_SESSION['transaction'] = $transaction;
  $user_id = $_SESSION['logged_user_id'];
  $types_of_options = ['expenses_category','payment_methods']; 
  $user_categories = []; 
  $expenses_category = []; 
  $payment_methods = [];

  require_once 'database.php';

  if ($transaction === 'expense') {

    foreach ($types_of_options as $type) {

      $sql_user_categories = "SELECT id,name FROM ".$type."_assigned_to_users WHERE user_id=:user_id";
      $load_categories = $db->prepare($sql_user_categories);
      $load_categories->bindValue(":user_id", $user_id, PDO::PARAM_INT);
      $load_categories->execute();

      if ($type === 'expenses_category') {
      
        $expenses_category = $load_categories->fetchAll();
        if (empty($expenses_category)) {

          $_SESSION['categories_error'] = "You don't have any expense categories. Add at least one.";
          header('Location: settings.php');
          exit();
          
        }

      } else {

        $payment_methods = $load_categories->fetchAll(); 
        if (empty($payment_methods)) {

          $_SESSION['categories_error'] = "You don't have any payment methods. Add at least one.";
          header('Location: settings.php');
          exit();
          
        }

      } 
      
    }
    
  } else {

    $sql_user_categories = "SELECT id,name FROM incomes_category_assigned_to_users WHERE user_id=:user_id";
    $load_categories = $db->prepare($sql_user_categories);	
    $load_categories->bindValue(":user_id", $user_id, PDO::PARAM_INT);	
    $load_categories->execute();

    $user_categories = $load_categories->fetchAll();

    if (empty($user_categories)) {

      $_SESSION['categories_error'] = "You don't have any income categories. Add at least one.";
      header('Location: settings.php');
      exit();
      
    }
    
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

  $active_transaction = $transaction.'_active';

  $_SESSION[$active_transaction] = true;

  require_once 'navbar.php';

?>

  </header> 

    <div class="container">

      <div class="row d-flex flex-column flex-md-row justify-content-between align-items-center">

<?php 

  echo '<main class="main-'.$transaction.' col-12 col-md-7 col-lg-6 col-xl-5 my-5">';
  echo '<header>';
  echo '<h1 class="text-center text-md-start">Add new '.$transaction.':</h1>';
  echo '</header>';
  echo '<form id="'.$transaction.'" action="add-transaction.php" method="post" novalidate>';
  echo '<div class="field-'.$transaction.'">';

?>

      <label for="amount">Amount:</label>
      <input id="amount" type="number" name="amount" <?= isset($_SESSION['given_amount']) ? 'value="'.e($_SESSION['given_amount']).'"' : '' ?> required>

    </div>
    
<?php

  if (isset($_SESSION['given_amount'])) {
    unset($_SESSION['given_amount']);
  }

  echo '<div class="field-'.$transaction.'">';

?>

      <label for="date">Date:</label>
      <input id="date" type="date" name="date" <?= isset($_SESSION['given_date']) ? 'value="'.e($_SESSION['given_date']).'"' : '' ?> required>

    </div>

<?php

    if (isset($_SESSION['given_date'])) {
      unset($_SESSION['given_date']);
    }

    echo '<div class="field-'.$transaction.'">';

    if ($transaction === 'income') {

      echo '<fieldset form="income">';
      echo '<legend>Category:</legend>';
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

      echo '</fieldset>';

    } else {

      foreach ($types_of_options as $type) {

        if ($type === 'expenses_category') {

          echo '<fieldset form="expense"><legend>Category:</legend>';

        } else {

          echo '<fieldset form="payment" class="mt-4"><legend>Payment method:</legend>';

        }

        $isItFirstCategory = true;

        $given_type_name = 'given_'.$type.'';

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

    }

    echo '</div>';
    echo '<div class="field-'.$transaction.' d-flex flex-sm-row flex-column align-items-start">';

?>

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
                                           
<?php

     echo '<h2 class="modal-title" id="staticBackdropLabel">Are you sure that you want to cancel this '.$transaction.'? You will lose all data.</h2>'; 

?>

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

  $transaction_added = $transaction.'_added';

    if (isset($_SESSION[$transaction_added])) {

        echo '<p class="transaction-added mt-3">'.$transaction.' added successfully!</p>';
        unset($_SESSION[$transaction_added]);

    } else if (isset($_SESSION['transaction_error'])) {

        echo '<p class="transaction-error mt-3">'.$_SESSION['transaction_error'].'</p>';
        unset($_SESSION['transaction_error']);

    }

    echo '</main>';
    echo '<img src="assets/images/'.$transaction.'.png" class="img-fluid illustration col-12 col-md-5 my-5"  alt="">';

?>

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

        



    







?>