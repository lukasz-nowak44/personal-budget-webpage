<?php

  session_start();

  $user_id = $_SESSION['logged_user_id'];
  require_once 'database.php';

  $_SESSION['given_amount'] = $_POST['amount'];
  $_SESSION['given_date'] = $_POST['date'];
  $_SESSION['given_category'] = $_POST['category'];
  $_SESSION['given_comment'] = $_POST['comment'];

  $isEverythingGood = true;

  if (empty($_POST['amount'])) {

    $_SESSION['amount_error'] = 'The amount cannot be empty';
    $isEverythingGood = false;
    header('location: income.php');
    exit();

  } else if (!(filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT))) {

      $_SESSION['amount_error'] = 'This field must be in correct format';
      $isEverythingGood = false;
      header('location: income.php');
      exit();
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
    $add_income->bindValue(":date", $date, PDO::PARAM_STR);
    $add_income->bindValue(":comment", $comment, PDO::PARAM_STR);
    $add_income->execute();

    $_SESSION['income_added'] = true;  
    unset($_SESSION['given_amount']);
    unset($_SESSION['given_date']);
    unset($_SESSION['given_category']);
    unset($_SESSION['given_comment']);

    header('location: income.php');

  }

  