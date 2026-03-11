<?php
require_once 'db.php';
include 'includes/header.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Use prepared statement for security
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container'><p style='margin: 50px 0; text-align: center;'>Property not found.</p></div>";
} else {
    $row = $result->fetch_assoc();
    ?>

    <main class="container" style="margin-top: 50px;">
        <div style="display: flex; flex-wrap: wrap; gap: 40px; margin-bottom: 50px;">
            <div style="flex: 1; min-width: 300px;">
                <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>"
                    style="width: 100%; border-radius: 8px; box-shadow: var(--shadow);"
                    onerror="this.src='https://via.placeholder.com/800x600?text=Property+Image'">
            </div>
            <div style="flex: 1; min-width: 300px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 15px;"><?php echo $row['title']; ?></h1>
                <div class="price"
                    style="color: var(--accent-color); font-size: 1.5rem; font-weight: bold; margin-bottom: 20px;">
                    Rs. <?php echo number_format($row['price'], 2); ?>
                </div>
                <p style="color: #777; margin-bottom: 20px;"><strong>Location:</strong> <?php echo $row['location']; ?></p>
                <div style="margin-bottom: 30px;">
                    <h3 style="margin-bottom: 10px;">Description</h3>
                    <p><?php echo nl2br($row['description']); ?></p>
                </div>
                <a href="contact.php?property=<?php echo urlencode($row['title']); ?>" class="btn">Inquire About This
                    Property</a>
            </div>
        </div>
    </main>

    <?php
}
include 'includes/footer.php';
?>