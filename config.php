<?php declare(strict_types=1); 
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "multi_vendeurs"; 

$conn = new mysqli("localhost", "root","", "multi_vendeurs");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>