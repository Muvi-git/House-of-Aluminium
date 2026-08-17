<?php 
include 'db_connect.php'; 
include 'header.php'; 

$search_query = isset($_GET['query']) ? $conn->real_escape_string(trim($_GET['query'])) : '';

$search_result = null;
$results_count = 0;

if (!empty($search_query)) {
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%' ORDER BY id DESC";
    $search_result = $conn->query($sql);
    $results_count = ($search_result) ? $search_result->num_rows : 0;
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
?>

<style>
 
    .search-container { padding-top: 50px; padding-bottom: 80px; background: #f8fafc; min-height: 60vh; }
    .search-meta-info { font-size: 1.05rem; color: #475569; margin-bottom: 35px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; font-weight: 500; }
    .search-meta-info span { color: #0f172a; font-weight: 700; }
    .search-meta-info .keyword { color: #ff3333; font-style: italic; }

    .wishlist-alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

    /* 🎯 EXACT ORIGINAL PRODUCT CARD STYLES MATCHING VIEW_PRODUCTS PAGE */
    .product-img-wrapper { height: 280px; background: #ffffff; padding: 0px !important; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; border-radius: 12px 12px 0 0; }
    .product-img-wrapper img { width: 100% !important; height: 100% !important; object-fit: contain !important; transition: transform 0.5s ease-out !important; }

    .product-card:hover .product-img-wrapper img.primary-img { opacity: 1 !important; transform: scale(1.06) !important; visibility: visible !important; display: block !important; }
    .product-card:hover .product-img-wrapper:not(.has-hover-swap) img { transform: scale(1.06) !important; }

    .product-img-wrapper.has-hover-swap img.primary-img { position: relative; z-index: 1; }
    .product-img-wrapper.has-hover-swap img.secondary-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; opacity: 0; z-index: 2; transition: opacity 0.4s ease, transform 0.5s ease-out; }
    
    .product-card:hover .product-img-wrapper.has-hover-swap img.secondary-img { opacity: 1 !important; transform: scale(1.06) !important; z-index: 3; }

    /* Empty Result Screen Component */
    .empty-search-panel { text-align: center; padding: 80px 20px; background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .empty-search-panel i { font-size: 65px; color: #cbd5e1; margin-bottom: 20px; }
    .empty-search-panel h2 { color: #0f172a; font-weight: 700; margin-bottom: 10px; font-size: 1.4rem; }
    .empty-search-panel p { color: #64748b; font-size: 0.95rem; margin-bottom: 25px; }
</style>

<main style="background: #f8fafc;">
    <div class="page-hero" style="background-image: url('images/slider3.jpg'); height: 25vh; min-height: 200px;">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1>Search Results</h1>
            <p><a href="index" style="color:#cbd5e1; text-decoration:none;">Home</a> / <span style="color:#fff;">Search</span></p>
        </div>
    </div>

    <div class="search-container">
        <div class="container">
            
            <?php if (!empty($wishlist_msg)): ?>
                <div class="wishlist-alert alert-<?php echo $wishlist_msg_type; ?>" data-aos="fade-up">
                    <i class="fas <?php echo $wishlist_msg_type == 'success' ? 'fa-check-circle' : 'fa-info-circle'; ?>"></i>
                    <?php echo $wishlist_msg; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($search_query)): ?>
                <div class="search-meta-info" data-aos="fade-up">
                    Showing <span><?php echo $results_count; ?></span> results found for <span class="keyword">"<?php echo htmlspecialchars($search_query); ?>"</span>
                </div>

                <?php if ($results_count > 0 && $search_result): ?>
                
                    <div class="product-grid" data-aos="fade-up">
                        <?php 
                        while ($row = $search_result->fetch_assoc()): 
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
                            
                            $price = (!empty($row['price']) && $row['price'] > 0) ? floatval($row['price']) : 0;
                        ?>
                            <div class="product-card" data-aos="fade-up">
                                <div class="product-img-wrapper <?php echo !empty($img2) ? 'has-hover-swap' : ''; ?>">
                                    <a href="search?query=<?php echo urlencode($search_query); ?>&add_to_wishlist=<?php echo $row['id']; ?>" class="wishlist-btn" title="Add to Wishlist" style="text-decoration: none; color: inherit; z-index: 10;">
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

                                    <img src="images/<?php echo $img1; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="primary-img" onerror="this.src='images/slider1.jpg';">
                                    
                                    <?php if(!empty($img2)): ?>
                                        <img src="images/<?php echo $img2; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="secondary-img" onerror="this.style.display='none';">
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                                    
                                    <?php if ($price > 0): ?>
                                        <p style="color: #64748b; font-weight: 600; margin-bottom: 15px;">Rs. <?php echo number_format($price, 2); ?></p>
                                    <?php endif; ?>

                                    <a href="product_details?id=<?php echo $row['id']; ?>" class="read-more-btn">Read More</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                
                    <div class="empty-search-panel" data-aos="zoom-in">
                        <i class="fas fa-search-minus"></i>
                        <h2>No Matches Found</h2>
                        <p>We couldn't find any premium architectural aluminum solutions matching your keyword. Please check your spelling or try searching for another product term.</p>
                        <a href="products" class="read-more-btn" style="display:inline-block; width:auto; padding:12px 35px; text-decoration:none;">Browse All Products</a>
                    </div>
                <?php endif; ?>

            <?php else: ?>
       
                <div class="empty-search-panel" data-aos="zoom-in">
                    <i class="fas fa-exclamation-circle" style="color:#f59e0b;"></i>
                    <h2>Empty Search Keyword</h2>
                    <p>Please enter a valid product title, profile code, or brand name in the header search input field to filter through our premium solutions inventory catalog.</p>
                    <a href="index" class="read-more-btn" style="display:inline-block; width:auto; padding:12px 35px; text-decoration:none;">Return to Home</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php include 'footer.php'; ?>