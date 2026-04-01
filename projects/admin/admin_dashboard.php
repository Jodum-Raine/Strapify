<?php
session_start();
include "../db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get statistics - COUNTING ORDER GROUPS, not individual items
$total_orders = $conn->query("SELECT COUNT(DISTINCT order_group) as count FROM orders WHERE status != 'Rejected'")->fetch_assoc()['count'];
$pending_orders = $conn->query("SELECT COUNT(DISTINCT order_group) as count FROM orders WHERE status = 'Pending'")->fetch_assoc()['count'];
$accepted_orders = $conn->query("SELECT COUNT(DISTINCT order_group) as count FROM orders WHERE status = 'Accepted'")->fetch_assoc()['count'];
$rejected_orders = $conn->query("SELECT COUNT(DISTINCT order_group) as count FROM orders WHERE status = 'Rejected'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total) as total FROM orders WHERE status = 'Accepted'")->fetch_assoc()['total'];

// Get recent orders - GROUPED BY order_group (FIXED: specify which id to use)
$recent_orders = $conn->query("SELECT o.order_group, 
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
                                ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Strapify</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #40a99b;
            transition: background-color 0.6s ease;
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

        .welcome-card {
            background: rgba(53, 122, 107, 0.95);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .welcome-card h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-card.total .stat-number { color: #667eea; }
        .stat-card.pending .stat-number { color: #ffc107; }
        .stat-card.accepted .stat-number { color: #28a745; }
        .stat-card.rejected .stat-number { color: #dc3545; }
        .stat-card.revenue .stat-number { color: #40a99b; }

        .nav-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 40px;
        }

        .nav-btn {
            background: white;
            color: #357a6b;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .nav-btn:hover {
            background: #357a6b;
            color: white;
            transform: translateY(-2px);
        }

        .recent-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
        }

        .recent-section h3 {
            color: #357a6b;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .orders-table {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            color: #357a6b;
            font-weight: bold;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .status-badge {
            padding: 4px 10px;
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

        .item-count-badge {
            background: #40a99b;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 8px;
        }

        .view-all-btn {
            display: inline-block;
            margin-top: 20px;
            background: #357a6b;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .view-all-btn:hover {
            background: #2a5f54;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header class="navbar">
    <h2>Strapify</h2>
    <nav class="menu">
        <a href="admin_dashboard.php" class="active">Dashboard</a>
        <a href="admin_orders.php">Orders</a>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </nav>
</header>

<main class="main-content">
    <div class="welcome-card">
        <h1>👑 Admin Dashboard</h1>
        <p>Welcome back! Here's what's happening with your store today.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-icon">📦</div>
            <div class="stat-number"><?= $total_orders ?></div>
            <div class="stat-label">Total Orders</div>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-icon">⏳</div>
            <div class="stat-number"><?= $pending_orders ?></div>
            <div class="stat-label">Pending Orders</div>
        </div>
        
        <div class="stat-card accepted">
            <div class="stat-icon">✅</div>
            <div class="stat-number"><?= $accepted_orders ?></div>
            <div class="stat-label">Accepted Orders</div>
        </div>
        
        <div class="stat-card rejected">
            <div class="stat-icon">❌</div>
            <div class="stat-number"><?= $rejected_orders ?></div>
            <div class="stat-label">Rejected Orders</div>
        </div>
        
        <div class="stat-card revenue">
            <div class="stat-icon">💰</div>
            <div class="stat-number">₱<?= number_format($total_revenue ?? 0, 2) ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>

    <div class="nav-buttons">
        <a href="admin_orders.php" class="nav-btn">📋 Manage Orders</a>
    </div>

    <div class="recent-section">
        <h3>📋 Recent Orders</h3>
        
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_orders && $recent_orders->num_rows > 0): ?>
                        <?php while ($order = $recent_orders->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td>
                                    <?= htmlspecialchars($order['user_name']) ?>
                                    <div style="font-size: 12px; color: #666;"><?= htmlspecialchars($order['user_email']) ?></div>
                                </td>
                                <td>
                                    <?= $order['item_count'] ?> item(s)
                                    <span class="item-count-badge"><?= $order['item_count'] ?> items</span>
                                </td>
                                <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                        <?= $order['status'] ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No orders yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="admin_orders.php" class="view-all-btn">View All Orders →</a>
        </div>
    </div>
</main>

</body>
</html>