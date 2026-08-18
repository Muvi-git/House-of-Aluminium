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
$ec = ['id' => '', 'name' => '', 'image_name' => ''];

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['edit_id']);
    $edit_res = $conn->query("SELECT * FROM categories WHERE id = $edit_id");
    if ($edit_res && $edit_res->num_rows > 0) {
        $ec = $edit_res->fetch_assoc();
    }
}

// === BACKEND: EDIT SUB CATEGORY CHECK ===
$edit_sub_mode = false;
$es = ['id' => '', 'category_id' => '', 'sub_name' => '', 'image_name' => ''];

if (isset($_GET['edit_sub_id'])) {
    $edit_sub_mode = true;
    $edit_sub_id = intval($_GET['edit_sub_id']);
    $edit_sub_res = $conn->query("SELECT * FROM sub_categories WHERE id = $edit_sub_id");
    if ($edit_sub_res && $edit_sub_res->num_rows > 0) {
        $es = $edit_sub_res->fetch_assoc();
    }
}

// === BACKEND: ADD OR UPDATE CATEGORY ===
if (isset($_POST['save_category'])) {
    $cat_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $name = $conn->real_escape_string($_POST['name']);
    $image_name = $_POST['old_image_name'] ?? '';


    if (!empty($_FILES['image_name']['name'])) {
        $img_file = time() . '_cat_' . $_FILES['image_name']['name'];
        if (move_uploaded_file($_FILES['image_name']['tmp_name'], '../images/' . $img_file)) {
            if (!empty($_POST['old_image_name']) && file_exists('../images/' . $_POST['old_image_name'])) {
                unlink('../images/' . $_POST['old_image_name']);
            }
            $image_name = $img_file;
        }
    }

    if (!empty($name)) {
        if ($cat_id > 0) {
            $sql = "UPDATE categories SET name='$name', image_name='$image_name' WHERE id=$cat_id";
            $action_text = "updated";
        } else {
            if (empty($image_name)) {
                $msg = "Category Image is required!";
                $msg_type = "error";
            } else {
                $sql = "INSERT INTO categories (name, image_name) VALUES ('$name', '$image_name')";
                $action_text = "added";
            }
        }

        if (empty($msg)) {
            if ($conn->query($sql)) {
                $msg = "Category successfully " . $action_text . "!";
                $msg_type = "success";
        
                echo "<script>setTimeout(function(){ window.location.href='admin_categories.php?page=" . $page . "'; }, 1500);</script>";
            } else {
                $msg = "Database Error: Operation failed.";
                $msg_type = "error";
            }
        }
    } else {
        $msg = "Category Name is required!";
        $msg_type = "error";
    }
}

// === BACKEND: ADD OR UPDATE SUB CATEGORY ===
if (isset($_POST['save_sub_category'])) {
    $sub_id = isset($_POST['sub_category_id']) ? intval($_POST['sub_category_id']) : 0;
    $parent_cat_id = intval($_POST['category_id']);
    $sub_name = $conn->real_escape_string($_POST['sub_name']);
    $image_name = $_POST['old_sub_image_name'] ?? '';

    if (!empty($_FILES['sub_image_name']['name'])) {
        $img_file = time() . '_sub_' . $_FILES['sub_image_name']['name'];
        if (move_uploaded_file($_FILES['sub_image_name']['tmp_name'], '../images/' . $img_file)) {
            if (!empty($_POST['old_sub_image_name']) && file_exists('../images/' . $_POST['old_sub_image_name'])) {
                unlink('../images/' . $_POST['old_sub_image_name']);
            }
            $image_name = $img_file;
        }
    }

    if (!empty($sub_name) && $parent_cat_id > 0) {
        if ($sub_id > 0) {
            $sql = "UPDATE sub_categories SET category_id=$parent_cat_id, sub_name='$sub_name', image_name='$image_name' WHERE id=$sub_id";
            $action_text = "updated";
        } else {
            $sql = "INSERT INTO sub_categories (category_id, sub_name, image_name) VALUES ($parent_cat_id, '$sub_name', '$image_name')";
            $action_text = "added";
        }

        if ($conn->query($sql)) {
            $msg = "Sub Category successfully " . $action_text . "!";
            $msg_type = "success";
            echo "<script>setTimeout(function(){ window.location.href='admin_categories.php?page=" . $page . "'; }, 1500);</script>";
        } else {
            $msg = "Database Error: Operation failed.";
            $msg_type = "error";
        }
    } else {
        $msg = "Parent Category and Sub Category Name are required!";
        $msg_type = "error";
    }
}

// === BACKEND: DELETE CATEGORY ===
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    
    $img_res = $conn->query("SELECT image_name FROM categories WHERE id = $del_id");
    if ($img_res && $img_res->num_rows > 0) {
        $img = $img_res->fetch_assoc()['image_name'];
        if(!empty($img) && file_exists('../images/'.$img)) unlink('../images/'.$img);
    }

    $conn->query("DELETE FROM categories WHERE id = $del_id");
    $msg = "Category completely removed!";
    $msg_type = "success";
    echo "<script>setTimeout(function(){ window.location.href='admin_categories.php?page=" . $page . "'; }, 1000);</script>";
}

// === BACKEND: DELETE SUB CATEGORY ===
if (isset($_GET['delete_sub_id'])) {
    $del_sub_id = intval($_GET['delete_sub_id']);
    
    $img_res = $conn->query("SELECT image_name FROM sub_categories WHERE id = $del_sub_id");
    if ($img_res && $img_res->num_rows > 0) {
        $img = $img_res->fetch_assoc()['image_name'];
        if(!empty($img) && file_exists('../images/'.$img)) unlink('../images/'.$img);
    }

    $conn->query("DELETE FROM sub_categories WHERE id = $del_sub_id");
    $msg = "Sub Category completely removed!";
    $msg_type = "success";
    echo "<script>setTimeout(function(){ window.location.href='admin_categories.php?page=" . $page . "'; }, 1000);</script>";
}

// === 🎯 MAIN CATEGORIES SEARCH & PAGINATION ===
$cat_search = isset($_GET['cat_search']) ? trim($conn->real_escape_string($_GET['cat_search'])) : '';
$cat_where = "1=1";
if (!empty($cat_search)) {
    $cat_where = "name LIKE '%$cat_search%'";
}
$cat_search_param = !empty($cat_search) ? "&cat_search=" . urlencode($cat_search) : "";

$limit = 10; 
$offset = ($page - 1) * $limit;

$cat_count_res = $conn->query("SELECT COUNT(*) as total FROM categories WHERE $cat_where");
$total_cats_rows = $cat_count_res ? $cat_count_res->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_cats_rows / $limit);


// === 🎯 SUB CATEGORIES SEARCH & PAGINATION ===
$sub_page = isset($_GET['sub_page']) ? intval($_GET['sub_page']) : 1;
if ($sub_page < 1) $sub_page = 1;

$sub_search = isset($_GET['sub_search']) ? trim($conn->real_escape_string($_GET['sub_search'])) : '';
$sub_where = "1=1";
if (!empty($sub_search)) {
    $sub_where = "(s.sub_name LIKE '%$sub_search%' OR c.name LIKE '%$sub_search%')";
}
$sub_search_param = !empty($sub_search) ? "&sub_search=" . urlencode($sub_search) : "";

$sub_limit = 10;
$sub_offset = ($sub_page - 1) * $sub_limit;

$sub_count_res = $conn->query("SELECT COUNT(*) as total FROM sub_categories s LEFT JOIN categories c ON s.category_id = c.id WHERE $sub_where");
$total_sub_rows = $sub_count_res ? $sub_count_res->fetch_assoc()['total'] : 0;
$total_sub_pages = ceil($total_sub_rows / $sub_limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | House of Aluminium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f5f9; display: flex; min-height: 100vh; }
        
        .main-content { flex-grow: 1; padding: 40px; background: #f8fafc; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .admin-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; align-items: flex-start; margin-bottom: 40px; }
        .panel-box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        .panel-box h2 { font-size: 1.3rem; margin-bottom: 20px; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.88rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; background: #f8fafc; outline: none; }
        
        .current-img-preview { width: 70px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; margin-top: 8px; display: block; }
        
        .btn-submit { width: 100%; background: #ff3333; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: 0.3s; text-transform: uppercase; }
        .btn-submit:hover { background: #d92626; }
        .btn-cancel { display: block; text-align: center; width: 100%; background: #64748b; color: #fff; padding: 10px; border-radius: 6px; font-weight: 600; text-decoration: none; margin-top: 10px; font-size: 0.9rem; }

        .table-wrapper { overflow-x: auto; }
        .prod-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
        .prod-table th { background: #f8fafc; padding: 12px; color: #475569; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
        .prod-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .img-preview { width: 60px; height: 50px; object-fit: cover; background: #f1f5f9; border-radius: 6px; border: 1px solid #e2e8f0; }
        
        .btn-edit { color: #3b82f6; text-decoration: none; font-weight: 600; padding: 6px 10px; border-radius: 4px; background: #dbeafe; transition: 0.3s; margin-right: 5px; }
        .btn-edit:hover { background: #3b82f6; color: #fff; }
        .btn-delete { color: #ef4444; text-decoration: none; font-weight: 600; padding: 6px 10px; border-radius: 4px; background: #fee2e2; transition: 0.3s; }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        /* PAGINATION STYLING */
        .pagination-wrapper { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 25px; padding-top: 15px; border-top: 1px solid #f1f5f9; }
        .page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 8px; border-radius: 6px; background: #f1f5f9; color: #475569; text-decoration: none; font-weight: 600; font-size: 0.88rem; transition: 0.2s; border: 1px solid #e2e8f0; }
        .page-link:hover { background: #0f172a; color: #fff; border-color: #0f172a; }
        .page-link.active { background: #ff3333 !important; color: #fff !important; border-color: #ff3333 !important; box-shadow: 0 4px 10px rgba(255, 51, 51, 0.15); }
        .page-disabled { background: #f8fafc; color: #cbd5e1; border-color: #f1f5f9; cursor: not-allowed; }

        @media (max-width: 992px) { .admin-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="main-header">
            <h1>Categories Console</h1>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <!-- ================= MAIN CATEGORIES SECTION ================= -->
        <div class="admin-grid">
            <div class="panel-box">
                <h2><?php echo $edit_mode ? 'Edit Main Category' : 'Add New Main Category'; ?></h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <?php if($edit_mode): ?>
                        <input type="hidden" name="category_id" value="<?php echo $ec['id']; ?>">
                        <input type="hidden" name="old_image_name" value="<?php echo $ec['image_name']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Category Name *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($ec['name']); ?>" placeholder="e.g. Architectural Hardware" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Category Image <?php echo $edit_mode ? '(Optional to change)' : '*'; ?></label>
                        <input type="file" name="image_name" accept="image/*" <?php echo $edit_mode ? '' : 'required'; ?>>
                        
                        <?php if($edit_mode && !empty($ec['image_name'])): ?>
                            <label style="margin-top:10px; font-size:0.8rem; color:#64748b;">Current Active Image:</label>
                            <img src="../images/<?php echo $ec['image_name']; ?>" class="current-img-preview">
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="save_category" class="btn-submit"><?php echo $edit_mode ? 'Update Changes' : 'Save Main Category'; ?></button>
                    
                    <?php if($edit_mode): ?>
                        <a href="admin_categories.php?page=<?php echo $page; ?>" class="btn-cancel">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="panel-box">
                <!-- 🎯 FIXED: SEARCH BAR INJECTED FOR MAIN CATEGORIES -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; flex-wrap: wrap; gap: 12px;">
                    <h2 style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">All Main Categories</h2>
                    <form action="admin_categories.php" method="GET" style="display: flex; gap: 8px; align-items: center;">
                        <input type="text" name="cat_search" value="<?php echo htmlspecialchars($cat_search); ?>" placeholder="Search category..." style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none; background: #f8fafc;">
                        <button type="submit" style="background: #0f172a; color: #fff; border: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.88rem; transition: 0.2s;"><i class="fas fa-search"></i></button>
                        <?php if (!empty($cat_search)): ?>
                            <a href="admin_categories.php" style="background: #fee2e2; color: #ef4444; padding: 7px 12px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.88rem;" title="Clear Search">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-wrapper">
                    <table class="prod-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cat_res = $conn->query("SELECT * FROM categories WHERE $cat_where ORDER BY id DESC LIMIT $offset, $limit");
                            if ($cat_res && $cat_res->num_rows > 0) {
                                while($c_row = $cat_res->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><img src="../images/<?php echo $c_row['image_name']; ?>" class="img-preview"></td>
                                        <td style="font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($c_row['name']); ?></td>
                                        <td style="white-space: nowrap;">
                                            <a href="admin_categories.php?edit_id=<?php echo $c_row['id']; ?>&page=<?php echo $page; ?><?php echo $cat_search_param; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                            <a href="admin_categories.php?delete_id=<?php echo $c_row['id']; ?>&page=<?php echo $page; ?><?php echo $cat_search_param; ?>" class="btn-delete" onclick="return confirm('Delete this category? All related items may lose parent mapping.');"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='3' style='text-align: center; color: #64748b;'>No categories available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- SLIDING WINDOW PAGINATION UI BANNER -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <?php if ($page > 1): ?>
                            <a href="admin_categories.php?page=1<?php echo $cat_search_param; ?>" class="page-link" title="First Page"><i class="fas fa-angle-double-left"></i></a>
                            <a href="admin_categories.php?page=<?php echo ($page - 1) . $cat_search_param; ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
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
                            <a href="admin_categories.php?page=<?php echo $i . $cat_search_param; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="admin_categories.php?page=<?php echo ($page + 1) . $cat_search_param; ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                            <a href="admin_categories.php?page=<?php echo $total_pages . $cat_search_param; ?>" class="page-link" title="Last Page"><i class="fas fa-angle-double-right"></i></a>
                        <?php else: ?>
                            <span class="page-link page-disabled"><i class="fas fa-chevron-right"></i></span>
                            <span class="page-link page-disabled"><i class="fas fa-angle-double-right"></i></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- ================= SUB CATEGORIES SECTION ================= -->
        <div class="admin-grid" id="subCategorySection">
            <div class="panel-box">
                <h2><?php echo $edit_sub_mode ? 'Edit Sub Category' : 'Add New Sub Category'; ?></h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <?php if($edit_sub_mode): ?>
                        <input type="hidden" name="sub_category_id" value="<?php echo $es['id']; ?>">
                        <input type="hidden" name="old_sub_image_name" value="<?php echo $es['image_name']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Select Parent Main Category *</label>
                        <select name="category_id" required>
                            <option value="">-- Choose Parent Category --</option>
                            <?php
                            $parent_cats = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
                            while($pc = $parent_cats->fetch_assoc()) {
                                $selected = ($pc['id'] == $es['category_id']) ? 'selected' : '';
                                echo "<option value='".$pc['id']."' $selected>".htmlspecialchars($pc['name'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Sub Category Name *</label>
                        <input type="text" name="sub_name" value="<?php echo htmlspecialchars($es['sub_name']); ?>" placeholder="e.g. Lanka Aluminium / Swisstek Aluminium" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Sub Category Banner Image <?php echo $edit_sub_mode ? '(Optional to change)' : '(Optional)'; ?></label>
                        <input type="file" name="sub_image_name" accept="image/*">
                        
                        <?php if($edit_sub_mode && !empty($es['image_name'])): ?>
                            <label style="margin-top:10px; font-size:0.8rem; color:#64748b;">Current Active Image:</label>
                            <img src="../images/<?php echo $es['image_name']; ?>" class="current-img-preview">
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="save_sub_category" class="btn-submit"><?php echo $edit_sub_mode ? 'Update Changes' : 'Save Sub Category'; ?></button>
                    
                    <?php if($edit_sub_mode): ?>
                        <a href="admin_categories.php?page=<?php echo $page; ?>#subCategorySection" class="btn-cancel">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="panel-box">
    
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; flex-wrap: wrap; gap: 12px;">
                    <h2 style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">All Sub Categories</h2>
                    <form action="admin_categories.php" method="GET" style="display: flex; gap: 8px; align-items: center;">
                        <?php if ($page > 1): ?><input type="hidden" name="page" value="<?php echo $page; ?>"><?php endif; ?>
                        <input type="text" name="sub_search" value="<?php echo htmlspecialchars($sub_search); ?>" placeholder="Search sub category..." style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none; background: #f8fafc;">
                        <button type="submit" style="background: #0f172a; color: #fff; border: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.88rem; transition: 0.2s;"><i class="fas fa-search"></i></button>
                        <?php if (!empty($sub_search)): ?>
                            <a href="admin_categories.php#subCategorySection" style="background: #fee2e2; color: #ef4444; padding: 7px 12px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.88rem;" title="Clear Search">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-wrapper">
                    <table class="prod-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Sub Category Name</th>
                                <th>Parent Main Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
        
                            $sub_cat_res = $conn->query("SELECT s.*, c.name as cat_name FROM sub_categories s LEFT JOIN categories c ON s.category_id = c.id WHERE $sub_where ORDER BY s.id DESC LIMIT $sub_offset, $sub_limit");
                            if ($sub_cat_res && $sub_cat_res->num_rows > 0) {
                                while($s_row = $sub_cat_res->fetch_assoc()) {
                                    $s_img = !empty($s_row['image_name']) ? $s_row['image_name'] : 'slider1.jpg';
                                    ?>
                                    <tr>
                                        <td><img src="../images/<?php echo $s_img; ?>" class="img-preview"></td>
                                        <td style="font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($s_row['sub_name']); ?></td>
                                        <td><span style="background: #e2e8f0; padding: 3px 8px; border-radius: 4px; font-size: 0.82rem; font-weight: 500;"><?php echo htmlspecialchars($s_row['cat_name'] ?? 'Unassigned'); ?></span></td>
                                        <td style="white-space: nowrap;">
                                            <a href="admin_categories.php?edit_sub_id=<?php echo $s_row['id']; ?>&page=<?php echo $page; ?>&sub_page=<?php echo $sub_page; ?><?php echo $sub_search_param; ?>#subCategorySection" class="btn-edit"><i class="fas fa-edit"></i></a>
                                            <a href="admin_categories.php?delete_sub_id=<?php echo $s_row['id']; ?>&page=<?php echo $page; ?>&sub_page=<?php echo $sub_page; ?><?php echo $sub_search_param; ?>" class="btn-delete" onclick="return confirm('Delete this sub category permanently?');"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align: center; color: #64748b; padding: 20px;'>No sub categories found matching your search.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

          
                <?php if ($total_sub_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <?php if ($sub_page > 1): ?>
                            <a href="admin_categories.php?sub_page=1<?php echo $sub_search_param; ?>#subCategorySection" class="page-link" title="First Page"><i class="fas fa-angle-double-left"></i></a>
                            <a href="admin_categories.php?sub_page=<?php echo ($sub_page - 1) . $sub_search_param; ?>#subCategorySection" class="page-link"><i class="fas fa-chevron-left"></i></a>
                        <?php else: ?>
                            <span class="page-link page-disabled"><i class="fas fa-angle-double-left"></i></span>
                            <span class="page-link page-disabled"><i class="fas fa-chevron-left"></i></span>
                        <?php endif; ?>

                        <?php
                        $sub_start = max(1, $sub_page - 1);
                        $sub_end = min($total_sub_pages, $sub_page + 1);
                        if ($sub_page == 1) $sub_end = min($total_sub_pages, 3);
                        if ($sub_page == $total_sub_pages) $sub_start = max(1, $total_sub_pages - 2);

                        for ($i = $sub_start; $i <= $sub_end; $i++): ?>
                            <a href="admin_categories.php?sub_page=<?php echo $i . $sub_search_param; ?>#subCategorySection" class="page-link <?php echo ($i == $sub_page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if ($sub_page < $total_sub_pages): ?>
                            <a href="admin_categories.php?sub_page=<?php echo ($sub_page + 1) . $sub_search_param; ?>#subCategorySection" class="page-link"><i class="fas fa-chevron-right"></i></a>
                            <a href="admin_categories.php?sub_page=<?php echo $total_sub_pages . $sub_search_param; ?>#subCategorySection" class="page-link" title="Last Page"><i class="fas fa-angle-double-right"></i></a>
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