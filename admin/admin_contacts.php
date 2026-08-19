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

// === BACKEND: MESSAGE DELETE ===
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    if ($conn->query("DELETE FROM contact_submissions WHERE id = $del_id")) {
        $msg = "Message deleted successfully!";
        $msg_type = "success";
    } else {
        $msg = "Failed to delete message.";
        $msg_type = "error";
    }
    echo "<script>setTimeout(function(){ window.location.href='admin_contacts.php'; }, 1000);</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages | House of Aluminium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f5f9; display: flex; min-height: 100vh; }
        
        /* Premium Sidebar Layout */
        .sidebar { width: 260px; background: #0f172a; color: #fff; padding: 30px 20px; flex-shrink: 0; display: flex; flex-direction: column; }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-brand img { width: 42px; height: 42px; border-radius: 8px; object-fit: cover; border: 2px solid #ff3333; }
        .sidebar-brand-text { font-size: 0.95rem; font-weight: 700; color: #ffffff; text-transform: uppercase; line-height: 1.3; letter-spacing: 0.5px; }
        .sidebar-brand-text span { color: #ff3333; display: block; }
        
        .sidebar-menu { list-style: none; }
        .sidebar-menu li { margin-bottom: 10px; }
        .sidebar-menu a { display: flex; align-items: center; gap: 15px; color: #94a3b8; text-decoration: none; padding: 12px 15px; border-radius: 8px; font-weight: 500; transition: all 0.3s; }
        
   
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #ff3333 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(255,51,51,0.25); }
        
        .main-content { flex-grow: 1; padding: 40px; background: #f8fafc; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        
        .panel-box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        .panel-box h2 { font-size: 1.3rem; margin-bottom: 20px; color: #1e293b; }
        
        .table-wrapper { overflow-x: auto; }
        .prod-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
        .prod-table th { background: #f8fafc; padding: 12px; color: #475569; font-weight: 600; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; font-size: 0.78rem; }
        .prod-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: top; }
        
        .msg-text { max-width: 300px; color: #64748b; font-size: 0.88rem; line-height: 1.4; word-wrap: break-word; }
        .btn-delete { color: #ef4444; text-decoration: none; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background: #fee2e2; transition: 0.3s; }
        .btn-delete:hover { background: #ef4444; color: #fff; }
        
        @media (max-width: 768px) { body { flex-direction: column; } .sidebar { width: 100%; } .sidebar-menu { display: flex; flex-wrap: wrap; gap: 8px; } }
    </style>
</head>
<body>

   <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="main-header">
            <h1>Contact Submissions</h1>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="panel-box">
            <h2>Customer Inquiries & Messages</h2>
            <div class="table-wrapper">
                <table class="prod-table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Sender Details</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM contact_submissions ORDER BY id DESC");
                        if ($res && $res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td style="font-size:0.85rem; font-weight:500; white-space:nowrap; color:#64748b;"><?php echo date('Y-m-d h:i A', strtotime($row['created_at'] ?? 'now')); ?></td>
                                    <td>
                                        <div style="font-weight:600; color:#0f172a;"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <div style="font-size:0.82rem; color:#64748b;"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></div>
                                    </td>
                                    <td style="font-weight:500; color:#1e293b;"><?php echo htmlspecialchars($row['subject'] ?? 'No Subject'); ?></td>
                                    <td><div class="msg-text"><?php echo nl2br(htmlspecialchars($row['message'])); ?></div></td>
                                    <td style="white-space: nowrap; vertical-align: middle;">
                                        <a href="admin_contacts.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this message?');"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align: center; color: #64748b;'>No contact messages received yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>