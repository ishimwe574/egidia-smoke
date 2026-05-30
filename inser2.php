<?php

$conn = new mysqli("localhost", "root", "", "sensor_db");

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

if(isset($_GET['temperature']) && isset($_GET['smoke'])){

    $temperature = $_GET['temperature'];
    $smoke = $_GET['smoke'];
    $distance = $_GET['distance'];


    $sql = "INSERT INTO sensor_data(temperature, smoke,distance)
            VALUES('$temperature', '$smoke', '$distance')";

    if($conn->query($sql) === TRUE){
        echo "Data Inserted";
    }else{
        echo "Error: " . $conn->error;
    }

}else{
    echo "No Data Received";
}

$conn->close();

?>