<?php
// admin/sales_report.php
require_once '../functions.php';
checkAdminAuth(); // Check if admin is logged in

// Mock sales data (in real app, this would come from database)
$daily_sales = [
  ['date' => '2024-12-16', 'orders' => 15, 'revenue' => 325.50],
  ['date' => '2024-12-17', 'orders' => 22, 'revenue' => 456.75],
  ['date' => '2024-12-18', 'orders' => 18, 'revenue' => 389.25],
  ['date' => '2024-12-19', 'orders' => 28, 'revenue' => 612.00],
  ['date' => '2024-12-20', 'orders' => 31, 'revenue' => 734.25]
];

$top_products = [
  ['name' => 'Espresso', 'quantity' => 45, 'revenue' => 225.00],
  ['name' => 'Latte', 'quantity' => 38, 'revenue' => 266.00],
  ['name' => 'Cappuccino', 'quantity' => 32, 'revenue' => 224.00],
  ['name' => 'Americano', 'quantity' => 28, 'revenue' => 168.00],
  ['name' => 'Mocha', 'quantity' => 25, 'revenue' => 200.00]
];

$monthly_overview = [
  'total_revenue' => 8567.50,
  'total_orders' => 387,
  'avg_order_value' => 22.14,
  'new_customers' => 56,
  'returning_customers' => 331
];

// Calculate totals
$week_revenue = array_sum(array_column($daily_sales, 'revenue'));
$week_orders = array_sum(array_column($daily_sales, 'orders'));
$avg_daily_revenue = $week_revenue / count($daily_sales);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Report - Admin Dashboard</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    .admin-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
    }

    .admin-header {
      background: rgba(255, 255, 255, 0.95);
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .admin-nav {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .nav-btn {
      background: #6F4E37;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
      font-weight: bold;
    }

    .nav-btn:hover,
    .nav-btn.active {
      background: #8B4513;
    }

    .content-section {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 1.8em;
      color: #6F4E37;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }

    .section-title i {
      margin-right: 10px;
    }

    .report-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: linear-gradient(135deg, #fff, #f8f9fa);
      padding: 25px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
      border-left: 4px solid #6F4E37;
      position: relative;
      overflow: hidden;
    }

    .stat-card.revenue {
      border-left-color: #28a745;
    }

    .stat-card.orders {
      border-left-color: #007bff;
    }

    .stat-card.average {
      border-left-color: #ffc107;
    }

    .stat-card.customers {
      border-left-color: #6f42c1;
    }

    .stat-number {
      font-size: 2.2em;
      font-weight: bold;
      color: #6F4E37;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #666;
      font-weight: 500;
    }

    .stat-icon {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 1.5em;
      color: rgba(111, 78, 55, 0.3);
    }

    .chart-container {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .chart-title {
      font-size: 1.3em;
      color: #6F4E37;
      margin-bottom: 20px;
      text-align: center;
    }

    .bar-chart {
      display: flex;
      align-items: end;
      height: 200px;
      gap: 10px;
      padding: 20px 0;
      border-bottom: 2px solid #dee2e6;
      position: relative;
    }

    .bar {
      background: linear-gradient(to top, #6F4E37, #8B4513);
      border-radius: 4px 4px 0 0;
      position: relative;
      min-width: 40px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .bar:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(111, 78, 55, 0.3);
    }

    .bar-label {
      position: absolute;
      bottom: -25px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 0.8em;
      color: #666;
    }

    .bar-value {
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 0.8em;
      font-weight: bold;
      color: #333;
    }

    .report-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .report-table th,
    .report-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #dee2e6;
    }

    .report-table th {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      font-weight: bold;
      color: #333;
    }

    .report-table tr:hover {
      background: #f8f9fa;
    }

    .report-controls {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      gap: 15px;
      align-items: center;
      flex-wrap: wrap;
    }

    .control-group {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .control-group label {
      font-weight: bold;
      color: #333;
      font-size: 0.9em;
    }

    .control-group input,
    .control-group select {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      margin: 5px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white;
    }

    .btn-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .btn-warning {
      background: linear-gradient(135deg, #ffc107, #e0a800);
      color: #212529;
    }

    .btn-danger {
      background: linear-gradient(135deg, #dc3545, #c82333);
      color: white;
    }

    .btn-info {
      background: linear-gradient(135deg, #17a2b8, #138496);
      color: white;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .report-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    .progress-bar {
      background: #e9ecef;
      border-radius: 10px;
      overflow: hidden;
      height: 20px;
      margin: 5px 0;
    }

    .progress-fill {
      background: linear-gradient(90deg, #28a745, #20c997);
      height: 100%;
      border-radius: 10px;
      transition: width 0.5s ease;
      position: relative;
    }

    .progress-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 0.8em;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 15px;
      }

      .report-stats {
        grid-template-columns: repeat(2, 1fr);
      }

      .report-grid {
        grid-template-columns: 1fr;
      }

      .report-controls {
        flex-direction: column;
        align-items: stretch;
      }

      .bar-chart {
        height: 150px;
      }
    }

    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }

      100% {
        transform: scale(1);
      }
    }

    @media print {

      .admin-nav,
      .report-controls,
      .btn {
        display: none !important;
      }

      body {
        background: white !important;
      }

      .content-section {
        box-shadow: none !important;
        border: 1px solid #ddd;
      }
    }
  </style>
</head>

<body>
  <div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header fade-in">
      <h1><i class="fas fa-chart-bar"></i> Sales Report & Analytics</h1>
      <div class="admin-nav">
        <a href="dashboard.php" class="nav-btn"><i class="fas fa-dashboard"></i> Dashboard</a>
        <a href="manage_staff.php" class="nav-btn"><i class="fas fa-users"></i> Staff</a>
        <a href="manage_members.php" class="nav-btn"><i class="fas fa-user-friends"></i> Members</a>
        <a href="manage_orders.php" class="nav-btn"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="sales_report.php" class="nav-btn active"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="logout.php" class="nav-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- Report Controls -->
    <div class="report-controls fade-in">
      <div class="control-group">
        <label for="dateFrom">From Date:</label>
        <input type="date" id="dateFrom" value="2024-12-16">
      </div>
      <div class="control-group">
        <label for="dateTo">To Date:</label>
        <input type="date" id="dateTo" value="2024-12-20">
      </div>
      <div class="control-group">
        <label for="reportType">Report Type:</label>
        <select id="reportType">
          <option value="daily">Daily Report</option>
          <option value="weekly">Weekly Report</option>
          <option value="monthly">Monthly Report</option>
          <option value="yearly">Yearly Report</option>
        </select>
      </div>
      <div style="align-self: end;">
        <button onclick="generateReport()" class="btn btn-primary">
          <i class="fas fa-chart-line"></i> Generate Report
        </button>
        <button onclick="exportReport()" class="btn btn-success">
          <i class="fas fa-download"></i> Export PDF
        </button>
        <button onclick="printReport()" class="btn btn-info">
          <i class="fas fa-print"></i> Print Report
        </button>
      </div>
    </div>

    <!-- Monthly Overview Statistics -->
    <div class="report-stats fade-in">
      <div class="stat-card revenue pulse">
        <i class="fas fa-dollar-sign stat-icon"></i>
        <div class="stat-number">$<?php echo number_format($monthly_overview['total_revenue'], 2); ?></div>
        <div class="stat-label">Total Revenue</div>
      </div>
      <div class="stat-card orders">
        <i class="fas fa-shopping-bag stat-icon"></i>
        <div class="stat-number"><?php echo $monthly_overview['total_orders']; ?></div>
        <div class="stat-label">Total Orders</div>
      </div>
      <div class="stat-card average">
        <i class="fas fa-calculator stat-icon"></i>
        <div class="stat-number">$<?php echo number_format($monthly_overview['avg_order_value'], 2); ?></div>
        <div class="stat-label">Avg Order Value</div>
      </div>
      <div class="stat-card customers">
        <i class="fas fa-users stat-icon"></i>
        <div class="stat-number"><?php echo $monthly_overview['new_customers']; ?></div>
        <div class="stat-label">New Customers</div>
      </div>
    </div>

    <!-- Report Grid -->
    <div class="report-grid">
      <!-- Daily Sales Chart -->
      <div class="content-section fade-in">
        <div class="section-title">
          <i class="fas fa-chart-line"></i>
          Daily Sales Performance
        </div>

        <div class="chart-container">
          <div class="chart-title">Revenue Trend (Last 5 Days)</div>
          <div class="bar-chart" id="salesChart">
            <?php foreach ($daily_sales as $day): ?>
              <div class="bar" style="height: <?php echo ($day['revenue'] / 800) * 100; ?>%;"
                title="<?php echo date('M j', strtotime($day['date'])); ?> - $<?php echo number_format($day['revenue'], 2); ?>">
                <div class="bar-value">$<?php echo number_format($day['revenue'], 0); ?></div>
                <div class="bar-label"><?php echo date('M j', strtotime($day['date'])); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Daily Sales Table -->
        <table class="report-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Orders</th>
              <th>Revenue</th>
              <th>Avg Order</th>
              <th>Growth</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($daily_sales as $index => $day):
              $prevRevenue = $index > 0 ? $daily_sales[$index - 1]['revenue'] : $day['revenue'];
              $growth = $prevRevenue > 0 ? (($day['revenue'] - $prevRevenue) / $prevRevenue) * 100 : 0;
              $avgOrder = $day['orders'] > 0 ? $day['revenue'] / $day['orders'] : 0;
            ?>
              <tr>
                <td><?php echo date('M j, Y', strtotime($day['date'])); ?></td>
                <td><strong><?php echo $day['orders']; ?></strong></td>
                <td><strong style="color: #28a745;">$<?php echo number_format($day['revenue'], 2); ?></strong></td>
                <td>$<?php echo number_format($avgOrder, 2); ?></td>
                <td>
                  <span style="color: <?php echo $growth >= 0 ? '#28a745' : '#dc3545'; ?>;">
                    <i class="fas fa-arrow-<?php echo $growth >= 0 ? 'up' : 'down'; ?>"></i>
                    <?php echo number_format(abs($growth), 1); ?>%
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Top Products and Summary -->
      <div>
        <!-- Week Summary -->
        <div class="content-section fade-in">
          <div class="section-title">
            <i class="fas fa-calendar-week"></i>
            Week Summary
          </div>

          <div style="text-align: center; margin-bottom: 20px;">
            <div style="font-size: 2em; font-weight: bold; color: #28a745;">
              $<?php echo number_format($week_revenue, 2); ?>
            </div>
            <div style="color: #666;">Total Revenue</div>

            <div style="font-size: 1.5em; font-weight: bold; color: #007bff; margin-top: 15px;">
              <?php echo $week_orders; ?>
            </div>
            <div style="color: #666;">Total Orders</div>

            <div style="font-size: 1.2em; font-weight: bold; color: #ffc107; margin-top: 15px;">
              $<?php echo number_format($avg_daily_revenue, 2); ?>
            </div>
            <div style="color: #666;">Avg Daily Revenue</div>
          </div>
        </div>

        <!-- Top Products -->
        <div class="content-section fade-in">
          <div class="section-title">
            <i class="fas fa-trophy"></i>
            Top Products
          </div>

          <?php foreach ($top_products as $index => $product):
            $maxQuantity = max(array_column($top_products, 'quantity'));
            $percentage = ($product['quantity'] / $maxQuantity) * 100;
          ?>
            <div style="margin-bottom: 15px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                <span style="font-weight: bold;"><?php echo $product['name']; ?></span>
                <span style="color: #28a745; font-weight: bold;">$<?php echo number_format($product['revenue'], 2); ?></span>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $percentage; ?>%;">
                  <div class="progress-text"><?php echo $product['quantity']; ?> sold</div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Additional Analytics -->
    <div class="content-section fade-in">
      <div class="section-title">
        <i class="fas fa-chart-pie"></i>
        Business Insights
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #28a745;">
          <h4 style="color: #28a745; margin-bottom: 10px;"><i class="fas fa-trending-up"></i> Performance Highlights</h4>
          <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> 15% increase in weekly revenue</li>
            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> Average order value up 8%</li>
            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> Customer retention rate: 87%</li>
            <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i> Peak hours: 8-10 AM, 2-4 PM</li>
          </ul>
        </div>

        <div style="background: #fff3cd; padding: 20px; border-radius: 10px; border-left: 4px solid #ffc107;">
          <h4 style="color: #856404; margin-bottom: 10px;"><i class="fas fa-lightbulb"></i> Recommendations</h4>
          <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 8px;"><i class="fas fa-star" style="color: #ffc107; margin-right: 8px;"></i> Promote Espresso during peak hours</li>
            <li style="margin-bottom: 8px;"><i class="fas fa-star" style="color: #ffc107; margin-right: 8px;"></i> Launch afternoon combo deals</li>
            <li style="margin-bottom: 8px;"><i class="fas fa-star" style="color: #ffc107; margin-right: 8px;"></i> Increase pastry variety</li>
            <li style="margin-bottom: 8px;"><i class="fas fa-star" style="color: #ffc107; margin-right: 8px;"></i> Consider loyalty program expansion</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Generate report functionality
    function generateReport() {
      const dateFrom = document.getElementById('dateFrom').value;
      const dateTo = document.getElementById('dateTo').value;
      const reportType = document.getElementById('reportType').value;

      if (!dateFrom || !dateTo) {
        alert('Please select both start and end dates');
        return;
      }

      // Simulate report generation
      alert(`Generating ${reportType} report from ${dateFrom} to ${dateTo}...\\n(In a real application, this would generate a custom report)`);
    }

    function exportReport() {
      alert('Exporting report as PDF...\\n(In a real application, this would generate a PDF file)');
    }

    function printReport() {
      window.print();
    }

    // Animate progress bars on load
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(() => {
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
          const width = bar.style.width;
          bar.style.width = '0%';
          setTimeout(() => {
            bar.style.width = width;
          }, 300);
        });
      }, 500);

      // Animate bars
      const bars = document.querySelectorAll('.bar');
      bars.forEach((bar, index) => {
        const height = bar.style.height;
        bar.style.height = '0%';
        setTimeout(() => {
          bar.style.height = height;
        }, 800 + (index * 200));
      });
    });

    // Auto-refresh data every 5 minutes
    setInterval(function() {
      console.log('Refreshing sales data...');
      // In real app, this would fetch updated data via AJAX
    }, 300000);

    // Add interactive tooltips
    document.querySelectorAll('.bar').forEach(bar => {
      bar.addEventListener('mouseover', function() {
        this.style.opacity = '0.8';
      });

      bar.addEventListener('mouseout', function() {
        this.style.opacity = '1';
      });
    });
  </script>
</body>

</html>