<?php
// admin/includes/header.php
require_once 'auth.php';
require_once '../db.php';

// Get current admin name safely
$admin_id = $_SESSION['admin_id'] ?? null;
$admin_name = 'Admin';

if ($admin_id) {
    $admin_query = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $admin_query->bind_param("i", $admin_id);
    $admin_query->execute();
    $admin_res = $admin_query->get_result()->fetch_assoc();
    $admin_name = $admin_res['name'] ?? 'Admin';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RealEstate Pro Admin Dashboard - Manage properties, leads, and inquiries.">
    <title>
        <?php echo $page_title ?? 'Admin Panel'; ?> - RealEstate Pro
    </title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-nav {
            background: var(--primary-color);
            padding: 15px 0;
            color: #fff;
        }

        .admin-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand span {
            color: var(--accent-color);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
            align-items: center;
        }

        .nav-links li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links li a:hover {
            color: #fff;
        }

        .nav-links li a.active {
            color: var(--accent-color);
        }

        .user-profile-nav {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 18px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .user-profile-nav:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .logout-btn {
            color: #ff4d4d;
            margin-left: 10px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .logout-btn:hover {
            color: #ff3333;
            transform: scale(1.1);
        }
    </style>
</head>

<body class="admin-body">
    <nav class="admin-nav">
        <div class="container">
            <a href="dashboard.php" class="nav-brand">
                <i class="fa-solid fa-hotel"></i> Estate<span>Pro</span>
            </a>
            <button class="mobile-nav-toggle" id="admin-mobile-toggle"
                style="background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; display: none;">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <ul class="nav-links" id="admin-nav-menu">
                <li><a href="dashboard.php" class="<?php echo ($page_title == 'Admin Dashboard') ? 'active' : ''; ?>"><i
                            class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                <li><a href="leads.php" class="<?php echo ($page_title == 'Manage Leads') ? 'active' : ''; ?>"><i
                            class="fa-solid fa-users-viewfinder"></i> Leads</a></li>
                <li><a href="../index.php" target="_blank"><i class="fa-solid fa-eye"></i> View Site</a></li>
                <li>
                    <a href="profile.php" class="user-profile-nav">
                        <i class="fa-solid fa-circle-user" style="font-size: 1.2rem; color: var(--accent-color);"></i>
                        <?php echo htmlspecialchars($admin_name); ?>
                    </a>
                </li>
                <li><a href="logout.php" class="logout-btn" title="Sign Out"><i class="fa-solid fa-power-off"></i></a>
                </li>
            </ul>
        </div>
    </nav>