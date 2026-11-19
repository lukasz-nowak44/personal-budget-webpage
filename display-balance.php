<?php

  session_start();

  if (!isset($_SESSION['logged_user_id'])) { 
    header('Location: index.php'); 
    exit(); 
  }

  $period = $_POST['period'] ?? str_replace('-', ' ', $_GET['period'] ?? 'current month');

  switch ($period) {
    case 'current month':
      $_SESSION['start_date'] = date('Y-m-01');
      $_SESSION['current_month_selected'] = true;
      break;

    case 'previous month':
      $_SESSION['start_date'] = (new DateTime('first day of last month'))->format('Y-m-d');
      $_SESSION['end_date']   = (new DateTime('last day of last month'))->format('Y-m-d');
      $_SESSION['previous_month_selected'] = true;
    break;

    case 'current year':
      $_SESSION['start_date'] = date('Y-01-01'); 
      $_SESSION['current_year_selected'] = true;
      break;

    case 'non standard':
      $_SESSION['start_date'] = $_POST['start_date'] ?? null; 
      $_SESSION['end_date'] = $_POST['end_date'] ?? null;
      $_SESSION['non_standard_selected'] = true;
      break;
  }

  header('location: balance.php');