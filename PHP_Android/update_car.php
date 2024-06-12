<?php
require_once "conn.php";
require_once "validate.php";

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['model']) && isset($_POST['year'])) {
    $id = validate($_POST['id']);
    $name = validate($_POST['name']);
    $model = validate($_POST['model']);
    $year = validate($_POST['year']);

    $sql = "UPDATE car SET name='$name', model='$model', year='$year' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "failure";
    }
} else {
    echo "failure";
}
?>
