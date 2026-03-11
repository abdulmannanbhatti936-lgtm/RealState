<?php
require_once 'db.php';
include 'includes/header.php';

// Filtering Logic
$where_clauses = [];
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $where_clauses[] = "title LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
    $types .= "s";
}

if (!empty($_GET['location'])) {
    $where_clauses[] = "location LIKE ?";
    $params[] = "%" . $_GET['location'] . "%";
    $types .= "s";
}

if (!empty($_GET['max_price'])) {
    $where_clauses[] = "price <= ?";
    $params[] = $_GET['max_price'];
    $types .= "d";
}

$query = "SELECT * FROM properties";
if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}
$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="container">
    <h2 class="section-title">Explore Properties</h2>

    <!-- Search & Filter Form -->
    <section
        style="background: var(--white); padding: 25px; border-radius: 8px; box-shadow: var(--shadow); margin-bottom: 40px;">
        <form action="properties.php" method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="search">Keywords</label>
                <input type="text" name="search" id="search" placeholder="Modern Villa..."
                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" placeholder="Karachi..."
                    value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="max_price">Max Price (Rs.)</label>
                <input type="number" name="max_price" id="max_price" placeholder="10000000"
                    value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn"
                style="height: 44px; margin-top: 0; width: 100%; border: none; cursor: pointer;">Search</button>
        </form>
    </section>

    <div class="property-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php $delay = 0; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="property-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>" data-aos-duration="800">
                    <div style="position: relative;">
                        <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>"
                            onerror="this.src='https://via.placeholder.com/400x300?text=Property+Image'">
                        <span
                            style="position: absolute; top: 10px; right: 10px; background: var(--primary-color); color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">
                            <?php echo $row['status'] ?? 'Available'; ?>
                        </span>
                    </div>
                    <div class="property-content">
                        <h3><?php echo $row['title']; ?></h3>
                        <p class="location"><?php echo $row['location']; ?></p>
                        <div class="price">Rs. <?php echo number_format($row['price'], 0); ?></div>
                        <a href="property-detail.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1/-1; padding: 50px 0;">No properties match your criteria.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>