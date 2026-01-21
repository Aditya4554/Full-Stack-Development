<?php
include 'db.php';
try{
	$id = $_GET['id'];
	$statement=$conn->prepare("DELETE FROM school WHERE id=?");
	$statement->execute([$id]);
	header("Location: index.php");
}catch(PDOException $e){
	die("Database Error: ". $e->getMessage());
}
?>
