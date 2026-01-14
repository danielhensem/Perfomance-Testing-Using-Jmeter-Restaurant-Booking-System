<?php
session_start();

// Check if session has expired
if (isset($_SESSION['booking_expires_at']) && time() > $_SESSION['booking_expires_at']) {
    session_unset();
    session_destroy();
    header('Location: index.php?expired=1');
    exit();
}

// Validate session and required data
if (!isset($_SESSION['booking_started']) || !isset($_SESSION['customer_info'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify payment confirmation
    if (isset($_POST['payment_confirmed']) && $_POST['payment_confirmed'] === 'on') {
        // Save booking details (in a real system, you would save to database)
        $_SESSION['booking_completed'] = true;
        $_SESSION['payment_confirmed'] = true;
        $_SESSION['booking_date'] = date('Y-m-d H:i:s');
        
        // In a real application, you would:
        // - Save booking to database
        // - Send confirmation email
        // - Process payment gateway response
        
        // Verify cart has items
        if (!isset($_SESSION['items']) || empty($_SESSION['items'])) {
            header('Location: index.php?payment=1&error=no_items');
            exit();
        }
        
        // Clear session data but keep booking_id for confirmation
        $booking_id = $_SESSION['booking_id'];
        $customer_info = $_SESSION['customer_info'];
        $items = $_SESSION['items'];
        $total = $_SESSION['total'];
        session_unset();
        session_destroy();
        
        // Start new session for success page
        session_start();
        $_SESSION['last_booking_id'] = $booking_id;
        
        header('Location: index.php?success=1');
        exit();
    } else {
        header('Location: index.php?payment=1&error=payment_not_confirmed');
        exit();
    }
}

header('Location: index.php');
exit();
?>

