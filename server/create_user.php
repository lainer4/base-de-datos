<?php


$servername = "localhost";

$username = "master";

$password = "4444";
$dbname = "agendaJMAN";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$stmt = $conn->prepare("INSERT INTO usuarios (email, nombre, password, nacimiento) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $email, $nombre, $password, $nacimiento);


$email = "jperez@gmail.com";
$nombre = "Juan Perez";
$password = password_hash("juanperez", PASSWORD_DEFAULT);
$nacimiento = "1973-09-13";

$stmt->execute();


$email = "agomez@gmail.com";
$nombre = "Alejandro Gomez";
$password = password_hash("agomez", PASSWORD_DEFAULT);
$nacimiento = "1968-04-02";

$stmt->execute();


$email = "fduran@gmail.com";
$nombre = "Fabio Duran";
$password = password_hash("fduran", PASSWORD_DEFAULT);
$nacimiento = "1975-03-03";

$stmt->execute();

echo "New records created successfully";

$stmt->close();
$conn->close();

 ?>
