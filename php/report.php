<?php

if(isset($_GET['action']) && $_GET['action'] == "purchase")
  showPurchases($_GET['start_date'], $_GET['end_date']);

if(isset($_GET['action']) && $_GET['action'] == "sales")
  showSales($_GET['start_date'], $_GET['end_date']);

function showPurchases($start_date, $end_date) {
  ?>
  <thead>
    <tr>
      <th>SL</th>
      <th>Purchase Date</th>
      <th>Voucher Number</th>
      <th>Invoice No</th>
      <th>Supplier Name</th>
      <th>Total Amount</th>
    </tr>
  </thead>
  <tbody>
  <?php
  require "db_connection.php";
  if($con) {
    $seq_no = 0;
    $total = 0;
    if($start_date == "" || $end_date == "")
      $query = "SELECT * FROM purchases";
    else
      $query = "SELECT * FROM purchases WHERE PURCHASE_DATE BETWEEN '$start_date' AND '$end_date'";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($result)) {
      $seq_no++;
      showPurchaseRow($seq_no, $row);
      $total = $total + $row['TOTAL_AMOUNT'];
    }
    ?>
    </tbody>
    <tfoot class="font-weight-bold">
      <tr style="text-align: right; font-size: 24px;">
        <td colspan="5" style="color: green;">&nbsp;Total Purchases =</td>
        <td style="color: red;"><?php echo $total; ?></td>
      </tr>
    </tfoot>
    <?php
  }
}

function showPurchaseRow($seq_no, $row) {
  ?>
  <tr>
    <td><?php echo $seq_no; ?></td>
    <td><?php echo $row['PURCHASE_DATE']; ?></td>
    <td><?php echo $row['VOUCHER_NUMBER']; ?></td>
    <td><?php echo $row['INVOICE_NUMBER']; ?></td>
    <td><?php echo $row['SUPPLIER_NAME'] ?></td>
    <td><?php echo $row['TOTAL_AMOUNT']; ?></td>
  </tr>
  <?php
}

function showSales($start_date, $end_date) {
  ?>
  <thead>
    <tr>
      <th>SL</th>
      <th>Sales Date</th>
      <th>Invoice Number</th>
      <th>Customer Name</th>
      <th>Profits</th>
      <th>Total Amount</th>
    </tr>
  </thead>
  <tbody>
  <?php
  require "db_connection.php";
  if($con) {
    $seq_no = 0;
    $total = 0;
    $total_profit = 0;
    if($start_date == "" || $end_date == "")
      $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID";
    else
      $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID WHERE INVOICE_DATE BETWEEN '$start_date' AND '$end_date'";
    $result = mysqli_query($con, $query);
while($row = mysqli_fetch_array($result)) {
  $seq_no++;
  $total += $row['NET_TOTAL'];
  $invoice_id = $row['INVOICE_NUMBER'];

  // Get profit for this invoice
  $profit_query = "SELECT SUM(PROFIT) AS total_profit FROM sales WHERE INVOICE_NUMBER = '$invoice_id'";
  $profit_result = mysqli_query($con, $profit_query);
  $invoice_profit = 0;
  if ($profit_row = mysqli_fetch_assoc($profit_result)) {
    $invoice_profit = $profit_row['total_profit'];
    $total_profit += $invoice_profit;
  }

  // Pass profit to row
  showSalesRow($seq_no, $row, $invoice_profit);
}

    ?>
    </tbody>
<tfoot class="font-weight-bold">
  <tr style="text-align: right; font-size: 24px;">
    <td colspan="4" style="color: green;">&nbsp;Total Sales =</td>
    <td class="text-primary"><?php echo $total; ?></td>
  </tr>
  <tr style="text-align: right; font-size: 24px;">
    <td colspan="4" style="color: green;">&nbsp;Total Profit =</td>
    <td class="text-success"><?php echo $total_profit; ?></td>
  </tr>
</tfoot>

    <?php
  }
}

function showSalesRow($seq_no, $row, $profit) {
  ?>
  <tr>
    <td><?php echo $seq_no; ?></td>
    <td><?php echo $row['INVOICE_DATE']; ?></td>
    <td><?php echo $row['INVOICE_ID']; ?></td>
    <td><?php echo $row['NAME']; ?></td>
    <td><?php echo number_format($profit, 2); ?></td>
    <td><?php echo number_format($row['NET_TOTAL'], 2); ?></td>
  </tr>
  <?php
}

function showConsultationCharges($start_date, $end_date) {
  ?>
  <thead>
    <tr>
      <th>SL</th>
      <th>Date</th>
      <th>Customer Name</th>
      <th>Description</th>
      <th>Added By</th>
      <th>Consultation Charge</th>
    </tr>
  </thead>
  <tbody>
  <?php
  require "db_connection.php";
  if ($con) {
    $seq_no = 0;
    $total_consultation = 0;

    // Join customer_history with customers to get customer NAME
    if ($start_date == "" || $end_date == "") {
      $query = "SELECT ch.*, c.NAME 
                FROM customer_history ch 
                LEFT JOIN customers c ON ch.customer_uid = c.customer_uid";
    } else {
      $query = "SELECT ch.*, c.NAME 
                FROM customer_history ch 
                LEFT JOIN customers c ON ch.customer_uid = c.customer_uid 
                WHERE ch.visit_date BETWEEN '$start_date' AND '$end_date'";
    }

    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_array($result)) {
      $seq_no++;
      $charge = isset($row['consultation_charge']) ? floatval($row['consultation_charge']) : 0;
      $total_consultation += $charge;
      ?>
      <tr>
        <td><?php echo $seq_no; ?></td>
        <td><?php echo $row['visit_date']; ?></td>
        <td><?php echo $row['NAME'] ?? 'Unknown'; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['added_by']; ?></td>
        <td><?php echo number_format($charge, 2); ?></td>
      </tr>
      <?php
    }
  }
  ?>
  </tbody>
  <tfoot class="font-weight-bold">
    <tr style="text-align: right; font-size: 24px;">
      <td colspan="5" style="color: green;">&nbsp;Total Consultation Charges =</td>
      <td class="text-info"><?php echo number_format($total_consultation, 2); ?></td>
    </tr>
  </tfoot>
  <?php
}

?>
