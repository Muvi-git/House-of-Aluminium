<?php 
include 'header.php'; 


$msg = "";
$msg_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $reference = $conn->real_escape_string($_POST['reference']);
    $address = $conn->real_escape_string($_POST['address']);
    $type = $conn->real_escape_string($_POST['type']);
    $amount = floatval($_POST['amount']);
    $slip_file = "";

   
    if (!empty($_FILES['payment_slip']['name'])) {
        $slip_file = time() . '_online_slip_' . $_FILES['payment_slip']['name'];
        if (!move_uploaded_file($_FILES['payment_slip']['tmp_name'], 'images/' . $slip_file)) {
            $msg = "Failed to upload payment slip. Please try again.";
            $msg_type = "error";
        }
    } else {
        $msg = "Payment deposit slip is required to verify the transaction.";
        $msg_type = "error";
    }

    if (empty($msg)) {
        if (!empty($customer_name) && !empty($contact_number) && !empty($email) && !empty($reference) && !empty($address) && !empty($type) && $amount > 0) {
          
            $sql = "INSERT INTO payment_submissions (customer_name, contact_number, email, reference, address, payment_type, amount, payment_slip, status, created_at) 
                    VALUES ('$customer_name', '$contact_number', '$email', '$reference', '$address', '$type', $amount, '$slip_file', 'Pending', NOW())";
            
            if ($conn->query($sql)) {
                $msg = "Your billing details and payment slip have been submitted securely! Admin will verify it shortly.";
                $msg_type = "success";
            } else {
                $msg = "Something went wrong with the database. Please try again later.";
                $msg_type = "error";
            }
        } else {
            $msg = "All fields are required and amount must be greater than 0!";
            $msg_type = "error";
        }
    }
}
?>

<style>
    /* Premium Response Alert Styles */
    .payment-alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; font-size: 0.95rem; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<main>
    <div class="page-hero" style="background-image: url('images/slider1.jpg');">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1>Online Payment</h1>
            <p>Home / Online Payment</p>
        </div>
    </div>

    <section class="payment-section">
        <div class="container">
            <div class="payment-wrapper">
                
                <div class="payment-info" data-aos="fade-right">
                    <h2>Secure Payment Gateway</h2>
                    <div class="title-line" style="margin: 15px 0 25px 0; margin-left: 0; background: #ffffff;"></div>
                    <p>Complete your transaction safely and securely. We use industry-standard encryption to protect your personal and payment details.</p>
                    
                    <div class="security-features">
                        <div class="feature-item">
                            <i class="fas fa-lock"></i>
                            <div>
                                <h4>100% Secure</h4>
                                <span>256-bit SSL Encryption</span>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-headset"></i>
                            <div>
                                <h4>24/7 Support</h4>
                                <span>Always here to help you</span>
                            </div>
                        </div>
                    </div>

                    <div class="accepted-cards">
                        <span>Accepted Payment Methods</span>
                        <div class="card-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-amex"></i>
                            <i class="fab fa-cc-discover"></i>
                        </div>
                    </div>
                </div>

                <div class="payment-form-container" data-aos="fade-left">
                    <div class="section-title" style="text-align: left; margin-bottom: 25px;">
                        <span class="sub-heading">Billing Details</span>
                        <h2 style="font-size: 1.8rem;">Payment Information</h2>
                    </div>

                    <?php if (!empty($msg)): ?>
                        <div class="payment-alert alert-<?php echo $msg_type; ?>">
                            <i class="fas <?php echo $msg_type == 'success' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>
                    
                  
                    <form action="payment.php" method="POST" enctype="multipart/form-data" class="premium-form payment-form">
                        
                        <div class="form-row">
                            <div class="input-group">
                                <label>Customer Name *</label>
                                <input type="text" name="customer_name" placeholder="Enter full name" required>
                            </div>
                            <div class="input-group">
                                <label>Contact Number *</label>
                                <input type="tel" name="contact_number" placeholder="Enter contact number" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label>Email *</label>
                                <input type="email" name="email" placeholder="Enter email address" required>
                            </div>
                            <div class="input-group">
                                <label>Reference *</label>
                                <input type="text" name="reference" placeholder="Invoice or Quote No" required>
                            </div>
                        </div>

                        <div class="input-group full-width">
                            <label>Address *</label>
                            <input type="text" name="address" placeholder="Enter your full address" required>
                        </div>

                        <div class="form-row align-bottom">
                            <div class="input-group">
                                <label>Payment Type *</label>
                                <div class="select-wrapper">
                                    <select name="type" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="Advance Payment">Advance Payment</option>
                                        <option value="Full Payment">Full Payment</option>
                                        <option value="Invoice Settlement">Invoice Settlement</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>Amount *</label>
                                <div class="amount-wrapper">
                                    <span class="currency">Rs.</span>
                                    <input type="number" name="amount" placeholder="0.00" step="0.01" required>
                                </div>
                            </div>
                        </div>

                      
                        <div class="input-group full-width" style="margin-top: 15px;">
                            <label>Upload Payment Slip Copy (Image or Document) *</label>
                            <input type="file" name="payment_slip" accept="image/*,.pdf,.doc,.docx" required style="background: #ffffff; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; width: 100%;">
                        </div>

                        <div class="checkbox-group full-width" style="margin-top: 15px;">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">I agree to the <a href="#" style="color:#ff3333; text-decoration:none;">Terms and Conditions</a> and authorize this payment.</label>
                        </div>

                        <button type="submit" class="primary-btn submit-btn" style="margin-top: 10px;">
                            <i class="fas fa-lock"></i> Pay Now Securely
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>