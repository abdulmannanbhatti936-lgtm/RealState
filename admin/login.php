<?php
session_start();
require_once '../db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Using prepared statements for security
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RealEstate Pro</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Shared Styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --login-bg: #0f172a;
            --primary-gradient: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--login-bg);
            background-image:
                radial-gradient(at 0% 0%, hsla(22, 84%, 60%, 0.15) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(210, 84%, 40%, 0.1) 0, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header .logo-icon {
            width: 64px;
            height: 64px;
            background: var(--primary-gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(230, 126, 34, 0.3);
        }

        .login-header h2 {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #94a3b8;
            margin-top: 8px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            transition: var(--transition);
        }

        .form-group input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px 16px 12px 48px;
            color: #fff;
            font-size: 15px;
            transition: var(--transition);
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(230, 126, 34, 0.15);
        }

        .form-group input:focus+i {
            color: var(--accent-color);
        }

        .btn-login {
            width: 100%;
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(230, 126, 34, 0.4);
            filter: brightness(1.1);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-links {
            text-align: center;
            margin-top: 32px;
            color: #64748b;
            font-size: 14px;
        }

        .footer-links a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: #fff;
        }

        /* Ambient Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            opacity: 0.3;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            background: var(--accent-color);
            top: -150px;
            right: -150px;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: #2c3e50;
            bottom: -200px;
            left: -200px;
        }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-wrapper">
        <div class="glass-card">
            <div class="login-header">
                <div class="logo-icon">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <h2>Admin Login</h2>
                <p>Enter your credentials to manage your properties</p>
            </div>

            <?php if ($error): ?>
                <div class="error-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" required placeholder="admin@realestate.com">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" required placeholder="••••••••">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login">
                    Sign In <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="footer-links">
                Don't have an account? <a href="signup.php">Create admin account</a>
            </div>
        </div>
    </div>
</body>

</html>