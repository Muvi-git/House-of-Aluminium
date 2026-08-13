<?php 
include 'db_connect.php'; 
include 'header.php'; 


$view_mode = isset($_GET['view_mode']) ? $_GET['view_mode'] : 'grid'; 
?>

<style>
    /* Premium Breadcrumb Styles */
    .bc-link { color: #cbd5e1; text-decoration: none; transition: all 0.3s ease; }
    .bc-link:hover { color: #ff3333; }
    .bc-current { color: #ffffff; font-weight: 500; }

    /* Premium Brand Filter Bar Styles */
    .brand-filter-bar { display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 12px 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(15,23,42,0.02); }
    .brand-title-text { font-size: 1.1rem; color: #1e293b; font-weight: 600; border-bottom: 2px solid #ff3333; padding-bottom: 4px; }
    .view-toggle-btns { display: flex; gap: 6px; background: #f1f5f9; padding: 4px; border-radius: 8px; }
    .toggle-view-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; color: #64748b; text-decoration: none; transition: all 0.2s ease; font-size: 1rem; }
    .toggle-view-btn:hover { color: #ff3333; background: #ffffff; }
    .toggle-view-btn.active { background: #ffffff; color: #ff3333; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    /* List View Custom Layout CSS for Brands */
    .brand-grid.list-view-active { display: flex; flex-direction: column; gap: 20px; }
    .brand-grid.list-view-active .brand-card { display: flex; flex-direction: row; align-items: center; text-align: left; width: 100%; margin: 0; padding: 20px; }
    .brand-grid.list-view-active .brand-img { width: 100px; height: 100px; margin: 0 25px 0 0; }
    .brand-grid.list-view-active .brand-title { margin-bottom: 5px; }
    .brand-grid.list-view-active .view-products-btn { margin-top: 10px; }

    /* Existing Styles */
    .brand-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; padding: 40px 0; transition: all 0.3s ease; }
    .brand-card { background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; text-align: center; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .brand-card:hover { transform: translateY(-10px); box-shadow: 0 20px 25px -5px rgba(255,51,51,0.15); border-color: #ff3333; }
    .brand-img { width: 120px; height: 120px; object-fit: contain; margin-bottom: 20px; transition: all 0.3s; }
    .brand-title { font-size: 1.2rem; color: #1e293b; font-weight: 700; margin-bottom: 15px; }
    .view-products-btn { display: inline-block; padding: 10px 20px; background: #f8fafc; color: #ff3333; font-weight: 600; border-radius: 8px; text-decoration: none; border: 1px solid #ff3333; transition: all 0.3s; }
    .view-products-btn:hover { background: #ff3333; color: #ffffff; }
</style>

<main style="background: #f8fafc; padding-bottom: 80px;">
    <div class="page-hero" style="background-image: url('images/slider2.jpg');">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1>OUR BRANDS</h1>
            <p><a href='index.php' class='bc-link'>Home</a> / <span class='bc-current'>Brands</span></p>
        </div>
    </div>

    <div class="container" style="padding-top: 50px;">
        <div class="brand-filter-bar" data-aos="fade-up">
            <div class="brand-title-text">Explore Our Brands</div>
            <div class="view-toggle-btns">
                <a href="brands.php?view_mode=grid" class="toggle-view-btn <?php echo $view_mode == 'grid' ? 'active' : ''; ?>" title="Grid View"><i class="fas fa-th"></i></a>
                <a href="brands.php?view_mode=list" class="toggle-view-btn <?php echo $view_mode == 'list' ? 'active' : ''; ?>" title="List View"><i class="fas fa-list"></i></a>
            </div>
        </div>

        <div class="brand-grid <?php echo $view_mode == 'list' ? 'list-view-active' : ''; ?>">
            <?php
            $brand_sql = "SELECT * FROM brands ORDER BY brand_name ASC";
            $result = $conn->query($brand_sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $img = !empty($row['image_name']) ? $row['image_name'] : 'slider1.jpg';
                    ?>
                    <div class="brand-card" data-aos="fade-up">
                        <img src="images/<?php echo $img; ?>" alt="<?php echo $row['brand_name']; ?>" class="brand-img" onerror="this.src='images/slider1.jpg';">
                        <div style="flex-grow: 1;">
                            <h3 class="brand-title"><?php echo $row['brand_name']; ?></h3>
                            <a href="view_products.php?brand_id=<?php echo $row['id']; ?>" class="view-products-btn">View Products</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='grid-column: 1 / -1; text-align: center; color: #64748b;'>No brands available at the moment.</p>";
            }
            ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>