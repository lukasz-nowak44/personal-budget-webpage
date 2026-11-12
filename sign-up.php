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

        <main class="col-md-6">

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


