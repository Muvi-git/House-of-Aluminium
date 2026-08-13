<?php 
include 'header.php'; 


$msg = "";
$msg_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $message = $conn->real_escape_string($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($message)) {
        $sql = "INSERT INTO contact_submissions (name, email, phone, message, created_at) 
                VALUES ('$name', '$email', '$phone', '$message', NOW())";
        
        if ($conn->query($sql)) {
            $msg = "Thank you! Your message has been sent successfully. We will contact you soon.";
            $msg_type = "success";
        } else {
            $msg = "Something went wrong. Please try again later.";
            $msg_type = "error";
        }
    } else {
        $msg = "All fields are required!";
        $msg_type = "error";
    }
}
?>

<style>
    /* Premium Response Alert Styles */
    .contact-alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; font-size: 0.95rem; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<main>
    <div class="page-hero" style="background-image: url('images/slider3.jpg');">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content" data-aos="zoom-in">
            <h1>Contact Us</h1>
            <p>Home / Contact Us</p>
        </div>
    </div>

    <section class="contact-info-section">
        <div class="container">
            <div class="info-cards-grid">
                
                <div class="info-card" data-aos="fade-up" data-aos-delay="0">
                    <div class="icon-box">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Our Location</h3>
                    <p>House of Aluminium (Pvt) Ltd<br>No 385, High Level Road,<br>Gangodawila, Nugegoda,<br>Sri Lanka.</p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="icon-box">
    <i class="fas fa-phone-alt fa-flip-horizontal"></i>
</div>
                    <h3>Call Us</h3>
                    <p>
                        <a href="tel:+94718791791">+94 718791791</a><br>
                        <a href="tel:+942824141">+94 2824141</a><br>
                        <a href="tel:+942824244">+94 2824244</a>
                    </p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-box">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Us</h3>
                    <p>
                        <a href="mailto:cladking@sltnet.lk">cladking@sltnet.lk</a><br><br>
                        <strong>Fax:</strong> <a href="tel:+942816169">+94 2816169</a>
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="contact-form-section">
        <div class="container">
            <div class="contact-wrapper">
                
                <div class="map-box" data-aos="fade-right">
                    <iframe 
                        src="https://maps.google.com/maps?width=100%25&height=600&hl=en&q=House%20of%20Aluminium%20(Pvt)%20Ltd,%20No%20385,%20High%20Level%20Road,%20Gangodawila,%20Nugegoda+(House%20of%20Aluminium)&t=&z=16&ie=UTF8&iwloc=B&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="form-box" data-aos="fade-left">
                    <div class="section-title" style="text-align: left; margin-bottom: 30px;">
                        <h2>Have Any Questions?</h2>
                        <div class="title-line" style="margin: 15px 0 0 0;"></div>
                    </div>

                    <?php if (!empty($msg)): ?>
                        <div class="contact-alert alert-<?php echo $msg_type; ?>">
                            <i class="fas <?php echo $msg_type == 'success' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="contact.php" method="POST" class="premium-form">
                        <div class="input-group">
                            <input type="text" name="name" placeholder="Name *" required>
                        </div>
                        <div class="input-group">
                            <input type="email" name="email" placeholder="E-mail *" required>
                        </div>
                        <div class="input-group">
                            <input type="tel" name="phone" placeholder="Telephone *" required>
                        </div>
                        <div class="input-group">
                            <textarea name="message" rows="4" placeholder="Message *" required></textarea>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">By using this form you agree with the storage and handling of your data by this website.</label>
                        </div>

                        <button type="submit" class="primary-btn submit-btn">SEND MESSAGE!</button>
                    </form>

                    <div class="contact-socials">
                        <a href="https://www.instagram.com/houseofalu/" target="_blank" class="social-btn"><i class="fab fa-instagram"></i> @House Of Aluminium</a>
                        <a href="https://www.facebook.com/profile.php?id=100064087003026#" target="_blank" class="social-btn"><i class="fab fa-facebook-f"></i> @House Of Aluminium</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>