<?php
require_once 'includes/auth.php';
require_once '../db.php';

$page_title = "Manage Leads";
require_once 'includes/header.php';

// Fetch all contact inquiries with safety
$query = "SELECT * FROM contacts ORDER BY created_at DESC";
$leads = $conn->query($query);
$total_leads = ($leads) ? $leads->num_rows : 0;
?>

<main class="container" style="padding: 40px 0;">
    <div class="admin-card">
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
            <div>
                <h2 style="font-weight: 700; color: var(--primary-color);">Customer Inquiries</h2>
                <p style="color: #64748b; font-size: 0.9rem;">Track and respond to potential property buyers</p>
            </div>
            <div
                style="background: var(--primary-color); color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; font-size: 0.9rem;">
                <i class="fa-solid fa-envelope-open-text" style="margin-right: 8px;"></i>
                <?php echo $total_leads; ?> Total Leads
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="admin-table-premium">
                <thead>
                    <tr>
                        <th style="text-align: left;">Customer Info</th>
                        <th>Interest Path</th>
                        <th>Message Content</th>
                        <th>Received On</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($leads && $leads->num_rows > 0): ?>
                        <?php while ($row = $leads->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td data-label="Customer Info">
                                    <div style="font-weight: 600; color: var(--primary-color);">
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </div>
                                    <div style="font-size: 0.85rem; color: #64748b;"><i class="fa-solid fa-envelope"
                                            style="margin-right: 4px;"></i> <?php echo htmlspecialchars($row['email']); ?></div>
                                </td>
                                <td style="text-align: center;" data-label="Property">
                                    <span
                                        style="background: #f1f5f9; padding: 4px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; color: var(--primary-color);">
                                        <i class="fa-solid fa-house-chimney" style="margin-right: 4px; font-size: 0.75rem;"></i>
                                        <?php echo htmlspecialchars($row['property_title'] ?? 'General Inquiry'); ?>
                                    </span>
                                </td>
                                <td data-label="Message Preview">
                                    <div style="max-width: 300px; font-size: 0.9rem; color: #334155; line-height: 1.4;">
                                        <?php echo htmlspecialchars(substr($row['message'], 0, 80)) . (strlen($row['message']) > 80 ? '...' : ''); ?>
                                    </div>
                                </td>
                                <td style="text-align: center; color: #64748b; font-size: 0.9rem;" data-label="Received On">
                                    <?php echo date('M d, g:i A', strtotime($row['created_at'])); ?>
                                </td>
                                <td style="text-align: right;" data-label="Actions">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <button
                                            onclick="alert('Message: <?php echo addslashes(htmlspecialchars($row['message'])); ?>')"
                                            class="action-btn-pill btn-edit-pill">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <a href="delete-lead.php?id=<?php echo $row['id']; ?>"
                                            class="action-btn-pill btn-delete-pill"
                                            onclick="return confirm('Remove this lead?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 60px; color: #94a3b8;">
                                <i class="fa-solid fa-envelope-circle-check"
                                    style="font-size: 3rem; margin-bottom: 20px; display: block;"></i>
                                All caught up! No new inquiries.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>