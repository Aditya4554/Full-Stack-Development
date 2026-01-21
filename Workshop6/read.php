<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Student</title>
</head>
<body>
<form method="POST" action="insert.php">
    <label>firstname:</label><br>
    <input type="text" name="firstname" required><br><br>

    <label>skill:</label><br>
    <input type="text" name="skill" required><br><br>

    <label>email:</label><br>
    <input type="email" name="email" required><br><br>

    <button type="submit">Add Student</button><br><br>
</form>
<button type="button" onclick="window.location.href='index.php'">back</button>

</body>
</html>
