<?php 
include 'db_connect.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_COOKIE['wishlist_token'])) {
    $wishlist_token = bin2hex(random_bytes(8));
    setcookie('wishlist_token', $wishlist_token, time() + (86400 * 30), "/");
} else {
    $wishlist_token = $_COOKIE['wishlist_token'];
}

$msg = "";
$msg_type = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['apply_action']) || isset($_POST['add_selected_cart']) || isset($_POST['add_all_cart']))) {
    $selected_items = isset($_POST['wishlist_items']) ? $_POST['wishlist_items'] : [];
    $action = isset($_POST['wishlist_action']) ? $_POST['wishlist_action'] : '';

    if (isset($_POST['add_all_cart'])) {
        $action = 'add_all';
    } elseif (isset($_POST['add_selected_cart'])) {
        $action = 'add_selected';
    }

    if ($action == 'remove' && !empty($selected_items)) {
        $ids = implode(',', array_map('intval', $selected_items));
        $conn->query("DELETE FROM wishlist WHERE token = '$wishlist_token' AND product_id IN ($ids)");
        $msg = "Selected items removed from your wishlist successfully.";
        $msg_type = "success";
    } elseif ($action == 'add_selected' && !empty($selected_items)) {
        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
        foreach ($selected_items as $pid) {
            $pid = intval($pid);
            if (!in_array($pid, $_SESSION['cart'])) { $_SESSION['cart'][] = $pid; }
        }
        // 🎯 PREMIUM LINK ADDED TO ALERT
        $msg = "Selected items added to cart successfully! <a href='cart.php' style='color: #ff3333; font-weight: 700; text-decoration: underline;'>View Shopping Cart ➔</a>";
        $msg_type = "success";
    } elseif ($action == 'add_all') {
        $all = $conn->query("SELECT product_id FROM wishlist WHERE token = '$wishlist_token'");
        if ($all && $all->num_rows > 0) {
            if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
            while ($r = $all->fetch_assoc()) {
                $pid = intval($r['product_id']);
                if (!in_array($pid, $_SESSION['cart'])) { $_SESSION['cart'][] = $pid; }
            }
            // 🎯 PREMIUM LINK ADDED TO ALERT
            $msg = "All items added to cart successfully! <a href='cart.php' style='color: #ff3333; font-weight: 700; text-decoration: underline;'>View Shopping Cart ➔</a>";
            $msg_type = "success";
        } else {
            $msg = "Your wishlist is empty.";
            $msg_type = "info";
        }
    } else {
        $msg = "No items or actions are selected.";
        $msg_type = "error";
    }
}


if (isset($_GET['remove_id'])) {
    $remove_id = intval($_GET['remove_id']);
    $conn->query("DELETE FROM wishlist WHERE token = '$wishlist_token' AND product_id = $remove_id");
    $msg = "Item removed from wishlist successfully.";
    $msg_type = "success";
}


$sql = "SELECT w.created_at, p.* FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.token = '$wishlist_token' ORDER BY w.id DESC";
$result = $conn->query($sql);
$count = $result ? $result->num_rows : 0;


include 'header.php'; 
?>

<style>
    /* Premium Breadcrumb Styles */
    .bc-link { color: #cbd5e1; text-decoration: none; transition: all 0.3s ease; }
    .bc-link:hover { color: #ff3333; }
    .bc-current { color: #ffffff; font-weight: 500; }

    /* Premium Wishlist Table Styles */
    .wishlist-table-container { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 25px; box-shadow: 0 4px 6px -1px rgba(15,23,42,0.02); margin-bottom: 30px; }
    .wishlist-table { width: 100%; border-collapse: collapse; text-align: left; }
    .wishlist-table th { padding: 15px; border-bottom: 2px solid #e2e8f0; color: #1e293b; font-weight: 600; font-size: 0.95rem; }
    .wishlist-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #475569; vertical-align: middle; font-size: 0.95rem; }
    
    .wishlist-thumb { width: 70px; height: 70px; object-fit: contain; background: #ffffff; border: 1px solid #f1f5f9; border-radius: 8px; padding: 5px; }
    .stock-status { font-weight: 600; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 5px; }
    .stock-in { color: #166534; }

    /* Action Bar Styles */
    .wishlist-actions-bar { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 20px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    .action-left { display: flex; align-items: center; gap: 10px; }
    .action-right { display: flex; align-items: center; gap: 10px; }
    
    .wishlist-select { padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc; outline: none; font-size: 0.9rem; cursor: pointer; color: #475569; font-weight: 500; }
    .btn-apply { background: #1e293b; color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
    .btn-apply:hover { background: #0f172a; }
    
    .btn-secondary-action { background: #ffffff; color: #ff3333; border: 1px solid #ff3333; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
    .btn-secondary-action:hover { background: #ff3333; color: #ffffff; box-shadow: 0 4px 12px rgba(255,51,51,0.15); }

    .btn-view-prod { background: #ff3333; color: #ffffff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-block; font-size: 0.85rem; transition: all 0.3s; text-align: center; }
    .btn-view-prod:hover { background: #d92626; box-shadow: 0 4px 12px rgba(255,51,51,0.2); }

    .btn-remove-single { color: #94a3b8; background: none; border: none; cursor: pointer; font-size: 1.1rem; transition: color 0.2s; }
    .btn-remove-single:hover { color: #ff3333; }

    /* Notifications Alert Styles */
    .wishlist-alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Share Icons Container */
    .wishlist-share { display: flex; align-items: center; gap: 12px; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    .wishlist-share span { font-weight: 600; color: #334155; font-size: 0.95rem; }
    .share-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; color: #ffffff; text-decoration: none; font-size: 0.95rem; transition: opacity 0.2s; }
    .share-btn:hover { opacity: 0.85; }
    .share-fb { background: #1877f2; }
    .share-wa { background: #25d366; }

    /* MOBILE RESPONSIVE LAYOUT */
    @media (max-width: 768px) {
        .wishlist-table thead { display: none; }
        .wishlist-table tr { display: flex; flex-direction: column; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .wishlist-table td { display: flex; justify-content: space-between; align-items: center; padding: 12px 5px; border-bottom: 1px solid #f1f5f9; text-align: right; width: 100%; box-sizing: border-box; }
        .wishlist-table td:last-child { border-bottom: none; }
        .wishlist-table td::before { content: attr(data-label); font-weight: 600; color: #334155; float: left; text-align: left; font-size: 0.9rem; }
        .wishlist-thumb { width: 65px; height: 65px; }
        .wishlist-actions-bar { flex-direction: column; align-items: stretch; }
        .action-left, .action-right { flex-direction: column; align-items: stretch; gap: 10px; }
        .wishlist-select, .btn-apply, .btn-secondary-action { width: 100%; text-align: center; }
    }
</style>

<main style="background: #f8fafc; padding-bottom: 80px;">
    <div class="page-hero" style="background-image: url('images/slider2.jpg');">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1>Wishlist</h1>
            <p><a href='index.php' class='bc-link'>Home</a> / <span class='bc-current'>Wishlist</span></p>
        </div>
    </div>

    <div class="container" style="padding-top: 50px;">
        <?php if (!empty($msg)): ?>
            <div class="wishlist-alert alert-<?php echo $msg_type; ?>">
                <i class="fas <?php echo $msg_type == 'success' ? 'fa-check-circle' : 'fa-info-circle'; ?>"></i>
                <span><?php echo $msg; ?></span>
            </div>
        <?php endif; ?>

        <form action="wishlist.php" method="POST">
            <div class="wishlist-table-container" data-aos="fade-up">
                <?php if ($count > 0): ?>
                    <table class="wishlist-table">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox" id="selectAll"></th>
                                <th width="10%">Image</th>
                                <th width="35%">Product Name</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Date Added</th>
                                <th width="12%">Stock Status</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): 
                                $img = !empty($row['image_primary']) ? $row['image_primary'] : 'slider1.jpg';
                                $price_display = (!empty($row['price']) && $row['price'] > 0) ? 'Rs. ' . number_format($row['price'], 2) : 'Contact Us';
                            ?>
                                <tr>
                                    <td><input type="checkbox" name="wishlist_items[]" value="<?php echo $row['id']; ?>" class="item-checkbox"></td>
                                    <td data-label="Image">
                                        <img src="images/<?php echo $img; ?>" alt="<?php echo $row['name']; ?>" class="wishlist-thumb" onerror="this.src='images/slider1.jpg';">
                                    </td>
                                    <td data-label="Product Name" style="font-weight: 600; color: #1e293b;">
                                        <?php echo $row['name']; ?>
                                    </td>
                                    <td data-label="Unit Price" style="font-weight: 600; color: #475569;">
                                        <?php echo $price_display; ?>
                                    </td>
                                    <td data-label="Date Added">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                    </td>
                                    <td data-label="Stock Status">
                                        <span class="stock-status stock-in"><i class="fas fa-check-circle"></i> In Stock</span>
                                    </td>
                                    <td data-label="Action">
                                        <div style="display: flex; align-items: center; gap: 12px; justify-content: flex-end;">
                                            <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn-view-prod">View</a>
                                            <a href="wishlist.php?remove_id=<?php echo $row['id']; ?>" class="btn-remove-single" title="Remove Item"><i class="fas fa-times"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <div class="wishlist-actions-bar">
                        <div class="action-left">
                            <select name="wishlist_action" class="wishlist-select">
                                <option value="">Bulk Actions</option>
                                <option value="add_selected">Add Selected to Cart</option>
                                <option value="remove">Remove Selected</option>
                            </select>
                            <button type="submit" name="apply_action" class="btn-apply">Apply</button>
                        </div>
                        <div class="action-right">
                            <button type="submit" name="add_selected_cart" class="btn-secondary-action">Add Selected to Cart</button>
                            <button type="submit" name="add_all_cart" class="btn-secondary-action">Add All to Cart</button>
                        </div>
                    </div>

                <?php else: ?>
                    <div style="text-align: center; padding: 40px 10px;">
                        <i class="far fa-heart" style="font-size: 60px; color: #cbd5e1; margin-bottom: 20px;"></i>
                        <h3 style="color: #334155; margin-bottom: 10px;">Your Wishlist is Empty</h3>
                        <p style="color: #64748b; margin-bottom: 20px;">Explore our premium collections to add architectural products to your wishlist.</p>
                        <a href="products.php" class="btn-apply" style="text-decoration: none; display: inline-block;">Browse Products</a>
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($count > 0): ?>
            <div class="wishlist-share" data-aos="fade-up">
                <span>Share Wishlist:</span>
                <a href="https://facebook.com" target="_blank" class="share-btn share-fb"><i class="fab fa-facebook-f"></i></a>
                <a href="https://whatsapp.com" target="_blank" class="share-btn share-wa"><i class="fab fa-whatsapp"></i></a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
</script>

<?php include 'footer.php'; ?>