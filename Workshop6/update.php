<?php
require 'db.php';
if (!isset($_GET['id'])) {
    die("ID not provided");
}

$id = $_GET['id'];
try {
    $stmt = $conn->prepare("SELECT * FROM school WHERE id = ?");
    $stmt->execute([$id]);
    $school = $stmt->fetch();

    if (!$school) {
        die("school not found");
    }

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $skill = $_POST['skill'];
    $email = $_POST['email'];

    try {
        $statement = $conn->prepare(
            "UPDATE school SET firstname = ?, skill = ?, email = ? WHERE id = ?"
        );

        $statement->execute([$firstname, $skill, $email, $id]);

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>

<h2>Edit Student</h2>

<form method="POST">
    Name: <br>
    <input type="text" name="firstname" value="<?= htmlspecialchars($school['firstname']); ?>" required><br><br>

    Skill: <br>
    <input type="text" name="skill" value="<?= htmlspecialchars($school['skill']); ?>" required><br><br>

    Email: <br>
    <input type="email" name="email" value="<?= htmlspecialchars($school['email']); ?>" required><br><br>

    <button type="submit">Update</button>
</form>

</body>
</html>
