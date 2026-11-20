<?php

  session_start();

  if (!isset($_SESSION['logged_user_id'])) {
    header('Location: index.php');
    exit();
  }

  $transaction = $_SESSION['transaction'] ?? '';
  if (!in_array($transaction, ['income', 'expense'], true)) {
      header('Location: home.php');
      exit();
  }
  $redirect = 'Location: ' . $transaction . '.php';
  $user_id  = (int) $_SESSION['logged_user_id'];

  require_once 'database.php';

  $_SESSION['given_amount'] = $_POST['amount'] ?? '';
  $_SESSION['given_date'] = $_POST['date'];

  if ($transaction === 'income') {
    $_SESSION['given_category'] = $_POST['category'];
  } else {
    $_SESSION['given_expenses_category'] = $_POST['expenses_category'];
    $_SESSION['given_payment_methods'] = $_POST['payment_methods'];
  }
 
  $_SESSION['given_comment'] = $_POST['comment'];

  $isEverythingGood = true;

  $amount = str_replace(',', '.', trim($_POST['amount'] ?? ''));

  if ($amount === '') {

    $_SESSION['amount_error'] = 'Amount cannot be empty';
    $isEverythingGood = false;
    header($redirect);
    exit();

  }

  if (($amount = filter_var($amount, FILTER_VALIDATE_FLOAT)) !== false) {

    $amount = (float) bcdiv((string)$amount, '1', 2);

  } else {

    $_SESSION['amount_error'] = 'Incorrect amount format';
    $isEverythingGood = false;
    header($redirect);
    exit();

  }

  $max_amount = 999999.99;

  if (($amount > $max_amount) || ($amount <= 0)) {

    $_SESSION['amount_error'] = 'Amount out of range';
    $isEverythingGood = false;
    header($redirect);
    exit();

  }

  $date = DateTime::createFromFormat('Y-m-d', $_POST['date'] ?? '');
  if (!$date || $date->format('Y-m-d') !== $_POST['date']) {
      $_SESSION['amount_error'] = 'Incorrect date';
      $isEverythingGood = false;
      header($redirect);
      exit();
  }

  $categoryId = (int) ($transaction === 'income' ? $_POST['category'] : $_POST['expenses_category']);
  $check = $db->prepare("SELECT 1 FROM {$transaction}s_category_assigned_to_users WHERE id = :id AND user_id = :user_id");
  $check->execute([':id' => $categoryId, ':user_id' => $user_id]);

  if (!$check->fetchColumn()) {
      $isEverythingGood = false;
      header($redirect);
      exit();
  }

  if ($transaction === 'expense') {
    $payment_method_id = $_POST['payment_methods'];

    $check_payment = $db->prepare("SELECT 1 FROM payment_methods_assigned_to_users WHERE id = :id AND user_id = :user_id");
    $check_payment->execute([':id' => $payment_method_id, ':user_id' => $user_id]);

    if (!$check_payment->fetchColumn()) {
      $isEverythingGood = false;
      header($redirect);
      exit();
    }

  }

  if ($isEverythingGood) {

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

    header($redirect);

  }

  