<?php
$servername = "localhost";
$username = "np03cs4a240187";
$password = "YiRubERoKV";
$dbname = "np03cs4a240187";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // sql to create table
  $sql = "CREATE TABLE IF NOT EXISTS school (
  id INT  AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(30) NOT NULL,
  skill VARCHAR(30) NOT NULL,
  email VARCHAR(50)
  )";

  // use exec() because no results are returned
  $conn->exec($sql);
  // $conn->exec("INSERT INTO School(firstname,skill,email)VALUES('Aditya','Actor','adityashiwakoti1@gmail.com')");
  // echo "Table School created successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
?>
