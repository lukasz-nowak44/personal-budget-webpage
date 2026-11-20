<?php

  session_start();

  require_once 'helpers.php';

  if (!(isset($_SESSION['logged_user_id']))) {
    header('location: index.php');
    exit();
  }

  $user_id = $_SESSION['logged_user_id'];
  $summaries = ['income' => [], 'expense' => []];
  $details   = ['income' => [], 'expense' => []];
  $sum_of_incomes = 0;
  $sum_of_expenses = 0;

  if (isset($_SESSION['start_date'])) {
    $start_date = $_SESSION['start_date'];
    unset($_SESSION['start_date']);
  } else {
    $start_date = date('Y-m-01');
  }

  if (isset($_SESSION['end_date'])) {
    $end_date = $_SESSION['end_date'];
    unset($_SESSION['end_date']);
  } else {
    $end_date = date('Y-m-d');
  }

  require_once 'database.php';

  foreach (array_keys($summaries) as $transaction) {

    $sql = "SELECT ".$transaction."s_category_assigned_to_users.name AS category_name, ".$transaction."s_category_assigned_to_users.id AS category_id,"." SUM(".$transaction."s.amount) AS total_amount FROM ".$transaction."s LEFT JOIN ".$transaction."s_category_assigned_to_users ON ".$transaction."s.".$transaction."_category_assigned_to_user_id=".$transaction."s_category_assigned_to_users.id WHERE ".$transaction."s.date_of_".$transaction." BETWEEN :start_date AND :end_date AND ".$transaction."s.user_id=:user_id
    GROUP BY category_name
    ORDER BY total_amount DESC";

    $load_category_summaries = $db->prepare($sql);
    $load_category_summaries->bindValue(":start_date", $start_date, PDO::PARAM_STR);
    $load_category_summaries->bindValue(":end_date", $end_date, PDO::PARAM_STR);
    $load_category_summaries->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $load_category_summaries->execute();

    $summaries[$transaction] = $load_category_summaries->fetchAll(PDO::FETCH_ASSOC);
  }

  $sum_of_incomes = array_sum(array_column($summaries['income'], 'total_amount'));
  $sum_of_expenses = array_sum(array_column($summaries['expense'], 'total_amount'));

?>
<!DOCTYPE html>
<html lang="en">

<?php

  require_once 'head.php';

?>

<body>

  <header>

<?php

  $_SESSION['balance_active'] = true;

  require_once 'navbar.php';

?>

  </header>

  <main>

    <div class="container">

      <div class="row">

        <header class="py-4">

          <h1>Check balance</h1>

          <form id="balance" action="display-balance.php" method="post" novalidate>

            <div class="field-balance">
              <label for="periods">For which period would you like to see the balance:</label>
              <select id="periods" name="period" onchange="this.form.submit()">

                <option class="current-month" value="current month" <?= isset($_SESSION['current_month_selected']) ? 'selected' : '' ?>>Current month</option>
                <option class="previous-month" value="previous month" <?= isset($_SESSION['previous_month_selected']) ? 'selected' : '' ?>>Previous month</option>
                <option class="current-year" value="current year" <?= isset($_SESSION['current_year_selected']) ? 'selected' : '' ?>>Current year</option>
                <option class="non-standard" value="non standard" <?= isset($_SESSION['non_standard_selected']) ? 'selected' : '' ?>>non-standard</option>
<?php

              if(isset($_SESSION['current_month_selected'])) {
                unset($_SESSION['current_month_selected']);
              }
              if(isset($_SESSION['previous_month_selected'])) {
                unset($_SESSION['previous_month_selected']);
              }
              if(isset($_SESSION['current_year_selected'])) {
                unset($_SESSION['current_year_selected']);
              }            

?>

            </select>

              <div class="modal" id="modalWithDates" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h2 class="modal-title">Select Period:</h2>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                  <div class="modal-body">
                      <div class="date d-flex flex-column">
                        <label for="start-date">Start date:</label>
                          <input class="mb-3" id="start-date" type="date" name="start_date">
                        <label for="end-date">End date:</label>
                          <input id="end-date" type="date" name="end_date">
                      </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save">Save changes</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          </form>

        </header>

        <article class="summary" >

          <div class="container p-0">

            <div class="row">

              <h2 class="pb-4">It's summary for <strong class="selected-period"></strong><?= isset($_SESSION['non_standard_selected']) ? ' (from '.$start_date.' to '.$end_date.' <button type="button" class="btn btn-primary close change-range">change</button>)' : '' ?>:</h2>
<?php

             if(isset($_SESSION['non_standard_selected'])) {
                unset($_SESSION['non_standard_selected']);
              }

?>             
              <section class="col-lg-6 d-flex flex-column tables">

<?php

    $target_id = 0;  
    $modal_id = 0;  

    foreach (array_keys($summaries) as $transaction) {

      echo '<h3 class="table-title">The summary of your '.$transaction.'s:</h3>';
      echo<<<END
      <table class="mb-4">
        <thead>
          <tr>
            <th>nr</th>
            <th>category</th>
            <th>amount [PLN]</th>
            <th>percent [%]</th>
            <th>details</th>
          </tr>
        </thead>
        <tbody>
      END;
      
      $counter = 0;
      $percent = 0;
      $payment_method_name = '';
      $payment_methods_joined = '';

      if ($transaction === 'expense') {
        $payment_method_name = 'payment_methods_assigned_to_users.name AS payment,';
        $payment_methods_joined = 'INNER JOIN payment_methods_assigned_to_users ON expenses.payment_method_assigned_to_user_id=payment_methods_assigned_to_users.id';
      }

      $stmt = $db->prepare("SELECT {$transaction}_category_assigned_to_user_id AS category_id,
      date_of_{$transaction} AS date, amount, {$payment_method_name} {$transaction}_comment AS comment FROM {$transaction}s {$payment_methods_joined}
      WHERE {$transaction}s.user_id = :user_id AND date_of_{$transaction} BETWEEN :start AND :end
      ORDER BY date_of_{$transaction}");
      $stmt->execute([':user_id' => $user_id, ':start' => $start_date, ':end' => $end_date]);

      foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $details[$transaction][$row['category_id']][] = $row;
      }    

      foreach($summaries[$transaction] as $category){

        $counter += 1;
        $class_percent = '';

        if ($transaction === 'income') {
          $percent = ($category['total_amount']*100)/$sum_of_incomes;
        } else {
          $percent = ($category['total_amount']*100)/$sum_of_expenses;
        }

        $percent = round($percent, 1);

        if ($transaction === 'expense') {
          $class_percent = ' class="percent"';
        }    

        $target_id++;
      
        echo '<tr>';
        echo '<td>'.$counter.'</td>';
        echo '<td class="category-'.$transaction.'">'.htmlspecialchars($category['category_name']).'</td>';
        echo '<td>'.$category['total_amount'].'</td>';
        echo '<td'.$class_percent.'>'.$percent.'</td>';
        echo '<td><button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#details-'.$target_id.'"><img src="./assets/icons/info-square.svg" alt="check details"></button></td>';
        echo '</tr>';   

      }

      echo '</tbody>';
      echo '</table>';
      
      foreach($summaries[$transaction] as $category) {

        $modal_id++;

        echo '<div class="modal fade" id="details-'.$modal_id.'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">'.htmlspecialchars($category['category_name']).'</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><table class="details"><thead><tr>';

        $payment_method_label = '';

        if ($transaction === "expense") {
          $payment_method_label = '<th>payment method</th>';
        }

        echo '<th>date</th><th>amount [PLN]</th>'.$payment_method_label.'<th>comment</th></tr>';
        echo '</thead><tbody>';
    
        foreach ($details[$transaction][$category['category_id']] ?? [] as $detail) {
          $payment_method_field = '';

          if ($transaction === "expense") {
            $payment_method_field = '<td>'.$detail['payment'].'</td>';
          }

          echo '<tr><td>' . e($detail['date']) . '</td><td class="amount">' . e($detail['amount']) . '</td>'
          . $payment_method_field . '<td>' . e($detail['comment']) . '</td></tr>';

        }

        echo '</tbody></table></div>';
        echo '<div class="modal-footer"><a class="btn btn-primary close" href="settings.php">Edit</a><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div></div></div></div>';

      }
  
    
    } 
?>

              </section>

              <div class="col-lg-6 mb-2">

                <section class="verdict">

                  <h3>Your balance is equal: <strong class="result"><?php echo ($sum_of_incomes-$sum_of_expenses) ?></strong> PLN.</h3>
                  <p class="surplus">Congratulations. You manage your finances brilliantly!</p>
                  <p class="deficit">Watch out—you’re falling into debt!</p>

                </section>

                <h3 class="chart-header">Check what you spend the most on:</h3>

                <p class="no-data">You have not registered any expenses... 
                  <br>Add something: 
                  <a href="./expense.php" class="btn btn-primary close mt-2 fw-bolder" alt="">ADD EXPENSE</a>
                </p>

                <canvas id="expensesChart"></canvas>

              </div>

            </div>

          </div>

        </article>

      </div>

    </div>

  </main>

<?php

  require_once 'footer.php';

?>
 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script src="balance.js" defer></script>

  <script src="navbar.js"></script>

</body>
</html>