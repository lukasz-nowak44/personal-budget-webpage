<?php

echo<<<END

<nav class="navbar navbar-expand-lg navbar-dark py-0">

  <a class="navbar-brand" href="home.php"><img src="./assets/images/logo-light.png" class="logo" alt=""></a>

  <button class="navbar-toggler me-3" type="button"        data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

    <span class="navbar-toggler-icon"></span>

  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">

  <ul class="navbar-nav mb-lg-0">
    <li class="nav-item">
END;
  
  echo '<a class="nav-link'.(isset($_SESSION['home_active']) ? ' active' : '').'" aria-current="page" href="home.php">Home</a>';

  echo '</li>';
  echo '<li class="nav-item">';
  echo '<a class="nav-link'.(isset($_SESSION['income_active']) ? ' active' : '').'" href="income.php">Add income</a>';
  echo '</li>'; 
  echo '<li class="nav-item">';
  echo '<a class="nav-link'.(isset($_SESSION['expense_active']) ? ' active' : '').'" href="expense.php">Add expense</a>';
  echo '</li>'; 
  echo '<li class="nav-item dropdown">';

  echo '<a class="nav-link'.(isset($_SESSION['balance_active']) ? ' active' : '').'" dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
  echo 'Display balance';
  echo '</a>';
  echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
  echo '<li><a class="dropdown-item" href="display-balance.php?period=current-month">Current month</a></li>';
  echo '<li><a class="dropdown-item" href="display-balance.php?period=previous-month">Previous month</a></li>';
  echo '<li><a class="dropdown-item" href="display-balance.php?period=current-year">Current year</a></li>';
  echo '<li><hr class="dropdown-divider"></li>';
  echo '<li><a class="dropdown-item" href="display-balance.php?period=non-standard">non-standard</a></li>';
  echo '</ul>';

  echo '</li>'; 
  echo '<li class="nav-item">';
  echo '<a class="nav-link'.(isset($_SESSION['settings_active']) ? ' active' : '').'" href="settings.php">Settings</a>';
  echo '</li>'; 

echo<<<END
    <li class="nav-item">
      <a class="nav-link" href="logout.php">Logout</a>
    </li>
    <li class="nav-item">
END;
    echo '<a class="nav-link disabled username-navbar" href="">Logged as '. htmlspecialchars($_SESSION['logged_user_username']).'</a>';
       
    echo '</li></ul></div></nav>';

if (isset($_SESSION['home_active'])) {
  unset($_SESSION['home_active']);
}
if (isset($_SESSION['income_active'])) {
  unset($_SESSION['income_active']);
}
if (isset($_SESSION['expense_active'])) {
  unset($_SESSION['expense_active']);
}
if (isset($_SESSION['balance_active'])) {
  unset($_SESSION['balance_active']);
}
if (isset($_SESSION['settings_active'])) {
  unset($_SESSION['settings_active']);
}
      

      

    

