<?php
// functions.php
require_once __DIR__ . '/config.php';

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
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
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
function checkAdminAuth()
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
                        session_start();
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
