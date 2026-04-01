<?php
session_start();
include "db.php";

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to place an order']);
    exit();
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['orders'])) {
    echo json_encode(['success' => false, 'message' => 'No items in cart']);
    exit();
}

$user_id = $_SESSION['user_id'];
$address = $conn->real_escape_string($data['address']);
$payment = $conn->real_escape_string($data['payment']);
$orders_data = $data['orders'];

// Generate ONE unique order group ID for this checkout (NOT inside the loop!)
$order_group = uniqid() . '_' . $user_id . '_' . time();

$success = true;
$error_message = '';
$total_amount = 0;

// Calculate total amount first
foreach ($orders_data as $order) {
    $total_amount += floatval($order['total']);
}

// Start transaction
$conn->begin_transaction();

try {
    foreach ($orders_data as $order) {
        $product = $conn->real_escape_string($order['product']);
        $price = floatval($order['price']);
        $quantity = intval($order['quantity']);
        $total = floatval($order['total']);
        
        $sql = "INSERT INTO orders (user_id, order_group, product_name, price, quantity, total, address, payment_method, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdiiss", $user_id, $order_group, $product, $price, $quantity, $total, $address, $payment);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to save order: " . $stmt->error);
        }
        $stmt->close();
    }
    
    $conn->commit();
    echo json_encode([
        'success' => true, 
        'message' => 'Order placed successfully!',
        'order_group' => $order_group,
        'total' => $total_amount,
        'item_count' => count($orders_data)
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>