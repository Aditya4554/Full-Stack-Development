<?php
require 'db.php';
$firstname=$_POST['firstname'];
$skill=$_POST['skill'];
$email=$_POST['email'];
try{
$statement=$conn->prepare("INSERT INTO school(firstname, skill, email) VALUES (?, ?, ?);");
$statement->bindValue(1, $firstname);
$statement->bindValue(2, $skill);
$statement->bindValue(3, $email);
$statement->execute();
header("Location: index.php");
}catch(PDOException $e){
  die("Database Error: ". $e->getMessage());
}
?>
