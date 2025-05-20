<?php
$host = "sql12.freesqldatabase.com";
$user = "sql12779950";
$pass = "IuvmKW82Y7s";
$db   = "sql12779950";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
