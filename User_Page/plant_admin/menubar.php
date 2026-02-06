<?php
// nav.php - Professional Sidebar Menu for Admin Dashboard
// Assumes Bootstrap 5, Font Awesome loaded in pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Move to individual pages if needed, but include here for completeness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: linear-gradient(180deg, #198754 0%, #20c997 100%);
            --sidebar-text: #fff;
            --sidebar-hover: rgba(255,255,255,0.1);
            --sidebar-active: rgba(255,255,255,0.2);
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: width 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sidebar-header h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--sidebar-text);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            transition: background 0.2s;
        }
        .sidebar-toggle:hover {
            background: var(--sidebar-hover);
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu > li {
            margin: 0.25rem 0;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--sidebar-hover);
            border-left-color: #fff;
            color: #fff;
        }
        .sidebar-menu i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-menu .menu-text {
            opacity: 1;
            transition: opacity 0.3s;
        }
        .sidebar.collapsed .menu-text {
            opacity: 0;
            position: absolute;
            left: -9999px;
        }
        .sidebar.collapsed .sidebar-menu a {
            justify-content: center;
            padding: 0.875rem 0.5rem;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        .main-content.expanded {
            margin-left: var(--sidebar-width);
        }
        .main-content.sidebar-collapsed {
            margin-left: 70px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .menu-text,
            .sidebar-header h4 {
                display: none;
            }
            .main-content {
                margin-left: 70px;
            }
        }
        /* Logo Animation */
        .logo-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .logo-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .gradient-text {
            background: linear-gradient(90deg, #fff, #f0f9ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .topbar {
            background: #fff;
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-dropdown {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--sidebar-bg);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-wrapper">
                <img src="../icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant Shop Logo" class="logo-img" />
                <span class="gradient-text">KP Plant Shop</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="index.php" class="active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="products.php">
                    <i class="fas fa-boxes-stacked"></i>
                    <span class="menu-text">Products</span>
                </a>
            </li>
            <li>
                <a href="categories.php">
                    <i class="fas fa-tags"></i>
                    <span class="menu-text">Categories</span>
                </a>
            </li>
            <li>
                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="menu-text">Orders</span>
                </a>
            </li>
            <li>
                <a href="users.php">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Users</span>
                </a>
            </li>
            <li>
                <a href="setting.php">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Topbar (Optional: For mobile or additional header) -->
    <nav class="topbar d-lg-none">
        <button class="sidebar-toggle d-lg-none" id="mobileToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="user-dropdown">
            <div class="user-avatar">A</div> <!-- Replace with dynamic admin initial -->
            <span>Admin</span>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <main class="main-content" id="mainContent">
        <!-- Page content goes here, e.g., include your dashboard/products etc. -->
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle Functionality
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggles = document.querySelectorAll('.sidebar-toggle');

        toggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
                const icon = toggle.querySelector('i');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            });
        });

        // Active Menu Highlight (based on current URL)
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname.split('/').pop() || 'index.php';
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });

        // Mobile Toggle (overlays sidebar on small screens if needed)
        document.getElementById('mobileToggle')?.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>