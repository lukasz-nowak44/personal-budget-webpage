<?php

  session_start();

  if (isset($_POST['period'])){
    $period = $_POST['period'];
  } else if (isset($_GET['period'])){
    $period = $_GET['period'];
  }

  switch ($period) {
    case 'current month':
    case 'current-month':
      $_SESSION['start_date'] = date('Y-m-01');
      $_SESSION['current_month_selected'] = true;
      break;

    case 'previous month':
    case 'previous-month':
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
    case 'current-year':
      $_SESSION['start_date'] = date('Y-01-01'); 
      $_SESSION['current_year_selected'] = true;
      break;

    case 'non standard':
    case 'non-standard':
      $_SESSION['start_date'] = $_POST['start_date']; 
      $_SESSION['end_date'] = $_POST['end_date'];
      $_SESSION['non_standard_selected'] = true;
      break;
  }

  header('location: balance.php');

  if (isset($_SESSION['end_date'])){
    echo $_SESSION['start_date'].' -> '.$_SESSION['end_date'].' jestem w display balance';
  }

  echo 'jestem w display balance';