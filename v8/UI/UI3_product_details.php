<?php
// product_detail.php
require_once '../functions/functions.php';
// Start session to enable cart functions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];
    $product = getProductById($productId);
} else {
    header("Location: UI2_menu.php");
    exit;
}

if (!$product) {
    echo "Product not found."; // This could be styled with a common error message style
    exit;
}

include '../header.php';
?>
<link rel="stylesheet" href="../css/product-details.css">

<div class="product-details">
    <div class="product-image">
        <?php if ($product['image']): ?>
            <img src="../<?php echo htmlspecialchars($product['image']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>"
                onerror="this.src='https://placehold.co/350x250/6F4E37/ffffff?text=<?php echo urlencode($product['name']); ?>'">
        <?php else: ?>
            <img src="https://placehold.co/350x250/6F4E37/ffffff?text=<?php echo urlencode($product['name']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>">
        <?php endif; ?>
    </div>

    <div class="product-info">
        <?php if (isset($product['category'])): ?>
            <span class="category-badge"><?php echo htmlspecialchars($product['category']); ?></span>
        <?php endif; ?>

        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <div class="price">RM <?php echo htmlspecialchars(number_format($product['price'], 2)); ?></div>

        <?php if (!empty($product['description'])): ?>
            <div class="section">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($product['ingredients'])): ?>
            <div class="section">
                <h3>Ingredients</h3>
                <p><?php echo nl2br(htmlspecialchars($product['ingredients'])); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($product['benefits'])): ?>
            <div class="section">
                <h3>Benefits</h3>
                <p><?php echo nl2br(htmlspecialchars($product['benefits'])); ?></p>
            </div>
        <?php endif; ?>

        <div class="add-to-cart-section">
            <form action="../functions/function2_add_to_cart.php" method="post" class="add-to-cart-form" id="addToCartForm">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">

                <div class="quantity-selector">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="10">
                </div>

                <div class="product-actions">
                    <input type="submit" value="Add to Cart" class="btn btn-primary">
                    <a href="UI2_menu.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Menu
                    </a>
                </div>
            </form>
            <div id="cartSuccessMsg" style="display:none; color: #27ae60; font-weight: bold; margin-top: 15px;">
                <i class="fas fa-check-circle"></i> Product added to cart successfully!
            </div>
        </div>
    </div>
</div>

<script>
    // Intercept form submission to show success message without page reload
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('cartSuccessMsg').style.display = 'block';
                // Optionally reset quantity to 1
                document.getElementById('quantity').value = 1;
                // Optionally hide message after 2 seconds
                setTimeout(function() {
                    document.getElementById('cartSuccessMsg').style.display = 'none';
                }, 2000);
            } else {
                alert('Failed to add to cart. Please try again.');
            }
        };
        xhr.send(formData);
    });
</script>

<?php include '../footer.php'; ?>