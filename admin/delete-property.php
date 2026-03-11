<?php
require_once 'includes/auth.php';
require_once '../db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Use prepared statement to fetch image name first
    $img_stmt = $conn->prepare("SELECT image FROM properties WHERE id = ?");
    $img_stmt->bind_param("i", $id);
    $img_stmt->execute();
    $img_res = $img_stmt->get_result();

    if ($img_res->num_rows > 0) {
        $row = $img_res->fetch_assoc();
        $image_path = "../assets/images/" . $row['image'];

        // Delete from database
        $stmt = $conn->prepare("DELETE FROM properties WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete physical file if it exists and isn't a default/special image
            if (file_exists($image_path) && !empty($row['image'])) {
                unlink($image_path);
            }
            header("Location: dashboard.php?msg=Property and associated image deleted successfully!");
            exit();
        } else {
            header("Location: dashboard.php?msg=Error deleting property record.");
            exit();
        }
    } else {
        header("Location: dashboard.php?msg=Property not found.");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>