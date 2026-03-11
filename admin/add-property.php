<?php
$page_title = "Add New Property";
require_once 'includes/header.php';

// Logic to handle property addition
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = $_POST['status'];

    $image_file = $_FILES['image'];
    $image_name = time() . '_' . basename($image_file['name']); // Basic sanitization/unique name
    $target = "../assets/images/" . $image_name;

    // Validation
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $file_type = $image_file['type'];

    if (!in_array($file_type, $allowed_types)) {
        $error = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
    } elseif ($image_file['size'] > 5000000) { // 5MB limit
        $error = "File is too large. Maximum size is 5MB.";
    } elseif (move_uploaded_file($image_file['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO properties (title, location, price, image, description, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddss", $title, $location, $price, $image_name, $description, $status);

        if ($stmt->execute()) {
            echo "<script>window.location.href='dashboard.php?msg=Property listed successfully!';</script>";
            exit();
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        $error = "Failed to upload image. Please check folder permissions.";
    }
}
?>

<main class="container" style="padding: 40px 0;">
    <div class="admin-card" style="max-width: 900px; margin: 0 auto;">
        <div
            style="margin-bottom: 35px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; display: flex; align-items: center; gap: 15px;">
            <div
                style="width: 50px; height: 50px; background: rgba(230, 126, 34, 0.1); color: var(--accent-color); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <h2 style="font-weight: 700; color: var(--primary-color);">List New Property</h2>
                <p style="color: #64748b; font-size: 0.9rem;">Fill in the details to add a property to the market</p>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div
                style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 12px; margin-bottom: 25px; font-weight: 600; font-size: 0.9rem; border: 1px solid #fecaca;">
                <i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="add-property.php" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <!-- Main Details -->
                <div style="display: grid; gap: 25px;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #475569; margin-bottom: 10px; display: block;">Listing
                            Title</label>
                        <input type="text" name="title" required placeholder="e.g. Modern Villa in DHA Phase 5"
                            style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-family: inherit;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label
                                style="font-weight: 600; color: #475569; margin-bottom: 10px; display: block;">Location</label>
                            <input type="text" name="location" required placeholder="e.g. DHA Karachi"
                                style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        </div>
                        <div class="form-group">
                            <label
                                style="font-weight: 600; color: #475569; margin-bottom: 10px; display: block;">Pricing
                                (Rs.)</label>
                            <input type="number" name="price" required placeholder="e.g. 15000000"
                                style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; color: #475569; margin-bottom: 10px; display: block;">Property
                            Description</label>
                        <textarea name="description" rows="8" required
                            placeholder="Describe the ambiance, amenities, and unique selling points..."
                            style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                </div>

                <!-- Sidebar Details -->
                <div style="display: grid; gap: 25px; align-content: start;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #475569; margin-bottom: 10px; display: block;">Listing
                            Image</label>
                        <div
                            style="border: 2px dashed #cbd5e1; padding: 20px; border-radius: 16px; text-align: center; position: relative; transition: var(--transition);">
                            <i class="fa-solid fa-cloud-arrow-up"
                                style="font-size: 2rem; color: #94a3b8; margin-bottom: 10px; display: block;"></i>
                            <input type="file" name="image" required accept=".png, .jpg, .jpeg"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                            <span style="font-size: 0.85rem; color: #64748b;">PNG or JPG recommended</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label
                            style="font-weight: 600; color: #475569; margin-bottom: 10px; display: block;">Availability
                            Status</label>
                        <select name="status" required
                            style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; appearance: none; position: relative;">
                            <option value="Available">Available (For Sale)</option>
                            <option value="Sold">Mark as Sold</option>
                            <option value="Rent">List for Rent</option>
                        </select>
                    </div>

                    <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 12px;">
                        <button type="submit" name="submit" class="btn"
                            style="width: 100%; margin-top: 0; border-radius: 12px; padding: 14px; font-weight: 600;">
                            Publish Listing
                        </button>
                        <a href="dashboard.php"
                            style="text-align: center; text-decoration: none; color: #64748b; font-weight: 600; font-size: 0.95rem; padding: 10px;">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>