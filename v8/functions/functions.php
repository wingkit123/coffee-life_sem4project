<?php
// functions.php
require_once __DIR__ . '/../config.php';

function getProducts()
{
    global $conn;
    $sql = "SELECT id, name, price, quantity, image_path FROM products"; // Added image_path
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

function getProductById($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching product by ID: " . $e->getMessage());
        return false;
    }
}

// Modified: Added $image_path parameter
function addProduct($name, $price, $quantity, $description, $ingredients, $preparation_method, $image_path = NULL)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, description, ingredients, preparation_method, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdissss", $name, $price, $quantity, $description, $ingredients, $preparation_method, $image_path);
    return $stmt->execute();
}

// Modified: Added $image_path parameter
function updateProduct($id, $name, $price, $quantity, $description, $ingredients, $preparation_method, $image_path = NULL)
{
    global $conn;
    // Build SQL query dynamically based on whether image_path is provided
    if ($image_path !== NULL) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ?, description = ?, ingredients = ?, preparation_method = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sdissssi", $name, $price, $quantity, $description, $ingredients, $preparation_method, $image_path, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ?, description = ?, ingredients = ?, preparation_method = ? WHERE id = ?");
        $stmt->bind_param("sdisssi", $name, $price, $quantity, $description, $ingredients, $preparation_method, $id);
    }
    return $stmt->execute();
}

function deleteProduct($id)
{
    global $conn;

    // Optional: Delete the image file from the server when product is deleted
    $product = getProductById($id); // Get product details to find image path
    if ($product && !empty($product['image_path'])) {
        $imageFilePath = __DIR__ . '/uploads/images/' . $product['image_path'];
        if (file_exists($imageFilePath)) {
            unlink($imageFilePath); // Delete the actual file
        }
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Basic admin authentication (for demonstration purposes)
function checkAdminAuthOld()
{
    session_start();
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}

// --- NEW USER MANAGEMENT FUNCTIONS ---

// Modified: Added $address parameter
function registerUser($username, $password, $email, $contact_number, $address = NULL)
{
    global $conn;

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Modified: Added address column to INSERT statement and 's' to bind_param
    $sql = "INSERT INTO users (username, password, email, contact_number, address) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Modified: Added $address to bind_param (now "sssss")
        $stmt->bind_param("sssss", $username, $hashed_password, $email, $contact_number, $address);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            // Handle duplicate entry errors if needed, though checkUserExists handles it primarily
            error_log("Error registering user: " . $stmt->error); // Log the actual database error
            $stmt->close();
            return false;
        }
    } else {
        error_log("Error preparing statement for registerUser: " . $conn->error); // Log SQL preparation error
        return false;
    }
}

function loginUser($username, $password)
{
    global $conn;

    $sql = "SELECT id, username, password FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_username, $hashed_password);
                if ($stmt->fetch()) {
                    // Verify the provided password against the hashed password
                    if (password_verify($password, $hashed_password)) {
                        // Start session if not already started
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }

                        // Regenerate session ID to prevent session fixation attacks
                        session_regenerate_id(true);

                        $_SESSION['user_logged_in'] = true;
                        $_SESSION['user_id'] = $id;
                        $_SESSION['user_username'] = $db_username;
                        $stmt->close();
                        return true;
                    }
                }
            }
        } else {
            error_log("Error executing loginUser statement: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("Error preparing loginUser statement: " . $conn->error);
    }
    return false; // Login failed
}

// Function to check if username, email, or contact number already exists
function checkUserExists($field, $value, $exclude_id = null)
{
    global $conn;
    $sql = "SELECT id FROM users WHERE " . $field . " = ?";
    if ($exclude_id !== null) {
        $sql .= " AND id != ?";
    }

    if ($stmt = $conn->prepare($sql)) {
        if ($exclude_id !== null) {
            $stmt->bind_param("si", $value, $exclude_id);
        } else {
            $stmt->bind_param("s", $value);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    return false;
}

function getCart()
{
    if (session_status() == PHP_SESSION_NONE) { // Check if session is not already started
        session_start();
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize an empty cart if it doesn't exist
    }
    return $_SESSION['cart'];
}

// Function to add an item to the cart
function addToCart($productId, $quantity)
{
    $cart = getCart(); // Get current cart
    $product = getProductById($productId); // Retrieve product details

    if (!$product) {
        return false; // Product not found
    }

    $quantity = max(1, (int)$quantity); // Ensure quantity is at least 1

    if (isset($cart[$productId])) {
        // If product already in cart, update quantity
        $cart[$productId]['quantity'] += $quantity;
    } else {
        // If new product, add it to cart
        $cart[$productId] = [
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'image_path' => $product['image_path'], // Include image path
            'quantity' => $quantity
        ];
    }

    $_SESSION['cart'] = $cart; // Save updated cart back to session
    return true;
}

// Enhanced function to add an item to the cart with customization options
function addToCartEnhanced($cartItem)
{
    $cart = getCart(); // Get current cart
    $productId = $cartItem['product_id'];
    $product = getProductById($productId); // Retrieve product details

    if (!$product) {
        return false; // Product not found
    }

    $quantity = max(1, (int)$cartItem['quantity']); // Ensure quantity is at least 1

    // Create unique cart key based on product and customizations
    $cartKey = $productId . '_' . md5(serialize([
        'sugar_level' => $cartItem['sugar_level'],
        'milk_type' => $cartItem['milk_type'],
        'special_instructions' => $cartItem['special_instructions']
    ]));

    if (isset($cart[$cartKey])) {
        // If same product with same customizations already in cart, update quantity
        $cart[$cartKey]['quantity'] += $quantity;
    } else {
        // If new product or different customizations, add it to cart
        $cart[$cartKey] = [
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'image_path' => $product['image_path'] ?? 'uploads/images/default.jpg',
            'quantity' => $quantity,
            'sugar_level' => $cartItem['sugar_level'],
            'milk_type' => $cartItem['milk_type'],
            'special_instructions' => $cartItem['special_instructions'],
            'added_time' => $cartItem['added_time']
        ];
    }

    $_SESSION['cart'] = $cart; // Save updated cart back to session
    return true;
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
    $cart = getCart();
    if (isset($cart[$productId])) {
        unset($cart[$productId]);
        $_SESSION['cart'] = $cart;
        return true;
    }
    return false;
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($productId, $newQuantity)
{
    $cart = getCart();
    $newQuantity = max(0, (int)$newQuantity); // Ensure quantity is non-negative

    if (isset($cart[$productId])) {
        if ($newQuantity > 0) {
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            // Remove item if quantity is 0
            unset($cart[$productId]);
        }
        $_SESSION['cart'] = $cart;
        return true;
    }
    return false;
}

// Function to clear the entire cart
function clearCart()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['cart'] = [];
    return true;
}

// Function to get total number of items (sum of quantities) in cart
function getTotalCartItems()
{
    $cart = getCart();
    $totalItems = 0;
    foreach ($cart as $item) {
        $totalItems += $item['quantity'];
    }
    return $totalItems;
}

// Function to get total price of items in cart
function getTotalCartPrice()
{
    $cart = getCart();
    $totalPrice = 0;
    foreach ($cart as $item) {
        $totalPrice += ($item['price'] * $item['quantity']);
    }
    return $totalPrice;
}

// === ADMIN FUNCTIONS ===

/**
 * Check if admin is logged in
 */
function checkAdminAuth()
{
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: admin_login.php");
        exit;
    }
}

/**
 * Get all products for admin management
 */
function getAllProductsAdmin()
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM product ORDER BY category, name");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching all products: " . $e->getMessage());
        return [];
    }
}

/**
 * Add new product (Admin function)
 */
function addProductAdmin($name, $price, $category, $description, $ingredients, $benefits, $quantity, $image = null)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO product (name, price, category, description, ingredients, benefits, quantity, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $price, $category, $description, $ingredients, $benefits, $quantity, $image]);
    } catch (PDOException $e) {
        error_log("Error adding product: " . $e->getMessage());
        return false;
    }
}

/**
 * Update product (Admin function)
 */
function updateProductAdmin($id, $name, $price, $category, $description, $ingredients, $benefits, $quantity, $image = null)
{
    global $pdo;
    try {
        if ($image !== null) {
            $stmt = $pdo->prepare("UPDATE product SET name = ?, price = ?, category = ?, description = ?, ingredients = ?, benefits = ?, quantity = ?, image = ? WHERE id = ?");
            return $stmt->execute([$name, $price, $category, $description, $ingredients, $benefits, $quantity, $image, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE product SET name = ?, price = ?, category = ?, description = ?, ingredients = ?, benefits = ?, quantity = ? WHERE id = ?");
            return $stmt->execute([$name, $price, $category, $description, $ingredients, $benefits, $quantity, $id]);
        }
    } catch (PDOException $e) {
        error_log("Error updating product: " . $e->getMessage());
        return false;
    }
}

function deleteProductAdmin($id)
{
    global $pdo;
    try {
        // Get product details to find image path
        $product = getProductById($id);
        if ($product && !empty($product['image'])) {
            $imageFilePath = __DIR__ . '/' . $product['image'];
            if (file_exists($imageFilePath)) {
                unlink($imageFilePath); // Delete the actual file
            }
        }

        $stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error deleting product: " . $e->getMessage());
        return false;
    }
}

function toggleProductQuantity($id, $quantity)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE product SET quantity = ? WHERE id = ?");
        return $stmt->execute([$quantity, $id]);
    } catch (PDOException $e) {
        error_log("Error updating product quantity: " . $e->getMessage());
        return false;
    }
}

/**
 * Get admin dashboard statistics
 */
function getAdminStats()
{
    global $pdo;
    try {
        $stats = [];

        // Total products
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM product");
        $stmt->execute();
        $stats['total_products'] = $stmt->fetchColumn();

        // Low stock products (quantity < 10)
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM product WHERE quantity < 10");
        $stmt->execute();
        $stats['low_stock'] = $stmt->fetchColumn();

        // Out of stock products
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM product WHERE quantity = 0");
        $stmt->execute();
        $stats['out_of_stock'] = $stmt->fetchColumn();

        // Total admin accounts
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin");
        $stmt->execute();
        $stats['total_admins'] = $stmt->fetchColumn();

        return $stats;
    } catch (PDOException $e) {
        error_log("Error getting admin stats: " . $e->getMessage());
        return [
            'total_products' => 0,
            'low_stock' => 0,
            'out_of_stock' => 0,
            'total_admins' => 0
        ];
    }
}

/**
 * Get all orders for admin
 */
function getAllOrdersAdmin($limit = 50)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT o.*, u.username, 
                   (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) as item_count
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching admin orders: " . $e->getMessage());
        return [];
    }
}

/**
 * Get order details with items
 */
function getOrderDetails($order_id)
{
    global $pdo;
    try {
        // Get order info
        $stmt = $pdo->prepare("
            SELECT o.*, u.username 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch();

        if (!$order) return null;

        // Get order items
        $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$order_id]);
        $order['items'] = $stmt->fetchAll();

        return $order;
    } catch (PDOException $e) {
        error_log("Error fetching order details: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all users for admin
 */
function getAllUsersAdmin($limit = 100)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT u.*, 
                   (SELECT COUNT(*) FROM orders o WHERE o.user_id = u.id) as order_count,
                   (SELECT COUNT(*) FROM reviews r WHERE r.user_id = u.id) as review_count
            FROM users u 
            ORDER BY u.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching admin users: " . $e->getMessage());
        return [];
    }
}

/**
 * Create order from cart
 */
function createOrder($user_id, $guest_name, $guest_phone, $cart_items, $total_amount)
{
    global $pdo;
    try {
        $pdo->beginTransaction();

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, guest_name, guest_phone, total_amount, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$user_id, $guest_name, $guest_phone, $total_amount]);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, sugar_level, milk_type, special_instructions, item_total) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $order_id,
                $item['product_id'],
                $item['name'],
                $item['price'],
                $item['quantity'],
                $item['sugar_level'] ?? null,
                $item['milk_type'] ?? null,
                $item['special_instructions'] ?? null,
                $item['total']
            ]);
        }

        $pdo->commit();
        return $order_id;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error creating order: " . $e->getMessage());
        return false;
    }
}

// Image upload handler - Unified function
function handleImageUpload($file)
{
    $uploadDir = __DIR__ . '/uploads/images/';
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Check if upload directory exists, create if not
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ['success' => false, 'message' => 'Upload directory could not be created'];
        }
    }

    // Check for upload errors
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error or no file uploaded'];
    }

    // Validate file type
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.'];
    }

    // Validate file size
    if ($file['size'] > $maxFileSize) {
        return ['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.'];
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}

// Admin Management Functions

// Get all admins
function getAllAdmins()
{
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, username, created_at, updated_at FROM admin ORDER BY username");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching admins: " . $e->getMessage());
        return [];
    }
}

// Get admin by ID
function getAdminById($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, username, created_at, updated_at FROM admin WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching admin: " . $e->getMessage());
        return false;
    }
}

// Add new admin
function addAdmin($username, $password)
{
    global $pdo;
    try {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return false; // Username already exists
        }

        // Hash password and insert admin
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashedPassword]);
    } catch (PDOException $e) {
        error_log("Error adding admin: " . $e->getMessage());
        return false;
    }
}

// Update admin
function updateAdmin($id, $username, $password = null)
{
    global $pdo;
    try {
        // Check if username already exists for other admins
        $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ? AND id != ?");
        $stmt->execute([$username, $id]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        if ($password) {
            // Update with new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admin SET username = ?, password = ? WHERE id = ?");
            $stmt->execute([$username, $hashedPassword, $id]);
        } else {
            // Update without changing password
            $stmt = $pdo->prepare("UPDATE admin SET username = ? WHERE id = ?");
            $stmt->execute([$username, $id]);
        }
        
        return ['success' => true, 'message' => 'Admin updated successfully'];
    } catch (PDOException $e) {
        error_log("Error updating admin: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

// Update admin password
function updateAdminPassword($id, $password)
{
    global $pdo;
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    } catch (PDOException $e) {
        error_log("Error updating admin password: " . $e->getMessage());
        return false;
    }
}

// Delete admin
function deleteAdmin($id)
{
    global $pdo;
    try {
        // Prevent deleting the last admin
        $stmt = $pdo->query("SELECT COUNT(*) FROM admin");
        $adminCount = $stmt->fetchColumn();
        
        if ($adminCount <= 1) {
            return false; // Cannot delete the last admin account
        }

        // Prevent deleting current admin
        if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $id) {
            return false; // Cannot delete your own account
        }

        $stmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error deleting admin: " . $e->getMessage());
        return false;
    }
}
