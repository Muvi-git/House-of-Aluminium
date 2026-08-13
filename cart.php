<?php
include 'db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";
$msg_type = "";
$order_success = false;
$submitted_whatsapp = "";


if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    if (array_values($_SESSION['cart']) === $_SESSION['cart']) {
        $temp_cart = [];
        foreach ($_SESSION['cart'] as $pid) {
            $pid = intval($pid);
            if (isset($temp_cart[$pid])) {
                $temp_cart[$pid]++;
            } else {
                $temp_cart[$pid] = 1;
            }
        }
        $_SESSION['cart'] = $temp_cart;
    }
} else {
    $_SESSION['cart'] = [];
}

// === BACKEND LOGIC: DIRECT CHECKOUT ===
if (isset($_POST['place_order_direct'])) {
    if (empty($_SESSION['cart'])) {
        $msg = "Your shopping cart is empty.";
        $msg_type = "error";
    } else {
        $whatsapp_number = $conn->real_escape_string($_POST['whatsapp_number']);
        $user_id = intval($_SESSION['user_id']);
        $submitted_whatsapp = $whatsapp_number;

    
        $ids = implode(',', array_keys($_SESSION['cart']));
        $products_query = $conn->query("SELECT id, price FROM products WHERE id IN ($ids)");
        
        $grand_total = 0;
        $items_to_save = [];
        
        while ($row = $products_query->fetch_assoc()) {
            $pid = intval($row['id']);
            $qty = intval($_SESSION['cart'][$pid]);
            $price = (!empty($row['price']) && $row['price'] > 0) ? floatval($row['price']) : 0;
            $item_total = $price * $qty;
            $grand_total += $item_total;
            
            $items_to_save[] = [
                'product_id' => $pid,
                'quantity' => $qty,
                'price' => $price
            ];
        }

   
        $insert_order = $conn->query("INSERT INTO orders (user_id, whatsapp_number, total_amount, status) VALUES ($user_id, '$whatsapp_number', $grand_total, 'Pending')");
        
        if ($insert_order) {
            $order_id = $conn->insert_id;

         
            foreach ($items_to_save as $item) {
                $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
            }

         
            $_SESSION['cart'] = [];
            $order_success = true;
            $msg = "Request submitted successfully! Admin will contact you soon.";
            $msg_type = "success";
        } else {
            $msg = "Database Error: Temporary failure, please try again.";
            $msg_type = "error";
        }
    }
}


if (isset($_POST['update_qty'])) {
    $pid = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']);
    if ($qty > 0) {
        $_SESSION['cart'][$pid] = $qty;
        $msg = "Cart quantities updated successfully.";
        $msg_type = "success";
    } else {
        unset($_SESSION['cart'][$pid]);
    }
}


if (isset($_GET['remove_id'])) {
    $remove_id = intval($_GET['remove_id']);
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
        $msg = "Item removed from shopping cart successfully.";
        $msg_type = "success";
    }
}

// === BACKEND: CLEAR ENTIRE CART ===
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    $msg = "All items cleared from your shopping cart.";
    $msg_type = "info";
}

$cart_items_count = count($_SESSION['cart']);
$products_result = null;

if ($cart_items_count > 0) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $products_result = $conn->query($sql);
}

include 'header.php';
?>

<style>
    /* Premium Cart Stylesheet */
    .cart-wrapper { display: grid; grid-template-columns: 2.2fr 1fr; gap: 30px; align-items: flex-start; margin-top: 40px; }
    .cart-main-panel { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 25px; box-shadow: 0 4px 6px -1px rgba(15,23,42,0.02); }
    .cart-summary-panel { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 25px; box-shadow: 0 4px 6px -1px rgba(15,23,42,0.02); position: sticky; top: 100px; }
    
    .cart-table { width: 100%; border-collapse: collapse; text-align: left; }
    .cart-table th { padding: 15px; border-bottom: 2px solid #e2e8f0; color: #1e293b; font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .cart-table td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; color: #475569; vertical-align: middle; }
    
    .cart-thumb { width: 65px; height: 65px; object-fit: contain; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px; }
    
    .qty-control { display: inline-flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; background: #f8fafc; }
    .qty-btn { background: none; border: none; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #475569; font-weight: bold; font-size: 0.9rem; transition: 0.2s; }
    .qty-btn:hover { background: #e2e8f0; color: #000; }
    .qty-input { width: 45px; height: 32px; border: none; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1; text-align: center; font-weight: 600; font-size: 0.9rem; color: #0f172a; background: #fff; outline: none; }

    .btn-remove-item { color: #94a3b8; background: none; border: none; cursor: pointer; font-size: 1.1rem; transition: color 0.2s; }
    .btn-remove-item:hover { color: #ff3333; }

    .summary-title { font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 0.95rem; color: #475569; }
    .summary-total-row { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 2px solid #f1f5f9; font-size: 1.15rem; font-weight: 700; color: #0f172a; }
    
    .btn-checkout { display: block; width: 100%; background: #ff3333; color: #ffffff; border: none; padding: 14px; border-radius: 8px; font-weight: 600; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 0.5px; text-decoration: none; text-align: center; margin-top: 15px; transition: all 0.3s; }
    .btn-checkout:hover { background: #d92626; box-shadow: 0 4px 15px rgba(255,51,51,0.25); }

    .btn-clear-cart { background: none; border: 1px solid #cbd5e1; color: #64748b; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 0.88rem; transition: 0.2s; }
    .btn-clear-cart:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

    .success-panel { background: #fff; padding: 60px 20px; border-radius: 14px; border: 1px solid #e2e8f0; text-align: center; max-width: 600px; margin: 40px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }

    @media (max-width: 992px) { .cart-wrapper { grid-template-columns: 1fr; gap: 25px; } .cart-summary-panel { position: relative; top: 0; } }
    @media (max-width: 768px) {
        .cart-table thead { display: none; }
        .cart-table tr { display: flex; flex-direction: column; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; margin-bottom: 15px; }
        .cart-table td { display: flex; justify-content: space-between; align-items: center; padding: 10px 5px; border-bottom: 1px solid #f1f5f9; text-align: right; width: 100%; box-sizing: border-box; }
        .cart-table td:last-child { border-bottom: none; }
        .cart-table td::before { content: attr(data-label); font-weight: 600; color: #334155; float: left; text-align: left; font-size: 0.88rem; }
    }
</style>

<main style="background: #f8fafc; padding-bottom: 80px;">
    <div class="page-hero" style="background-image: url('images/slider2.jpg');">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1>Shopping Cart</h1>
            <p><a href='index.php' style="color:#cbd5e1; text-decoration:none;">Home</a> / <span style="color:#fff;">Cart</span></p>
        </div>
    </div>

    <div class="container" style="padding-top: 50px;">
        
        <?php if ($order_success): ?>
            <div class="success-panel" data-aos="zoom-in">
                <i class="fas fa-clock" style="font-size: 70px; color: #ff3333; margin-bottom: 25px;"></i>
                <h2 style="color: #0f172a; font-weight: 700; margin-bottom: 12px;">Request Placed Successfully!</h2>
                <p style="color: #64748b; font-size: 1rem; max-width: 480px; margin: 0 auto 25px auto; line-height: 1.6;">
                    Admin will contact you soon. Please stay tuned on your provided WhatsApp number.
                </p>
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 20px; border-radius: 8px; font-weight: 700; color: #166534; margin-bottom: 25px; display: inline-block;">
                    <i class="fab fa-whatsapp"></i> Linked: <?php echo htmlspecialchars($submitted_whatsapp); ?>
                </div>
                <div>
                    <a href="products.php" class="btn-checkout" style="display:inline-block; width:auto; padding:12px 35px; background:#0f172a; text-decoration:none;">Back To Store</a>
                </div>
            </div>

        <?php else: ?>

            <?php if (!empty($msg)): ?>
                <div style="padding:15px; border-radius:8px; margin-bottom:25px; font-weight:500; background:<?php echo $msg_type == 'success'?'#dcfce7':'#fee2e2';?>; color:<?php echo $msg_type == 'success'?'#166534':'#991b1b';?>; border: 1px solid <?php echo $msg_type == 'success'?'#bbf7d0':'#fecaca';?>;">
                    <i class="fas <?php echo $msg_type == 'success' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if ($cart_items_count > 0 && $products_result): ?>
                <div class="cart-wrapper">
                    
                    <div class="cart-main-panel" data-aos="fade-up">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th width="12%">Product</th>
                                    <th width="38%">Description</th>
                                    <th width="15%">Unit Price</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Total</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal = 0;
                                while($row = $products_result->fetch_assoc()): 
                                    $pid = intval($row['id']);
                                    $qty = $_SESSION['cart'][$pid];
                                    $img = !empty($row['image_primary']) ? $row['image_primary'] : 'slider1.jpg';
                                    $price = (!empty($row['price']) && $row['price'] > 0) ? floatval($row['price']) : 0;
                                    $item_total = $price * $qty;
                                    $subtotal += $item_total;
                                ?>
                                    <tr>
                                        <td data-label="Product">
                                            <img src="images/<?php echo $img; ?>" alt="<?php echo $row['name']; ?>" class="cart-thumb" onerror="this.src='images/slider1.jpg';">
                                        </td>
                                        <td data-label="Description" style="font-weight: 600; color: #1e293b; text-align: left;">
                                            <?php echo $row['name']; ?>
                                        </td>
                                        <td data-label="Unit Price" style="font-weight: 600; color: #475569;">
                                            <?php echo $price > 0 ? 'Rs. ' . number_format($price, 2) : 'Contact Us'; ?>
                                        </td>
                                        <td data-label="Quantity">
                                            <form action="cart.php" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                                                <input type="hidden" name="update_qty" value="1">
                                                <div class="qty-control">
                                                    <button type="button" class="qty-btn dec-btn">-</button>
                                                    <input type="number" name="quantity" value="<?php echo $qty; ?>" min="1" class="qty-input" onchange="this.form.submit();">
                                                    <button type="button" class="qty-btn inc-btn">+</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td data-label="Total" style="font-weight: 700; color: #ff3333;">
                                            <?php echo $item_total > 0 ? 'Rs. ' . number_format($item_total, 2) : 'Contact Us'; ?>
                                        </td>
                                        <td data-label="Remove">
                                            <a href="cart.php?remove_id=<?php echo $pid; ?>" class="btn-remove-item" title="Remove Item"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <div style="display: flex; justify-content: flex-end; margin-top: 25px;">
                            <form action="cart.php" method="POST">
                                <button type="submit" name="clear_cart" class="btn-clear-cart" onclick="return confirm('Clear all items from shopping cart?');"><i class="fas fa-eraser"></i> Clear Cart</button>
                            </form>
                        </div>
                    </div>

                    <div class="cart-summary-panel" data-aos="fade-up" data-aos-delay="100">
                        <h3 class="summary-title">Order Summary</h3>
                        
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span style="font-weight: 600; color: #1e293b;">Rs. <?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Estimated Shipping</span>
                            <span style="color: #166534; font-weight: 600;">FREE</span>
                        </div>
                        
                        <div class="summary-total-row">
                            <span>Grand Total</span>
                            <span style="color: #ff3333;">Rs. <?php echo number_format($subtotal, 2); ?></span>
                        </div>

                        <form action="cart.php" method="POST" style="margin-top: 25px;">
                            <div style="margin-bottom: 15px; text-align: left;">
                                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px;">WhatsApp Number *</label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <i class="fab fa-whatsapp" style="position: absolute; left: 14px; color: #25d366; font-size: 1.15rem; z-index: 5;"></i>
                                    <input type="tel" name="whatsapp_number" required placeholder="e.g. 0771234567" style="width: 100%; padding: 12px 15px 12px 42px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; background: #f8fafc; outline: none; color: #334155; transition: all 0.3s ease;">
                                </div>
                            </div>
                            <button type="submit" name="place_order_direct" class="btn-checkout" style="width: 100%; cursor: pointer;">Submit & Request Checkout ➔</button>
                        </form>

                        <a href="products.php" style="display: block; text-align: center; margin-top: 15px; color: #64748b; font-size: 0.88rem; font-weight: 600; text-decoration: none;"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                    </div>

                </div>
            <?php else: ?>
                <div class="cart-main-panel" style="text-align: center; padding: 60px 20px;" data-aos="fade-up">
                    <i class="fas fa-shopping-basket" style="font-size: 70px; color: #cbd5e1; margin-bottom: 25px;"></i>
                    <h2 style="color: #1e293b; font-weight: 700; margin-bottom: 10px;">Your Shopping Cart is Empty</h2>
                    <p style="color: #64748b; max-width: 500px; margin: 0 auto 25px auto; font-size: 0.95rem;">You haven't added any premium architectural aluminum products to your cart yet.</p>
                    <a href="products.php" class="btn-checkout" style="display: inline-block; width: auto; padding: 12px 30px; text-decoration: none;">Start Shopping</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.inc-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let input = this.parentElement.querySelector('.qty-input');
                input.value = parseInt(input.value) + 1;
                this.closest('form').submit();
            });
        });

        document.querySelectorAll('.dec-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let input = this.parentElement.querySelector('.qty-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    this.closest('form').submit();
                }
            });
        });
    });
</script>

<?php include 'footer.php'; ?>