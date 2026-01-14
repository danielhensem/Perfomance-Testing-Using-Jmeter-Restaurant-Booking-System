<?php
session_start();

// Check if session has expired
if (isset($_SESSION['booking_expires_at']) && time() > $_SESSION['booking_expires_at']) {
    http_response_code(408); // Request Timeout
    echo json_encode(['error' => 'Session expired']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['cart']) && isset($input['total'])) {
        $_SESSION['items'] = $input['cart'];
        $_SESSION['total'] = floatval($input['total']);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>

