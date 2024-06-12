<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cardb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function validate($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['name'], $_POST['model'], $_POST['year'], $_POST['price'], $_POST['availableStart'], $_POST['availableEnd'], $_POST['ownerId'], $_POST['imageUrl'], $_POST['availability'])) {
    $name = validate($_POST['name']);
    $model = validate($_POST['model']);
    $year = validate($_POST['year']);
    $price = validate($_POST['price']);
    $availableStart = validate($_POST['availableStart']);
    $availableEnd = validate($_POST['availableEnd']);
    $ownerId = validate($_POST['ownerId']);
    $availability = validate($_POST['availability']);
    $image = $_POST['imageUrl'];


    // Decode the image from base64
    $decodedImage = base64_decode($image);

    // Directory to save uploaded images
    $targetDir = "uploads/";

    // Generate a unique filename for the image
    $imageName = uniqid() . '.jpg';

    // Path to save the image
    $targetFilePath = $targetDir . $imageName;

    // Check if uploads directory exists
    if (!file_exists($targetDir)) {
        // Create the uploads directory if it doesn't exist
        if (!mkdir($targetDir, 0777, true)) {
            die("failure: Failed to create uploads directory");
        }
    }

    // Save the image to the server
    if (file_put_contents($targetFilePath, $decodedImage) !== false) {
        // Image saved successfully, now insert data into database
        $addImage = "http://10.0.2.2/PHP_Android/".$targetFilePath;
        $stmt = $conn->prepare("INSERT INTO car (imageUrl, model,availability, year, price, Available_start, Available_end, Owner_id, name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissssis",$addImage,$model, $availability, $year, $price, $availableStart, $availableEnd, $ownerId, $name);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "failure: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "failure: Failed to save the image.";
    }
} else {
    echo "failure: Missing required POST parameters";
}

$conn->close();
?>
