<?php
// get_orders.php
session_start();
include "db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to view orders']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Try to get orders with grouping first
$orders = array();

// Check if order_group column exists and has data
$check_group = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE order_group IS NOT NULL AND order_group != '' AND user_id = $user_id");
$has_groups = $check_group->fetch_assoc()['cnt'] > 0;

if ($has_groups) {
    // Method 1: Group by order_group
    $stmt = $conn->prepare("SELECT order_group, 
                                   MIN(id) as id,
                                   MAX(created_at) as created_at,
                                   MAX(address) as address,
                                   MAX(payment_method) as payment_method,
                                   MAX(status) as status,
                                   SUM(total) as total_amount,
                                   COUNT(*) as item_count
                            FROM orders 
                            WHERE user_id = ? AND status != 'Rejected'
                            GROUP BY order_group
                            ORDER BY created_at DESC");
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Get all items in this order group
        $items_stmt = $conn->prepare("SELECT product_name, price, quantity, total 
                                      FROM orders 
                                      WHERE order_group = ?");
        $items_stmt->bind_param("s", $row['order_group']);
        $items_stmt->execute();
        $items_result = $items_stmt->get_result();
        
        $items = array();
        while ($item = $items_result->fetch_assoc()) {
            $items[] = [
                'product_name' => $item['product_name'],
                'price' => floatval($item['price']),
                'quantity' => intval($item['quantity']),
                'total' => floatval($item['total'])
            ];
        }
        
        $orders[] = [
            'id' => $row['id'],
            'created_at' => $row['created_at'],
            'address' => $row['address'],
            'payment_method' => $row['payment_method'],
            'status' => $row['status'],
            'total_amount' => floatval($row['total_amount']),
            'item_count' => intval($row['item_count']),
            'items' => $items
        ];
        $items_stmt->close();
    }
    $stmt->close();
    
} else {
    // Method 2: No grouping - show each item separately
    $stmt = $conn->prepare("SELECT id, product_name, price, quantity, total, status, address, payment_method, created_at 
                            FROM orders 
                            WHERE user_id = ? AND status != 'Rejected'
                            ORDER BY created_at DESC");
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $orders[] = [
            'id' => $row['id'],
            'created_at' => $row['created_at'],
            'address' => $row['address'],
            'payment_method' => $row['payment_method'],
            'status' => $row['status'],
            'total_amount' => floatval($row['total']),
            'item_count' => 1,
            'items' => [[
                'product_name' => $row['product_name'],
                'price' => floatval($row['price']),
                'quantity' => intval($row['quantity']),
                'total' => floatval($row['total'])
            ]]
        ];
    }
    $stmt->close();
}

echo json_encode([
    'success' => true,
    'orders' => $orders
]);

$conn->close();
?>