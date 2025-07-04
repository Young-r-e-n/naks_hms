<?php
require "db_connection.php";

if ($con) {
    $name = ucwords($_GET["name"]);
    $contact_number = $_GET["contact_number"];
    $address = ucwords($_GET["address"]);
    $doctor_name = ucwords($_GET["doctor_name"]);
    $doctor_address = ucwords($_GET["doctor_address"]);

    // New fields for history
    $visit_date = $_GET["visit_date"];
    $history_description = ucwords($_GET["history_description"]);
    $added_by = $_GET["added_by"];
    $consultation_charges = isset($_GET["consultation_charges"]) ? floatval($_GET["consultation_charges"]) : 0;


    // Check if customer exists
    $query = "SELECT * FROM customers WHERE CONTACT_NUMBER = '$contact_number'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);

    if ($row) {
        echo "Customer " . $row['NAME'] . " with contact number $contact_number already exists!";
    } else {
        // Generate unique customer_uid
        $date_prefix = date("Ymd"); // e.g. 20240618
        $random_suffix = mt_rand(1000, 9999); // 4-digit random number
        $customer_uid = "CUS" . $date_prefix . $random_suffix;

        // Insert new customer
        $query = "INSERT INTO customers (customer_uid, NAME, CONTACT_NUMBER, ADDRESS, DOCTOR_NAME, DOCTOR_ADDRESS) 
                  VALUES('$customer_uid', '$name', '$contact_number', '$address', '$doctor_name', '$doctor_address')";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Get the newly inserted customer ID
            $customer_id = mysqli_insert_id($con);

            // Insert into customer_history
            $history_query = "INSERT INTO customer_history (customer_uid, visit_date, description, added_by,consultation_charge)
                              VALUES ('$customer_uid', '$visit_date', '$history_description', '$added_by','$consultation_charges')";
            $history_result = mysqli_query($con, $history_query);

            if ($history_result) {
                echo "$name added with UID $customer_uid and medical history.";
            } else {
                echo "$name added (UID: $customer_uid), but failed to add history!";
            }
        } else {
            echo "Failed to add $name!";
        }
    }
}
?>
