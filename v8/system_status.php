<?php
// system_status.php - Comprehensive system check
header('Content-Type: text/html; charset=UTF-8');
session_start();

$criticalErrors = [];
$warnings = [];
$successItems = [];

// Test 1: Database Configuration
try {
    require_once 'config.php';
    $successItems[] = "Database configuration loaded successfully";
    
    // Test database connection
    $pdo->query("SELECT 1");
    $successItems[] = "Database connection established";
    
} catch (Exception $e) {
    $criticalErrors[] = "Database connection failed: " . $e->getMessage();
}

// Test 2: Check required tables
if (empty($criticalErrors)) {
    try {
        // Check admin table
        $stmt = $pdo->query("SHOW TABLES LIKE 'admin'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM admin");
            $adminCount = $stmt->fetchColumn();
            $successItems[] = "Admin table exists with $adminCount records";
        } else {
            $warnings[] = "Admin table does not exist - run database setup";
        }
        
        // Check product table
        $stmt = $pdo->query("SHOW TABLES LIKE 'product'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM product");
            $productCount = $stmt->fetchColumn();
            $successItems[] = "Product table exists with $productCount records";
        } else {
            $warnings[] = "Product table does not exist - run database setup";
        }
        
    } catch (Exception $e) {
        $criticalErrors[] = "Database table check failed: " . $e->getMessage();
    }
}

// Test 3: Check functions
try {
    require_once 'functions/functions.php';
    
    $requiredFunctions = [
        'getProductById',
        'getAllProductsAdmin', 
        'addProductAdmin',
        'updateProductAdmin',
        'deleteProductAdmin',
        'checkAdminAuth',
        'handleImageUpload',
        'getAdminStats'
    ];
    
    $missingFunctions = [];
    foreach ($requiredFunctions as $func) {
        if (!function_exists($func)) {
            $missingFunctions[] = $func;
        }
    }
    
    if (empty($missingFunctions)) {
        $successItems[] = "All required functions are available";
    } else {
        $criticalErrors[] = "Missing functions: " . implode(', ', $missingFunctions);
    }
    
} catch (Exception $e) {
    $criticalErrors[] = "Functions file error: " . $e->getMessage();
}

// Test 4: Check file permissions and directories
$uploadDir = __DIR__ . '/uploads/images/';
if (!is_dir($uploadDir)) {
    if (mkdir($uploadDir, 0755, true)) {
        $successItems[] = "Upload directory created successfully";
    } else {
        $criticalErrors[] = "Cannot create upload directory";
    }
} else {
    if (is_writable($uploadDir)) {
        $successItems[] = "Upload directory is writable";
    } else {
        $warnings[] = "Upload directory exists but is not writable";
    }
}

// Test 5: Check key files exist
$keyFiles = [
    'admin/admin_login.php' => 'Admin login page',
    'admin/admin_products.php' => 'Product management',
    'admin/admin_admins.php' => 'Admin management', 
    'admin/admin_dashboard.php' => 'Admin dashboard',
    'menu.php' => 'Customer menu',
    'execute_db_setup.php' => 'Database setup'
];

foreach ($keyFiles as $file => $description) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $successItems[] = "$description file exists";
    } else {
        $criticalErrors[] = "Missing file: $file ($description)";
    }
}

// Test 6: Test login credentials (if tables exist)
if (empty($criticalErrors) && isset($adminCount) && $adminCount > 0) {
    try {
        $stmt = $pdo->prepare("SELECT password FROM admin WHERE username = ?");
        $stmt->execute(['test123']);
        $adminData = $stmt->fetch();
        
        if ($adminData && password_verify('test123', $adminData['password'])) {
            $successItems[] = "Default admin credentials (test123/test123) are working";
        } else {
            $warnings[] = "Default admin credentials may not be working correctly";
        }
    } catch (Exception $e) {
        $warnings[] = "Could not verify admin credentials: " . $e->getMessage();
    }
}

$overallStatus = empty($criticalErrors) ? 'READY' : 'NEEDS ATTENTION';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status - Coffee's Life</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { margin: 0; font-size: 2.5em; }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 15px;
        }
        .status-ready { background: #27ae60; color: white; }
        .status-attention { background: #e74c3c; color: white; }
        
        .content { padding: 30px; }
        .section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-header {
            padding: 15px 20px;
            font-weight: bold;
            color: white;
        }
        .section-success { background: #27ae60; }
        .section-warning { background: #f39c12; }
        .section-error { background: #e74c3c; }
        
        .section-body {
            padding: 20px;
            background: #f8f9fa;
        }
        .item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .item:last-child { border-bottom: none; }
        .item-icon {
            width: 30px;
            text-align: center;
            margin-right: 15px;
        }
        .success-icon { color: #27ae60; }
        .warning-icon { color: #f39c12; }
        .error-icon { color: #e74c3c; }
        
        .actions {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 10px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn:hover { background: #2980b9; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        .btn-warning { background: #f39c12; }
        .btn-warning:hover { background: #e67e22; }
        
        .summary {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-coffee"></i> Coffee's Life</h1>
            <h2>System Status Check</h2>
            <div class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $overallStatus)); ?>">
                <?php echo $overallStatus === 'READY' ? '✅' : '❌'; ?> System Status: <?php echo $overallStatus; ?>
            </div>
        </div>
        
        <div class="content">
            <div class="summary">
                <h3><i class="fas fa-chart-pie"></i> Summary</h3>
                <p><strong>✅ Successful checks:</strong> <?php echo count($successItems); ?></p>
                <p><strong>⚠️ Warnings:</strong> <?php echo count($warnings); ?></p>
                <p><strong>❌ Critical errors:</strong> <?php echo count($criticalErrors); ?></p>
            </div>
            
            <?php if (!empty($successItems)): ?>
            <div class="section">
                <div class="section-header section-success">
                    <i class="fas fa-check-circle"></i> ✅ Working Correctly (<?php echo count($successItems); ?>)
                </div>
                <div class="section-body">
                    <?php foreach ($successItems as $item): ?>
                    <div class="item">
                        <div class="item-icon success-icon"><i class="fas fa-check"></i></div>
                        <div><?php echo htmlspecialchars($item); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($warnings)): ?>
            <div class="section">
                <div class="section-header section-warning">
                    <i class="fas fa-exclamation-triangle"></i> ⚠️ Warnings (<?php echo count($warnings); ?>)
                </div>
                <div class="section-body">
                    <?php foreach ($warnings as $warning): ?>
                    <div class="item">
                        <div class="item-icon warning-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div><?php echo htmlspecialchars($warning); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($criticalErrors)): ?>
            <div class="section">
                <div class="section-header section-error">
                    <i class="fas fa-times-circle"></i> ❌ Critical Issues (<?php echo count($criticalErrors); ?>)
                </div>
                <div class="section-body">
                    <?php foreach ($criticalErrors as $error): ?>
                    <div class="item">
                        <div class="item-icon error-icon"><i class="fas fa-times"></i></div>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="actions">
            <h3>Quick Actions</h3>
            <?php if (!empty($warnings) && (empty($criticalErrors))): ?>
                <a href="execute_db_setup.php" class="btn btn-warning">
                    <i class="fas fa-database"></i> Setup Database
                </a>
            <?php endif; ?>
            
            <?php if ($overallStatus === 'READY'): ?>
                <a href="admin_login.php" class="btn btn-success">
                    <i class="fas fa-sign-in-alt"></i> Admin Login
                </a>
                <a href="menu.php" class="btn">
                    <i class="fas fa-coffee"></i> View Menu
                </a>
                <a href="admin_dashboard.php" class="btn" onclick="return checkLogin()">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            <?php endif; ?>
            
            <a href="index.php" class="btn">
                <i class="fas fa-home"></i> Main Website
            </a>
            
            <button onclick="location.reload()" class="btn">
                <i class="fas fa-sync-alt"></i> Refresh Status
            </button>
        </div>
    </div>
    
    <script>
        function checkLogin() {
            alert('Please login first via Admin Login if you haven\'t already.');
            return true;
        }
        
        // Auto-refresh every 30 seconds if there are critical errors
        <?php if (!empty($criticalErrors)): ?>
        setTimeout(function() {
            if (confirm('There are critical errors. Would you like to refresh the status?')) {
                location.reload();
            }
        }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>
