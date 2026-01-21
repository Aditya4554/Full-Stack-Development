<?php
require "db.php";

$query = $conn->query("SELECT * FROM school");
$data = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style> 
		body{
			text-align: center;
		}
		table {
        	border-collapse: collapse;
        	margin: auto;
        	border:2px solid black;
    	}
		th, td {
        	padding: 12px 20px; 
        	border: 1px solid black;
    	}
	</style>
</head>
<body>
<button onclick="window.location.href ='read.php' ">Add Student</button><br><br>
<table>
  <tr>
  	<th>id</th>
    <th>firstname</th>
    <th>skill</th>
    <th>email</th>
  </tr>
  <?php
  foreach ($data as $student){
  echo"<tr><td>{$student['id']}</td>
  <td>{$student['firstname']}</td>
  <td>{$student['skill']}</td>
  <td>{$student['email']}</td>
<td>
<a href=update.php?id={$student['id']}>Edit</a>
</td>
<td>
<a href=delete.php?id={$student['id']}>Delete</a>
</td>
</tr>";
$conn=null;
}
?>
</table>
</body>
</html>
