<?php 
include 'db_connect.php'; 
include 'header.php'; 

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$success_msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $rating = intval($_POST['rating']);
    $review_msg = $conn->real_escape_string($_POST['review']);
    $rev_name = $conn->real_escape_string($_POST['name']);
    $rev_email = $conn->real_escape_string($_POST['email']);
    
    if ($product_id > 0 && $rating > 0 && !empty($review_msg) && !empty($rev_name)) {
        $insert_sql = "INSERT INTO reviews (product_id, rating, review, name, email) 
                       VALUES ($product_id, $rating, '$review_msg', '$rev_name', '$rev_email')";
        if ($conn->query($insert_sql)) {
            $success_msg = "Thank you! Your review has been submitted successfully.";
        }
    }
}

$wish_detail_msg = "";
$wish_detail_type = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_wishlist_detail'])) {
    if (!isset($_COOKIE['wishlist_token'])) {
        $wishlist_token = bin2hex(random_bytes(8));
        setcookie('wishlist_token', $wishlist_token, time() + (86400 * 30), "/");
    } else {
        $wishlist_token = $_COOKIE['wishlist_token'];
    }
    
    $check_w = $conn->query("SELECT * FROM wishlist WHERE token = '$wishlist_token' AND product_id = $product_id");
    if ($check_w && $check_w->num_rows == 0) {
        $conn->query("INSERT INTO wishlist (token, product_id, created_at) VALUES ('$wishlist_token', $product_id, NOW())");
        $wish_detail_msg = "Product added to your wishlist successfully!";
        $wish_detail_type = "success";
        echo "<script>window.location.href='product_details.php?id=".$product_id."';</script>";
    } else {
        $wish_detail_msg = "Product is already in your wishlist.";
        $wish_detail_type = "info";
    }
}

$is_already_in_wishlist = false;
if (isset($_COOKIE['wishlist_token'])) {
    $current_w_token = $_COOKIE['wishlist_token'];
    $check_exist = $conn->query("SELECT * FROM wishlist WHERE token = '$current_w_token' AND product_id = $product_id");
    if ($check_exist && $check_exist->num_rows > 0) {
        $is_already_in_wishlist = true;
    }
}

$reviews_query = $conn->query("SELECT * FROM reviews WHERE product_id = $product_id ORDER BY id DESC");
$review_count = ($reviews_query) ? $reviews_query->num_rows : 0;

$sql = "SELECT p.*, c.name as cat_name, s.sub_name, b.brand_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN sub_categories s ON p.sub_category_id = s.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.id = $product_id";
        
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
    
    $breadcrumb = "<a href='index.php' class='bc-link'>Home</a> / <a href='products.php' class='bc-link'>Products</a>";
    if (!empty($product['cat_name'])) {
        $breadcrumb .= " / <a href='view_products.php?cat_id=" . $product['category_id'] . "' class='bc-link'>" . $product['cat_name'] . "</a>";
    }
    if (!empty($product['sub_name'])) {
        $breadcrumb .= " / <a href='view_products.php?sub_id=" . $product['sub_category_id'] . "' class='bc-link'>" . $product['sub_name'] . "</a>";
    }
    if (!empty($product['brand_name'])) {
        $breadcrumb .= " / <a href='view_products.php?brand_id=" . $product['brand_id'] . "' class='bc-link'>" . $product['brand_name'] . "</a>";
    }
    $breadcrumb .= " / <span class='bc-current'>" . $product['name'] . "</span>";

    $slides = [];
    if(!empty($product['image_primary'])) $slides[] = $product['image_primary'];
    if(!empty($product['image_secondary'])) $slides[] = $product['image_secondary'];

    $gallery_query = $conn->query("SELECT image_name FROM product_gallery WHERE product_id = $product_id ORDER BY id ASC");
    if ($gallery_query && $gallery_query->num_rows > 0) {
        while ($g_row = $gallery_query->fetch_assoc()) {
            $slides[] = $g_row['image_name'];
        }
    }
    
    if(empty($slides)) $slides[] = 'slider1.jpg';
?>

<style>
    .bc-link { color: #cbd5e1; text-decoration: none; transition: all 0.3s ease; }
    .bc-link:hover { color: #ff3333; }
    .bc-current { color: #ffffff; font-weight: 500; }

    /* 🎯 FIXED: FULL UNCROPPED IMAGE DISPLAY IN MAIN IMAGE BOX WITH CONTAIN FIT */
    .main-image-box { overflow: hidden; position: relative; cursor: zoom-in; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; height: 460px; width: 100%; box-sizing: border-box; padding: 15px !important; }
    .main-image-box img { width: 100% !important; height: 100% !important; object-fit: contain !important; transition: transform 0.15s ease-out; transform-origin: center center; padding: 0px !important; margin: 0px !important; }

    .thumbnail-row { display: flex; gap: 10px; margin-top: 15px; overflow-x: auto; padding: 6px 4px; box-sizing: border-box; }
    .thumb-img { width: 80px; height: 80px; padding: 6px; border: 2px solid #e2e8f0; border-radius: 8px; background: #ffffff; cursor: pointer; transition: all 0.3s; flex-shrink: 0; display: flex; align-items: center; justify-content: center; box-sizing: border-box; }
    .thumb-img img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .thumb-img.active, .thumb-img:hover { border-color: #ff3333; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(255, 51, 51, 0.15); }

    .review-section-container { display: flex; flex-direction: column; gap: 30px; }
    .reviews-list { margin-bottom: 20px; display: flex; flex-direction: column; gap: 15px; }
    .single-review { background: #f8fafc; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #ff3333; }
    .review-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 5px; }
    .review-meta .reviewer-name { font-weight: 600; color: #1e293b; font-size: 0.95rem; }
    .review-meta .review-date { font-size: 0.82rem; color: #94a3b8; }
    .review-stars { color: #ff3333; font-size: 0.85rem; }
    .review-text-content { color: #475569; font-size: 0.92rem; line-height: 1.6; }

    .review-form-wrapper { background: #ffffff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; }
    .review-form-wrapper h3 { font-size: 1.3rem; color: #1e293b; margin-bottom: 5px; font-weight: 600; }
    .form-note { font-size: 0.9rem; color: #64748b; margin-bottom: 20px; }
    
    .review-form-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
    .review-form-group label { font-size: 0.95rem; color: #334155; font-weight: 500; }
    .review-form-group textarea, 
    .review-form-group input[type="text"], 
    .review-form-group input[type="email"] { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; background: #f8fafc; transition: all 0.3s ease; box-sizing: border-box; }
    
    .review-form-group textarea:focus, 
    .review-form-group input[type="text"]:focus, 
    .review-form-group input[type="email"]:focus { border-color: #ff3333; background: #fff; outline: none; box-shadow: 0 0 0 3px rgba(255,51,51,0.1); }
    
    .review-form-row-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 768px) { .review-form-row-2col { grid-template-columns: 1fr; gap: 0; } }
    
    .star-rating-select { display: inline-flex; flex-direction: row-reverse; justify-content: flex-end; gap: 6px; }
    .star-rating-select input { display: none; }
    .star-rating-select label { font-size: 24px; color: #cbd5e1; cursor: pointer; transition: color 0.2s ease; }
    .star-rating-select input:checked ~ label, .star-rating-select label:hover, .star-rating-select label:hover ~ label { color: #ff3333; }
    
    .submit-review-btn { background: #ff3333; color: #fff; border: none; padding: 12px 35px; font-weight: 600; border-radius: 8px; cursor: pointer; display: inline-block; transition: all 0.3s ease; width: max-content; text-transform: uppercase; letter-spacing: 0.5px; }
    .submit-review-btn:hover { background: #d92626; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,51,51,0.2); }
    .alert-success, .alert-info { background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0; font-weight: 500; }
    .alert-info { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }

    .wishlist-btn-large { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s; background: #ffffff; border: 1px solid #cbd5e1; color: #334155; }
    .wishlist-btn-large:hover, .wishlist-btn-large.in-wishlist-active { border-color: #ff3333; color: #ff3333; background: #fff5f5; }
</style>

<main style="background: #f8fafc; padding-bottom: 80px;">
    <div class="page-hero" style="background-image: url('images/slider3.jpg'); height: 30vh; min-height: 250px;">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1><?php echo $product['name']; ?></h1>
            <p><?php echo $breadcrumb; ?></p>
        </div>
    </div>

    <div class="container" style="margin-top: 50px;">
        
        <?php if(!empty($wish_detail_msg)): ?>
            <div class="alert-<?php echo $wish_detail_type == 'success' ? 'success' : 'info'; ?>"><i class="fas fa-info-circle"></i> <?php echo $wish_detail_msg; ?></div>
        <?php endif; ?>

        <div class="product-detail-wrapper" data-aos="fade-up">
            <div class="product-gallery">
                <div class="main-image-box" id="zoom-container">
                    <img id="main-product-image" src="images/<?php echo $slides[0]; ?>" alt="<?php echo $product['name']; ?>" onerror="this.src='images/slider1.jpg';">
                </div>
                
                <div class="thumbnail-row">
                    <?php foreach($slides as $index => $slide_img): ?>
                        <div class="thumb-img <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage('images/<?php echo $slide_img; ?>', this)">
                            <img src="images/<?php echo $slide_img; ?>" alt="Thumb <?php echo $index+1; ?>" onerror="this.src='images/slider1.jpg';">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="product-info-panel">
                <div class="brand-tag"><?php echo !empty($product['brand_name']) ? $product['brand_name'] : $product['cat_name']; ?></div>
                <h2 class="product-main-title"><?php echo $product['name']; ?></h2>
                
                <?php if(!empty($product['price']) && $product['price'] > 0): ?>
                    <div class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></div>
                <?php endif; ?>

                <?php if (!empty(trim($product['short_desc'] ?? ''))): ?>
                    <p class="product-short-desc">
                        <?php echo nl2br(htmlspecialchars($product['short_desc'])); ?>
                    </p>
                <?php endif; ?>

                <div class="product-actions" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                    <a href="contact.php" class="primary-btn"><i class="fas fa-envelope"></i> Inquire Now</a>
                    
                    <form action="product_details.php?id=<?php echo $product_id; ?>" method="POST" style="margin: 0;">
                        <button type="submit" name="add_to_wishlist_detail" class="wishlist-btn-large <?php echo $is_already_in_wishlist ? 'in-wishlist-active' : ''; ?>">
                            <i class="<?php echo $is_already_in_wishlist ? 'fas fa-heart' : 'far fa-heart'; ?>" style="<?php echo $is_already_in_wishlist ? 'color: #ff3333;' : ''; ?>"></i> 
                            <?php echo $is_already_in_wishlist ? 'In Wishlist' : 'Add to Wishlist'; ?>
                        </button>
                    </form>
                </div>

                <div class="product-share">
                    <span>Share this product:</span>
                    <a href="#" class="share-icon fb"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="share-icon wa"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <div class="product-tabs-wrapper" data-aos="fade-up" style="margin-bottom: 60px;">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="openTab(event, 'Description')">Description & Specifications</button>
                <button class="tab-btn" onclick="openTab(event, 'Reviews')">Reviews (<?php echo $review_count; ?>)</button>
            </div>
            
            <div id="Description" class="tab-content" style="display: block;">
                <div style="color: #475569; line-height: 1.8;">
                    <?php echo !empty($product['description']) ? nl2br($product['description']) : '<p>No description available.</p>'; ?>
                </div>
            </div>
            
            <div id="Reviews" class="tab-content">
                <div class="review-section-container">
                    <div class="reviews-display-area">
                        <?php if(!empty($success_msg)): ?>
                            <div class="alert-success"><i class="fas fa-check-circle"></i> <?php echo $success_msg; ?></div>
                        <?php endif; ?>

                        <?php if ($review_count > 0): ?>
                            <div class="reviews-list">
                                <?php while($rev = $reviews_query->fetch_assoc()): ?>
                                    <div class="single-review">
                                        <div class="review-meta">
                                            <span class="reviewer-name"><?php echo htmlspecialchars($rev['name']); ?></span>
                                            <div class="review-stars">
                                                <?php 
                                                for($i=1; $i<=5; $i++) {
                                                    echo ($i <= $rev['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                                }
                                                ?>
                                            </div>
                                            <span class="review-date"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></span>
                                        </div>
                                        <div class="review-text-content">
                                            <?php echo nl2br(htmlspecialchars($rev['review'])); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p style="color: #64748b; margin-bottom: 20px;">There are no reviews yet. Be the first to review this product!</p>
                        <?php endif; ?>
                    </div>

                    <div class="review-form-wrapper">
                        <h3>Add a review</h3>
                        <p class="form-note">Your email address will not be published. Required fields are marked *</p>
                        
                        <form action="product_details.php?id=<?php echo $product_id; ?>" method="POST">
                            <div class="review-form-group">
                                <label>Your rating *</label>
                                <div class="star-rating-select">
                                    <input type="radio" id="star5" name="rating" value="5" required/><label for="star5" class="far fa-star"></label>
                                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="far fa-star"></label>
                                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="far fa-star"></label>
                                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="far fa-star"></label>
                                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="far fa-star"></label>
                                </div>
                            </div>
                            
                            <div class="review-form-group">
                                <label for="review_msg">Your review *</label>
                                <textarea id="review_msg" name="review" rows="5" required></textarea>
                            </div>
                            
                            <div class="review-form-row-2col">
                                <div class="review-form-group">
                                    <label for="rev_name">Name *</label>
                                    <input type="text" id="rev_name" name="name" required>
                                </div>
                                <div class="review-form-group">
                                    <label for="rev_email">Email *</label>
                                    <input type="email" id="rev_email" name="email" required>
                                </div>
                            </div>
                            
                            <button type="submit" name="submit_review" class="submit-review-btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-sections-wrapper" data-aos="fade-up">
            <div class="related-products-area">
                <h3 class="section-sub-title">Related Products</h3>
                <div class="title-line-small"></div>
                <div class="related-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px;">
                    <?php
                    $cat_id = $product['category_id'];
                    $related_result = $conn->query("SELECT * FROM products WHERE category_id = $cat_id AND id != $product_id LIMIT 8");

                    if ($related_result && $related_result->num_rows > 0) {
                        while($rel = $related_result->fetch_assoc()) {
                            $rel_img = !empty($rel['image_primary']) ? $rel['image_primary'] : 'slider1.jpg';
                    ?>
                        <div class="product-card" style="box-shadow: 0 4px 12px rgba(15,23,42,0.04); border: 1px solid #f1f5f9; border-radius: 10px; background: #ffffff; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; margin: 0;">
                            <div class="product-img-wrapper" style="height: 140px; background: #ffffff; padding: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                                <img src="images/<?php echo $rel_img; ?>" alt="<?php echo $rel['name']; ?>" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;" onerror="this.src='images/slider1.jpg';">
                            </div>
                            <div class="product-info" style="padding: 10px; text-align: center; border-top: 1px solid #f8fafc;">
                                <h3 class="product-title" style="font-size: 0.88rem; margin-bottom: 6px; color: #334155; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 0;" title="<?php echo $rel['name']; ?>">
                                    <?php echo $rel['name']; ?>
                                </h3>
                                <?php if(!empty($rel['price']) && $rel['price'] > 0): ?>
                                    <p style="color: #64748b; font-weight: 600; margin-bottom: 8px; font-size: 13px;">Rs. <?php echo number_format($rel['price'], 2); ?></p>
                                <?php endif; ?>
                                <a href="product_details.php?id=<?php echo $rel['id']; ?>" class="read-more-btn" style="padding: 6px 12px; font-size: 11px; display: inline-block; width: 100%; text-align: center; box-sizing: border-box; margin: 0;">View</a>
                            </div>
                        </div>
                    <?php 
                        }
                    } else {
                        echo "<p style='color:#64748b;'>No related products found.</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="top-rated-sidebar">
                <h3 class="section-sub-title">Top Rated Products</h3>
                <div class="title-line-small"></div>
                <div class="top-rated-list">
                    <?php

                    $top_query = "SELECT p.*, COALESCE(AVG(r.rating), 0) as avg_rating 
                                  FROM products p 
                                  LEFT JOIN reviews r ON p.id = r.product_id 
                                  WHERE p.id != $product_id 
                                  GROUP BY p.id 
                                  ORDER BY avg_rating DESC, p.id DESC 
                                  LIMIT 4";
                    $top_result = $conn->query($top_query);
                    if ($top_result && $top_result->num_rows > 0) {
                        while($top = $top_result->fetch_assoc()) {
                            $top_img = !empty($top['image_primary']) ? $top['image_primary'] : 'slider1.jpg';
                            $calculated_rating = round($top['avg_rating']);
                    ?>
                        <a href="product_details.php?id=<?php echo $top['id']; ?>" class="top-rated-item">
                            <div class="top-img-box">
                                <img src="images/<?php echo $top_img; ?>" alt="<?php echo $top['name']; ?>" onerror="this.src='images/slider1.jpg';">
                            </div>
                            <div class="top-info">
                                <h4><?php echo htmlspecialchars($top['name']); ?></h4>
                                <?php if(!empty($top['price']) && $top['price'] > 0): ?>
                                    <span class="top-price">Rs. <?php echo number_format($top['price'], 2); ?></span>
                                <?php endif; ?>
                                
                                <div class="rating-stars">
                                    <?php 
                                    for($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $calculated_rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </a>
                    <?php 
                        }
                    } else {
                        echo "<p style='color:#64748b; font-size:13px;'>No top rated products registered yet.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const zoomContainer = document.getElementById('zoom-container');
    const mainImg = document.getElementById('main-product-image');

    if (window.innerWidth > 768) {
        zoomContainer.addEventListener('mousemove', function(e) {
            const rect = zoomContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            mainImg.style.transformOrigin = `${x}px ${y}px`;
            mainImg.style.transform = 'scale(1.8)';
        });

        zoomContainer.addEventListener('mouseleave', function() {
            mainImg.style.transformOrigin = 'center center';
            mainImg.style.transform = 'scale(1)';
        });
    }

    function changeImage(imageSrc, element) {
        mainImg.src = imageSrc;
        let thumbs = document.getElementsByClassName('thumb-img');
        for(let i=0; i<thumbs.length; i++){ thumbs[i].classList.remove('active'); }
        element.classList.add('active');
    }

    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>

<?php 
} else {
    echo "<div style='text-align: center; padding: 100px; font-size: 20px;'>Product not found!</div>";
}
include 'footer.php'; 
?>