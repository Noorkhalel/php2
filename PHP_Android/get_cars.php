<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conn.php";
require_once "validate.php";

// Ensure no extraneous output
header('Content-Type: application/json');

$sql = "SELECT * FROM car";
$result = $conn->query($sql);

$cars = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $car = array(
            "vehicleId" => $row["Vehicle_id"],
            "model" => $row["Model"],
            "year" => $row["Year"],
            "availableStart" => $row["Available_start"],
            "availableEnd" => $row["Available_end"],
            "ownerId" => $row["Owner_id"],
            "price" => $row["price"],
            "name" => $row["name"],
            "imageUrl" => $row["imageUrl"],
            "availability"=> $row["availability"]
        );
        array_push($cars, $car);
    }
}


$conn->close();

// Output JSON
echo json_encode($cars);
?>
