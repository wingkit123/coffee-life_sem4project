<?php
// admin/edit_product.php
require_once '../functions.php';
checkAdminAuth();

$id = $name = $price = $quantity = $description = $ingredients = $preparation_method = $current_image_path = "";
$name_err = $price_err = $quantity_err = $image_err = "";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $product = getProductById($id);

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    $name = $product['name'];
    $price = $product['price'];
    $quantity = $product['quantity'];
    $description = $product['description'];
    $ingredients = $product['ingredients'];
    $preparation_method = $product['preparation_method'];
    $current_image_path = $product['image_path']; // Get current image path
    $image_path_to_save = $current_image_path; // Initialize with current path
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hidden ID field from the form
    $id = $_POST['id'];
    $current_image_path = $_POST['current_image_path'] ?? ''; // Get current image path from hidden field
    $image_path_to_save = $current_image_path; // Assume existing image unless new one is uploaded

    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a product name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate price
    if (empty(trim($_POST["price"]))) {
        $price_err = "Please enter a price.";
    } elseif (!is_numeric($_POST["price"]) || $_POST["price"] < 0) {
        $price_err = "Please enter a valid positive price.";
    } else {
        $price = $_POST["price"];
    }

    // Validate quantity
    if (empty(trim($_POST["quantity"]))) {
        $quantity_err = "Please enter a quantity.";
    } elseif (!filter_var($_POST["quantity"], FILTER_VALIDATE_INT) || $_POST["quantity"] < 0) {
        $quantity_err = "Please enter a valid non-negative integer quantity.";
    } else {
        $quantity = $_POST["quantity"];
    }

    // Handle new image upload
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../uploads/images/"; // Path to your images folder
        $imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));

        // Generate a unique filename
        $unique_filename = uniqid('img_', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        // Basic validation
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($imageFileType, $valid_extensions)) {
            $image_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES["product_image"]["size"] > $max_size) {
            $image_err = "Sorry, your file is too large (max 5MB).";
        } else {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                // If a new image is uploaded successfully, delete the old one if it exists
                if (!empty($current_image_path) && file_exists($target_dir . $current_image_path)) {
                    unlink($target_dir . $current_image_path);
                }
                $image_path_to_save = $unique_filename; // Update path to new filename
            } else {
                $image_err = "Sorry, there was an error uploading your file.";
            }
        }
    } elseif (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] != UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors (e.g., file too large by php.ini)
        $image_err = "Error uploading file: " . $_FILES["product_image"]["error"];
    }

    // Optional fields
    $description = trim($_POST["description"]);
    $ingredients = trim($_POST["ingredients"]);
    $preparation_method = trim($_POST["preparation_method"]);

    // Check input errors before updating in database
    if (empty($name_err) && empty($price_err) && empty($quantity_err) && empty($image_err)) {
        if (updateProduct($id, $name, $price, $quantity, $description, $ingredients, $preparation_method, $image_path_to_save)) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
} else {
    // If no ID is provided via GET or POST, redirect to dashboard or show an error
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group input[type="number"], .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .form-group input[type="file"] { padding: 5px; }
        .form-group .error { color: red; font-size: 0.9em; }
        .btn { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
        .back-btn { margin-left: 10px; background-color: #6c757d; }
        .back-btn:hover { background-color: #5a6268; }
        .current-image { margin-top: 10px; }
        .current-image img { max-width: 150px; height: auto; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Product (ID: <?php echo htmlspecialchars($id); ?>)</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($current_image_path); ?>">

            <div class="form-group">
                <label>Product Name <span style="color: red;">*</span></label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <span class="error"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Price <span style="color: red;">*</span></label>
                <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>">
                <span class="error"><?php echo $price_err; ?></span>
            </div>
            <div class="form-group">
                <label>Quantity <span style="color: red;">*</span></label>
                <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
                <span class="error"><?php echo $quantity_err; ?></span>
            </div>
            <div class="form-group">
                <label>Product Image</label>
                <?php if (!empty($current_image_path)): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="../uploads/images/<?php echo htmlspecialchars($current_image_path); ?>" alt="Current Product Image">
                    </div>
                <?php endif; ?>
                <input type="file" name="product_image" accept="image/*">
                <p><small>Upload a new image to replace the current one (if any).</small></p>
                <span class="error"><?php echo $image_err; ?></span>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-group">
                <label>Ingredients</label>
                <textarea name="ingredients" rows="3"><?php echo htmlspecialchars($ingredients); ?></textarea>
            </div>
            <div class="form-group">
                <label>Preparation Method</label>
                <textarea name="preparation_method" rows="3"><?php echo htmlspecialchars($preparation_method); ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Update Product">
                <a href="dashboard.php" class="btn back-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>