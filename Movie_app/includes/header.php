<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movie Database</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<h2>🎬 Movie Database Application</h2>

<!-- Login Menu -->
<div class="navigation">
<?php if(isset($_SESSION['user'])) { ?>
    <span class="nav-welcome">Welcome,</span> <span class="nav-username"><?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?></span> |
    <a href="logout.php">Logout</a>
<?php } else { ?>
    <a href="login.php">Login</a> |
    <a href="signup.php">Sign Up</a>
<?php } ?>
</div>
<div id="result"></div>

<hr>
<script src="/Movie_app/assets/search.js"></script>
