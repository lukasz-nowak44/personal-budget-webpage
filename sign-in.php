<?php

session_start();

$_SESSION['given_signin_username'] = $_POST['username'];
$_SESSION['given_signin_password'] = $_POST['password'];

require_once 'database.php';

$isEverythingGood = true;

if (empty($_POST['username'])) {

   $_SESSION['username_signin_error'] = '<p class="error login">Username cannot be empty!</p>';
   $isEverythingGood = false;
   header('location: index.php');
   exit();

} else {

   $username = $_POST['username'];
   $sql = "SELECT username FROM users WHERE username=:username";
   $query = $db->prepare($sql);
   $query->bindValue(":username", $username, PDO::PARAM_STR);
   $query->execute();

   if($query->rowCount() === 0) {

     $_SESSION['username_signin_error'] = '<p class="error login">Username does not exist in database!</p>';
     $isEverythingGood = false;
     header('location: index.php');
     exit();

   }

 }

 if (empty($_POST['password'])) {

   $_SESSION['password_signin_error'] = '<p class="error password">Password cannot be empty!</p>';
   $isEverythingGood = false;
   header('location: index.php');
   exit();

 } else {

  $password = $_POST['password'];
  $sql = "SELECT * FROM users WHERE username=:username";
  $query = $db->prepare($sql);
  $query->bindValue(":username", $username, PDO::PARAM_STR);
  $query->execute();

  $user = $query->fetch();

  if(!(password_verify($password, $user['password']))) {

    $_SESSION['password_signin_error'] = '<p class="error password">Password is incorrect! Try again</p>';
    $isEverythingGood = false;
    header('location: index.php');
    exit();

  } else if ($isEverythingGood) {

    $_SESSION['logged_user_id'] = $user['id'];
    $_SESSION['logged_user_username'] = $user['username'];

    header('location: home.php');

  }

}