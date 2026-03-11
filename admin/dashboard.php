<?php
require_once 'includes/auth.php';
require_once '../db.php';

$page_title = "Admin Dashboard";
require_once 'includes/header.php';

// Stats Calculation
// Stats Calculation with Error Handling
$prop_count = 0;
$sold_count = 0;
$lead_count = 0;

try {
    $res1 = $conn->query("SELECT COUNT(*) as total FROM properties");
    $prop_count = ($res1) ? $res1->fetch_assoc()['total'] : 0;

    $res2 = $conn->query("SELECT COUNT(*) as total FROM properties WHERE status = 'Sold'");
    $sold_count = ($res2) ? $res2->fetch_assoc()['total'] : 0;

    $res3 = $conn->query("SELECT COUNT(*) as total FROM contacts");
    $lead_count = ($res3) ? $res3->fetch_assoc()['total'] : 0;
} catch (Exception $e) {
    // Fail silently but don't crash the page
}

// Fetch all properties for management
$query = "SELECT * FROM properties ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<main class="container" style="padding: 40px 0;">
    <!-- Premium Stats Widgets -->
    <div class="stats-grid">
        <div class="stat-card-premium">
            <div class="stat-icon icon-blue">
                <i class="fa-solid fa-building"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $prop_count; ?></h3>
                <p>Total Properties</p>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon icon-green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $sold_count; ?></h3>
                <p>Properties Sold</p>
            </div>
        </div>
        <div class="stat-card-premium">
            <div class="stat-icon icon-orange">
                <i class="fa-solid fa-bolt-lightning"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $lead_count; ?></h3>
                <p>Active Leads</p>
            </div>
        </div>
    </div>

    <!-- Management Section -->
    <div class="admin-card">
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
            <div>
                <h2 style="font-weight: 700; color: var(--primary-color);">Property Inventory</h2>
                <p style="color: #64748b; font-size: 0.9rem;">Manage and track your real estate listings</p>
            </div>
            <a href="add-property.php" class="btn"
                style="margin-top: 0; border-radius: 12px; padding: 12px 24px; font-weight: 600;">
                <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> New Listing
            </a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div
                style="background: rgba(46, 204, 113, 0.1); color: #27ae60; padding: 15px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; font-weight: 500;">
                <i class="fa-solid fa-circle-check"></i>
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div style="overflow-x: auto;">
            <table class="admin-table-premium">
                <thead>
                    <tr>
                        <th style="text-align: left;">Property Info</th>
                        <th>Status</th>
                        <th>Pricing</th>
                        <th>Listed Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td data-label="Property Info">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="../assets/images/<?php echo $row['image']; ?>" width="80" height="60"
                                            style="border-radius: 12px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1);"
                                            onerror="this.src='https://via.placeholder.com/80x60?text=Property'">
                                        <div>
                                            <div style="font-weight: 600; color: var(--primary-color);">
                                                <?php echo htmlspecialchars($row['title']); ?>
                                            </div>
                                            <div style="font-size: 0.8rem; color: #64748b;"><i class="fa-solid fa-location-dot"
                                                    style="margin-right: 4px;"></i>
                                                <?php echo htmlspecialchars($row['location']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;" data-label="Status">
                                    <span class="badge badge-<?php echo strtolower($row['status'] ?? 'available'); ?>"
                                        style="padding: 6px 14px; border-radius: 20px;">
                                        <?php echo $row['status'] ?? 'Available'; ?>
                                    </span>
                                </td>
                                <td style="text-align: center; font-weight: 700; color: var(--accent-color);"
                                    data-label="Price">
                                    Rs. <?php echo number_format($row['price'], 2); ?>
                                </td>
                                <td style="text-align: center; color: #64748b; font-size: 0.9rem;" data-label="Listed Date">
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                </td>
                                <td style="text-align: right;" data-label="Actions">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="edit-property.php?id=<?php echo $row['id']; ?>"
                                            class="action-btn-pill btn-edit-pill">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="delete-property.php?id=<?php echo $row['id']; ?>"
                                            class="action-btn-pill btn-delete-pill"
                                            onclick="return confirm('Archive this property listing?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 60px; color: #94a3b8;">
                                <i class="fa-solid fa-folder-open"
                                    style="font-size: 3rem; margin-bottom: 20px; display: block;"></i>
                                No property listings found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>