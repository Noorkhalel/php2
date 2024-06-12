<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conn.php";
require_once "validate.php";

if (isset($_POST['emaila']) && isset($_POST['passworda'])) {
    $email = validate($_POST['emaila']);
    $password = validate($_POST['passworda']);
    
    // First check in the customer table
    $stmt = $conn->prepare("SELECT Idno, password FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            echo "customer:success:$id";
        } else {
            echo "failure";
        }
    } else {
        // If not found in customer table, check in owner table
        $stmt->close();
        $stmt = $conn->prepare("SELECT Owner_id, o_password FROM owner WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                echo "owner:success:$id";
            } else {
                echo "failure";
            }
        } else {
            echo "failure";
        }
    }
    
    $stmt->close();
} else {
    echo "missing_parameters";
}
?>
