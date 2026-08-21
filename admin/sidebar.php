<?php

$current_page = basename($_SERVER['PHP_SELF']);


$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $base_dir . '/';
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (!document.querySelector("base")) {
            var base = document.createElement('base');
            base.href = '<?php echo $base_url; ?>';
            document.getElementsByTagName('head')[0].appendChild(base);
        }
        var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
        link.type = 'image/jpeg';
        link.rel = 'shortcut icon';
        link.href = '../images/logo.jpg';
        document.getElementsByTagName('head')[0].appendChild(link);
    });
</script>

<style>

    .sidebar { 
        width: 260px; 
        background: #0f172a; 
        color: #fff; 
        padding: 30px 20px; 
        flex-shrink: 0; 
        display: flex; 
        flex-direction: column; 
        height: 100vh;           
        position: sticky;        
        top: 0;                 
        left: 0;
        z-index: 1000;          
        box-shadow: 4px 0 15px rgba(15, 23, 42, 0.08);
        box-sizing: border-box;  
    }
    
    .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .sidebar-brand img { width: 42px; height: 42px; border-radius: 8px; object-fit: cover; border: 2px solid #ff3333; }
    .sidebar-brand-text { font-size: 0.95rem; font-weight: 700; color: #ffffff; text-transform: uppercase; line-height: 1.3; letter-spacing: 0.5px; }
    .sidebar-brand-text span { color: #ff3333; display: block; }
    
  
    .sidebar-menu { 
        list-style: none; 
        padding: 0; 
        margin: 0; 
        display: flex; 
        flex-direction: column; 
        gap: 4px; 
        flex-grow: 1; 
    }
    .sidebar-menu li { margin-bottom: 0; }
    .sidebar-menu a { display: flex; align-items: center; gap: 15px; color: #94a3b8; text-decoration: none; padding: 12px 15px; border-radius: 8px; font-weight: 500; transition: all 0.3s; }
    
   
    .sidebar-menu a i {
        width: 22px;
        text-align: center;
        font-size: 1.05rem;
    }
    
    /* Active & Hover Highlights */
    .sidebar-menu a:hover, .sidebar-menu a.active { background: #ff3333 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(255,51,51,0.25); }
    

    @media (max-width: 768px) {
        .sidebar { width: 100%; height: auto; position: relative; padding: 20px; }
        .sidebar-brand { margin-bottom: 20px; justify-content: center; }
        .sidebar-menu { flex-direction: row; flex-wrap: wrap; gap: 8px; justify-content: center; }
        .sidebar-menu a { padding: 8px 12px; font-size: 0.88rem; }
        .sidebar-menu li[style*="margin-top: auto"] { margin-top: 0 !important; padding-top: 0 !important; }
    }
</style>

<div class="sidebar">
    <div class="sidebar-brand">
        <img src="../images/logo.jpg" alt="Logo">
        <div class="sidebar-brand-text">House Of <span>Aluminium</span></div>
    </div>
    <ul class="sidebar-menu">
        <li><a href="admin_dashboard" class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-th-large"></i> Dashboard</a></li>
        <li><a href="admin_products" class="<?php echo ($current_page == 'admin_products.php') ? 'active' : ''; ?>"><i class="fas fa-box"></i> Products</a></li>
        <li><a href="admin_categories" class="<?php echo ($current_page == 'admin_categories.php') ? 'active' : ''; ?>"><i class="fas fa-list"></i> Categories</a></li>
        <li><a href="admin_brands" class="<?php echo ($current_page == 'admin_brands.php') ? 'active' : ''; ?>"><i class="fas fa-tags"></i> Brands</a></li>
        <li><a href="admin_contacts" class="<?php echo ($current_page == 'admin_contacts.php') ? 'active' : ''; ?>"><i class="fas fa-envelope-open-text"></i> Contact Messages</a></li>
        <li><a href="admin_payments" class="<?php echo ($current_page == 'admin_payments.php') ? 'active' : ''; ?>"><i class="fas fa-file-invoice-dollar"></i> Online Payments</a></li>
        <li><a href="admin_reviews" class="<?php echo ($current_page == 'admin_reviews.php') ? 'active' : ''; ?>"><i class="fas fa-star"></i> Product Reviews</a></li>
        <li><a href="admin_orders" class="<?php echo ($current_page == 'admin_orders.php') ? 'active' : ''; ?>"><i class="fab fa-whatsapp"></i> Checkout Requests</a></li>
        <li><a href="../index" target="_blank"><i class="fas fa-globe"></i> View Website</a></li>
        <li style="margin-top: auto; padding-top: 20px;"><a href="../logout" style="color: #f87171;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>