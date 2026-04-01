<?php
session_start();
include "../db.php";

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get filter from URL or default to 'active'
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'active';

// Handle order status update
if (isset($_POST['update_status'])) {
    $order_group = $_POST['order_group'];
    $new_status = $_POST['status'];
    
    // Update ALL items in this order group
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_group = ?");
    $stmt->bind_param("ss", $new_status, $order_group);
    
    if ($stmt->execute()) {
        $success = "Order group updated to $new_status!";
    } else {
        $error = "Error updating order status.";
    }
    $stmt->close();
}

// Get orders grouped by order_group
if ($filter == 'all') {
    $sql = "SELECT o.order_group, 
                   MIN(o.id) as id,
                   MAX(o.created_at) as created_at,
                   MAX(o.address) as address,
                   MAX(o.payment_method) as payment_method,
                   MAX(o.status) as status,
                   SUM(o.total) as total_amount,
                   COUNT(*) as item_count,
                   u.name as user_name,
                   u.email as user_email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            GROUP BY o.order_group
            ORDER BY created_at DESC";
} else {
    $sql = "SELECT o.order_group, 
                   MIN(o.id) as id,
                   MAX(o.created_at) as created_at,
                   MAX(o.address) as address,
                   MAX(o.payment_method) as payment_method,
                   MAX(o.status) as status,
                   SUM(o.total) as total_amount,
                   COUNT(*) as item_count,
                   u.name as user_name,
                   u.email as user_email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.status != 'Rejected'
            GROUP BY o.order_group
            ORDER BY created_at DESC";
}

$result = $conn->query($sql);
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Orders | Strapify</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #40a99b;
        }

        .navbar {
            background-color: inherit;
            color: white;
            padding: 40px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar h2 {
            font-size: 45px;
        }

        .menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu a {
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 5px;
            transition: 0.3s;
            font-size: 18px;
        }

        .menu a:hover {
            background-color: #3b8a7a;
        }

        .menu a.active {
            background-color: #357a6b;
        }

        .logout-btn {
            background: #ff4c4c;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #e04343;
            transform: scale(1.05);
        }

        .main-content {
            padding: 30px;
            min-height: calc(100vh - 120px);
        }

        .page-header {
            background: rgba(53, 122, 107, 0.95);
            color: white;
            padding: 25px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            font-size: 28px;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            background: white;
            color: #357a6b;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: #357a6b;
            color: white;
        }

        .filter-btn:hover {
            background: #357a6b;
            color: white;
            transform: translateY(-2px);
        }

        .back-btn {
            background: white;
            color: #357a6b;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #357a6b;
            color: white;
            transform: translateX(-5px);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .orders-container {
            background: white;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f8f9fa;
            color: #357a6b;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
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
        
        .status-form {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }
        
        .status-select {
            padding: 6px 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
        }
        
        .update-btn {
            background: #357a6b;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .update-btn:hover {
            background: #2a5f54;
        }
        
        .user-info {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }
        
        .item-count-badge {
            background: #40a99b;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 8px;
        }
        
        .no-orders {
            text-align: center;
            padding: 60px;
            color: #999;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 20px;
            }
            
            .menu {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                text-align: center;
            }
            
            th, td {
                padding: 10px;
                font-size: 12px;
            }
            
            .status-form {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>

<header class="navbar">
    <h2>Strapify</h2>
    <nav class="menu">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_orders.php" class="active">Orders</a>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </nav>
</header>

<main class="main-content">
    <div class="page-header">
        <h1>📋 Order Management</h1>
        <div style="display: flex; gap: 10px; align-items: center;">
            <div class="filter-buttons">
                <a href="?filter=active" class="filter-btn <?= $filter == 'active' ? 'active' : '' ?>">Active Orders</a>
                <a href="?filter=all" class="filter-btn <?= $filter == 'all' ? 'active' : '' ?>">All Orders</a>
            </div>
            <a href="admin_dashboard.php" class="back-btn">← Back</a>
        </div>
    </div>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success">✅ <?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error">❌ <?= $error ?></div>
    <?php endif; ?>
    
    <div class="orders-container">
        <table>
            <thead>
                <tr>
                    <th>Receipt #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="no-orders">
                            📭 No orders found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?= $order['id'] ?></strong></td>
                            <td>
                                <?= htmlspecialchars($order['user_name']) ?>
                                <div class="user-info"><?= htmlspecialchars($order['user_email']) ?></div>
                            </td>
                            <td>
                                <?= $order['item_count'] ?> item(s)
                                <span class="item-count-badge"><?= $order['item_count'] ?> items</span>
                            </td>
                            <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($order['payment_method']) ?></td>
                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                    <?= $order['status'] ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($order['status'] != 'Rejected'): ?>
                                    <form method="POST" class="status-form">
                                        <input type="hidden" name="order_group" value="<?= $order['order_group'] ?>">
                                        <select name="status" class="status-select">
                                            <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Accepted" <?= $order['status'] == 'Accepted' ? 'selected' : '' ?>>Accept</option>
                                            <option value="Rejected" <?= $order['status'] == 'Rejected' ? 'selected' : '' ?>>Reject</option>
                                        </select>
                                        <button type="submit" name="update_status" class="update-btn">Update</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: #999;">Archived</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>