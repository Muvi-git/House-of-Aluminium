<?php 
include 'db_connect.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$auth_error_msg = "";
$auth_msg_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth_input = $conn->real_escape_string($_POST['login_input']);
    $auth_password = $_POST['password'];

    if (!empty($auth_input) && !empty($auth_password)) {
        $auth_query = "SELECT * FROM users WHERE username='$auth_input' OR email='$auth_input'";
        $auth_result = $conn->query($auth_query);
        
        if ($auth_result && $auth_result->num_rows > 0) {
            $auth_row = $auth_result->fetch_assoc();
            
            if (password_verify($auth_password, $auth_row['password'])) {
                $_SESSION['user_id'] = $auth_row['id'];
                $_SESSION['username'] = $auth_row['username'];
                $_SESSION['full_name'] = $auth_row['full_name'];
                $_SESSION['role'] = $auth_row['role']; // 🎯 ඩේටාබේස් එකේ තියෙන role එක සෙෂන් එකට එකතු කළා
                
         
                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin/admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $auth_error_msg = "Incorrect password! Please try again.";
                $auth_msg_type = "error";
            }
        } else {
            $auth_error_msg = "Account not found with that username/email.";
            $auth_msg_type = "error";
        }
    } else {
        $auth_error_msg = "Please fill in all fields.";
        $auth_msg_type = "error";
    }
}

include 'header.php'; 
?>

<style>
    .auth-container { min-height: 85vh; display: flex; align-items: center; justify-content: center; background: #f8fafc; padding: 40px 20px; }
    .auth-card { width: 100%; max-width: 440px; background: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(15,23,42,0.06); border: 1px solid #e2e8f0; }
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
            <h2>Welcome Back</h2>
            <p>Login to secure your architectural dashboard</p>
        </div>

        <?php if (!empty($auth_error_msg)): ?>
            <div class="auth-alert <?php echo $auth_msg_type; ?>">
                <i class="fas <?php echo $auth_msg_type == 'success' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                <?php echo $auth_error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="auth-form-group">
                <label>Username or Email *</label>
                <div class="auth-input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="login_input" placeholder="Enter username or email" required>
                </div>
            </div>

            <div class="auth-form-group">
                <label>Password *</label>
                <div class="auth-input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
            </div>

            <button type="submit" class="auth-submit-btn">Secure Login</button>
        </form>

        <div class="auth-footer">
            Don't have an account yet? <a href="register.php">Register Here</a>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>