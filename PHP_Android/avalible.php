<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conn.php"; 
require_once "validate.php"; 
if (isset($_POST['vehicle_id'])) {
    $vehicle_id = validateInput($_POST['vehicle_id']);
    
    $query = "SELECT * FROM availability WHERE Vehicle_id = ?";
    
    $stmt = mysqli_prepare($connection, $query);
    
    mysqli_stmt_bind_param($stmt, "i", $vehicle_id);
    
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $response['available'] = true;
    } else {
        $response['available'] = false;
    }
    
    echo json_encode($response);
    
    mysqli_stmt_close($stmt);
} else {
    $response['error'] = "Invalid input parameter";
    echo json_encode($response);
}

// Close the database connection
mysqli_close($connection);
?>
