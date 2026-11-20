<?php

  session_start();

  if (!(isset($_SESSION['logged_user_id']))) {
    header('location: index.php');
    exit();
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

  $_SESSION['settings_active'] = true;

  require_once 'navbar.php';

?>
    
  </header>

<?php

  if (isset($_SESSION['categories_error'])) {

    echo '<p class="transaction-error m-3">'.$_SESSION['categories_error'].'</p>';
    unset ($_SESSION['categories_error']);

  }

?>

  <h1 class="ms-5">This page is under construction... </h1>

  <img src="./assets/icons/backhoe.svg" class="img-fluid construction" alt="">

<?php

  require_once 'footer.php';

?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script src="navbar.js"></script>

</body>
</html>