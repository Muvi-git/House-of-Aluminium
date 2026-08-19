<?php
session_start();
include '../db_connect.php';

// ඇඩ්මින් ද යන්න සහ ආරක්ෂක පියවර පරීක්ෂාව
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// === 🎯 BACKEND DATA FETCH ===
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$total_categories = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];
$total_brands = $conn->query("SELECT COUNT(*) as total FROM brands")->fetch_assoc()['total'];
$total_contacts = $conn->query("SELECT COUNT(*) as total FROM contact_submissions")->fetch_assoc()['total'];
$total_payments = $conn->query("SELECT COUNT(*) as total FROM payment_submissions")->fetch_assoc()['total'];
$total_reviews = $conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard | House of Aluminium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f8fafc; display: flex; min-height: 100vh; }
        
        /* Main Workspace Container */
        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; background: #f8fafc; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .main-header h1 { font-size: 1.75rem; color: #0f172a; font-weight: 800; letter-spacing: -0.5px; }
        .user-info { font-weight: 600; color: #475569; display: flex; align-items: center; gap: 8px; background: #fff; padding: 8px 16px; border-radius: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        
        /* Premium Welcome Header Banner */
        .welcome-box { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 35px; border-radius: 16px; margin-bottom: 35px; box-shadow: 0 10px 25px rgba(15,23,42,0.05); position: relative; overflow: hidden; }
        .welcome-box::before { content: ''; position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255, 51, 51, 0.05); border-radius: 50%; }
        .welcome-box h2 { margin-bottom: 8px; font-size: 1.65rem; font-weight: 700; }
        .welcome-box p { color: #94a3b8; line-height: 1.6; font-size: 0.95rem; max-width: 700px; }
        
        /* 6-Card Premium Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-bottom: 35px; }
        .stat-card { background: #fff; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,23,42,0.02); border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease; text-decoration: none; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(15,23,42,0.08); border-color: #cbd5e1; }
        .stats-grid a { text-decoration: none; }
        .stat-info h3 { font-size: 2.3rem; color: #0f172a; font-weight: 800; margin-bottom: 2px; line-height: 1; }
        .stat-info p { color: #64748b; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; }
        
        /* Icon Tints for Premium Appearance */
        .stat-icon { width: 54px; height: 54px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; transition: 0.3s; }
        .card-prod .stat-icon { background: rgba(255, 51, 51, 0.08); color: #ff3333; }
        .card-cat .stat-icon { background: rgba(59, 130, 246, 0.08); color: #3b82f6; }
        .card-brand .stat-icon { background: rgba(20, 184, 166, 0.08); color: #14b8a6; }
        .card-msg .stat-icon { background: rgba(245, 158, 11, 0.08); color: #f59e0b; }
        .card-pay .stat-icon { background: rgba(34, 197, 94, 0.08); color: #22c55e; }
        .card-rev .stat-icon { background: rgba(168, 85, 247, 0.08); color: #a855f7; }
        
        /* Twin Section Grid Layout (Recent Activities) */
        .dashboard-twin-section { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .activity-panel { background: #fff; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(15,23,42,0.02); }
        .panel-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; }
        .panel-top h4 { font-size: 1.1rem; color: #0f172a; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .btn-view-all { font-size: 0.82rem; font-weight: 600; color: #ff3333; text-decoration: none; background: rgba(255,51,51,0.05); padding: 6px 14px; border-radius: 20px; transition: 0.2s; }
        .btn-view-all:hover { background: #ff3333; color: #fff; }
        
        /* Micro-Lists styling inside panels */
        .activity-list { list-style: none; }
        .activity-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px dashed #e2e8f0; }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
        .item-details h5 { font-size: 0.92rem; color: #1e293b; font-weight: 600; }
        .item-details p { font-size: 0.78rem; color: #64748b; margin-top: 2px; }
        
        /* Badges */
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge.pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .badge.approved { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        
        @media (max-width: 1200px) { .dashboard-twin-section { grid-template-columns: 1fr; } }
        @media (max-width: 992px) { body { flex-direction: column; } }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="main-header">
            <h1>Overview Dashboard</h1>
            <div class="user-info">
                <i class="fas fa-shield-alt" style="color:#ff3333;"></i> Status: Admin Corporate
            </div>
        </div>

        <div class="welcome-box">
            <h2>Welcome to House of Aluminium Control Center!</h2>
            <p>Here is your comprehensive real-time live business intelligence summary. Easily monitor store statistics, respond to inquiries, audit slip uploads, and moderate consumer feedbacks.</p>
        </div>

        <div class="stats-grid">
            
            <a href="admin_products.php" class="stat-card card-prod">
                <div class="stat-info">
                    <h3><?php echo $total_products; ?></h3>
                    <p>Products</p>
                </div>
                <div class="stat-icon"><i class="fas fa-box"></i></div>
            </a>

            <a href="admin_categories.php" class="stat-card card-cat">
                <div class="stat-info">
                    <h3><?php echo $total_categories; ?></h3>
                    <p>Categories</p>
                </div>
                <div class="stat-icon"><i class="fas fa-list-ul"></i></div>
            </a>

            <a href="admin_brands.php" class="stat-card card-brand">
                <div class="stat-info">
                    <h3><?php echo $total_brands; ?></h3>
                    <p>Active Brands</p>
                </div>
                <div class="stat-icon"><i class="fas fa-tags"></i></div>
            </a>

            <a href="admin_contacts.php" class="stat-card card-msg">
                <div class="stat-info">
                    <h3><?php echo $total_contacts; ?></h3>
                    <p>Messages</p>
                </div>
                <div class="stat-icon"><i class="fas fa-envelope-open-text"></i></div>
            </a>

            <a href="admin_payments.php" class="stat-card card-pay">
                <div class="stat-info">
                    <h3><?php echo $total_payments; ?></h3>
                    <p>Bank Slips</p>
                </div>
                <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            </a>

            <a href="admin_reviews.php" class="stat-card card-rev">
                <div class="stat-info">
                    <h3><?php echo $total_reviews; ?></h3>
                    <p>User Reviews</p>
                </div>
                <div class="stat-icon"><i class="fas fa-star"></i></div>
            </a>

        </div>

        <div class="dashboard-twin-section">
            
            <div class="activity-panel">
                <div class="panel-top">
                    <h4><i class="fas fa-comment-alt" style="color:#f59e0b;"></i> Recent Customer Messages</h4>
                    <a href="admin_contacts.php" class="btn-view-all">View All</a>
                </div>
                <ul class="activity-list">
                    <?php
                    $latest_msgs = $conn->query("SELECT * FROM contact_submissions ORDER BY id DESC LIMIT 3");
                    if ($latest_msgs && $latest_msgs->num_rows > 0) {
                        while($m_row = $latest_msgs->fetch_assoc()) {
                            $title_preview = !empty($m_row['subject']) ? $m_row['subject'] : (!empty($m_row['message']) ? substr($m_row['message'], 0, 35) . '...' : 'General Inquiry');
                            ?>
                            <li class="activity-item">
                                <div class="item-details">
                                    <h5><?php echo htmlspecialchars($title_preview); ?></h5>
                                    <p>From: <?php echo htmlspecialchars($m_row['name']); ?></p>
                                </div>
                                <span style="font-size:0.75rem; color:#94a3b8; font-weight:500;">
                                    <?php echo isset($m_row['created_at']) ? date('h:i A', strtotime($m_row['created_at'])) : date('h:i A'); ?>
                                </span>
                            </li>
                            <?php
                        }
                    } else {
                        echo "<li style='color:#94a3b8; font-size:0.85rem; padding: 10px 0;'>No messages log entries recorded.</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="activity-panel">
                <div class="panel-top">
                    <h4><i class="fas fa-wallet" style="color:#22c55e;"></i> Recent Slip Submissions</h4>
                    <a href="admin_payments.php" class="btn-view-all">Audit Center</a>
                </div>
                <ul class="activity-list">
                    <?php
                    $latest_pays = $conn->query("SELECT * FROM payment_submissions ORDER BY id DESC LIMIT 3");
                    if ($latest_pays && $latest_pays->num_rows > 0) {
                        while($p_row = $latest_pays->fetch_assoc()) {
                            $status_lbl = $p_row['status'] ?? 'Pending';
                            $badge_class = strtolower($status_lbl);
                            ?>
                            <li class="activity-item">
                                <div class="item-details">
                                    <h5><?php echo htmlspecialchars($p_row['customer_name'] ?? 'N/A'); ?></h5>
                                    <p style="color:#ff3333; font-weight:700;">Rs. <?php echo number_format($p_row['amount'], 2); ?></p>
                                </div>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status_lbl); ?></span>
                            </li>
                            <?php
                        }
                    } else {
                        echo "<li style='color:#94a3b8; font-size:0.85rem; padding: 10px 0;'>No active bank transfer slip proofs found.</li>";
                    }
                    ?>
                </ul>
            </div>

        </div>
    </div>

</body>
</html>