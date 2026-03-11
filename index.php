<?php
require_once 'db.php';
include 'includes/header.php';

// Fetch latest 5 properties
$query = "SELECT * FROM properties ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($query);
$delay = 100;
?>

<section class="hero-premium">
    <div class="hero-bg-zoom"></div>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-glass-card" data-aos="zoom-in" data-aos-duration="1000">
            <h1 class="hero-title">Find Your <span class="text-gradient">Dream Home</span></h1>
            <p class="hero-subtitle">Discover premium properties in Pakistan's most sought-after locations.</p>

            <form action="properties.php" method="GET" class="hero-search-box">
                <div class="search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Search by property title..." required>
                </div>
                <button type="submit" class="btn-hero">Search Now</button>
            </form>

            <div class="hero-badges">
                <span><i class="fa-solid fa-check"></i> Verified Listings</span>
                <span><i class="fa-solid fa-star"></i> Premium Service</span>
                <span><i class="fa-solid fa-shield-halved"></i> Secure Deals</span>
            </div>
        </div>
    </div>
</section>

<main class="container">
    <h2 class="section-title" data-aos="fade-up" data-aos-duration="1000">Latest Featured Properties</h2>

    <div class="property-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php $delay = 0; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="property-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>" data-aos-duration="1000">
                    <?php $delay += 100; ?>
                    <div style="position: relative;">
                        <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>"
                            onerror="this.src='https://via.placeholder.com/400x300?text=Property+Image'">
                        <span class="badge badge-<?php echo strtolower($row['status'] ?? 'available'); ?>"
                            style="position: absolute; top: 15px; right: 15px;">
                            <?php echo $row['status'] ?? 'Available'; ?>
                        </span>
                    </div>
                    <div class="property-content">
                        <h3><?php echo $row['title']; ?></h3>
                        <p class="location"><i class="fa-solid fa-location-dot"
                                style="margin-right: 5px; color: var(--accent-color);"></i> <?php echo $row['location']; ?></p>
                        <div class="price">Rs. <?php echo number_format($row['price'], 2); ?></div>
                        <a href="property-detail.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1/-1;">No properties found.</p>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-bottom: 80px;" data-aos="fade-up">
        <a href="properties.php" class="btn btn-secondary">Browse All Properties <i class="fa-solid fa-arrow-right"
                style="margin-left: 8px;"></i></a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>