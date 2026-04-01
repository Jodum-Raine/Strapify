<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Strapify</title>
    <link rel="stylesheet" href="Dashboard2.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .back-btn {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .orders-grid {
            display: grid;
            gap: 20px;
        }
        
        .order-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .order-id {
            font-weight: bold;
            color: #667eea;
        }
        
        .order-date {
            color: #999;
            font-size: 14px;
        }
        
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-accepted {
            background: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .order-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .order-item-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .order-total {
            text-align: right;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        
        .shipping-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .no-orders {
            text-align: center;
            color: white;
            padding: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        
        .no-orders a {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="Dashboard1.php" class="back-btn">← Back to Dashboard</a>
        <h1>📦 My Orders</h1>
        
        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <p>🛍️ You haven't placed any orders yet.</p>
                <p>Go to the <a href="Dashboard1.php#items">Items</a> section to start shopping!</p>
            </div>
        <?php else: ?>
            <div class="orders-grid">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Order #<?= $order['id'] ?></span>
                            <span class="order-date">📅 <?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></span>
                            <span class="status status-<?= strtolower($order['status']) ?>">
                                <?= $order['status'] ?>
                            </span>
                        </div>
                        
                        <div class="order-item">
                            <div class="order-item-info">
                                <div>
                                    <strong><?= htmlspecialchars($order['product_name']) ?></strong>
                                    <br>
                                    <small>Quantity: <?= $order['quantity'] ?> × ₱<?= number_format($order['price'], 2) ?></small>
                                </div>
                                <div style="font-weight: bold; color: #28a745;">
                                    ₱<?= number_format($order['total'], 2) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-total">
                            Total: ₱<?= number_format($order['total'], 2) ?>
                        </div>
                        
                        <div class="shipping-info">
                            <strong>📍 Address:</strong> <?= htmlspecialchars($order['address']) ?><br>
                            <strong>💳 Payment:</strong> <?= htmlspecialchars($order['payment_method']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>