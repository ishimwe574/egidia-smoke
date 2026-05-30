<?php

header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "sensor_db");

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sensor_data ORDER BY id DESC LIMIT 20";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

$conn->close();

?>