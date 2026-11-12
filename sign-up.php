<?php 

  session_start();
  
  $username = filter_input(INPUT_POST, 'username');
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, 'password');
  $repeat_password = filter_input(INPUT_POST, 'repeat_password');
  

  if (!$email) {

    echo 'E-mail ma niepoprawny format';
    exit();

  } else {

    require_once 'database.php';

    $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
   
    $query = $db->prepare($sql);

    $query->bindValue(':username', $username, PDO::PARAM_STR);
    $query->bindValue(':password', $password, PDO::PARAM_STR);
    $query->bindValue(':email', $email, PDO::PARAM_STR);

    $query->execute();

    echo '<p class="ms-5">Registration completed successful! You can now log in!<p>';

    // header('location: index.php');

  }

      


