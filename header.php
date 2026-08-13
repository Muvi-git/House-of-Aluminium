<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        include_once 'db_connect.php'; 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $current_page = basename($_SERVER['PHP_SELF']); 

     
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        $base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $base_dir . '/';
        
       
        $header_wish_count = 0;
        if (isset($_COOKIE['wishlist_token'])) {
            $w_token = $_COOKIE['wishlist_token'];
            $count_q = $conn->query("SELECT COUNT(*) as total FROM wishlist WHERE token = '$w_token'");
            if ($count_q) {
                $header_wish_count = $count_q->fetch_assoc()['total'];
            }
        }

    
        $header_cart_count = 0;
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            if (array_values($_SESSION['cart']) === $_SESSION['cart']) {
                $header_cart_count = count($_SESSION['cart']);
            } else {
                $header_cart_count = array_sum($_SESSION['cart']);
            }
        }
    ?>
    
    <base href="<?php echo $base_url; ?>">

    <link rel="icon" type="image/jpeg" href="images/logo.jpg">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House of Aluminium | Premium Architectural Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .wish-badge { background: #ff3333; color: #ffffff; padding: 2px 7px; border-radius: 50%; font-size: 11px; font-weight: 700; margin-left: 5px; display: inline-block; vertical-align: middle; }
        .top-wishlink { color: #cbd5e1; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: flex; align-items: center; gap: 6px; transition: all 0.3s; }
        .top-wishlink:hover { color: #ff3333; }

        .cart-badge { background: #ff3333; color: #ffffff; padding: 2px 7px; border-radius: 50%; font-size: 11px; font-weight: 700; margin-left: 5px; display: inline-block; vertical-align: middle; }
        .top-cartlink { color: #cbd5e1; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: flex; align-items: center; gap: 6px; transition: all 0.3s; }
        .top-cartlink:hover { color: #ff3333; }

     
        .auth-btn a {
            background: #ff3333 !important;
            color: #ffffff !important;
            padding: 6px 16px !important;
            border-radius: 30px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 8px rgba(255, 51, 51, 0.2) !important;
        }
        
        .auth-btn a:hover {
            background: #d92626 !important;
            box-shadow: 0 4px 12px rgba(255, 51, 51, 0.4) !important;
            transform: translateY(-1px) !important;
            color: #ffffff !important;
        }

        .auth-btn a i {
            font-size: 13px !important;
        }

        /* ==========================================================================
           🎯 ULTRA-SMOOTH CINEMATIC TRANSITION LOADER SYSTEM BY GEMINI
           ========================================================================== */
        .premium-page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            height: 100dvh; 
            background: #0f172a; 
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999999; 

            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.6s ease;
            opacity: 1;
            visibility: visible;
            will-change: opacity; 
        }

        .premium-page-loader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .loader-core-wrapper {
            position: relative;
            width: 80px;  
            height: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .glowing-orbit {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top-color: #ff3333; 
            border-radius: 50%;
            animation: orbitSpin 1.2s cubic-bezier(0.5, 0.1, 0.5, 0.9) infinite;
            filter: drop-shadow(0 0 8px rgba(255, 51, 51, 0.6));
        }

    
        .glowing-orbit.orbit-inner {
            width: 75%;
            height: 75%;
            border-top-color: transparent;
            border-bottom-color: #ffffff;
            animation: orbitSpinReverse 1s linear infinite;
            filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.4));
        }

      
        .pulse-center-core {
            width: 16px;
            height: 16px;
            background: #ff3333;
            border-radius: 50%;
            box-shadow: 0 0 20px #ff3333, 0 0 40px #ff3333;
            animation: corePulse 1.4s ease-in-out infinite;
        }

        /* Keyframes Animations */
        @keyframes orbitSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes orbitSpinReverse {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(0deg); }
        }
        @keyframes corePulse {
            0%, 100% { transform: scale(0.85); opacity: 0.6; }
            50% { transform: scale(1.2); opacity: 1; }
        }
    </style>
</head>
<body>

    <div class="premium-page-loader" id="universalPageLoader">
        <div class="loader-core-wrapper">
            <div class="glowing-orbit"></div>
            <div class="glowing-orbit orbit-inner"></div>
            <div class="pulse-center-core"></div>
        </div>
    </div>

    <div class="top-bar">
        <div class="top-container">
            <div class="left-side">
                <a href="tel:+94718791791" class="top-link"><i class="fas fa-phone-alt"></i> Hotline: +94 718791791</a>
            </div>
            <div class="right-side">
                <div class="social-wrapper">
                    <a href="https://www.facebook.com/profile.php?id=100064087003026#" target="_blank"><i class="fab fa-facebook-f"></i></a> 
                    <a href="https://www.instagram.com/houseofalu/" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
                
                <a href="wishlist" class="top-wishlink"><i class="fas fa-heart" style="color: #ff3333;"></i> Wishlist <?php echo ($header_wish_count > 0) ? '<span class="wish-badge">'.$header_wish_count.'</span>' : ''; ?></a>
                
                <a href="cart" class="top-cartlink"><i class="fas fa-shopping-cart" style="color: #ff3333;"></i> Cart <?php echo ($header_cart_count > 0) ? '<span class="cart-badge">'.$header_cart_count.'</span>' : ''; ?></a>

                <form action="search" method="GET" class="search-box">
                    <input type="text" name="query" placeholder="Search products...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                
                <div class="auth-btn">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <?php else: ?>
                        <a href="login"><i class="fas fa-user-circle"></i> Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <header class="header-main">
        <div class="nav-container">
            <div class="logo">
                <a href="index"><img src="images/logo.jpg" alt="House of Aluminium"></a>
            </div>

            <nav class="nav-navigation">
                <ul class="nav-links" id="navLinks">
                    <li><a href="index" class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="about" class="nav-item <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>"><i class="fas fa-info-circle"></i> About Us</a></li>
                    
                    <li class="dropdown">
                        <a href="products" class="nav-item <?php echo ($current_page == 'products.php' || $current_page == 'view_products.php') ? 'active' : ''; ?>">
                            <i class="fas fa-shopping-bag"></i> Products <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            if(isset($conn)) {
                                $cat_query = "SELECT * FROM categories ORDER BY name ASC";
                                $cat_result = $conn->query($cat_query);
                                
                                if ($cat_result && $cat_result->num_rows > 0) {
                                    while($cat = $cat_result->fetch_assoc()) {
                                        $cat_id = $cat['id'];
                                        $sub_query = "SELECT * FROM sub_categories WHERE category_id = $cat_id ORDER BY sub_name ASC";
                                        $sub_result = $conn->query($sub_query);
                                        
                                        if ($sub_result && $sub_result->num_rows > 0) {
                                            echo '<li class="sub-dropdown">';
                                            echo '<a href="view_products?cat_id='.$cat_id.'">'.$cat['name'].' <i class="fas fa-chevron-right"></i></a>';
                                            echo '<ul class="sub-dropdown-menu">';
                                            
                                            while($sub = $sub_result->fetch_assoc()) {
                                                $sub_id = $sub['id'];
                                                $brand_query = "SELECT * FROM brands WHERE sub_category_id = $sub_id ORDER BY brand_name ASC";
                                                $brand_result = $conn->query($brand_query);
                                                
                                                if ($brand_result && $brand_result->num_rows > 0) {
                                                    echo '<li class="sub-dropdown-2">';
                                                    echo '<a href="view_products?sub_id='.$sub_id.'">'.$sub['sub_name'].' <i class="fas fa-chevron-right"></i></a>';
                                                    echo '<ul class="sub-dropdown-menu-2">';
                                                    while($brand = $brand_result->fetch_assoc()) {
                                                        echo '<li><a href="view_products?brand_id='.$brand['id'].'">'.$brand['brand_name'].'</a></li>';
                                                    }
                                                    echo '</ul></li>';
                                                } else {
                                                    echo '<li><a href="view_products?sub_id='.$sub_id.'">'.$sub['sub_name'].'</a></li>';
                                                }
                                            }
                                            echo '</ul></li>';
                                        } else {
                                            echo '<li><a href="view_products?cat_id='.$cat_id.'">'.$cat['name'].'</a></li>';
                                        }
                                    }
                                }  
                            }
                            ?>
                        </ul>
                    </li>

                    <li><a href="brands" class="nav-item <?php echo ($current_page == 'brands.php') ? 'active' : ''; ?>"><i class="fas fa-tags"></i> Brands</a></li>
                    <li><a href="contact" class="nav-item <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Contact Us</a></li>
                    <li><a href="payment" class="nav-item payment-link"><i class="fas fa-credit-card"></i> Online Payment</a></li>
                </ul>
            </nav>

            <div class="menu-toggle" id="menuToggle" aria-label="Toggle Navigation Menu">
                <i class="fas fa-bars icon-open"></i>
                <i class="fas fa-times icon-close" style="display: none;"></i>
            </div>
        </div>
    </header>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        const openIcon = menuToggle.querySelector('.icon-open');
        const closeIcon = menuToggle.querySelector('.icon-close');

        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('mobile-active');
            if (navLinks.classList.contains('mobile-active')) {
                openIcon.style.display = 'none';
                closeIcon.style.display = 'block';
            } else {
                openIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            }
        });


        function hideUniversalLoader() {
            const loaderInstance = document.getElementById('universalPageLoader');
            if(loaderInstance) {
                loaderInstance.classList.add('fade-out');
            }
        }


        window.addEventListener('load', function() {
            setTimeout(hideUniversalLoader, 1000);
        });

       
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                const loaderInstance = document.getElementById('universalPageLoader');
                if(loaderInstance) {
                    loaderInstance.classList.remove('fade-out');
                    setTimeout(hideUniversalLoader, 1000);
                }
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const destinationHref = this.getAttribute('href');
                    const targetWindow = this.getAttribute('target');
                    
                    if (destinationHref && 
                        !destinationHref.startsWith('#') && 
                        !destinationHref.startsWith('tel:') && 
                        !destinationHref.startsWith('mailto:') && 
                        targetWindow !== '_blank' && 
                        !e.ctrlKey && !e.metaKey) {
                        
                        const loaderInstance = document.getElementById('universalPageLoader');
                        if(loaderInstance) {
                            loaderInstance.classList.remove('fade-out');
                        }
                    }
                });
            });
        });
    </script>