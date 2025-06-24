<?php
// admin/delete_product.php
require_once '../functions.php';
checkAdminAuth();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];
    if (deleteProduct($productId)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error deleting product. Please try again.";
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>