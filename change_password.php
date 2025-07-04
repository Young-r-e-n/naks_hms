<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['user']['role'];
$username = $_SESSION['user']['username'];

require "php/db_connection.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        $stmt = $con->prepare("SELECT PASSWORD FROM users WHERE USERNAME = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($old_password, $hashed_password)) {
            $error = "Old password is incorrect.";
        } else {
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE users SET PASSWORD = ? WHERE USERNAME = ?");
            $update->bind_param("ss", $new_hashed, $username);
            if ($update->execute()) {
                $success = "Password successfully changed.";
            } else {
                $error = "Failed to change password.";
            }
            $update->close();
        }
    }
}
?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Add New Patient</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<script src="bootstrap/js/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/sidenav.css">
    <link rel="stylesheet" href="css/home.css">
    <script src="js/validateForm.js"></script>
    <script src="js/my_profile.js"></script>
    <script src="js/restrict.js"></script>
  </head>
  <body>
    <!-- including side navigations -->
    <?php include("sections/sidenav.html"); ?>
    <div class="container-fluid">
      <div class="container">
        <!-- header section -->
        <?php
          require "php/header.php";
          createHeader('key', 'Change Passowrd', 'Set New Password');
          // header section end
        ?>
        <?php if (!empty($success)) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
<?php if (!empty($error)) { ?>
  <div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

        <div class="row">
          <div class="row col col-md-6">

<form method="POST" action="">
  <div class="row col col-md-12 form-group">
    <label class="font-weight-bold" for="old_password">Old Password :</label>
    <input id="old_password" name="old_password" type="password" class="form-control" required>
  </div>

  <div class="row col col-md-12 form-group">
    <label class="font-weight-bold" for="password">New Password :</label>
    <input id="password" name="new_password" type="password" class="form-control" required>
  </div>

  <div class="row col col-md-12 form-group">
    <label class="font-weight-bold" for="confirm_password">Confirm New Password :</label>
    <input id="confirm_password" name="confirm_password" type="password" class="form-control" required>
  </div>

  <div class="row col col-md-12 m-auto" id="change">
    <div class="col col-md-4 form-group float-right"></div>
    <div id="change_button" class="col col-md-4 form-group float-right">
      <button type="submit" name="change_password" class="btn btn-warning form-control font-weight-bold">Reset Password</button>
    </div>
    <div id="password_button" class="col col-md-4 form-group float-right">
      <a href="my_profile.php" class="btn btn-primary form-control font-weight-bold">Profile</a>
    </div>
  </div>
</form>


          </div>
        </div>
        <hr style="border-top: 2px solid #ff5252;">
      </div>
    </div>
  </body>
</html>
