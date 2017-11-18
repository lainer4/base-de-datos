<?php

//$servername = "localhost";
$servername = "localhost";
//$username = "agendaNavidad";
$username = "master";
//$password = "MerryChristmas2017?";
$password = "4444";
$dbname = "agendajman";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// prepare and bind
$stmt = $conn->prepare("INSERT INTO usuarios (email, nombre, password, nacimiento) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $email, $nombre, $password, $nacimiento);

// set parameters and execute
//first user
$email = "jperez@gmail.com";
$nombre = "Juan Perez";
$password = password_hash("juanperez", PASSWORD_DEFAULT);
$nacimiento = "1973-09-13";

$stmt->execute();

//second user
$email = "agomez@gmail.com";
$nombre = "Alejandro Gomez";
$password = password_hash("agomez", PASSWORD_DEFAULT);
$nacimiento = "1968-04-02";

$stmt->execute();


//third user
$email = "fduran@gmail.com";
$nombre = "Fabio Duran";
$password = password_hash("fduran", PASSWORD_DEFAULT);
$nacimiento = "1975-03-03";

$stmt->execute();

echo "New records created successfully";

$stmt->close();
$conn->close();

 ?>
