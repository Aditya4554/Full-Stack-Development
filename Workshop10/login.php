<?php

require 'session.php';
require 'db.php';

$error = '';

// Generate CSRF token if not already in session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Verify CSRF token
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error = "Invalid request";
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (empty($email)) {
                $error = "Email cannot be empty";
            } elseif (empty($password)) {
                $error = "Password cannot be empty";
            } else {
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user) {
                    if (password_verify($password, $user['password'])) {
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = $user['id'];
                        header('Location: dashboard.php');
                        exit;
                    } else {
                        $error = "Invalid email or password";
                    }
                } else {
                    $error = "Invalid email or password";
                }
            }
        }
    }

} catch (Exception $e) {
    $error = "Something went wrong.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    <label>Email:</label><br>
    <input type="text" name="email"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Login</button>
</form>
<br>
<a href="signup.php">Go to Signup</a>
</body>
</html>
