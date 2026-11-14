<?php

  session_start();

  $transaction = $_SESSION['transaction'];
  unset($_SESSION['transaction']);
  $name_for_header = 'location: '.$transaction.'.php';

  $user_id = $_SESSION['logged_user_id'];
  require_once 'database.php';

  $_SESSION['given_amount'] = $_POST['amount'];
  $_SESSION['given_date'] = $_POST['date'];

  if ($transaction === 'income') {
    $_SESSION['given_category'] = $_POST['category'];
  } else {
    $_SESSION['given_expenses_category'] = $_POST['expenses_category'];
    $_SESSION['given_payment_methods'] = $_POST['payment_methods'];
  }
 
  $_SESSION['given_comment'] = $_POST['comment'];

  $isEverythingGood = true;

  if (empty($_POST['amount'])) {

    $_SESSION['amount_error'] = 'The amount cannot be empty';
    $isEverythingGood = false;
    header($name_for_header);
    exit();

  } else if (!(filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT))) {

      $_SESSION['amount_error'] = 'This field must be in correct format';
      $isEverythingGood = false;
      header($name_for_header);
      exit();
  }


  if ($isEverythingGood) {

    $amount = round($_POST['amount'],2);
    $date = $_POST['date'];

    if ($transaction === 'income') {
      $id_category = $_POST['category'];
    } else {
      $id_category = $_POST['expenses_category'];
      $id_payment_method = $_POST['payment_methods'];
    }
    
    $comment = $_POST['comment'];

    $payment_column_name = '';
    $payment_variable_name = '';

    if ($transaction === 'expense') {
      $payment_column_name = ', payment_method_assigned_to_user_id';
      $payment_variable_name = ', :id_payment_method';
    }
    
    $sql_add = "INSERT INTO ".$transaction."s (user_id, ".$transaction."_category_assigned_to_user_id, amount, date_of_".$transaction.", ".$transaction."_comment".$payment_column_name.") VALUES (:user_id, :id_category, :amount, :date, :comment".$payment_variable_name.")";
    echo $sql_add;
    $add_transaction = $db->prepare($sql_add);
    $add_transaction->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $add_transaction->bindValue(":id_category", $id_category, PDO::PARAM_INT);
    $add_transaction->bindValue(":amount", $amount, PDO::PARAM_STR);
    $add_transaction->bindValue(":date", $date, PDO::PARAM_STR);
    $add_transaction->bindValue(":comment", $comment, PDO::PARAM_STR);
    if ($transaction === 'expense') {
      $add_transaction->bindValue(":id_payment_method", $id_payment_method, PDO::PARAM_INT);
    }
    $add_transaction->execute();

    $name_transaction_added = $transaction.'_added';
    $_SESSION[$name_transaction_added] = true;  
    unset($_SESSION['given_amount']);
    unset($_SESSION['given_date']);

    if ($transaction === 'income') {
      unset($_SESSION['given_category']);
    } else {
      unset($_SESSION['given_expenses_category']);
      unset($_SESSION['given_payment_methods']);
    }
      
    unset($_SESSION['given_date']);
    unset($_SESSION['given_comment']);

    header($name_for_header);

  }

  