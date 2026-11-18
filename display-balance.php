<?php

  session_start();

  if (isset($_POST['period'])){
    $period = $_POST['period'];
  } else if (isset($_GET['period'])){
    $period = $_GET['period'];
    $period = str_replace('-', ' ', $period);
  }

  switch ($period) {
    case 'current month':
      $_SESSION['start_date'] = date('Y-m-01');
      $_SESSION['current_month_selected'] = true;
      break;

    case 'previous month':
      $previous_month = date('m')-1;
      $year_of_previous_month = date('Y');
      if ($previous_month < 1) {
        $previous_month === 12;
        $year_of_previous_month -= 1;
      }

      $start_date = $year_of_previous_month.'-'.$previous_month.'-01';

      $_SESSION['start_date'] = date($start_date);
      $_SESSION['end_date'] = date('Y-m-d',strtotime('last day of this month', mktime(0, 0, 0, $previous_month, 1, $year_of_previous_month))); 
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