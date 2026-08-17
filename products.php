<?php include 'db_connect.php'; ?>
<?php include 'header.php'; ?>

<main>
    <div class="page-hero" style="background-image: url('images/slider2.jpg');">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1>Products</h1>
            <p>Home / Products</p>
        </div>
    </div>

    <section class="products-page-section">
        <div class="container">
            
            <div class="products-toolbar" data-aos="fade-up">
                <div class="toolbar-left">
                    <h3>Explore Our Categories</h3>
                </div>
                <div class="toolbar-right">
                    <span class="view-text">View As:</span>
                    <button id="btn-grid" class="view-btn active" title="Grid View" onclick="setView('grid')">
                        <i class="fas fa-th"></i>
                    </button>
                    <button id="btn-list" class="view-btn" title="List View" onclick="setView('list')">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <div id="product-view-container" class="products-grid-container">
                <?php
         
                $cat_sql = "SELECT * FROM categories ORDER BY name ASC";
                $cat_result = $conn->query($cat_sql);

                if ($cat_result && $cat_result->num_rows > 0) {
                    $delay = 0;
                    while($cat_row = $cat_result->fetch_assoc()) {
                        ?>
                        <div class="premium-cat-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <div class="premium-cat-img" style="height: 250px; background: #ffffff; padding: 20px; display: flex; align-items: center; justify-content: center;">
                                <img src="images/<?php echo $cat_row['image_name']; ?>" alt="<?php echo $cat_row['name']; ?>" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;" onerror="this.src='images/slider1.jpg';">
                            </div>
                            <div class="premium-cat-content">
                                <h3><?php echo $cat_row['name']; ?></h3>
                                <a href="view_products.php?cat_id=<?php echo $cat_row['id']; ?>" class="cat-link-btn">
                                    View Products <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                        // එකින් එක එන්න Delay එකක් දානවා (උපරිම 400ms)
                        $delay = ($delay < 400) ? $delay + 100 : 0; 
                    }
                } else {
                    echo "<div class='no-products'><p>No categories available right now. Please update your database.</p></div>";
                }
                ?>
            </div>

        </div>
    </section>
</main>

<script>
    function setView(viewType) {
        const container = document.getElementById('product-view-container');
        const btnGrid = document.getElementById('btn-grid');
        const btnList = document.getElementById('btn-list');

        if (viewType === 'list') {
            container.classList.add('list-view-active');
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
        } else {
            container.classList.remove('list-view-active');
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
        }
    }
</script>

<?php include 'footer.php'; ?>