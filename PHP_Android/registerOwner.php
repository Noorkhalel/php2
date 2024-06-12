<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conn.php";
require_once "validate.php";

if(isset($_POST['Owner_type']) && isset($_POST['Cname']) && isset($_POST['email']) && isset($_POST['Fname']) && isset($_POST['Lname']) && isset($_POST['password'])){
    $ownerType = validate($_POST['Owner_type']);
    $cname = validate($_POST['Cname']);
    $email = validate($_POST['email']);
    $fname = validate($_POST['Fname']);
    $lname = validate($_POST['Lname']);
    $password = validate($_POST['password']);
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $checkEmailQuery = $conn->prepare("SELECT * FROM Owner WHERE email = ?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $checkResult = $checkEmailQuery->get_result();

    if ($checkResult->num_rows > 0) {
        echo "failure"; // Email already exists
    } else {
        // Insert new owner into the database
        $insertOwnerQuery = $conn->prepare("INSERT INTO Owner (Owner_type, Cname, Fname, Lname, o_password, email) VALUES (?, ?, ?, ?, ?, ?)");
        $insertOwnerQuery->bind_param("ssssss", $ownerType, $cname, $fname, $lname, $passwordHash, $email);

        if ($insertOwnerQuery->execute()) {
            echo "success";
        } else {
            echo "failure"; // Failed to insert owner
        }

        $insertOwnerQuery->close();
    }

    $checkEmailQuery->close();
}
?>
