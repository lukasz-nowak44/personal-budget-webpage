<?php

  session_start();

  if (isset($_SESSION['logged_user_id'])) {
    unset($_SESSION['logged_user_id']);
    unset($_SESSION['logged_user_username']);
    header('location: index.php');
    exit();
  }