<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conn.php";
require_once "validate.php";

if(isset($_POST['Phone']) && isset($_POST['Lname']) && isset($_POST['Fname']) && isset($_POST['email']) && isset($_POST['password'])){
    $phone = validate($_POST['Phone']);
    $lname = validate($_POST['Lname']);
    $fname = validate($_POST['Fname']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $checkEmailQuery = $conn->prepare("SELECT * FROM customer WHERE email = ?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $checkResult = $checkEmailQuery->get_result();

    if ($checkResult->num_rows > 0) {
        echo "failure"; // Email already exists
    } else {
        // Insert new customer into the database
        $insertCustomerQuery = $conn->prepare("INSERT INTO customer (Phone, Lname, Fname, email, password) VALUES (?, ?, ?, ?, ?)");
        $insertCustomerQuery->bind_param("sssss", $phone, $lname, $fname, $email, $passwordHash);

        if ($insertCustomerQuery->execute()) {
            echo "success";
        } else {
            echo "failure"; // Failed to insert customer
        }

        $insertCustomerQuery->close();
    }

    $checkEmailQuery->close();
}
?>
