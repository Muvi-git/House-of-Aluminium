<?php
session_start();
include '../db_connect.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);

// === 🎯 PAGINATION BACKEND LOGIC ===
$limit = 10; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$total_res = $conn->query("SELECT COUNT(*) as total FROM orders");
$total_rows = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// === 🎯 FIXED: BACKEND LOGIC TO DELETE CHECKOUT REQUEST RECORD ===
$msg = "";
$msg_type = "";
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    if ($conn->query("DELETE FROM orders WHERE id = $del_id")) {
        $msg = "Checkout request deleted successfully!";
        $msg_type = "success";
    } else {
        $msg = "Failed to delete checkout request.";
        $msg_type = "error";
    }
    echo "<script>setTimeout(function(){ window.location.href='admin_orders.php?page=" . $page . "'; }, 1200);</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Checkout Requests | House of Aluminium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f5f9; display: flex; min-height: 100vh; }
        
        .main-content { flex-grow: 1; padding: 40px; background: #f8fafc; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        .main-header h1 { font-size: 1.6rem; color: #0f172a; font-weight: 800; }

        .panel-box { background: #fff; padding: 30px; border-radius: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        .panel-box h2 { font-size: 1.25rem; margin-bottom: 20px; color: #1e293b; display: flex; align-items: center; gap: 10px; }
        
        .table-wrapper { overflow-x: auto; }
        .orders-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
        .orders-table th { background: #f8fafc; padding: 14px; color: #475569; font-weight: 600; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; }
        .orders-table td { padding: 16px 14px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: top; }
        
        .wa-badge { display: inline-flex; align-items: center; gap: 6px; background: #e8fbf0; color: #15803d; padding: 6px 12px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 0.88rem; border: 1px solid #bbf7d0; }
        .wa-badge:hover { background: #15803d; color: #fff; }
        
        .items-list { list-style: none; padding: 0; }
        .items-list li { font-size: 0.85rem; color: #475569; margin-bottom: 4px; padding-left: 12px; position: relative; }
        .items-list li::before { content: "•"; color: #ff3333; position: absolute; left: 0; font-weight: bold; }

        .badge-status { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .badge-status.pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }

        /* 🎯 FIXED: INJECTED ALERT & DELETE BUTTON PREMIUM STYLES */
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 0.92rem; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .btn-delete { color: #ef4444; text-decoration: none; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background: #fee2e2; transition: 0.2s; }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        /* PAGINATION WINDOW */
        .pagination-wrapper { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 25px; padding-top: 15px; border-top: 1px solid #f1f5f9; }
        .page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 8px; border-radius: 6px; background: #f1f5f9; color: #475569; text-decoration: none; font-weight: 600; font-size: 0.88rem; transition: 0.2s; border: 1px solid #e2e8f0; }
        .page-link:hover { background: #0f172a; color: #fff; border-color: #0f172a; }
        .page-link.active { background: #ff3333 !important; color: #fff !important; border-color: #ff3333 !important; }
        .page-disabled { background: #f8fafc; color: #cbd5e1; border-color: #f1f5f9; cursor: not-allowed; }

        @media (max-width: 768px) { body { flex-direction: column; } .main-content { padding: 20px; } }
    </style>
</head>
<body>


    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="main-header">
            <h1>Checkout Requests</h1>
        </div>

        <!-- 🎯 FIXED: DISPLAY NOTIFICATION MESSAGES -->
        <?php if(!empty($msg)): ?>
            <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="panel-box">
            <h2><i class="fab fa-whatsapp" style="color:#25d366;"></i> Active Digital Orders Log</h2>
            
            <div class="table-wrapper">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th width="8%">Order ID</th>
                            <th width="15%">Customer Account</th>
                            <th width="18%">WhatsApp Contact</th>
                            <th width="32%">Requested Products Line</th>
                            <th width="12%">Total Value</th>
                            <th width="10%">Status</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            
                        $q = "SELECT o.*, u.full_name, u.username FROM orders o 
                              LEFT JOIN users u ON o.user_id = u.id 
                              ORDER BY o.id DESC LIMIT $offset, $limit";
                        $res = $conn->query($q);
                        
                        if ($res && $res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $order_id = intval($row['id']);
                                $cus_name = !empty($row['full_name']) ? $row['full_name'] : $row['username'];
                                
                              
                                $clean_wa = preg_replace('/[^0-9]/', '', $row['whatsapp_number']);
                                if(strlen($clean_wa) == 9 && $clean_wa[0] == '7') { $clean_wa = '94' . $clean_wa; }
                                ?>
                                <tr>
                                    <td style="font-weight: 700; color: #0f172a;">#<?php echo $order_id; ?></td>
                                    <td style="font-weight: 600;"><?php echo htmlspecialchars($cus_name); ?></td>
                                    <td>
                                        <a href="https://wa.me/<?php echo $clean_wa; ?>" target="_blank" class="wa-badge">
                                            <i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($row['whatsapp_number']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <ul class="items-list">
                                            <?php
                                        
                                            $items_q = "SELECT oi.quantity, p.name FROM order_items oi 
                                                        INNER JOIN products p ON oi.product_id = p.id 
                                                        WHERE oi.order_id = $order_id";
                                            $items_res = $conn->query($items_q);
                                            while($item = $items_res->fetch_assoc()) {
                                                echo "<li>" . htmlspecialchars($item['name']) . " <strong>(x" . $item['quantity'] . ")</strong></li>";
                                            }
                                            ?>
                                        </ul>
                                    </td>
                                    <td style="color: #ff3333; font-weight: 700;">Rs. <?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td><span class="badge-status pending"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                    
                                    <!-- 🎯 FIXED: INJECTED DELETE BUTTON UNDER NEW ACTION COLUMN -->
                                    <td style="white-space: nowrap; vertical-align: middle; text-align: center;">
                                        <a href="admin_orders.php?delete_id=<?php echo $order_id; ?>&page=<?php echo $page; ?>" class="btn-delete" onclick="return confirm('Delete this checkout request permanently?');" title="Delete Log"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; color: #94a3b8; padding: 20px;'>No checkout logs registered yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- SLIDING WINDOW PAGINATION BUTTONS -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination-wrapper">
                    <?php if ($page > 1): ?>
                        <a href="admin_orders.php?page=1" class="page-link"><i class="fas fa-angle-double-left"></i></a>
                        <a href="admin_orders.php?page=<?php echo $page - 1; ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
                    <?php else: ?>
                        <span class="page-link page-disabled"><i class="fas fa-angle-double-left"></i></span>
                        <span class="page-link page-disabled"><i class="fas fa-chevron-left"></i></span>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $page - 1);
                    $end = min($total_pages, $page + 1);
                    if ($page == 1) $end = min($total_pages, 3);
                    if ($page == $total_pages) $start = max(1, $total_pages - 2);

                    for ($i = $start; $i <= $end; $i++): ?>
                        <a href="admin_orders.php?page=<?php echo $i; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="admin_orders.php?page=<?php echo $page + 1; ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                        <a href="admin_orders.php?page=<?php echo $total_pages; ?>" class="page-link"><i class="fas fa-angle-double-right"></i></a>
                    <?php else: ?>
                        <span class="page-link page-disabled"><i class="fas fa-chevron-right"></i></span>
                        <span class="page-link page-disabled"><i class="fas fa-angle-double-right"></i></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>