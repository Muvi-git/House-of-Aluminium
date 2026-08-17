<?php 
include 'db_connect.php'; 
include 'header.php'; 


$page_title = "All Products";
$breadcrumb = "<a href='index.php' class='bc-link'>Home</a> / <a href='products.php' class='bc-link'>Products</a>"; 
$where_clause = "1=1"; 
$is_sub_category_view = false; 
$is_brand_view = false; 
$result_subs = null;
$result_brands = null;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$view_mode = isset($_GET['view_mode']) ? $_GET['view_mode'] : 'grid'; 

$order_by = "id DESC"; 
if ($sort == 'price_low') {
    $order_by = "price ASC";
} elseif ($sort == 'price_high') {
    $order_by = "price DESC";
}

$limit = 16; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $limit;

$url_params = "";

if (isset($_GET['cat_id'])) {
    $id = intval($_GET['cat_id']);
    $url_params .= "&cat_id=" . $id;
    
    $sub_check = $conn->query("SELECT * FROM sub_categories WHERE category_id = $id");
    if ($sub_check && $sub_check->num_rows > 0) {
        $is_sub_category_view = true;
        $result_subs = $sub_check;
        $total_rows = $sub_check->num_rows; 
    } else {
        $where_clause = "category_id = $id";
    }
    
    $q = $conn->query("SELECT name FROM categories WHERE id = $id");
    if($r = $q->fetch_assoc()) { 
        $page_title = $r['name']; 
        $breadcrumb = "<a href='index.php' class='bc-link'>Home</a> / <a href='products.php' class='bc-link'>Products</a> / <span class='bc-current'>" . $r['name'] . "</span>";
    }

} elseif (isset($_GET['sub_id'])) {
    $id = intval($_GET['sub_id']);
    $url_params .= "&sub_id=" . $id;
    
    $brand_check = $conn->query("SELECT * FROM brands WHERE sub_category_id = $id");
    if ($brand_check && $brand_check->num_rows > 0) {
        $is_brand_view = true;
        $result_brands = $brand_check;
        $total_rows = $brand_check->num_rows; 
    } else {
        $where_clause = "sub_category_id = $id";
    }
    
    $q = $conn->query("
        SELECT s.sub_name, c.name as cat_name, c.id as cat_id
        FROM sub_categories s 
        JOIN categories c ON s.category_id = c.id 
        WHERE s.id = $id
    ");
    if($r = $q->fetch_assoc()) { 
        $page_title = $r['sub_name']; 
        $breadcrumb = "<a href='index.php' class='bc-link'>Home</a> / <a href='products.php' class='bc-link'>Products</a> / <a href='view_products.php?cat_id=".$r['cat_id']."' class='bc-link'>" . $r['cat_name'] . "</a> / <span class='bc-current'>" . $r['sub_name'] . "</span>";
    }

} elseif (isset($_GET['brand_id'])) {
    $id = intval($_GET['brand_id']);
    $url_params .= "&brand_id=" . $id;
    $where_clause = "brand_id = $id";
    
    $q = $conn->query("
        SELECT b.brand_name, s.sub_name, s.id as sub_id, c.name as cat_name, c.id as cat_id
        FROM brands b 
        JOIN sub_categories s ON b.sub_category_id = s.id 
        JOIN categories c ON s.category_id = c.id 
        WHERE b.id = $id
    ");
    if($r = $q->fetch_assoc()) { 
        $page_title = $r['brand_name']; 
        $breadcrumb = "<a href='index.php' class='bc-link'>Home</a> / <a href='products.php' class='bc-link'>Products</a> / <a href='view_products.php?cat_id=".$r['cat_id']."' class='bc-link'>" . $r['cat_name'] . "</a> / <a href='view_products.php?sub_id=".$r['sub_id']."' class='bc-link'>" . $r['sub_name'] . "</a> / <span class='bc-current'>" . $r['brand_name'] . "</span>";
    }
}

$wishlist_msg = "";
$wishlist_msg_type = "";
if (isset($_GET['add_to_wishlist'])) {
    $add_pid = intval($_GET['add_to_wishlist']);
    
    if (!isset($_COOKIE['wishlist_token'])) {
        $wishlist_token = bin2hex(random_bytes(8));
        setcookie('wishlist_token', $wishlist_token, time() + (86400 * 30), "/");
    } else {
        $wishlist_token = $_COOKIE['wishlist_token'];
    }
    
    $check_wish = $conn->query("SELECT * FROM wishlist WHERE token = '$wishlist_token' AND product_id = $add_pid");
    if ($check_wish && $check_wish->num_rows == 0) {
        $conn->query("INSERT INTO wishlist (token, product_id, created_at) VALUES ('$wishlist_token', $add_pid, NOW())");
        $wishlist_msg = "Product added to wishlist successfully!";
        $wishlist_msg_type = "success";
    } else {
        $wishlist_msg = "Product is already in your wishlist.";
        $wishlist_msg_type = "info";
    }
}

$total_pages = 0;
if (!$is_sub_category_view && !$is_brand_view) {
    $count_sql = "SELECT COUNT(*) as total FROM products WHERE $where_clause";
    $count_result = $conn->query($count_sql);
    $total_rows = ($count_result) ? $count_result->fetch_assoc()['total'] : 0;
    $total_pages = ceil($total_rows / $limit);

    $sql = "SELECT * FROM products WHERE $where_clause ORDER BY $order_by LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
}

$page_base_url = "view_products.php?sort=" . $sort . "&view_mode=" . $view_mode . $url_params;
?>

<style>
    .bc-link { color: #cbd5e1; text-decoration: none; transition: all 0.3s ease; }
    .bc-link:hover { color: #ff3333; }
    .bc-current { color: #ffffff; font-weight: 500; }

    .product-filter-bar { display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 12px 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; box-shadow: 0 4px 6px -1px rgba(15,23,42,0.02); }
    .sort-select { padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; color: #475569; font-weight: 500; font-size: 0.92rem; outline: none; background: #f8fafc; cursor: pointer; transition: all 0.3s ease; }
    .sort-select:focus { border-color: #ff3333; box-shadow: 0 0 0 3px rgba(255,51,51,0.08); }
    .filter-right-box { display: flex; align-items: center; gap: 20px; }
    .showing-results-txt { color: #64748b; font-size: 0.92rem; font-weight: 500; }
    .view-toggle-btns { display: flex; gap: 6px; background: #f1f5f9; padding: 4px; border-radius: 8px; }
    .toggle-view-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; color: #64748b; text-decoration: none; transition: all 0.2s ease; font-size: 1rem; }
    .toggle-view-btn:hover { color: #ff3333; background: #ffffff; }
    .toggle-view-btn.active { background: #ffffff; color: #ff3333; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    .wishlist-alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }


    .product-img-wrapper { height: 280px; background: #ffffff; padding: 0px !important; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; border-radius: 12px 12px 0 0; }
    .product-img-wrapper img { width: 100% !important; height: 100% !important; object-fit: contain !important; transition: transform 0.5s ease-out !important; }


    .product-card:hover .product-img-wrapper img.primary-img { opacity: 1 !important; transform: scale(1.06) !important; visibility: visible !important; display: block !important; }
    .product-card:hover .product-img-wrapper:not(.has-hover-swap) img { transform: scale(1.06) !important; }

  
    .product-img-wrapper.has-hover-swap img.primary-img { position: relative; z-index: 1; }
    .product-img-wrapper.has-hover-swap img.secondary-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; opacity: 0; z-index: 2; transition: opacity 0.4s ease, transform 0.5s ease-out; }
    
  
    .product-card:hover .product-img-wrapper.has-hover-swap img.secondary-img { opacity: 1 !important; transform: scale(1.06) !important; z-index: 3; }

    .product-grid.list-view-active { display: flex; flex-direction: column; gap: 20px; }
    .product-grid.list-view-active .product-card { display: flex; flex-direction: row; align-items: center; text-align: left; width: 100%; margin: 0; box-shadow: 0 4px 12px rgba(15,23,42,0.03); border: 1px solid #e2e8f0; }
    .product-grid.list-view-active .product-img-wrapper { width: 220px; height: 180px !important; flex-shrink: 0; border-right: 1px solid #f1f5f9; padding: 0; }
    .product-grid.list-view-active .product-info { flex-grow: 1; padding: 25px; text-align: left !important; display: flex; flex-direction: column; align-items: flex-start; border: none; }
    .product-grid.list-view-active .product-title { font-size: 1.15rem; margin-bottom: 8px; white-space: normal !important; overflow: visible; text-overflow: clip; }
    .product-grid.list-view-active .read-more-btn { width: auto; min-width: 130px; margin-top: 10px; }

    @media (max-width: 576px) {
        .product-grid.list-view-active .product-card { flex-direction: column; text-align: center; }
        .product-grid.list-view-active .product-img-wrapper { width: 100%; border-right: none; border-bottom: 1px solid #f1f5f9; }
        .product-grid.list-view-active .product-info { align-items: center; text-align: center !important; }
    }

    .pagination-wrapper { display: flex; justify-content: center; align-items: center; margin-top: 40px; gap: 8px; width: 100%; }
    .pagination-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 6px; border-radius: 8px; background: #ffffff; border: 1px solid #e2e8f0; color: #334155; font-weight: 600; text-decoration: none; transition: all 0.3s ease; font-size: 0.95rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .pagination-btn:hover { border-color: #ff3333; color: #ff3333; background: #fff5f5; transform: translateY(-2px); }
    .pagination-btn.active { background: #ff3333; border-color: #ff3333; color: #ffffff; box-shadow: 0 4px 12px rgba(255,51,51,0.25); }
    .pagination-btn.disabled { background: #f1f5f9; border-color: #e2e8f0; color: #94a3b8; pointer-events: none; }
</style>

<main>
    <div class="page-hero" style="background-image: url('images/slider2.jpg');">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1><?php echo $page_title; ?></h1>
            <p><?php echo $breadcrumb; ?></p>
        </div>
    </div>

    <section class="latest-products-section" style="background: #f8fafc; padding-top: 50px;">
        <div class="container">

            <?php if (!empty($wishlist_msg)): ?>
                <div class="wishlist-alert alert-<?php echo $wishlist_msg_type; ?>" data-aos="fade-up">
                    <i class="fas <?php echo $wishlist_msg_type == 'success' ? 'fa-check-circle' : 'fa-info-circle'; ?>"></i>
                    <?php echo $wishlist_msg; ?>
                </div>
            <?php endif; ?>

            <?php if ($total_rows > 0): ?>
                <div class="product-filter-bar" data-aos="fade-up">
                    <div class="filter-left-box">
                        <?php if ($is_sub_category_view): ?>
                            <select class="sort-select" disabled><option>Select Sub Category</option></select>
                        <?php elseif ($is_brand_view): ?>
                            <select class="sort-select" disabled><option>Select Brand</option></select>
                        <?php else: ?>
                            <select onchange="location = this.value;" class="sort-select">
                                <option value="view_products.php?page=1&view_mode=<?php echo $view_mode . $url_params; ?>&sort=default" <?php echo $sort == 'default' ? 'selected' : ''; ?>>Default sorting</option>
                                <option value="view_products.php?page=1&view_mode=<?php echo $view_mode . $url_params; ?>&sort=popularity" <?php echo $sort == 'popularity' ? 'selected' : ''; ?>>Sort by popularity</option>
                                <option value="view_products.php?page=1&view_mode=<?php echo $view_mode . $url_params; ?>&sort=rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Sort by average rating</option>
                                <option value="view_products.php?page=1&view_mode=<?php echo $view_mode . $url_params; ?>&sort=latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Sort by latest</option>
                                <option value="view_products.php?page=1&view_mode=<?php echo $view_mode . $url_params; ?>&sort=price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Sort by price: low to high</option>
                                <option value="view_products.php?page=1&view_mode=<?php echo $view_mode . $url_params; ?>&sort=price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Sort by price: high to low</option>
                            </select>
                        <?php endif; ?>
                    </div>
                    <div class="filter-right-box">
                        <span class="showing-results-txt">
                            <?php if ($is_sub_category_view): ?>
                                Showing all <?php echo $total_rows; ?> sub categories
                            <?php elseif ($is_brand_view): ?>
                                Showing all <?php echo $total_rows; ?> brands
                            <?php else: ?>
                                Showing <?php echo (($offset + 1) > $total_rows) ? $total_rows : ($offset + 1); ?>–<?php echo (($offset + $limit) > $total_rows) ? $total_rows : ($offset + $limit); ?> of <?php echo $total_rows; ?> results
                            <?php endif; ?>
                        </span>
                        <div class="view-toggle-btns">
                            <a href="view_products.php?page=<?php echo $page; ?>&sort=<?php echo $sort . $url_params; ?>&view_mode=grid" class="toggle-view-btn <?php echo $view_mode == 'grid' ? 'active' : ''; ?>" title="Grid View"><i class="fas fa-th"></i></a>
                            <a href="view_products.php?page=<?php echo $page; ?>&sort=<?php echo $sort . $url_params; ?>&view_mode=list" class="toggle-view-btn <?php echo $view_mode == 'list' ? 'active' : ''; ?>" title="List View"><i class="fas fa-list"></i></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="product-grid <?php echo $view_mode == 'list' ? 'list-view-active' : ''; ?>">
                <?php
        
                if ($is_sub_category_view) {
                    while($sub = $result_subs->fetch_assoc()) {
                        $sub_img = !empty($sub['image_name']) ? $sub['image_name'] : 'slider1.jpg';
                        ?>
                        <div class="product-card" data-aos="fade-up">
                            <div class="product-img-wrapper">
                                <img src="images/<?php echo $sub_img; ?>" alt="<?php echo $sub['sub_name']; ?>" onerror="this.src='images/slider1.jpg';">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo $sub['sub_name']; ?></h3>
                                <a href="view_products.php?sub_id=<?php echo $sub['id']; ?>" class="read-more-btn">View Details</a>
                            </div>
                        </div>
                        <?php
                    }
                } 
       
                elseif ($is_brand_view) {
                    while($brand = $result_brands->fetch_assoc()) {
                        $brand_img = !empty($brand['image_name']) ? $brand['image_name'] : 'slider1.jpg';
                        ?>
                        <div class="product-card" data-aos="fade-up">
                            <div class="product-img-wrapper">
                                <img src="images/<?php echo $brand_img; ?>" alt="<?php echo $brand['brand_name']; ?>" onerror="this.src='images/slider1.jpg';">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo $brand['brand_name']; ?></h3>
                                <a href="view_products.php?brand_id=<?php echo $brand['id']; ?>" class="read-more-btn">View Products</a>
                            </div>
                        </div>
                        <?php
                    }
                }
             
                elseif ($result && $result->num_rows > 0) {
                    $delay = 0;
                    while($row = $result->fetch_assoc()) {
                        $img1 = !empty($row['image_primary']) ? trim($row['image_primary']) : 'slider1.jpg';
                        
                        $img2 = "";
                        if (!empty($row['image_secondary'])) {
                            $trimmed2 = trim($row['image_secondary']);
                            if ($trimmed2 != "" && strtolower($trimmed2) != "null" && $trimmed2 != $img1) {
                                if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $trimmed2)) {
                                    $img2 = $trimmed2;
                                }
                            }
                        }
                        ?>
                        <div class="product-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            
                            <div class="product-img-wrapper <?php echo !empty($img2) ? 'has-hover-swap' : ''; ?>">
                                <a href="<?php echo $page_base_url; ?>&page=<?php echo $page; ?>&add_to_wishlist=<?php echo $row['id']; ?>" class="wishlist-btn" title="Add to Wishlist" style="text-decoration: none; color: inherit; z-index: 10;">
                                    <?php 
                                    if (isset($_COOKIE['wishlist_token'])) {
                                        $current_token = $_COOKIE['wishlist_token'];
                                        $pid = $row['id'];
                                        $in_wish = $conn->query("SELECT * FROM wishlist WHERE token = '$current_token' AND product_id = $pid");
                                        if ($in_wish && $in_wish->num_rows > 0) {
                                            echo '<i class="fas fa-heart" style="color: #ff3333;"></i>';
                                        } else {
                                            echo '<i class="far fa-heart"></i>';
                                        }
                                    } else {
                                        echo '<i class="far fa-heart"></i>';
                                    }
                                    ?>
                                </a>

                                <img src="images/<?php echo $img1; ?>" alt="<?php echo $row['name']; ?>" class="primary-img" onerror="this.src='images/slider1.jpg';">
                                
                                <?php if(!empty($img2)): ?>
                                    <img src="images/<?php echo $img2; ?>" alt="<?php echo $row['name']; ?>" class="secondary-img" onerror="this.style.display='none';">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo $row['name']; ?></h3>
                                <?php if(!empty($row['price']) && $row['price'] > 0): ?>
                                    <p style="color: #64748b; font-weight: 600; margin-bottom: 15px;">Rs. <?php echo number_format($row['price'], 2); ?></p>
                                <?php endif; ?>
                                <a href="product_details.php?id=<?php echo $row['id']; ?>" class="read-more-btn">Read More</a>
                            </div>
                        </div>
                        <?php
                        $delay = ($delay < 400) ? $delay + 100 : 0; 
                    }
                } else {
                    echo "<div style='grid-column: 1 / -1; text-align: center; padding: 60px; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(15,23,42,0.05);'>";
                    echo "<i class='fas fa-box-open' style='font-size: 50px; color: #cbd5e1; margin-bottom: 20px;'></i>";
                    echo "<h3 style='color: #334155; font-size: 1.5rem; margin-bottom: 10px;'>No Products Found</h3>";
                    echo "<p style='color: #64748b;'>We couldn't find any products in this category at the moment.</p>";
                    echo "<a href='products.php' class='primary-btn' style='margin-top: 20px;'>View All Categories</a>";
                    echo "</div>";
                }
                ?>
            </div>

            <?php if (!$is_sub_category_view && !$is_brand_view && $total_pages > 1): ?>
                <div class="pagination-wrapper" data-aos="fade-up">
                    <a href="<?php echo $page_base_url; ?>&page=<?php echo ($page - 1); ?>" class="pagination-btn <?php echo ($page <= 1) ? 'disabled' : ''; ?>"><i class="fas fa-chevron-left"></i></a>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo $page_base_url; ?>&page=<?php echo $i; ?>" class="pagination-btn <?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <a href="<?php echo $page_base_url; ?>&page=<?php echo ($page + 1); ?>" class="pagination-btn <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>"><i class="fas fa-chevron-right"></i></a>
                </div>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php include 'footer.php'; ?>