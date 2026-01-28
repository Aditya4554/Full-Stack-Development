<?php

require 'session.php';
require 'db.php';

$user_email = '';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION);
    header('Location: dashboard.php');
    exit;
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if ($user) {
        $user_email = $user['email'];
    }
}
?>
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Welcome to my site</h1>
<?php if ($user_email): ?>
    <p>Logged In User : <?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></p>
    <a href="dashboard.php?logout=1">
        <button>Logout</button>
    </a>
<?php else: ?>
    <a href="login.php">
        <button>Login</button>
    </a>
<?php endif; ?>

</body>
</html>