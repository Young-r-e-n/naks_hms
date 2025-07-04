<?php

  if(isset($_GET["action"]) && $_GET["action"] == "delete") {
    require "db_connection.php";
    $invoice_number = $_GET["invoice_number"];
    $query = "DELETE FROM invoices WHERE INVOICE_ID = $invoice_number";
    $result = mysqli_query($con, $query);
    if(!empty($result))
  		showInvoices();
  }

  if(isset($_GET["action"]) && $_GET["action"] == "refresh")
    showInvoices();

  if(isset($_GET["action"]) && $_GET["action"] == "search")
    searchInvoice(strtoupper($_GET["text"]), $_GET["tag"]);

  if(isset($_GET["action"]) && $_GET["action"] == "print_invoice")
    printInvoice($_GET["invoice_number"]);

  function showInvoices() {
    require "db_connection.php";
    if($con) {
      $seq_no = 0;
      $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID";
      $result = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($result)) {
        $seq_no++;
        showInvoiceRow($seq_no, $row);
      }
    }
  }

  function showInvoiceRow($seq_no, $row) {
    ?>
    <tr>
      <td><?php echo $seq_no; ?></td>
      <td><?php echo $row['INVOICE_ID']; ?></td>
      <td><?php echo $row['NAME']; ?></td>
      <td><?php echo $row['INVOICE_DATE']; ?></td>
      <td><?php echo $row['TOTAL_AMOUNT']; ?></td>
      <td><?php echo $row['TOTAL_DISCOUNT']; ?></td>
      <td><?php echo $row['NET_TOTAL']; ?></td>
      <td>
        <button class="btn btn-warning btn-sm" onclick="printInvoice(<?php echo $row['INVOICE_NUMBER']; ?>);">          <i class="fa fa-fax"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="deleteInvoice(<?php echo $row['INVOICE_NUMBER']; ?>);">
          <i class="fa fa-trash"></i>
        </button>
      </td>
    </tr>
    <?php
  }

  function searchInvoice($text, $column) {
    require "db_connection.php";
    if($con) {
      $seq_no = 0;
      if($column == 'INVOICE_ID')
        $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID WHERE CAST(invoices.$column AS VARCHAR(9)) LIKE '%$text%'";
      else if($column == "INVOICE_DATE")
        $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID WHERE invoices.$column = '$text'";
      else
        $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID WHERE UPPER(customers.$column) LIKE '%$text%'";

      $result = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($result)) {
        $seq_no++;
        showInvoiceRow($seq_no, $row);
      }
    }
  }

 function printInvoice($invoice_number) {
    require "db_connection.php";

    if (!$con) {
        echo "<div class='alert alert-danger'>Database connection failed.</div>";
        return;
    }

    // Sanitize input
    $invoice_id = intval($invoice_number);

    // Step 1: Fetch invoice + customer data using INVOICE_ID
    $query = "
        SELECT 
            i.INVOICE_NUMBER,
            i.INVOICE_DATE,
            i.TOTAL_AMOUNT,
            i.TOTAL_DISCOUNT,
            i.NET_TOTAL,
            c.NAME AS customer_name,
            c.ADDRESS AS customer_address,
            c.CONTACT_NUMBER AS contact_number,
            c.DOCTOR_NAME AS doctor_name,
            c.DOCTOR_ADDRESS AS doctor_address
        FROM invoices i
        INNER JOIN customers c ON i.CUSTOMER_ID = c.ID
        WHERE i.INVOICE_NUMBER = '$invoice_id'
        LIMIT 1
    ";

    $result = mysqli_query($con, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<div class='alert alert-danger'>Invoice ID #$invoice_id not found.</div>";
        return;
    }

    $row = mysqli_fetch_assoc($result);
    $invoice_number   = $row['INVOICE_NUMBER'];
    $invoice_date     = $row['INVOICE_DATE'];
    $total_amount     = $row['TOTAL_AMOUNT'];
    $total_discount   = $row['TOTAL_DISCOUNT'];
    $net_total        = $row['NET_TOTAL'];
    $customer_name    = $row['customer_name'];
    $customer_address = $row['customer_address'];
    $contact_number   = $row['contact_number'];
    $doctor_name      = $row['doctor_name'];
    $doctor_address   = $row['doctor_address'];

    // Step 2: Fetch itemized sales using INVOICE_NUMBER
    $items_query = "
        SELECT MEDICINE_NAME, BATCH_ID, EXPIRY_DATE, QUANTITY, MRP, DISCOUNT, TOTAL
        FROM sales
        WHERE INVOICE_NUMBER = '$invoice_number'
    ";
    $items_result = mysqli_query($con, $items_query);

    // Step 3: Render invoice
    echo "
    <style>
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; font-family: Arial; }
        .invoice-box h2 { text-align: center; }
        .invoice-box table { width: 100%; border-collapse: collapse; }
        .invoice-box table, th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .invoice-box .totals td { font-weight: bold; }
        .print-btn { margin-top: 20px; text-align: center; }
    </style>

    <div class='invoice-box'>
        <h2>Invoice #$invoice_number</h2>
        <p><strong>Date:</strong> $invoice_date</p>
        <p><strong>Customer:</strong> $customer_name<br>
        <strong>Address:</strong> $customer_address<br>
        <strong>Contact:</strong> $contact_number</p>
        <p><strong>Doctor:</strong> $doctor_name<br>
        <strong>Doctor Address:</strong> $doctor_address</p>

        <table>
            <tr>
                <th>Medicine</th>
                <th>Batch</th>
                <th>Expiry</th>
                <th>Qty</th>
                <th>MRP</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
    ";

    if ($items_result && mysqli_num_rows($items_result) > 0) {
        while ($item = mysqli_fetch_assoc($items_result)) {
            echo "
            <tr>
                <td>{$item['MEDICINE_NAME']}</td>
                <td>{$item['BATCH_ID']}</td>
                <td>{$item['EXPIRY_DATE']}</td>
                <td>{$item['QUANTITY']}</td>
                <td>{$item['MRP']}</td>
                <td>{$item['DISCOUNT']}</td>
                <td>{$item['TOTAL']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No items found for this invoice.</td></tr>";
    }

    echo "
        </table>
        <br>
        <table class='totals'>
            <tr><td colspan='6'>Total Amount</td><td>$total_amount</td></tr>
            <tr><td colspan='6'>Discount</td><td>$total_discount</td></tr>
            <tr><td colspan='6'>Net Total</td><td>$net_total</td></tr>
        </table>

        <div class='print-btn'>
            <button onclick='window.print()'>Print Invoice</button>
        </div>
    </div>";
}
