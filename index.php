<?php 
include 'db_connect.php'; 
include 'header.php'; 


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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style>
  
    .product-img-wrapper { height: 280px; background: #ffffff; padding: 0px !important; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; border-radius: 12px 12px 0 0; box-sizing: border-box; }
    .product-img-wrapper img { width: 100% !important; height: 100% !important; object-fit: contain !important; transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94), opacity 0.4s ease; }

   
    .product-card:hover .product-img-wrapper img.primary-img { opacity: 1 !important; transform: scale(1.06) !important; }
    .product-card:hover .product-img-wrapper:not(.has-hover-swap) img { transform: scale(1.06) !important; }

  
    .product-img-wrapper.has-hover-swap img.primary-img { position: relative; z-index: 1; }
    .product-img-wrapper.has-hover-swap img.secondary-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; opacity: 0; z-index: 2; transition: opacity 0.4s ease; }
    
    .product-card:hover .product-img-wrapper.has-hover-swap img.secondary-img { opacity: 1 !important; transform: scale(1.06) !important; z-index: 3; }

    /* Wishlist Alert Box Style */
    .home-wishlist-alert { padding: 15px; border-radius: 8px; margin: 20px auto 0 auto; max-width: 1200px; font-weight: 500; display: flex; align-items: center; gap: 10px; font-size: 0.95rem; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }


    .home-brand-card { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 25px 20px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; text-decoration: none; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .home-brand-card:hover { transform: translateY(-8px); border-color: #ff3333; box-shadow: 0 15px 20px -5px rgba(255,51,51,0.1); }
    .home-brand-img { width: 100px; height: 100px; object-fit: contain; margin-bottom: 15px; transition: transform 0.3s; }
    .home-brand-title { font-size: 1rem; color: #1e293b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
    
    /* Wishlist Button Anchor Link */
    .wishlist-btn-anchor { position: absolute; top: 15px; right: 15px; z-index: 10; width: 36px; height: 36px; background: #ffffff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); color: #64748b; text-decoration: none; transition: all 0.3s; }
    .wishlist-btn-anchor:hover { color: #ff3333; transform: scale(1.08); }

    /* View All Button Wrapper */
    .view-all-btn-wrapper { text-align: center; margin-top: 40px; }
</style>

<main>
    <!-- ==========================================
         HERO SLIDER SECTION[cite: 3]
    ========================================== -->
    <section class="hero-slider">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                
                <div class="swiper-slide" style="background-image: url('images/slider1.jpg');">
                    <div class="slide-content">
                        <h1>Premium Raised Flooring</h1>
                        <p>Discover the ultimate in durability and modern architectural design for your spaces.</p>
                        <a href="products.php" class="primary-btn">Explore Collection</a>
                    </div>
                </div>

                <div class="swiper-slide" style="background-image: url('images/slider2.jpg');">
                    <div class="slide-content">
                        <h1>Advanced Aluminium Extrusions</h1>
                        <p>High-quality, precision-engineered aluminium solutions for industrial and commercial needs.</p>
                        <a href="products.php" class="primary-btn">View Products</a>
                    </div>
                </div>

                <div class="swiper-slide" style="background-image: url('images/slider3.jpg');">
                    <div class="slide-content">
                        <h1>Architectural Hardware</h1>
                        <p>Secure and stylish door locks, handles, and accessories with a modern finish.</p>
                        <a href="products.php" class="primary-btn">Learn More</a>
                    </div>
                </div>

            </div>
            
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Wishlist Alert Placement[cite: 3] -->
    <?php if (!empty($wishlist_msg)): ?>
        <div class="home-wishlist-alert alert-<?php echo $wishlist_msg_type; ?>" data-aos="fade-up">
            <i class="fas <?php echo $wishlist_msg_type == 'success' ? 'fa-check-circle' : 'fa-info-circle'; ?>"></i>
            <?php echo $wishlist_msg; ?>
        </div>
    <?php endif; ?>

    <!-- ==========================================
         PRODUCT CATEGORIES SECTION (ORIGINAL SIZE + LIMIT 6)[cite: 3]
    ========================================== -->
    <section class="categories-section" style="background: #ffffff; padding: 60px 0;">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <span class="sub-heading">Explore By Range</span>
                <h2>Product Categories</h2>
                <div class="title-line"></div>
                <p class="section-desc">Browse through our extensive collection of premium aluminium materials and architectural solutions.</p>
            </div>
            
      
            <div class="category-grid">
                <?php
             
                $cat_sql = "SELECT * FROM categories ORDER BY id ASC LIMIT 6";
                $cat_result = $conn->query($cat_sql);

                if ($cat_result && $cat_result->num_rows > 0) {
                    $delay = 0; 
                    while($cat_row = $cat_result->fetch_assoc()) {
                        ?>
                        <a href="view_products.php?cat_id=<?php echo $cat_row['id']; ?>" class="cat-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>" style="text-decoration: none;">
                            <img src="images/<?php echo $cat_row['image_name']; ?>" alt="<?php echo $cat_row['name']; ?>">
                            <div class="cat-overlay">
                                <h3><?php echo $cat_row['name']; ?></h3>
                            </div>
                        </a>
                        <?php
                        $delay += 100; 
                    }
                } else {
                    echo "<p style='text-align: center; width: 100%; color: #64748b;'>No categories available right now.</p>";
                }
                ?>
            </div>

            <div class="view-all-btn-wrapper" data-aos="fade-up">
                <a href="products.php" class="primary-btn" style="text-decoration: none; display: inline-block;">View All Categories</a>
            </div>
        </div>
    </section>

    <!-- ==========================================
         LATEST PRODUCTS SECTION (LIMIT 4 + VIEW ALL)[cite: 3]
    ========================================== -->
    <section class="latest-products-section" style="background: #f8fafc; padding: 60px 0;">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <span class="sub-heading">New Arrivals</span>
                <h2>Discover Our Latest Additions</h2>
                <div class="title-line"></div>
                <p class="section-desc">Explore our newest range of premium quality aluminium hardware and accessories crafted for modern spaces.</p>
            </div>
            
            <div class="product-grid">
                <?php
                $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
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
                                
                                <a href="index.php?add_to_wishlist=<?php echo $row['id']; ?>" class="wishlist-btn-anchor" title="Add to Wishlist">
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

                                <img src="images/<?php echo $img1; ?>" class="primary-img" alt="<?php echo $row['name']; ?>" onerror="this.src='images/slider1.jpg';">
                                
                                <?php if(!empty($img2)): ?>
                                    <img src="images/<?php echo $img2; ?>" class="secondary-img" alt="<?php echo $row['name']; ?> Hover" onerror="this.style.display='none';">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo $row['name']; ?></h3>
                                <a href="product_details.php?id=<?php echo $row['id']; ?>" class="read-more-btn">Read More</a>
                            </div>
                        </div>
                        <?php
                        $delay += 100;
                    }
                } else {
                    echo "<p style='text-align: center; width: 100%; color: #64748b;'>No products available right now. Please check back later!</p>";
                }
                ?>
            </div>

            <div class="view-all-btn-wrapper" data-aos="fade-up">
                <a href="view_products.php" class="primary-btn" style="text-decoration: none; display: inline-block;">View All Products</a>
            </div>
        </div>
    </section>

    <!-- ==========================================
         OUR PREMIUM BRANDS SECTION (LIMIT 6 + VIEW ALL)[cite: 3]
    ========================================== -->
    <section class="home-brands-section" style="background: #ffffff; padding: 60px 0; border-top: 1px solid #edf2f7;">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <span class="sub-heading">Trusted Partners</span>
                <h2>Our Premium Brands</h2>
                <div class="title-line"></div>
                <p class="section-desc">We collaborate with top-tier international brands to bring you the highest quality architectural components.</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 25px; margin-top: 40px;">
                <?php
                $brand_sql = "SELECT * FROM brands ORDER BY brand_name ASC LIMIT 6";
                $brand_result = $conn->query($brand_sql);

                if ($brand_result && $brand_result->num_rows > 0) {
                    $b_delay = 0;
                    while($b_row = $brand_result->fetch_assoc()) {
                        $b_img = !empty($b_row['image_name']) ? $b_row['image_name'] : 'slider1.jpg';
                        ?>
                        <a href="view_products.php?brand_id=<?php echo $b_row['id']; ?>" class="home-brand-card" data-aos="zoom-in" data-aos-delay="<?php echo $b_delay; ?>">
                            <img src="images/<?php echo $b_img; ?>" alt="<?php echo $b_row['brand_name']; ?>" class="home-brand-img" onerror="this.src='images/slider1.jpg';">
                            <span class="home-brand-title"><?php echo $b_row['brand_name']; ?></span>
                        </a>
                        <?php
                        $b_delay += 100;
                    }
                } else {
                    echo "<p style='grid-column: 1/-1; text-align: center; color: #64748b;'>No brands available at the moment.</p>";
                }
                ?>
            </div>

            <div class="view-all-btn-wrapper" data-aos="fade-up">
                <a href="brands.php" class="primary-btn" style="text-decoration: none; display: inline-block;">View All Brands</a>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            effect: "fade",
            fadeEffect: {
                crossFade: true 
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    });
</script>

<?php include 'footer.php'; ?>