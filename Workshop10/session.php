<?php

// Configure session cookie parameters for security
session_set_cookie_params([
    'httponly' => true,      // Prevent JavaScript access to session cookies
    'samesite' => 'Lax',     // Reduce risk of CSRF attacks
    'secure' => false        // Set to true if using HTTPS
]);

// Start the session
session_start();

?>