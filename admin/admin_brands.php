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

$edit_mode = false;
$eb = ['id' => '', 'brand_name' => '', 'sub_category_id' => '', 'image_name' => ''];

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['edit_id']);
    $edit_res = $conn->query("SELECT * FROM brands WHERE id = $edit_id");
    if ($edit_res && $edit_res->num_rows > 0) {
        $eb = $edit_res->fetch_assoc();
    }
}


if (isset($_POST['save_brand'])) {
    $brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
    $brand_name = $conn->real_escape_string($_POST['brand_name']);
    $sub_category_id = intval($_POST['sub_category_id']);
    $image_name = $_POST['old_image_name'] ?? '';

    if (!empty($_FILES['image_name']['name'])) {
        $img_file = time() . '_brand_' . $_FILES['image_name']['name'];
        if (move_uploaded_file($_FILES['image_name']['tmp_name'], '../images/' . $img_file)) {
            if (!empty($_POST['old_image_name']) && file_exists('../images/' . $_POST['old_image_name'])) {
                unlink('../images/' . $_POST['old_image_name']);
            }
            $image_name = $img_file;
        }
    }

    if (!empty($brand_name) && $sub_category_id > 0) {
        if ($brand_id > 0) {
            $sql = "UPDATE brands SET brand_name='$brand_name', image_name='$image_name', sub_category_id=$sub_category_id WHERE id=$brand_id";
            $action_text = "updated";
        } else {
            if (empty($image_name)) {
                $msg = "Brand Logo is required!";
                $msg_type = "error";
            } else {
                $sql = "INSERT INTO brands (brand_name, image_name, sub_category_id) VALUES ('$brand_name', '$image_name', $sub_category_id)";
                $action_text = "added";
            }
        }

        if (empty($msg)) {
            if ($conn->query($sql)) {
                $msg = "Brand successfully " . $action_text . "!";
                $msg_type = "success";
               
                echo "<script>setTimeout(function(){ window.location.href='admin_brands.php?page=" . $page . "'; }, 1500);</script>";
            } else {
                $msg = "Database Error: Operation failed.";
                $msg_type = "error";
            }
        }
    } else {
        $msg = "All fields are required!";
        $msg_type = "error";
    }
}

// === BACKEND: DELETE BRAND ===
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    
    $img_res = $conn->query("SELECT image_name FROM brands WHERE id = $del_id");
    if ($img_res && $img_res->num_rows > 0) {
        $img = $img_res->fetch_assoc()['image_name'];
        if(!empty($img) && file_exists('../images/'.$img)) unlink('../images/'.$img);
    }

    $conn->query("DELETE FROM brands WHERE id = $del_id");
    $msg = "Brand successfully removed!";
    $msg_type = "success";

    echo "<script>setTimeout(function(){ window.location.href='admin_brands.php?page=" . $page . "'; }, 1000);</script>";
}

// === 🎯 PAGINATION MATHEMATICAL CONFIG ===
$limit = 10; 
$offset = ($page - 1) * $limit;

$brand_count_res = $conn->query("SELECT COUNT(*) as total FROM brands");
$total_brands_rows = $brand_count_res->fetch_assoc()['total'];
$total_pages = ceil($total_brands_rows / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Brands | House of Aluminium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f5f9; display: flex; min-height: 100vh; }
        
        .main-content { flex-grow: 1; padding: 40px; background: #f8fafc; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .admin-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; align-items: flex-start; }
        .panel-box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        .panel-box h2 { font-size: 1.3rem; margin-bottom: 20px; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.88rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; background: #f8fafc; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: #ff3333; background: #fff; }
        
        .current-img-preview { width: 90px; height: 55px; object-fit: contain; background: #fff; border-radius: 6px; border: 1px solid #cbd5e1; margin-top: 8px; padding: 2px; display: block; }
        
        .btn-submit { width: 100%; background: #ff3333; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: 0.3s; text-transform: uppercase; }
        .btn-submit:hover { background: #d92626; }
        .btn-cancel { display: block; text-align: center; width: 100%; background: #64748b; color: #fff; padding: 10px; border-radius: 6px; font-weight: 600; text-decoration: none; margin-top: 10px; font-size: 0.9rem; }

        .table-wrapper { overflow-x: auto; }
        .prod-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
        .prod-table th { background: #f8fafc; padding: 12px; color: #475569; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
        .prod-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .img-preview { width: 80px; height: 50px; object-fit: contain; background: #fff; border-radius: 6px; border: 1px solid #e2e8f0; padding: 3px; }
        
        .btn-edit { color: #3b82f6; text-decoration: none; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background: #dbeafe; transition: 0.3s; margin-right: 4px; font-size: 0.95rem; }
        .btn-edit:hover { background: #3b82f6; color: #fff; }
        .btn-delete { color: #ef4444; text-decoration: none; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background: #fee2e2; transition: 0.3s; font-size: 0.95rem; }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        /* 🎯 PAGINATION STYLING */
        .pagination-wrapper { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 25px; padding-top: 15px; border-top: 1px solid #f1f5f9; }
        .page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 8px; border-radius: 6px; background: #f1f5f9; color: #475569; text-decoration: none; font-weight: 600; font-size: 0.88rem; transition: 0.2s; border: 1px solid #e2e8f0; }
        .page-link:hover { background: #0f172a; color: #fff; border-color: #0f172a; }
        .page-link.active { background: #ff3333 !important; color: #fff !important; border-color: #ff3333 !important; box-shadow: 0 4px 10px rgba(255, 51, 51, 0.15); }
        .page-disabled { background: #f8fafc; color: #cbd5e1; border-color: #f1f5f9; cursor: not-allowed; }

        @media (max-width: 992px) { .admin-grid { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { body { flex-direction: column; } }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="main-header">
            <h1>Brands Console Center</h1>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="admin-grid">
            <div class="panel-box">
                <h2><?php echo $edit_mode ? 'Edit Brand Info' : 'Add New Brand'; ?></h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <?php if($edit_mode): ?>
                        <input type="hidden" name="brand_id" value="<?php echo $eb['id']; ?>">
                        <input type="hidden" name="old_image_name" value="<?php echo $eb['image_name']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Brand Name *</label>
                        <input type="text" name="brand_name" value="<?php echo htmlspecialchars($eb['brand_name']); ?>" placeholder="e.g. Alumex" required>
                    </div>

                    <div class="form-group">
                        <label>Assign Parent Category *</label>
                        <select name="sub_category_id" required>
                            <option value="">-- Select Category Line --</option>
                            <?php
                            $cat_map_q = "SELECT s.id as sub_id, s.sub_name, c.name as cat_name FROM sub_categories s INNER JOIN categories c ON s.category_id = c.id ORDER BY c.name ASC";
                            $cat_map_res = $conn->query($cat_map_q);
                            while($cm = $cat_map_res->fetch_assoc()) {
                                $sel = ($cm['sub_id'] == $eb['sub_category_id']) ? 'selected' : '';
                                echo "<option value='".$cm['sub_id']."' $sel>".$cm['cat_name']." ➔ ".$cm['sub_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Brand Logo <?php echo $edit_mode ? '(Optional to change)' : '*'; ?></label>
                        <input type="file" name="image_name" accept="image/*" <?php echo $edit_mode ? '' : 'required'; ?>>
                        
                        <?php if($edit_mode && !empty($eb['image_name'])): ?>
                            <label style="margin-top:10px; font-size:0.8rem; color:#64748b;">Current Active Logo:</label>
                            <img src="../images/<?php echo $eb['image_name']; ?>" class="current-img-preview">
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="save_brand" class="btn-submit"><?php echo $edit_mode ? 'Update Brand' : 'Save Brand'; ?></button>
                    
                    <?php if($edit_mode): ?>
                        <a href="admin_brands.php?page=<?php echo $page; ?>" class="btn-cancel">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="panel-box">
                <h2>All Existing Brands</h2>
                <div class="table-wrapper">
                    <table class="prod-table">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Brand Name</th>
                                <th>Category Mapping</th>
                                <th style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                     
                            $brand_query = "SELECT b.*, s.sub_name, c.name as cat_name FROM brands b 
                                           LEFT JOIN sub_categories s ON b.sub_category_id = s.id 
                                           LEFT JOIN categories c ON s.category_id = c.id 
                                           ORDER BY b.id DESC LIMIT $offset, $limit";
                            $brand_res = $conn->query($brand_query);
                            if ($brand_res && $brand_res->num_rows > 0) {
                                while($b_row = $brand_res->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><img src="../images/<?php echo $b_row['image_name']; ?>" class="img-preview"></td>
                                        <td style="font-weight: 600; color: #0f172a; text-transform: uppercase;"><?php echo htmlspecialchars($b_row['brand_name']); ?></td>
                                        <td>
                                            <?php if(!empty($b_row['sub_name'])): ?>
                                                <span style="background: #e2e8f0; padding: 6px 10px; border-radius: 4px; font-size: 0.82rem; font-weight: 500; display: inline-block; line-height: 1.5; margin: 3px 0;">
                                                    <?php echo htmlspecialchars($b_row['cat_name'] . " ➔ " . $b_row['sub_name']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span style="color: #94a3b8; font-style: italic;">Unassigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="white-space: nowrap; vertical-align: middle;">
                                            <a href="admin_brands.php?edit_id=<?php echo $b_row['id']; ?>&page=<?php echo $page; ?>" class="btn-edit" title="Edit Brand"><i class="fas fa-edit"></i></a>
                                            <a href="admin_brands.php?delete_id=<?php echo $b_row['id']; ?>&page=<?php echo $page; ?>" class="btn-delete" onclick="return confirm('Delete this brand?');" title="Delete Brand"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align: center; color: #64748b;'>No brands inside system.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- 🎯 SLIDING WINDOW PAGINATION UI BANNER -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <?php if ($page > 1): ?>
                            <a href="admin_brands.php?page=1" class="page-link" title="First Page"><i class="fas fa-angle-double-left"></i></a>
                            <a href="admin_brands.php?page=<?php echo $page - 1; ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
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
                            <a href="admin_brands.php?page=<?php echo $i; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="admin_brands.php?page=<?php echo $page + 1; ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                            <a href="admin_brands.php?page=<?php echo $total_pages; ?>" class="page-link" title="Last Page"><i class="fas fa-angle-double-right"></i></a>
                        <?php else: ?>
                            <span class="page-link page-disabled"><i class="fas fa-chevron-right"></i></span>
                            <span class="page-link page-disabled"><i class="fas fa-angle-double-right"></i></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>