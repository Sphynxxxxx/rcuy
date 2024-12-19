<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "papapdol"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

if ($password !== $confirm_password) {
    echo "Passwords do not match!";
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (full_name, address, username, password) VALUES ('$full_name', '$address', '$username', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    header("Location: login.php"); 
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
