<?php 

  session_start();

  $_SESSION['given_username'] = $_POST['username'];
  $_SESSION['given_email'] = $_POST['email'];
  $_SESSION['given_password'] = $_POST['password'];
  $_SESSION['given_repeat_password'] = $_POST['repeat_password'];

  if (isset($_POST['agreement'])) {

    $_SESSION['agreement'] = true;

  }
  
  $isEverythingGood = true;

  require_once 'database.php';

  if (empty($_POST['username'])){

    $_SESSION['username_error'] = '<p class="error login">Username cannot be empty!</p>';
    $isEverythingGood = false;
   
  } else {

    $username = $_POST['username'];
    $sql = "SELECT username FROM users WHERE username=:username";
    $query = $db->prepare($sql);
    $query->bindValue(':username', $username, PDO::PARAM_STR);
    $query->execute();
    
    if ($query->rowCount() > 0) {

      $_SESSION['username_error'] = '<p class="error login">This username exists in our database! Please set another name.</p>';
      $isEverythingGood = false;

    } else if ((strlen($username) < 5 ) || (strlen($username) > 50)) {

      $_SESSION['username_error'] = '<p class="error login">Username must have between 5 and 50 characters!</p>';
      $isEverythingGood = false;
    } 

  } 

  
  if (empty($_POST['email'])) {

    $_SESSION['email_error'] = '<p class="error email">Email cannot be empty!</p>';
    $isEverythingGood = false;

  } else {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!$email || ($email != $sanitized_email)) {

      $_SESSION['email_error'] = '<p class="error email">E-mail has wrong format</p>';

      $isEverythingGood = false;

    } else {

      $sql = "SELECT email FROM users WHERE email=:email";
      $query = $db->prepare($sql);
      $query->bindValue(':email', $email, PDO::PARAM_STR);
      $query->execute();
    
      if ($query->rowCount() > 0) {

        $_SESSION['email_error'] = '<p class="error email">The user with this e-mail address exists in our database! Please set another e-mail address.</p>';
        $isEverythingGood = false;

      }

    }

  }

  if (empty($_POST['password'])) {

    $_SESSION['password_error'] = '<p class="error password">Password cannot be empty!</p>';
    $isEverythingGood = false;

  } else if (empty($_POST['repeat_password'])) {

    $_SESSION['repeat_password_error'] = '<p class="error repeat_password">Repeat password cannot be empty!</p>';
    $isEverythingGood = false;

  } else {

    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    if ($password != $repeat_password) {

      $_SESSION['password_error'] = '<p class="error password">Password and repeat password must be the same!</p>';
      $_SESSION['repeat_password_error'] = '<p class="error repeat_password">Password and repeat password must be the same!</p>';
      $isEverythingGood = false;

    } else if ((strlen($password) < 5) || (strlen($password) > 50)){

      $_SESSION['password_error'] = '<p class="error password">Password must have between 5 and 50 characters!</p>';
      $isEverythingGood = false;

    } 

  }

  if (!isset($_POST['agreement'])) {

    $_SESSION['agreement_error'] = '<p class="error agreement">You must check this field to continue!</p>';
    $isEverythingGood = false;

  } 
  
  if ($isEverythingGood) {

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
   
    $query = $db->prepare($sql);
    $query->bindValue(':username', $username, PDO::PARAM_STR);
    $query->bindValue(':password', $password_hashed, PDO::PARAM_STR);
    $query->bindValue(':email', $email, PDO::PARAM_STR);
    $query->execute();

  } else {

    header('location: registration.php');
    exit();

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
  <link rel="stylesheet" href="styles.css"> 
  
</head>
<body>

    <div class="container external-box p-4">

      <div class="row">

        <main class="col-lg-6">

          <img src="assets/images/logo.png" class="logo" alt="">

          <p class="ms-5 fs-2 fw-bold">Registration completed successfully! You can now log in!<p>

          <a href="index.php" class="fs-4">⤆ Return  for signing in</a>  

        </main>

      </div>
    
  </div>

  <footer>
      <small>® Personal Budget 2026.</small>
  </footer>

</body>
</html>


