<?php
include "../config/db.php";
include "../includes/functions.php";

if (!isset($_SESSION['user'])) {
    redirect("login.php");
}

// Check if user is admin
if (!is_admin()) {
    redirect("index.php");
}

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM movies WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

redirect("index.php");
?>