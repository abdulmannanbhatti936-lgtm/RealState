<?php
require_once 'includes/auth.php';
require_once '../db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: leads.php?msg=Inquiry removed successfully.");
        exit();
    } else {
        echo "Error deleting inquiry: " . $conn->error;
    }
} else {
    header("Location: leads.php");
    exit();
}
?>