<?php
// admin/dashboard.php
require_once '../functions.php';
// checkAdminAuth(); // Check if admin is logged in (uncomment when auth is implemented)

// Sample data for demonstration (replace with actual database queries)
$recentOrders = 15;
$totalProducts = 24;
$totalMembers = 35;
$totalStaff = 8;
$monthlySales = 12500.50;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BeanMarket</title>
    <link rel="stylesheet" href="../home_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }

        .admin-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #6F4E37;
        }

        .admin-header h1 {
            color: #6F4E37;
            margin-bottom: 10px;
            font-size: 2.5em;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, #6F4E37, #8B4513);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(111, 78, 55, 0.3);
        }

        .stat-card:hover::before {
            top: -25%;
            right: -25%;
        }

        .stat-icon {
            font-size: 3em;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .action-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .action-card:hover {
            border-color: #6F4E37;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .action-icon {
            font-size: 2.5em;
            color: #6F4E37;
            margin-bottom: 15px;
        }

        .action-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .action-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .action-btn {
            background: #6F4E37;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .action-btn:hover {
            background: #8B4513;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .recent-activity {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .activity-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: #6F4E37;
        }

        .activity-header i {
            font-size: 1.5em;
            margin-right: 10px;
        }

        .activity-list {
            list-style: none;
            padding: 0;
        }

        .activity-item {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            transition: background 0.3s ease;
        }

        .activity-item:hover {
            background: white;
            border-radius: 8px;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: #6F4E37;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-time {
            color: #888;
            font-size: 0.9em;
        }

        .logout-section {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            font-size: 1.1em;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-stat {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .quick-stat-number {
            font-size: 1.8em;
            font-weight: bold;
            color: #6F4E37;
        }

        .quick-stat-label {
            color: #666;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .admin-actions {
                grid-template-columns: 1fr;
            }

            .dashboard-stats {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .stat-card {
                padding: 20px;
            }

            .stat-number {
                font-size: 2em;
            }

            .action-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> BeanMarket Admin Dashboard</h1>
            <p>Welcome back, Administrator! Manage your coffee shop efficiently.</p>
        </div>

        <!-- Dashboard Statistics -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-number"><?php echo $recentOrders; ?></div>
                <div class="stat-label">Recent Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-box"></i></div>
                <div class="stat-number"><?php echo $totalProducts; ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?php echo $totalMembers; ?></div>
                <div class="stat-label">Registered Members</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-number">RM <?php echo number_format($monthlySales, 2); ?></div>
                <div class="stat-label">Monthly Sales</div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="quick-stat">
                <div class="quick-stat-number"><?php echo $totalStaff; ?></div>
                <div class="quick-stat-label">Staff Members</div>
            </div>
            <div class="quick-stat">
                <div class="quick-stat-number">6</div>
                <div class="quick-stat-label">Categories</div>
            </div>
            <div class="quick-stat">
                <div class="quick-stat-number">3</div>
                <div class="quick-stat-label">Low Stock Items</div>
            </div>
            <div class="quick-stat">
                <div class="quick-stat-number">98%</div>
                <div class="quick-stat-label">Customer Satisfaction</div>
            </div>
        </div>

        <!-- Admin Action Cards -->
        <div class="admin-actions">
            <div class="action-card">
                <div class="action-icon"><i class="fas fa-tags"></i></div>
                <div class="action-title">Manage Categories</div>
                <div class="action-description">Add, edit, and organize product categories to keep your menu structured and easy to navigate.</div>
                <a href="manage_categories.php" class="action-btn">
                    <i class="fas fa-cog"></i> Manage Categories
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon"><i class="fas fa-box"></i></div>
                <div class="action-title">Manage Products</div>
                <div class="action-description">Add new coffee products, update prices, manage inventory, and organize your complete product catalog.</div>
                <a href="manage_products.php" class="action-btn">
                    <i class="fas fa-cog"></i> Manage Products
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="action-title">Manage Orders</div>
                <div class="action-description">Process customer orders, update order status, and track order fulfillment in real-time.</div>
                <a href="manage_orders.php" class="action-btn">
                    <i class="fas fa-list-alt"></i> View Orders
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon"><i class="fas fa-users"></i></div>
                <div class="action-title">Manage Members</div>
                <div class="action-description">View and manage customer accounts, member information, and customer relationship management.</div>
                <a href="manage_members.php" class="action-btn">
                    <i class="fas fa-user-friends"></i> Manage Members
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon"><i class="fas fa-user-tie"></i></div>
                <div class="action-title">Manage Staff</div>
                <div class="action-description">Handle staff accounts, roles, permissions, and employee information management.</div>
                <a href="manage_staff.php" class="action-btn">
                    <i class="fas fa-users-cog"></i> Manage Staff
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon"><i class="fas fa-chart-line"></i></div>
                <div class="action-title">Sales Reports</div>
                <div class="action-description">Generate detailed sales reports, analyze trends, and export data for business insights.</div>
                <a href="sales_report.php" class="action-btn">
                    <i class="fas fa-chart-bar"></i> View Reports
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <div class="activity-header">
                <i class="fas fa-clock"></i>
                <h3>Recent Activity</h3>
            </div>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon"><i class="fas fa-plus"></i></div>
                    <div class="activity-content">
                        <div>New product "Caramel Macchiato" added to menu</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon"><i class="fas fa-edit"></i></div>
                    <div class="activity-content">
                        <div>Updated pricing for "Premium Espresso Blend"</div>
                        <div class="activity-time">4 hours ago</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                    <div class="activity-content">
                        <div>3 new customers registered today</div>
                        <div class="activity-time">6 hours ago</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon"><i class="fas fa-chart-up"></i></div>
                    <div class="activity-content">
                        <div>Monthly sales target achieved (105%)</div>
                        <div class="activity-time">Yesterday</div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Logout Section -->
        <div class="logout-section">
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <script>
        // Add some interactivity to the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat cards on load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    card.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 150);
            });

            // Add click animation to action cards
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'A') {
                        const link = this.querySelector('.action-btn');
                        if (link) {
                            link.click();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>