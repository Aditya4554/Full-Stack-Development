<?php
$host = "localhost";
$user = "np03cs4a240187";
$pass = "YiRubERoKV";
$db   = "np03cs4a240187";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed");
}
?>
