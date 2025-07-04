<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Get role securely from session
$role = $_SESSION['user']['role'];
$username = $_SESSION['user']['username'];

require "php/db_connection.php";

$uid = $_GET['uid'] ?? '';
if (!$uid) die("Invalid request.");

// Fetch customer details
$customer = null;
$history_result = [];

if ($stmt = $con->prepare("SELECT * FROM customers WHERE customer_uid = ?")) {
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $customer = $stmt->get_result()->fetch_assoc();
}

// Handle new history entry submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["description"])) {
    $description = trim($_POST["description"]);
    $visit_date = $_POST["visit_date"];
    $consultation_charges = isset($_POST["consultation_charges"]) ? floatval($_POST["consultation_charges"]) : 0;
    $added_by = "Admin"; // Replace with actual user if session-based

    if ($stmt = $con->prepare("INSERT INTO customer_history (customer_uid, description, visit_date, added_by, consultation_charge) VALUES (?, ?, ?, ?, ?)")) {
        $stmt->bind_param("sssss", $uid, $description, $visit_date, $added_by, $consultation_charges);
        $stmt->execute();
    }
}


// Fetch history records
if ($stmt = $con->prepare("SELECT * FROM customer_history WHERE customer_uid = ? ORDER BY visit_date DESC")) {
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $history_result = $stmt->get_result();
}
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Patient History - <?php echo htmlspecialchars($customer['NAME']); ?></title> <title>Manage Customer</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<script src="bootstrap/js/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/sidenav.css">
    <link rel="stylesheet" href="css/home.css">
    <script src="js/manage_customer.js"></script>
    <script src="js/validateForm.js"></script>
    <script src="js/restrict.js"></script>
<style>
.container .card {
  background-color: #fff !important;
  color: #000 !important;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 15px;
  margin-bottom: 20px;
}
</style>



  </head>
  <body style="max-height: 100%;">
    <!-- including side navigations -->
    <?php include("sections/sidenav.html"); ?>

    <div class="container-fluid">
      <div class="container">

        <!-- header section -->
        <?php
          require "php/header.php";
          createHeader('handshake', 'Patient History', 'View Patient History');
        ?>
        <!-- header section end -->

        <!-- form content -->
 <div class="container mt-5">
    <h2 class="text-center mb-4">Patient History - <?php echo htmlspecialchars($customer['NAME']); ?></h2>

    <div class="card p-3 mb-4 custom-light">
      <h5>Customer Info</h5>
      <p><strong>Name:</strong> <?= htmlspecialchars($customer['NAME']) ?></p>
      <p><strong>Doctor:</strong> <?= htmlspecialchars($customer['DOCTOR_NAME']) ?></p>
    </div>

    <!-- Add History Form -->
    <div class="card p-3 mb-4 custom-light">
      <h5>Add New History</h5>
      <form method="POST">
        <div class="form-group">
          <label for="visit_date">Visit Date</label>
          <input type="date" name="visit_date" class="form-control" required>
        </div>
            <div class="form-group mt-2">
      <label for="consultation_charges">Consultation Charges</label>
      <input type="number" name="consultation_charges" class="form-control" placeholder="e.g., 500" min="0" step="0.01">
    </div>
        <div class="form-group mt-2">
          <label for="description">Description</label>
          <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-2">Add Entry</button>
      </form>
    </div>

    <!-- History Table -->
    <div class="card p-3">
      <h5>Visit History</h5>
      <table class="table table-bordered table-hover">
        <thead class="custom-light">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Description</th>
            <th>Added By</th>
             <th>Consultation Charges</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        foreach ($history_result as $row): ?>
          <tr>
            <td><?= $count++ ?></td>
            <td><?= $row['visit_date'] ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['added_by']) ?></td>
            <td><?= htmlspecialchars($row['consultation_charge']) ?></td>
            <td>
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
            </td>
          </tr>

          <!-- Edit Modal -->
          <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
              <form class="modal-content" method="POST" action="php/update_history.php">
                <div class="modal-header">
                  <h5 class="modal-title">Edit Entry</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="customer_uid" value="<?= $uid ?>">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="visit_date" class="form-control" value="<?= $row['visit_date'] ?>" required>
                  </div>
                  <div class="form-group mt-2">
                    <label>Description</label>
                    <textarea name="description" class="form-control" required><?= htmlspecialchars($row['description']) ?></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>

        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
        <!-- form content end -->
        <hr style="border-top: 2px solid #ff5252;">
      </div>
    </div>
  </body>
</html>
