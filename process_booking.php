<?php
session_start();

// Check if session has expired
if (isset($_SESSION['booking_expires_at']) && time() > $_SESSION['booking_expires_at']) {
    session_unset();
    session_destroy();
    header('Location: index.php?expired=1');
    exit();
}

// Validate session exists
if (!isset($_SESSION['booking_started'])) {
    header('Location: index.php');
    exit();
}

// Save customer information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['customer_info'] = [
        'name' => htmlspecialchars($_POST['name'] ?? ''),
        'phone' => htmlspecialchars($_POST['phone'] ?? ''),
        'email' => htmlspecialchars($_POST['email'] ?? ''),
        'date' => htmlspecialchars($_POST['date'] ?? ''),
        'time' => htmlspecialchars($_POST['time'] ?? ''),
        'guests' => intval($_POST['guests'] ?? 0)
    ];
    
    // Redirect to menu section
    header('Location: index.php');
    exit();
}

header('Location: index.php');
exit();
?>

