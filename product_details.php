<?php
// product_detail.php
require_once 'functions.php';
// Start session to enable cart functions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];
    $product = getProductById($productId);
} else {
    header("Location: menu.php");
    exit;
}

if (!$product) {
    echo "Product not found."; // This could be styled with a common error message style
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styles for product_details.php */
        .product-details {
            max-width: 700px;
            margin: 30px auto;
            padding: 25px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center; /* Center content within the box */
        }

        .product-details img {
            max-width: 100%;
            height: auto;
            max-height: 350px; /* Limit image height */
            object-fit: cover;
            display: block;
            margin: 0 auto 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-details h1 {
            font-size: 2.2em;
            color: #343a40;
            margin-bottom: 15px;
        }

        .product-details p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 10px;
            line-height: 1.8;
            text-align: left; /* Align text left within its container */
            padding: 0 10px;
        }

        .product-details p strong {
            color: #333;
        }

        .add-to-cart-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 15px;
        }

        .add-to-cart-section label {
            font-weight: bold;
            font-size: 1.1em;
            color: #555;
        }

        .add-to-cart-section input[type="number"] {
            width: 90px;
            padding: 10px;
            text-align: center;
            font-size: 1.1em;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .add-to-cart-section button {
            padding: 12px 25px;
            background-color: #28a745; /* Success green */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .add-to-cart-section button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .back-to-menu {
            margin-top: 30px;
            text-align: center;
        }

        .back-to-menu a {
            padding: 10px 20px;
            background-color: #6c757d; /* Secondary gray */
            color: white;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-to-menu a:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .product-details {
                margin: 20px auto;
                padding: 20px;
            }
            .product-details h1 {
                font-size: 1.8em;
            }
            .product-details p {
                font-size: 1em;
            }
            .add-to-cart-section {
                flex-direction: column;
                gap: 10px;
            }
            .add-to-cart-section input[type="number"],
            .add-to-cart-section button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="product-details">
        <?php if (!empty($product['image_path'])): ?>
            <img src="uploads/images/<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <?php else: ?>
            <img src="https://via.placeholder.com/600x300?text=No+Image+Available" alt="No Image Available">
        <?php endif; ?>
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
        <p><strong>Quantity Available:</strong> <?php echo htmlspecialchars($product['quantity']); ?></p>
        <?php if (!empty($product['description'])): ?>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <?php endif; ?>
        <?php if (!empty($product['ingredients'])): ?>
            <p><strong>Ingredients:</strong> <?php echo nl2br(htmlspecialchars($product['ingredients'])); ?></p>
        <?php endif; ?>
        <?php if (!empty($product['preparation_method'])): ?>
            <p><strong>Preparation Method:</strong> <?php echo nl2br(htmlspecialchars($product['preparation_method'])); ?></p>
        <?php endif; ?>

        <div class="add-to-cart-section">
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>

        <p class="back-to-menu"><a href="menu.php">Back to Menu</a></p>
    </div>
</body>
</html>
