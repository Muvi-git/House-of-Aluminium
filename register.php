<?php 
include 'db_connect.php'; 
include 'header.php'; 


$reg_error_msg = "";
$reg_msg_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_full_name = $conn->real_escape_string($_POST['full_name']);
    $reg_username = $conn->real_escape_string($_POST['username']);
    $reg_email = $conn->real_escape_string($_POST['email']);
    $reg_password = $_POST['password'];
    $reg_confirm_password = $_POST['confirm_password'];

    if (!empty($reg_full_name) && !empty($reg_username) && !empty($reg_email) && !empty($reg_password)) {
        if ($reg_password !== $reg_confirm_password) {
            $reg_error_msg = "Passwords do not match!";
            $reg_msg_type = "error";
        } else {

            $reg_check_user = $conn->query("SELECT id FROM users WHERE username='$reg_username' OR email='$reg_email'");
            if ($reg_check_user && $reg_check_user->num_rows > 0) {
                $reg_error_msg = "Username or Email already exists!";
                $reg_msg_type = "error";
            } else {
       
                $reg_hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);
                $reg_sql = "INSERT INTO users (full_name, username, email, password) VALUES ('$reg_full_name', '$reg_username', '$reg_email', '$reg_hashed_password')";
                
                if ($conn->query($reg_sql)) {
                    $reg_error_msg = "Registration successful! Redirecting to login...";
                    $reg_msg_type = "success";
                    echo "<script>setTimeout(function(){ window.location.href='login.php'; }, 2000);</script>";
                } else {
                    $reg_error_msg = "Something went wrong. Please try again.";
                    $reg_msg_type = "error";
                }
            }
        }
    } else {
        $reg_error_msg = "All fields are required!";
        $reg_msg_type = "error";
    }
}
?>

<style>
    .auth-container { min-height: 85vh; display: flex; align-items: center; justify-content: center; background: #f8fafc; padding: 40px 20px; }
    .auth-card { width: 100%; max-width: 480px; background: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(15,23,42,0.06); border: 1px solid #e2e8f0; }
    .auth-header { text-align: center; margin-bottom: 30px; }
    .auth-header h2 { font-size: 1.8rem; color: #1e293b; font-weight: 700; margin-bottom: 8px; }
    .auth-header p { color: #64748b; font-size: 0.92rem; }
    
    .auth-alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; }
    .auth-alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .auth-alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    .auth-form-group { margin-bottom: 20px; position: relative; }
    .auth-form-group label { display: block; font-size: 0.88rem; color: #334155; font-weight: 600; margin-bottom: 8px; }
    .auth-input-wrapper { position: relative; display: flex; align-items: center; }
    .auth-input-wrapper i { position: absolute; left: 15px; color: #94a3b8; font-size: 1rem; }
    .auth-input-wrapper input { width: 100%; padding: 12px 15px 12px 42px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; background: #f8fafc; transition: all 0.3s ease; color: #334155; }
    .auth-input-wrapper input:focus { border-color: #ff3333; background: #ffffff; outline: none; box-shadow: 0 0 0 3px rgba(255,51,51,0.08); }
    

    .auth-submit-btn { width: 100%; background: #ff3333; color: #ffffff; border: none; padding: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; margin-top: 10px; }
    .auth-submit-btn:hover { background: #d92626; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,51,51,0.2); }
    
    .auth-footer { text-align: center; margin-top: 25px; color: #64748b; font-size: 0.92rem; }
    .auth-footer a { color: #ff3333; text-decoration: none; font-weight: 600; }
    .auth-footer a:hover { text-decoration: underline; }
</style>

<main class="auth-container">
    <div class="auth-card" data-aos="fade-up">
        <div class="auth-header">
            <h2>Create An Account</h2>
            <p>Join us to monitor payments and customized orders</p>
        </div>

        <?php if (!empty($reg_error_msg)): ?>
            <div class="auth-alert <?php echo $reg_msg_type; ?>">
                <i class="fas <?php echo $reg_msg_type == 'success' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                <?php echo $reg_error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="auth-form-group">
                <label>Full Name *</label>
                <div class="auth-input-wrapper">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
                </div>
            </div>

            <div class="auth-form-group">
                <label>Username *</label>
                <div class="auth-input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Choose a unique username" required>
                </div>
            </div>

            <div class="auth-form-group">
                <label>Email Address *</label>
                <div class="auth-input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="auth-form-group">
                <label>Password *</label>
                <div class="auth-input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Create a strong password" required>
                </div>
            </div>

            <div class="auth-form-group">
                <label>Confirm Password *</label>
                <div class="auth-input-wrapper">
                    <i class="fas fa-shield-alt"></i>
                    <input type="password" name="confirm_password" placeholder="Repeat your password" required>
                </div>
            </div>

            <button type="submit" class="auth-submit-btn">Register Now</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Login Here</a>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>