<?php
// admin/profile.php
$page_title = "Admin Profile";
require_once 'includes/header.php';

$error = '';
$success = '';

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $admin_id);

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        $admin_name = $name; // Update local variable for header
    } else {
        $error = "Error updating profile.";
    }
}

// Handle Password Change
if (isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Verify old password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (password_verify($old_pass, $res['password'])) {
        if ($new_pass === $confirm_pass) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $hashed, $admin_id);
            if ($update->execute()) {
                $success = "Password changed successfully!";
            } else {
                $error = "Error updating password.";
            }
        } else {
            $error = "New passwords do not match!";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin_data = $stmt->get_result()->fetch_assoc();
?>

<main class="container" style="padding: 50px 0;">
    <h2 class="section-title">Account Settings</h2>

    <?php if ($error): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 25px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 25px;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
        <!-- General Info -->
        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: var(--shadow);">
            <h3><i class="fa-solid fa-user-gear"></i> Update Profile</h3>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            <form action="profile.php" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($admin_data['name']); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>"
                        required>
                </div>
                <button type="submit" name="update_profile" class="btn"
                    style="width: 100%; border: none; cursor: pointer;">Save Changes</button>
            </form>
        </div>

        <!-- Password Change -->
        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: var(--shadow);">
            <h3><i class="fa-solid fa-lock"></i> Change Password</h3>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            <form action="profile.php" method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="old_password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-secondary"
                    style="width: 100%; border: none; cursor: pointer;">Update Password</button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>