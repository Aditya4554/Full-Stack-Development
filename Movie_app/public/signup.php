<?php
include "../config/db.php";
include "../includes/functions.php";
include "../includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = "Security validation failed. Please try again.";
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password_confirm = $_POST["password_confirm"];

        if ($password !== $password_confirm) {
            $message = "Passwords do not match!";
        } elseif (!validate_password($password)) {
            $message = "Password must be at least 6 characters and contain at least one number.";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users(username, password) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ss", $username, $password_hash);
                try {
                    if ($stmt->execute()) {
                        redirect("login.php");
                    }
                } catch (mysqli_sql_exception $e) {
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $message = "Username already exists! Please choose a different username.";
                    } else {
                        $message = "Registration failed: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                    }
                }
                $stmt->close();
            } else {
                $message = "Database error. Please try again.";
            }
        }
    }
}

$csrf_token = generate_csrf_token();
?>

<h3>Sign Up</h3>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required title="At least 6 characters with at least one number">

    <label for="password_confirm">Confirm Password:</label>
    <input type="password" id="password_confirm" name="password_confirm" required>

    <button type="submit">Register</button>
</form>

<p style="color:red;"><?= $message ?></p>
<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include "../includes/footer.php"; ?>
