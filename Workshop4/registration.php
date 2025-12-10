<?php
// registration.php
// A simple user registration system storing users in users.json

// === Configuration ===
$jsonFile = __DIR__ . '/users.json';

// === Helpers ===
function old($key) {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
}

function add_error(&$errors, $key, $message) {
    $errors[$key] = $message;
}

// === Processing ===
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Validation: required
    if ($name === '') {
        add_error($errors, 'name', 'Name is required.');
    }

    if ($email === '') {
        add_error($errors, 'email', 'Email is required.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        add_error($errors, 'email', 'Email format is invalid.');
    }

    // Password rules: min 8 chars, at least 1 lowercase, 1 uppercase, 1 digit, 1 special
    $pwdErrors = [];
    if ($password === '') {
        add_error($errors, 'password', 'Password is required.');
    } else {
        if (strlen($password) < 8) {
            $pwdErrors[] = 'at least 8 characters';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $pwdErrors[] = 'a lowercase letter';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $pwdErrors[] = 'an uppercase letter';
        }
        if (!preg_match('/\d/', $password)) {
            $pwdErrors[] = 'a number';
        }
        if (!preg_match('/[\W_]/', $password)) {
            $pwdErrors[] = 'a special character';
        }
        if (!empty($pwdErrors)) {
            add_error($errors, 'password', 'Password must contain ' . implode(', ', $pwdErrors) . '.');
        }
    }

    if ($confirm === '') {
        add_error($errors, 'confirm_password', 'Confirm password is required.');
    } elseif ($password !== $confirm) {
        add_error($errors, 'confirm_password', 'Passwords do not match.');
    }

    // If validation passed, proceed to store user
    if (empty($errors)) {
        // Ensure JSON file exists and contains a JSON array
        if (!file_exists($jsonFile)) {
            // try to create it
            if (false === file_put_contents($jsonFile, json_encode([], JSON_PRETTY_PRINT))) {
                add_error($errors, 'general', 'Failed to create users storage file.');
            }
            // attempt to set permissive permissions (may fail on some hosts)
            @chmod($jsonFile, 0664);
        }

        if (empty($errors)) {
            // Read current users
            $raw = @file_get_contents($jsonFile);
            if ($raw === false) {
                add_error($errors, 'general', 'Failed to read users storage file. Check permissions.');
            } else {
                $users = json_decode($raw, true);
                if (!is_array($users)) {
                    // malformed file — try to reset to empty array (but warn)
                    $users = [];
                }

                // Prevent duplicate email
                $emails = array_map(function($u){ return strtolower($u['email'] ?? ''); }, $users);
                if (in_array(strtolower($email), $emails, true)) {
                    add_error($errors, 'email', 'This email is already registered.');
                } else {
                    // Hash password
                    $hashed = password_hash($password, PASSWORD_DEFAULT);

                    // Prepare new user record (you can add more fields like created_at)
                    $newUser = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashed
                    ];

                    // Append and save with exclusive lock
                    $users[] = $newUser;
                    $jsonData = json_encode($users, JSON_PRETTY_PRINT);

                    if (false === @file_put_contents($jsonFile, $jsonData, LOCK_EX)) {
                        add_error($errors, 'general', 'Failed to write to users storage file. Check permissions.');
                    } else {
                        $success = 'Registration successful! You may now log in.';
                        // Clear POST data so form resets (except we keep nothing)
                        $_POST = [];
                    }
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>User Registration</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 700px; margin: auto; }
    form { border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
    .field { margin-bottom: 12px; }
    label { display:block; margin-bottom:6px; font-weight:600; }
    input[type="text"], input[type="email"], input[type="password"] { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; }
    .error { color: #b00020; font-size: 0.9em; margin-top:6px; }
    .success { color: #006400; padding:10px; border:1px solid #cfc; background:#f6ffef; margin-bottom:12px; border-radius:6px; }
    .general-error { color:#800; padding:10px; border:1px solid #f9d6d6; background:#fff0f0; margin-bottom:12px; border-radius:6px; }
    button { padding:10px 16px; border:none; border-radius:6px; cursor:pointer; background:#0066cc; color:#fff; }
</style>
</head>
<body>

<h1>Create an account</h1>

<?php if (!empty($success)): ?>
    <div class="success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (!empty($errors['general'])): ?>
    <div class="general-error"><?php echo htmlspecialchars($errors['general']); ?></div>
<?php endif; ?>

<form method="post" action="">
    <div class="field">
        <label for="name">Name *</label>
        <input id="name" name="name" type="text" value="<?php echo old('name'); ?>" />
        <?php if (!empty($errors['name'])): ?><div class="error"><?php echo htmlspecialchars($errors['name']); ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="email">Email address *</label>
        <input id="email" name="email" type="email" value="<?php echo old('email'); ?>" />
        <?php if (!empty($errors['email'])): ?><div class="error"><?php echo htmlspecialchars($errors['email']); ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="password">Password *</label>
        <input id="password" name="password" type="password" />
        <small>Minimum 8 characters, include uppercase, lowercase, number, and special character.</small>
        <?php if (!empty($errors['password'])): ?><div class="error"><?php echo htmlspecialchars($errors['password']); ?></div><?php endif; ?>
    </div>

    <div class="field">
        <label for="confirm_password">Confirm Password *</label>
        <input id="confirm_password" name="confirm_password" type="password" />
        <?php if (!empty($errors['confirm_password'])): ?><div class="error"><?php echo htmlspecialchars($errors['confirm_password']); ?></div><?php endif; ?>
    </div>

    <button type="submit">Register</button>
</form>

</body>
</html>
