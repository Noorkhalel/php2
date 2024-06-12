<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conn.php";
require_once "validate.php";

// Check if all required fields are set
if (
    isset($_POST['Customer_id'], $_POST['Vehicle_id'], $_POST['Start_date'], $_POST['Return_date'], 
          $_POST['Noofdays'], $_POST['Noofweeks'], $_POST['Amount_Due'], $_POST['Active'], 
          $_POST['Scheduled'], $_POST['email'], $_POST['phone'], $_POST['Name'])
) {
    $Customer_id = validate($_POST['Customer_id']);
    $Vehicle_id = validate($_POST['Vehicle_id']);
    $Start_date = validate($_POST['Start_date']);
    $Return_date = validate($_POST['Return_date']);
    $Noofdays = validate($_POST['Noofdays']);
    $Noofweeks = validate($_POST['Noofweeks']);
    $Amount_Due = validate($_POST['Amount_Due']);
    $Active = validate($_POST['Active']);
    $Scheduled = validate($_POST['Scheduled']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $Name = validate($_POST['Name']);

    // Prepare an SQL statement for execution
    $stmt = $conn->prepare("INSERT INTO rents (Customer_id, Vehicle_id, Start_date, Return_date, Noofdays, Noofweeks, Amount_Due, Active, Scheduled, email, phone, Name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissiiisssss", $Customer_id, $Vehicle_id, $Start_date, $Return_date, $Noofdays, $Noofweeks, $Amount_Due, $Active, $Scheduled, $email, $phone, $Name);

    // Execute the statement
    if ($stmt->execute()) {
        // Update the car table to set availability to 2
        $update_stmt = $conn->prepare("UPDATE car SET availability = 2 WHERE Vehicle_id = ?");
        $update_stmt->bind_param("i", $Vehicle_id);

        if ($update_stmt->execute()) {
            echo "success";
        } else {
            echo "failure: " . $update_stmt->error;
        }

        // Close the update statement
        $update_stmt->close();
    } else {
        echo "failure: " . $stmt->error;
    }

    // Close the insert statement
    $stmt->close();
} else {
    echo "failure: Missing required POST parameters";
}

// Close the connection
$conn->close();
?>
