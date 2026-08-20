<?php
session_start();
include '../db_connect.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$msg = "";
$msg_type = "";
$current_page = basename($_SERVER['PHP_SELF']);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

// === BACKEND FIXED: UPDATE PAYMENT STATUS (APPROVE / REVERT TOGGLE) ===
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action === 'approve') {
        $sql = "UPDATE payment_submissions SET status = 'Approved' WHERE id = $id";
        $msg = "Payment request approved successfully!";
        $msg_type = "success";
    } elseif ($action === 'revert') {
        $sql = "UPDATE payment_submissions SET status = 'Pending' WHERE id = $id";
        $msg = "Payment status reverted back to Pending!";
        $msg_type = "info";
    }

    if (isset($sql) && $conn->query($sql)) {
       
    } else {
        $msg = "Something went wrong. Please try again.";
        $msg_type = "error";
    }
    echo "<script>setTimeout(function(){ window.location.href='admin_payments.php?page=" . $page . "'; }, 1200);</script>";
}

// === 🎯 BACKEND FIXED: DELETE PAYMENT RECORD ===
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    if ($conn->query("DELETE FROM payment_submissions WHERE id = $del_id")) {
        $msg = "Payment record deleted successfully!";
        $msg_type = "success";
    } else {
        $msg = "Failed to delete payment record.";
        $msg_type = "error";
    }
    echo "<script>setTimeout(function(){ window.location.href='admin_payments.php?page=" . $page . "'; }, 1200);</script>";
}

// === PAGINATION MATHEMATICAL CONFIG ===
$limit = 10;
$offset = ($page - 1) * $limit;

$total_res = $conn->query("SELECT COUNT(*) as total FROM payment_submissions");
$total_rows = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bank Transfers | House of Aluminium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f5f9; display: flex; min-height: 100vh; }
        
        .main-content { flex-grow: 1; padding: 40px; background: #f8fafc; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 0.92rem; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .panel-box { background: #fff; padding: 30px; border-radius: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        .panel-box h2 { font-size: 1.25rem; margin-bottom: 20px; color: #1e293b; display: flex; align-items: center; gap: 10px; }
        
        .table-wrapper { overflow-x: auto; }
        .orders-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
        .orders-table th { background: #f8fafc; padding: 14px; color: #475569; font-weight: 600; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; }
        .orders-table td { padding: 16px 14px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        
        .slip-thumb-container { width: 65px; height: 50px; background: #f1f5f9; border-radius: 6px; border: 1px solid #cbd5e1; overflow: hidden; padding: 2px; cursor: pointer; transition: 0.2s; position: relative; }
        .slip-thumb-container:hover { transform: scale(1.05); border-color: #ff3333; }
        .slip-thumb-container img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; }
        .slip-view-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; opacity: 0; transition: 0.2s; }
        .slip-thumb-container:hover .slip-view-overlay { opacity: 1; }

        .badge-status { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .badge-status.pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .badge-status.approved { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

        .btn-approve { display: inline-flex; align-items: center; gap: 6px; color: #15803d; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 6px 12px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.85rem; transition: 0.2s; }
        .btn-approve:hover { background: #15803d; color: #fff; box-shadow: 0 4px 10px rgba(21,128,61,0.15); }
        
        .btn-revert { display: inline-flex; align-items: center; gap: 6px; color: #c2410c; background: #fff7ed; border: 1px solid #fed7aa; padding: 6px 12px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.85rem; transition: 0.2s; }
        .btn-revert:hover { background: #c2410c; color: #fff; box-shadow: 0 4px 10px rgba(194,65,12,0.15); }

        /* 🎯 FIXED: PREMIUM RED DELETE BUTTON STYLING INJECTED */
        .btn-delete { color: #ef4444; text-decoration: none; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background: #fee2e2; transition: 0.2s; }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        /* PREMIUM LIGHTBOX MODAL STYLING */
        .slip-modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(4px); align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
        .slip-modal.show { display: flex; opacity: 1; }
        .slip-modal-content { max-width: 90%; max-height: 85%; border-radius: 14px; background: #fff; padding: 12px; position: relative; transform: scale(0.9); transition: transform 0.3s ease; }
        .slip-modal.show .slip-modal-content { transform: scale(1); }
        .slip-modal-img { max-width: 100%; max-height: 75vh; object-fit: contain; border-radius: 8px; display: block; }
        .close-modal { position: absolute; top: -45px; right: 0; color: #ffffff; font-size: 32px; font-weight: bold; cursor: pointer; background: none; border: none; transition: 0.2s; }
        .close-modal:hover { color: #ff3333; }
        .doc-link-btn { display: inline-flex; align-items: center; gap: 8px; background: #0f172a; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; }
        .doc-link-btn:hover { background: #ff3333; }

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
            <h1>Online Bank Transfers</h1>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="panel-box">
            <h2><i class="fas fa-file-invoice-dollar" style="color:#ff3333;"></i> Payment Verification Center</h2>
            
            <div class="table-wrapper">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th width="10%">Transfer Slip</th>
                            <th width="25%">Customer Info</th>
                            <th width="20%">Ref / Order No</th>
                            <th width="15%">Amount Paid</th>
                            <th width="15%">Status</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = "SELECT * FROM payment_submissions ORDER BY id DESC LIMIT $offset, $limit";
                        $res = $conn->query($q);
                        
                        if ($res && $res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $slip = $row['payment_slip'] ?? '';
                                $is_image = true;
                                
                                if (!empty($slip)) {
                                    $ext = strtolower(pathinfo($slip, PATHINFO_EXTENSION));
                                    if (in_array($ext, ['pdf', 'doc', 'docx'])) {
                                        $is_image = false;
                                    }
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($slip)): ?>
                                            <?php if ($is_image): ?>
                                                <div class="slip-thumb-container" onclick="openSlipModal('../images/<?php echo $slip; ?>', true)">
                                                    <img src="../images/<?php echo $slip; ?>" onerror="this.src='../images/slider1.jpg';">
                                                    <div class="slip-view-overlay"><i class="fas fa-eye"></i> View</div>
                                                </div>
                                            <?php else: ?>
                                                <div class="slip-thumb-container" onclick="openSlipModal('../images/<?php echo $slip; ?>', false)" style="display:flex; align-items:center; justify-content:center; color:#ff3333; font-size:1.3rem;">
                                                    <i class="fas fa-file-pdf"></i>
                                                    <div class="slip-view-overlay"><i class="fas fa-external-link-alt"></i> Open</div>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span style="color:#94a3b8; font-style:italic; font-size:0.85rem;">No Slip</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <div style="font-weight:700; color:#0f172a;"><?php echo htmlspecialchars($row['customer_name'] ?? 'N/A'); ?></div>
                                        <div style="font-size:0.82rem; color:#64748b; margin-top:2px;"><i class="fas fa-phone-alt" style="font-size:10px;"></i> <?php echo htmlspecialchars($row['contact_number'] ?? 'N/A'); ?></div>
                                        <div style="font-size:0.82rem; color:#64748b;"><i class="fas fa-envelope" style="font-size:10px;"></i> <?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></div>
                                    </td>
                                    
                                    <td style="font-weight:600; color:#475569;"><?php echo !empty($row['reference']) ? htmlspecialchars($row['reference']) : 'N/A'; ?></td>
                                    <td style="color:#ff3333; font-weight:800; font-size:0.95rem;">Rs. <?php echo number_format($row['amount'], 2); ?></td>
                                    
                                    <td>
                                        <?php 
                                        $status = strtolower($row['status'] ?? 'pending'); 
                                        echo "<span class='badge-status $status'>".ucfirst($status)."</span>";
                                        ?>
                                    </td>
                                    
                                    <!-- 🎯 FIXED: INJECTED DISPLAY FLEX WRAPPER AND A CLEAN RED DELETE BUTTON -->
                                    <td style="white-space: nowrap; vertical-align: middle;">
                                        <div style="display: inline-flex; gap: 8px; align-items: center;">
                                            <?php if ($status === 'pending'): ?>
                                                <a href="admin_payments.php?action=approve&id=<?php echo $row['id']; ?>&page=<?php echo $page; ?>" class="btn-approve" onclick="return confirm('Approve this bank transfer transaction?');"><i class="fas fa-check"></i> Approve</a>
                                            <?php else: ?>
                                                <a href="admin_payments.php?action=revert&id=<?php echo $row['id']; ?>&page=<?php echo $page; ?>" class="btn-revert" onclick="return confirm('Revert this transaction status back to Pending?');"><i class="fas fa-undo"></i> Revert</a>
                                            <?php endif; ?>
                                            
                                            <a href="admin_payments.php?delete_id=<?php echo $row['id']; ?>&page=<?php echo $page; ?>" class="btn-delete" onclick="return confirm('Delete this payment record permanently?');" title="Delete Submission"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center; color: #94a3b8; padding: 20px;'>No online bank transfer requests registered.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- SLIDING WINDOW PAGINATION BUTTONS -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination-wrapper">
                    <?php if ($page > 1): ?>
                        <a href="admin_payments.php?page=1" class="page-link"><i class="fas fa-angle-double-left"></i></a>
                        <a href="admin_payments.php?page=<?php echo $page - 1; ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
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
                        <a href="admin_payments.php?page=<?php echo $i; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="admin_payments.php?page=<?php echo $page + 1; ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                        <a href="admin_payments.php?page=<?php echo $total_pages; ?>" class="page-link"><i class="fas fa-angle-double-right"></i></a>
                    <?php else: ?>
                        <span class="page-link page-disabled"><i class="fas fa-chevron-right"></i></span>
                        <span class="page-link page-disabled"><i class="fas fa-angle-double-right"></i></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- PREMIUM LIGHTBOX MODAL -->
    <div id="slipLightbox" class="slip-modal" onclick="closeSlipModal()">
        <div class="slip-modal-content" onclick="event.stopPropagation()">
            <button class="close-modal" onclick="closeSlipModal()">&times;</button>
            <div id="modalInnerContent"></div>
        </div>
    </div>

    <script>
        function openSlipModal(src, isImage) {
            const modal = document.getElementById('slipLightbox');
            const container = document.getElementById('modalInnerContent');
            container.innerHTML = '';
            
            if (isImage) {
                container.innerHTML = `<img src="${src}" class="slip-modal-img" alt="Bank Transfer Deposit Slip">`;
            } else {
                container.innerHTML = `
                    <div style="text-align:center; padding:30px 10px;">
                        <i class="fas fa-file-pdf" style="font-size:60px; color:#ff3333; margin-bottom:20px;"></i>
                        <h4 style="color:#0f172a; margin-bottom:20px;">Document Sheet Payment Slip File</h4>
                        <a href="${src}" target="_blank" class="doc-link-btn"><i class="fas fa-external-link-alt"></i> Open Document File</a>
                    </div>
                `;
            }
            
            modal.style.display = 'flex';
            setTimeout(() => { modal.classList.add('show'); }, 10);
        }

        function closeSlipModal() {
            const modal = document.getElementById('slipLightbox');
            modal.classList.remove('show');
            setTimeout(() => { modal.style.display = 'none'; }, 300);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeSlipModal(); }
        });
    </script>
</body>
</html>