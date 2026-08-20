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


if (isset($_GET['delete_gal_id']) && isset($_GET['edit_id'])) {
    $gal_id = intval($_GET['delete_gal_id']);
    $edit_id = intval($_GET['edit_id']);
    
    $gal_res = $conn->query("SELECT image_name FROM product_gallery WHERE id = $gal_id");
    if ($gal_res && $gal_res->num_rows > 0) {
        $gal_file = $gal_res->fetch_assoc()['image_name'];
        if (!empty($gal_file) && file_exists('../images/' . $gal_file)) {
            unlink('../images/' . $gal_file);
        }
        $conn->query("DELETE FROM product_gallery WHERE id = $gal_id");
        $msg = "Gallery image removed successfully from server!";
        $msg_type = "success";
    }
    header("Location: admin_products.php?edit_id=" . $edit_id . "&page=" . $page);
    exit();
}


$edit_mode = false;
$ep = ['id' => '', 'name' => '', 'category_id' => '', 'brand_id' => '', 'price' => '', 'short_desc' => '', 'description' => '', 'image_primary' => '', 'image_secondary' => ''];

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['edit_id']);
    $edit_res = $conn->query("SELECT * FROM products WHERE id = $edit_id");
    if ($edit_res && $edit_res->num_rows > 0) {
        $ep = $edit_res->fetch_assoc();
    }
}


if (isset($_POST['save_product'])) {
    $prod_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = $conn->real_escape_string($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $brand_id = !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : "NULL";
    
    $price = $conn->real_escape_string($_POST['price']);
    $short_desc = $conn->real_escape_string($_POST['short_desc']);
    $description = $conn->real_escape_string($_POST['description']);
    
    $image_primary = $_POST['old_image_primary'] ?? '';
    $image_secondary = $_POST['old_image_secondary'] ?? '';

    if (!empty($_FILES['image_primary']['name'])) {
        $img1_name = time() . '_' . $_FILES['image_primary']['name'];
        if (move_uploaded_file($_FILES['image_primary']['tmp_name'], '../images/' . $img1_name)) {
            if(!empty($_POST['old_image_primary']) && file_exists('../images/'.$_POST['old_image_primary'])) {
                unlink('../images/'.$_POST['old_image_primary']);
            }
            $image_primary = $img1_name;
        }
    }

    if (!empty($_FILES['image_secondary']['name'])) {
        $img2_name = time() . '_hover_' . $_FILES['image_secondary']['name'];
        if (move_uploaded_file($_FILES['image_secondary']['tmp_name'], '../images/' . $img2_name)) {
            if(!empty($_POST['old_image_secondary']) && file_exists('../images/'.$_POST['old_image_secondary'])) {
                unlink('../images/'.$_POST['old_image_secondary']);
            }
            $image_secondary = $img2_name;
        }
    }

    if (!empty($name)) {
        if ($prod_id > 0) {
            $sql = "UPDATE products SET name='$name', category_id=$category_id, brand_id=$brand_id, price='$price', short_desc='$short_desc', description='$description', image_primary='$image_primary', image_secondary='$image_secondary' WHERE id=$prod_id";
            $conn->query($sql);
            $last_id = $prod_id;
            $action_text = "updated";
        } else {
            $sql = "INSERT INTO products (name, category_id, brand_id, price, short_desc, description, image_primary, image_secondary) VALUES ('$name', $category_id, $brand_id, '$price', '$short_desc', '$description', '$image_primary', '$image_secondary')";
            $conn->query($sql);
            $last_id = $conn->insert_id;
            $action_text = "added";
        }

        if (!empty($_FILES['product_gallery']['name'][0])) {
            foreach ($_FILES['product_gallery']['name'] as $key => $val) {
                if (!empty($_FILES['product_gallery']['name'][$key])) {
                    $gal_file_name = time() . '_gal_' . $_FILES['product_gallery']['name'][$key];
                    if (move_uploaded_file($_FILES['product_gallery']['tmp_name'][$key], '../images/' . $gal_file_name)) {
                        $conn->query("INSERT INTO product_gallery (product_id, image_name) VALUES ($last_id, '$gal_file_name')");
                    }
                }
            }
        }

        $msg = "Product successfully " . $action_text . "!";
        $msg_type = "success";
    
        echo "<script>setTimeout(function(){ window.location.href='admin_products.php?page=" . $page . "'; }, 1500);</script>";
    } else {
        $msg = "Product Name is required!";
        $msg_type = "error";
    }
}


if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    
    $img_res = $conn->query("SELECT image_primary, image_secondary FROM products WHERE id = $del_id");
    if ($img_res && $img_res->num_rows > 0) {
        $imgs = $img_res->fetch_assoc();
        if(!empty($imgs['image_primary']) && file_exists('../images/'.$imgs['image_primary'])) unlink('../images/'.$imgs['image_primary']);
        if(!empty($imgs['image_secondary']) && file_exists('../images/'.$imgs['image_secondary'])) unlink('../images/'.$imgs['image_secondary']);
    }

    $gal_list = $conn->query("SELECT image_name FROM product_gallery WHERE product_id = $del_id");
    while($g = $gal_list->fetch_assoc()) {
        if(!empty($g['image_name']) && file_exists('../images/'.$g['image_name'])) unlink('../images/'.$g['image_name']);
    }
    
    $conn->query("DELETE FROM product_gallery WHERE product_id = $del_id");
    $conn->query("DELETE FROM products WHERE id = $del_id");
    
    $msg = "Product completely removed!";
    $msg_type = "success";
    echo "<script>setTimeout(function(){ window.location.href='admin_products.php?page=" . $page . "'; }, 1000);</script>";
}


$search = isset($_GET['search']) ? trim($conn->real_escape_string($_GET['search'])) : '';
$where_clause = "1=1";
if (!empty($search)) {
    $where_clause = "(p.name LIKE '%$search%' OR p.price LIKE '%$search%' OR c.name LIKE '%$search%')";
}
$search_param = !empty($search) ? "&search=" . urlencode($search) : "";

$limit = 10; 
$offset = ($page - 1) * $limit;

$total_products_res = $conn->query("SELECT COUNT(*) as total FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE $where_clause");
$total_products_rows = $total_products_res ? $total_products_res->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_products_rows / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | House of Aluminium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f5f9; display: flex; min-height: 100vh; }
        
        .main-content { flex-grow: 1; padding: 40px; background: #f8fafc; overflow-y: auto; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .admin-grid { display: grid; grid-template-columns: 1.3fr 2fr; gap: 30px; align-items: flex-start; }
        .panel-box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
        .panel-box h2 { font-size: 1.3rem; margin-bottom: 20px; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.88rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.95rem; background: #f8fafc; outline: none; font-family: inherit; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #ff3333; background: #fff; }
        
        .edit-gallery-wrapper { display: flex; flex-wrap: wrap; gap: 12px; margin: 15px 0; padding: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; }
        .gallery-thumb-box { position: relative; width: 65px; height: 65px; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; overflow: hidden; padding: 2px; }
        .gallery-thumb-box img { width: 100%; height: 100%; object-fit: contain; }
        .gallery-thumb-del { position: absolute; top: 2px; right: 2px; width: 18px; height: 18px; background: rgba(239, 68, 68, 0.9); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; text-decoration: none; font-weight: bold; cursor: pointer; border: none; }
        .gallery-thumb-del:hover { background: #ef4444; transform: scale(1.1); }

        .btn-submit { width: 100%; background: #ff3333; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: 0.3s; text-transform: uppercase; }
        .btn-submit:hover { background: #d92626; }
        .btn-cancel { display: block; text-align: center; width: 100%; background: #64748b; color: #fff; padding: 10px; border-radius: 6px; font-weight: 600; text-decoration: none; margin-top: 10px; font-size: 0.9rem; }

        .table-wrapper { overflow-x: auto; }
        .prod-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
        .prod-table th { background: #f8fafc; padding: 12px; color: #475569; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
        .prod-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .img-preview { width: 45px; height: 45px; object-fit: contain; background: #f1f5f9; border-radius: 6px; border: 1px solid #e2e8f0; }
        
        .btn-edit { color: #3b82f6; text-decoration: none; font-weight: 600; padding: 6px 10px; border-radius: 4px; background: #dbeafe; transition: 0.3s; margin-right: 5px; }
        .btn-edit:hover { background: #3b82f6; color: #fff; }
        .btn-delete { color: #ef4444; text-decoration: none; font-weight: 600; padding: 6px 10px; border-radius: 4px; background: #fee2e2; transition: 0.3s; }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        /* 🎯 PREMIUM SLIDING PAGINATION STYLING: ලේඅවුට් එක කැඩෙන්නේ නැති වෙන්න බටන් 3ක් පමණක් පෙන්වන ස්ටයිල් එක */
        .pagination-wrapper { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 25px; padding-top: 15px; border-top: 1px solid #f1f5f9; }
        .page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 8px; border-radius: 6px; background: #f1f5f9; color: #475569; text-decoration: none; font-weight: 600; font-size: 0.88rem; transition: 0.2s; border: 1px solid #e2e8f0; }
        .page-link:hover { background: #0f172a; color: #fff; border-color: #0f172a; }
        .page-link.active { background: #ff3333 !important; color: #fff !important; border-color: #ff3333 !important; box-shadow: 0 4px 10px rgba(255, 51, 51, 0.15); }
        .page-disabled { background: #f8fafc; color: #cbd5e1; border-color: #f1f5f9; cursor: not-allowed; }

        @media (max-width: 992px) { .admin-grid { grid-template-columns: 1fr; gap: 25px; } .main-content { padding: 20px; } }
        @media (max-width: 768px) { body { flex-direction: column; } .main-header { text-align: center; justify-content: center; } }
    </style>
</head>
<body>

  
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="main-header">
            <h1>Products Center</h1>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="admin-grid">
            
            <div class="panel-box">
                <h2><?php echo $edit_mode ? 'Edit Architectural Product' : 'Add New Product'; ?></h2>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <?php if($edit_mode): ?>
                        <input type="hidden" name="product_id" value="<?php echo $ep['id']; ?>">
                        <input type="hidden" name="old_image_primary" value="<?php echo $ep['image_primary']; ?>">
                        <input type="hidden" name="old_image_secondary" value="<?php echo $ep['image_secondary']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($ep['name']); ?>" placeholder="e.g. Luxury Sliding Partition" required>
                    </div>

                    <div class="form-group">
                        <label>Price / Rate (Optional)</label>
                        <input type="text" name="price" value="<?php echo htmlspecialchars($ep['price']); ?>" placeholder="e.g. LKR 6,200 per sqft">
                    </div>
                    
                    <div class="form-group">
                        <label>Select Category *</label>
                        <select name="category_id" required>
                            <?php
                            $cat_list = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
                            while($c = $cat_list->fetch_assoc()) {
                                $sel = ($c['id'] == $ep['category_id']) ? 'selected' : '';
                                echo "<option value='".$c['id']."' $sel>".$c['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Select Brand (Optional)</label>
                        <select name="brand_id">
                            <option value="">-- No Brand / None --</option>
                            <?php
                            $brand_list = $conn->query("SELECT id, brand_name FROM brands ORDER BY brand_name ASC");
                            while($b = $brand_list->fetch_assoc()) {
                                $sel = ($b['id'] == $ep['brand_id']) ? 'selected' : '';
                                echo "<option value='".$b['id']."' $sel>".$b['brand_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Short Summary</label>
                        <textarea name="short_desc" rows="2" placeholder="Brief tagline..."><?php echo htmlspecialchars($ep['short_desc']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Full Specifications (Description)</label>
                        <textarea name="description" rows="3" placeholder="Detailed architectural details..."><?php echo htmlspecialchars($ep['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Primary Image <?php echo $edit_mode ? '(Leave empty to keep current)' : '*'; ?></label>
                        <input type="file" name="image_primary" accept="image/*" <?php echo $edit_mode ? '' : 'required'; ?>>
                    </div>

                    <div class="form-group">
                        <label>Secondary Image (Hover Swap)</label>
                        <input type="file" name="image_secondary" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Upload Gallery Images (Select Multiple Files)</label>
                        <input type="file" id="galleryInput" name="product_gallery[]" multiple accept="image/*">
                        <div id="liveGalleryPreview" class="edit-gallery-wrapper" style="display:none; margin-top:10px;"></div>
                    </div>

                    <?php if($edit_mode): 
                        $current_id = intval($ep['id']);
                        $gal_fetch = $conn->query("SELECT * FROM product_gallery WHERE product_id = $current_id");
                        if($gal_fetch && $gal_fetch->num_rows > 0): ?>
                            <label style="font-size: 0.85rem; font-weight: 600; color: #475569;">Already Saved Gallery Images (Click × to delete from database):</label>
                            <div class="edit-gallery-wrapper">
                                <?php while($g_row = $gal_fetch->fetch_assoc()): ?>
                                    <div class="gallery-thumb-box">
                                        <img src="../images/<?php echo $g_row['image_name']; ?>" alt="Gallery">
                                   
                                        <a href="admin_products.php?edit_id=<?php echo $current_id; ?>&delete_gal_id=<?php echo $g_row['id']; ?>&page=<?php echo $page; ?>" class="gallery-thumb-del" onclick="return confirm('Remove this image from database permanently?');">×</a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <button type="submit" name="save_product" class="btn-submit"><?php echo $edit_mode ? 'Update Changes' : 'Save Product'; ?></button>
                    
                    <?php if($edit_mode): ?>
                        <a href="admin_products.php?page=<?php echo $page; ?>" class="btn-cancel">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="panel-box">
                <!-- 🎯 FIXED: INJECTED SEARCH BAR HEADER UI -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; flex-wrap: wrap; gap: 12px;">
                    <h2 style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">All Existing Items</h2>
                    <form action="admin_products.php" method="GET" style="display: flex; gap: 8px; align-items: center;">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search product / category..." style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none; background: #f8fafc;">
                        <button type="submit" style="background: #0f172a; color: #fff; border: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.88rem; transition: 0.2s;"><i class="fas fa-search"></i></button>
                        <?php if (!empty($search)): ?>
                            <a href="admin_products.php" style="background: #fee2e2; color: #ef4444; padding: 7px 12px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.88rem;" title="Clear Search">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-wrapper">
                    <table class="prod-table">
                        <thead>
                            <tr>
                                <th>Primary</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $prod_query = "SELECT p.*, c.name as cat_name FROM products p 
                                           LEFT JOIN categories c ON p.category_id = c.id 
                                           WHERE $where_clause
                                           ORDER BY p.id DESC LIMIT $offset, $limit";
                            $prod_res = $conn->query($prod_query);
                            
                            if ($prod_res && $prod_res->num_rows > 0) {
                                while($p_row = $prod_res->fetch_assoc()) {
                                    $p_img = !empty($p_row['image_primary']) ? $p_row['image_primary'] : 'slider1.jpg';
                                    ?>
                                    <tr>
                                        <td><img src="../images/<?php echo $p_img; ?>" class="img-preview"></td>
                                        <td style="font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($p_row['name']); ?></td>
                                        <td style="color: #ff3333; font-weight: 600;"><?php echo !empty($p_row['price']) ? htmlspecialchars($p_row['price']) : 'N/A'; ?></td>
                                        <td><span style="background: #e2e8f0; padding: 3px 8px; border-radius: 4px; font-size: 0.82rem; font-weight: 500;"><?php echo htmlspecialchars($p_row['cat_name'] ?? 'Uncategorized'); ?></span></td>
                                        <td style="white-space: nowrap;">
                                            <!-- 🎯 එඩිට් සහ ඩිලීට් බටන් වලට දැනට පවතින පේජ් නම්බර් එක සහ සර්ච් පැරාම් එක පාස් කිරීම -->
                                            <a href="admin_products.php?edit_id=<?php echo $p_row['id']; ?>&page=<?php echo $page; ?><?php echo $search_param; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                            <a href="admin_products.php?delete_id=<?php echo $p_row['id']; ?>&page=<?php echo $page; ?><?php echo $search_param; ?>" class="btn-delete" onclick="return confirm('Completely delete this product and its gallery?');"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center; color: #64748b; padding: 20px;'>No products found matching your search query.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                   
                        <?php if ($page > 1): ?>
                            <a href="admin_products.php?page=1<?php echo $search_param; ?>" class="page-link" title="First Page"><i class="fas fa-angle-double-left"></i></a>
                            <a href="admin_products.php?page=<?php echo ($page - 1) . $search_param; ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
                        <?php else: ?>
                            <span class="page-link page-disabled"><i class="fas fa-angle-double-left"></i></span>
                            <span class="page-link page-disabled"><i class="fas fa-chevron-left"></i></span>
                        <?php endif; ?>

                     
                        <?php
                        $start = max(1, $page - 1);
                        $end = min($total_pages, $page + 1);
                        
                    
                        if ($page == 1) {
                            $end = min($total_pages, 3);
                        }
                        if ($page == $total_pages) {
                            $start = max(1, $total_pages - 2);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="admin_products.php?page=<?php echo $i . $search_param; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

              
                        <?php if ($page < $total_pages): ?>
                            <a href="admin_products.php?page=<?php echo ($page + 1) . $search_param; ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                            <a href="admin_products.php?page=<?php echo $total_pages . $search_param; ?>" class="page-link" title="Last Page"><i class="fas fa-angle-double-right"></i></a>
                        <?php else: ?>
                            <span class="page-link page-disabled"><i class="fas fa-chevron-right"></i></span>
                            <span class="page-link page-disabled"><i class="fas fa-angle-double-right"></i></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>

<script>
let galleryImagesDT = new DataTransfer();
const galleryInput = document.getElementById('galleryInput');
const previewContainer = document.getElementById('liveGalleryPreview');

galleryInput.addEventListener('change', function(event) {
    const newFiles = event.target.files;
    if (!newFiles || newFiles.length === 0) return;

    for (let i = 0; i < newFiles.length; i++) {
        galleryImagesDT.items.add(newFiles[i]);
    }

    this.files = galleryImagesDT.files;
    renderLiveGalleryPreviews();
});

function renderLiveGalleryPreviews() {
    previewContainer.innerHTML = '';
    const currentFiles = galleryImagesDT.files;

    if (currentFiles.length > 0) {
        previewContainer.style.display = 'flex';
    } else {
        previewContainer.style.display = 'none';
        return;
    }

    Array.from(currentFiles).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgBox = document.createElement('div');
            imgBox.className = 'gallery-thumb-box';

            const img = document.createElement('img');
            img.src = e.target.result;

            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'gallery-thumb-del';
            delBtn.innerHTML = '×';

            delBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const newDT = new DataTransfer();
                for (let i = 0; i < currentFiles.length; i++) {
                    if (i !== index) {
                        newDT.items.add(currentFiles[i]);
                    }
                }
                galleryImagesDT = newDT;
                galleryInput.files = galleryImagesDT.files;
                
                renderLiveGalleryPreviews();
            });

            imgBox.appendChild(img);
            imgBox.appendChild(delBtn);
            previewContainer.appendChild(imgBox);
        }
        reader.readAsDataURL(file);
    });
}
</script>

</body>
</html>