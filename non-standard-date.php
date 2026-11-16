<?php

  session_start();

  $_SESSION['start_date'] = $_POST['start_date']; 
  $_SESSION['end_date'] = $_POST['end_date'];

   if (isset($_SESSION['end_date'])){
    echo $_SESSION['start_date'].' -> '.$_SESSION['end_date'].' jestem w display balance';
  }

  echo 'jestem w non standard';