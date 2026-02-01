<?php
// register_process.php - Handles registration logic

// Check if simulated or real POST data is set
if (isset($_POST['username']) && !empty($_POST['username'])) {
    // Retrieve and sanitize inputs
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, simulate successful registration
    if (empty($errors)) {
        // Here, you could save to a database, send an email, etc.
        // For now, just echo a success message
        echo "Registration successful for user: $username ($email)<br>";
        echo "Password hashed (simulated): " . password_hash($password, PASSWORD_DEFAULT) . "<br>";
    } else {
        // Display errors
        echo "Registration failed:<br>";
        foreach ($errors as $error) {
            echo "- $error<br>";
        }
    }
} else {
    echo "No POST data received. This script expects form submission or simulated data.";
}
?>