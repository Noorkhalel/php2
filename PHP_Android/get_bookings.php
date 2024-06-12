<?php
require_once "conn.php";
require_once "validate.php";

header('Content-Type: application/json');

if(isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];

    // Escape user input to prevent SQL injection
    $user_id = $conn->real_escape_string($user_id);

    $sql = "SELECT * FROM rents WHERE Customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $booking = array(
                "customerId" => isset($row["Customer_id"]) ? $row["Customer_id"] : null,
                "vehicleId" => isset($row["Vehicle_id"]) ? $row["Vehicle_id"] : null,
                "startDate" => isset($row["Start_date"]) ? $row["Start_date"] : null,
                "returnDate" => isset($row["Return_date"]) ? $row["Return_date"] : null,
                "numOfDays" => isset($row["Noofdays"]) ? $row["Noofdays"] : null,
                "amountDue" => isset($row["Amount_Due"]) ? $row["Amount_Due"] : null,
                "email" => isset($row["email"]) ? $row["email"] : null,
                "phone" => isset($row["phone"]) ? $row["phone"] : null,
                "name" => isset($row["Name"]) ? $row["Name"] : null
            );
            array_push($bookings, $booking);
        }
    }

    $stmt->close();
    $conn->close();

    echo json_encode($bookings);

} else {
    echo json_encode(array("error" => "User ID not provided"));
}
?>
