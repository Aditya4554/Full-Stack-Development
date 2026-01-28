<?php
include "../config/db.php";
include "../includes/functions.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = "Security validation failed. Please try again.";
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $username;
                $_SESSION['user_id'] = $user['id'];
                redirect("index.php");
            } else {
                $message = "Invalid username or password!";
            }
        } else {
            $message = "Database error. Please try again.";
        }
    }
}

$csrf_token = generate_csrf_token();
include "../includes/header.php";
?>

<h3>Login</h3>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
</form>

<p style="color:red;"><?= $message ?></p>
<p>Don't have an account? <a href="signup.php">Sign up here</a></p>

<?php include "../includes/footer.php"; ?>
