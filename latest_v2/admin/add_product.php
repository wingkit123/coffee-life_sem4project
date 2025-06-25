<?php
// admin/add_product.php
require_once '../functions.php';
checkAdminAuth();

$name = $price = $quantity = $description = $ingredients = $preparation_method = $image_path = "";
$name_err = $price_err = $quantity_err = $image_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Handle image upload
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../uploads/images/"; // Path to your images folder
        $imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));

        // Generate a unique filename to prevent overwrites
        $unique_filename = uniqid('img_', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        // Basic validation (more robust validation needed for production)
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($imageFileType, $valid_extensions)) {
            $image_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES["product_image"]["size"] > $max_size) {
            $image_err = "Sorry, your file is too large (max 5MB).";
        } else {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $image_path = $unique_filename; // Save only the filename in DB
            } else {
                $image_err = "Sorry, there was an error uploading your file.";
            }
        }
    } elseif (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] != UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors
        $image_err = "Error uploading file: " . $_FILES["product_image"]["error"];
    }

    // Optional fields
    $description = trim($_POST["description"]);
    $ingredients = trim($_POST["ingredients"]);
    $preparation_method = trim($_POST["preparation_method"]);

    // Check input errors before inserting in database
    if (empty($name_err) && empty($price_err) && empty($quantity_err) && empty($image_err)) {
        if (addProduct($name, $price, $quantity, $description, $ingredients, $preparation_method, $image_path)) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group input[type="number"], .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .form-group input[type="file"] { padding: 5px; } /* Adjust padding for file input */
        .form-group .error { color: red; font-size: 0.9em; }
        .btn { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
        .back-btn { margin-left: 10px; background-color: #6c757d; }
        .back-btn:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add New Product</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                <input type="file" name="product_image" accept="image/*">
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
                <input type="submit" class="btn" value="Add Product">
                <a href="dashboard.php" class="btn back-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>