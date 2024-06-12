<?php
require_once "conn.php"; // Include your database connection script

// Check if customer_id parameter is set
if (isset($_POST['customer_id'])) {
    $customerId = intval($_POST['customer_id']); // Cast to integer to prevent SQL injection

    // Prepare and execute SQL query to fetch booking details
    $stmt = $conn->prepare("SELECT Customer_id, Vehicle_id, Start_date, Return_date, Noofdays, Noofweeks, Amount_Due, Active, Scheduled FROM rents WHERE Customer_id = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $stmt->bind_result($customerId, $vehicleId, $startDate, $returnDate, $noOfDays, $noOfWeeks, $amountDue, $active, $scheduled);

    // Fetch booking details
    if ($stmt->fetch()) {
        // Return booking details as JSON
        echo json_encode(array(
            "success" => true,
            "customer_id" => $customerId,
            "vehicle_id" => $vehicleId,
            "start_date" => $startDate,
            "return_date" => $returnDate,
            "no_of_days" => $noOfDays,
            "no_of_weeks" => $noOfWeeks,
            "amount_due" => $amountDue,
            "active" => $active,
            "scheduled" => $scheduled
        ));
    } else {
        // No record found for the given customer ID
        echo json_encode(array("success" => false, "message" => "Booking details not found"));
    }
    
    $stmt->close(); // Close statement
} else {
    // customer_id parameter is missing
    echo json_encode(array("success" => false, "message" => "Customer ID parameter missing"));
}
?>
