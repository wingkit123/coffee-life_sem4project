<?php
// admin_products.php - Product Management for new database structure
session_start();
require_once '../functions/functions.php';

// Check admin authentication
checkAdminAuth();

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Add new product
        $name = trim($_POST['name']);
        $price = floatval($_POST['price']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $ingredients = trim($_POST['ingredients']);
        $benefits = trim($_POST['benefits']);
        $quantity = intval($_POST['quantity']);

        $image_path = null;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = handleImageUpload($_FILES['image']);
            if ($upload_result['success']) {
                $image_path = 'uploads/images/' . $upload_result['filename'];
            } else {
                $message = 'Product added but image upload failed: ' . $upload_result['message'];
                $message_type = 'error';
            }
        }

        if (addProductAdmin($name, $price, $category, $description, $ingredients, $benefits, $quantity, $image_path)) {
            $message = 'Product added successfully!';
            $message_type = 'success';
        } else {
            $message = 'Failed to add product. Please try again.';
            $message_type = 'error';
        }
    } elseif (isset($_POST['update_product'])) {
        // Update existing product
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $price = floatval($_POST['price']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $ingredients = trim($_POST['ingredients']);
        $benefits = trim($_POST['benefits']);
        $quantity = intval($_POST['quantity']);

        $image_path = null;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = handleImageUpload($_FILES['image']);
            if ($upload_result['success']) {
                $image_path = 'uploads/images/' . $upload_result['filename'];
            } else {
                $message = 'Product updated but image upload failed: ' . $upload_result['message'];
                $message_type = 'error';
            }
        }

        if (updateProductAdmin($id, $name, $price, $category, $description, $ingredients, $benefits, $quantity, $image_path)) {
            $message = 'Product updated successfully!';
            $message_type = 'success';
        } else {
            $message = 'Failed to update product. Please try again.';
            $message_type = 'error';
        }
    } elseif (isset($_POST['update_quantity'])) {
        // Update product quantity
        $id = intval($_POST['id']);
        $quantity = intval($_POST['quantity']);

        if (toggleProductQuantity($id, $quantity)) {
            $message = 'Product quantity updated successfully!';
            $message_type = 'success';
        } else {
            $message = 'Failed to update product quantity.';
            $message_type = 'error';
        }
    } elseif (isset($_POST['delete_product'])) {
        // Delete product
        $id = intval($_POST['id']);

        if (deleteProductAdmin($id)) {
            $message = 'Product deleted successfully!';
            $message_type = 'success';
        } else {
            $message = 'Failed to delete product.';
            $message_type = 'error';
        }
    }
}

// Get all products
$products = getAllProductsAdmin();

// Check if editing a specific product
$editing_product = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editing_product = getProductById($_GET['edit']);
}

$show_add_form = isset($_GET['action']) && $_GET['action'] === 'add';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Coffee's Life</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-body">
    <div class="admin-container">
        <!-- Admin Header -->
        <div class="admin-header">
            <h1><i class="fas fa-coffee"></i> <span style="color:white">Product Management</span></h1>
        </div>

        <!-- Navigation -->
        <nav class="admin-nav">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="admin_products.php" class="active"><i class="fas fa-coffee"></i> Products</a></li>
                <!-- <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li> -->
                <li><a href="admin_users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="admin_admins.php"><i class="fas fa-user-shield"></i> Admins</a></li>
                <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Messages -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Add/Edit Product Form -->
        <?php if ($show_add_form || $editing_product): ?>
            <div class="admin-card">
                <h2><i class="fas fa-plus-circle"></i> <?php echo $editing_product ? 'Edit Product' : 'Add New Product'; ?></h2>

                <form action="admin_products.php" method="post" enctype="multipart/form-data" class="admin-form">
                    <?php if ($editing_product): ?>
                        <input type="hidden" name="id" value="<?php echo $editing_product['id']; ?>">
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" required
                                value="<?php echo $editing_product ? htmlspecialchars($editing_product['name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Coffee" <?php echo ($editing_product && $editing_product['category'] === 'Coffee') ? 'selected' : ''; ?>>Coffee</option>
                                <option value="Snacks" <?php echo ($editing_product && $editing_product['category'] === 'Snacks') ? 'selected' : ''; ?>>Snacks</option>
                                <option value="Pastries" <?php echo ($editing_product && $editing_product['category'] === 'Pastries') ? 'selected' : ''; ?>>Pastries</option>
                                <option value="Cold Drinks" <?php echo ($editing_product && $editing_product['category'] === 'Cold Drinks') ? 'selected' : ''; ?>>Cold Drinks</option>
                                <option value="Cold Coffee" <?php echo ($editing_product && $editing_product['category'] === 'Cold Coffee') ? 'selected' : ''; ?>>Cold Coffee</option>
                                <option value="Healthy" <?php echo ($editing_product && $editing_product['category'] === 'Healthy') ? 'selected' : ''; ?>>Healthy</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (RM) *</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" required
                                value="<?php echo $editing_product ? $editing_product['price'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" min="0" required
                                value="<?php echo $editing_product ? $editing_product['quantity'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3"><?php echo $editing_product ? htmlspecialchars($editing_product['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="ingredients">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" rows="3"><?php echo $editing_product ? htmlspecialchars($editing_product['ingredients']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="benefits">Benefits</label>
                        <textarea id="benefits" name="benefits" rows="3"><?php echo $editing_product ? htmlspecialchars($editing_product['benefits']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if ($editing_product && $editing_product['image']): ?>
                            <div class="current-image">
                                <p>Current image:</p>
                                <img src="../<?php echo htmlspecialchars($editing_product['image']); ?>"
                                    alt="Current product image" style="max-width: 100px; max-height: 100px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="<?php echo $editing_product ? 'update_product' : 'add_product'; ?>" class="btn btn-primary">
                            <i class="fas <?php echo $editing_product ? 'fa-save' : 'fa-plus'; ?>"></i>
                            <?php echo $editing_product ? 'Update Product' : 'Add Product'; ?>
                        </button>
                        <a href="admin_products.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Products List -->
        <div class="admin-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> <span style="color:white">All Products</span></h2>
                <a href="admin_products.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No products found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                                    <td>
                                        <?php if ($product['image']): ?>
                                            <img src="../<?php echo htmlspecialchars($product['image']); ?>"
                                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="color: #ccc;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                                    <td>RM <?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <form action="admin_products.php" method="post" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>"
                                                min="0" style="width: 80px; padding: 2px 5px;">
                                            <button type="submit" name="update_quantity" class="btn-small btn-primary">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $product['quantity'] > 0 ? 'status-available' : 'status-unavailable'; ?>">
                                            <?php echo $product['quantity'] > 0 ? 'Available' : 'Out of Stock'; ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <a href="admin_products.php?edit=<?php echo $product['id']; ?>" class="btn-small btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="admin_products.php" method="post" style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" name="delete_product" class="btn-small btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide messages after 5 seconds
        setTimeout(function() {
            var messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.style.display = 'none';
                }, 300);
            });
        }, 5000);
    </script>
</body>

</html>