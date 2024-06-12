<?php
require_once "conn.php"; // Include your database connection script

// Check if user_id parameter is set
if (isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']); // Cast to integer to prevent SQL injection

    // Prepare and execute SQL query to fetch user details
    $stmt = $conn->prepare("SELECT Idno, Phone, Lname, Fname, password, email FROM customer WHERE Idno = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($Idno, $Phone, $Lname, $Fname, $password, $email);

    // Fetch user details
    if ($stmt->fetch()) {
        // Return user details as JSON
        echo json_encode(array(
            "success" => true,
            "Idno" => $Idno,
            "Fname" => $Fname,
            "Lname" => $Lname,
            "email" => $email,
            "phone" => $Phone
        ));
    } else {
        // No record found for the given user ID
        echo json_encode(array("success" => false, "message" => "User not found"));
    }
    
    $stmt->close(); // Close statement
} else {
    // user_id parameter is missing
    echo json_encode(array("success" => false, "message" => "User ID parameter missing"));
}
?>
