<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        
        .footer {
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.85)), url('images/footer-bg.jpg'); 
            background-size: cover;
            background-position: center;
            color: #ffffff;
            padding: 60px 20px;
            font-size: 15px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
        }
        
        .footer-section { flex: 1; min-width: 280px; }
        
        .footer-logo img { width: 250px; height: auto; margin-bottom: 20px; }
        
        h3 { color: #ffffff; margin-bottom: 20px; font-size: 20px; font-weight: bold; border-bottom: 2px solid #ff3333; display: inline-block; padding-bottom: 5px; }
        
        .contact-info p { margin-bottom: 12px; line-height: 1.6; }
        .contact-info i { margin-right: 10px; color: #ff3333; }
        
        .product-list { list-style: none; }
        .product-list li { margin-bottom: 10px; cursor: pointer; transition: 0.3s; }
        .product-list li:hover { color: #ff3333; }
        
        .social-links { margin-top: 20px; }
        .social-links a { color: white; background: #333; padding: 10px 15px; border-radius: 50%; margin-right: 10px; text-decoration: none; transition: 0.3s; display: inline-block; }
        .social-links a:hover { background: #ff3333; }
        
        .footer-bottom { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #444; font-size: 13px; color: #aaa; }

        @media (max-width: 768px) {
            .footer-container { flex-direction: column; text-align: center; }
            .footer-logo { display: flex; justify-content: center; }
        }
    </style>
</head>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
            duration: 800,
            once: false,
            mirror: true,
            offset: 100
        });
    });
</script>

<body>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <div class="footer-logo">
                <a href="index.php"><img src="images/logo.jpg" alt="House of Aluminium"></a>
            </div>
        </div>

        <div class="footer-section contact-info">
            <h3>Contact Us</h3>
            <p><strong>Address</strong><br>
            House of Aluminium (Pvt) Ltd<br>
            No 385, High Level Road,<br>
            Gangodawila,<br>
            Nugegoda,<br>
            Sri Lanka.</p>
            <p><i class="fas fa-phone-alt fa-flip-horizontal"></i> +94 2824141, +94 2824244</p>
            <p><i class="fas fa-fax"></i> +94 2816169</p>
            <p><i class="fas fa-envelope"></i> cladking@sltnet.lk</p>
            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=100064087003026#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/houseofalu/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Product Categories</h3>
            <ul class="product-list">
                <?php
                if(isset($conn)) {
                    $footer_cat_sql = "SELECT * FROM categories ORDER BY name ASC"; 
                    $footer_cat_result = $conn->query($footer_cat_sql);
                    
                    if ($footer_cat_result && $footer_cat_result->num_rows > 0) {
                        while($f_cat = $footer_cat_result->fetch_assoc()) {
                            echo '<li><a href="view_products.php?cat_id='.$f_cat['id'].'" style="color: inherit; text-decoration: none;">'.$f_cat['name'].'</a></li>';
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p><strong>Copyright &copy; 2026 House of Aluminium . All Rights Reserved. Powered By SLT-DIGITAL</strong></p>
    </div>
</footer>
</body>
</html>