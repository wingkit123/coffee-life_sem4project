<?php
// rating_comments.php
require_once 'functions.php';
session_start();

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$rating = $comment = "";
$rating_err = $comment_err = "";
$success_msg = "";

// Get product information
$products = getProducts();
$current_product = null;
foreach ($products as $product) {
  if ($product['id'] == $product_id) {
    $current_product = $product;
    break;
  }
}

if (!$current_product && $product_id > 0) {
  header("Location: menu.php");
  exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate rating
  if (empty($_POST["rating"])) {
    $rating_err = "Please select a rating.";
  } else {
    $rating = (int)$_POST["rating"];
    if ($rating < 1 || $rating > 5) {
      $rating_err = "Rating must be between 1 and 5 stars.";
    }
  }

  // Validate comment
  if (empty(trim($_POST["comment"]))) {
    $comment_err = "Please enter your comment.";
  } elseif (strlen(trim($_POST["comment"])) < 10) {
    $comment_err = "Comment must be at least 10 characters long.";
  } else {
    $comment = trim($_POST["comment"]);
  }

  // If no errors, save the rating/comment
  if (empty($rating_err) && empty($comment_err)) {
    // In a real application, you would save to database
    $success_msg = "Thank you for your feedback! Your rating and comment have been submitted.";
    $rating = $comment = ""; // Clear form
  }
}

// Mock existing reviews for display
$existing_reviews = [
  ['name' => 'Sarah M.', 'rating' => 5, 'comment' => 'Absolutely delicious! The aroma alone is worth the visit. Will definitely come back for more.', 'date' => '2024-12-20'],
  ['name' => 'John D.', 'rating' => 4, 'comment' => 'Great coffee, friendly staff. The atmosphere is perfect for working or catching up with friends.', 'date' => '2024-12-18'],
  ['name' => 'Emily R.', 'rating' => 5, 'comment' => 'Best coffee in town! The baristas really know their craft. Highly recommended!', 'date' => '2024-12-15']
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reviews & Ratings - Beans Cafe</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    .review-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
    }

    .review-header {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .review-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      margin-bottom: 30px;
    }

    .rating-form,
    .existing-reviews {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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

    .product-info {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 25px;
      border-left: 5px solid #6F4E37;
    }

    .product-name {
      font-size: 1.3em;
      font-weight: bold;
      color: #333;
      margin-bottom: 5px;
    }

    .product-price {
      color: #6F4E37;
      font-size: 1.1em;
      font-weight: bold;
    }

    .star-rating {
      display: flex;
      gap: 5px;
      margin-bottom: 20px;
      justify-content: center;
    }

    .star {
      font-size: 2em;
      color: #ddd;
      cursor: pointer;
      transition: color 0.3s ease, transform 0.2s ease;
    }

    .star:hover,
    .star.active {
      color: #ffc107;
      transform: scale(1.1);
    }

    .star.hover {
      color: #ffeb3b;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
    }

    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      resize: vertical;
      min-height: 120px;
      transition: border-color 0.3s ease;
    }

    .form-group textarea:focus {
      outline: none;
      border-color: #6F4E37;
      box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
    }

    .error {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }

    .success {
      background: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #c3e6cb;
    }

    .submit-btn {
      background: linear-gradient(135deg, #6F4E37, #8B4513);
      color: white;
      padding: 15px 30px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.3s ease;
      width: 100%;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(111, 78, 55, 0.3);
    }

    .review-item {
      border-bottom: 1px solid #eee;
      padding: 20px 0;
    }

    .review-item:last-child {
      border-bottom: none;
    }

    .review-header-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .reviewer-name {
      font-weight: bold;
      color: #333;
    }

    .review-date {
      color: #666;
      font-size: 0.9em;
    }

    .review-stars {
      color: #ffc107;
      margin-bottom: 10px;
    }

    .review-comment {
      color: #555;
      line-height: 1.6;
    }

    .rating-summary {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
      margin-bottom: 30px;
    }

    .average-rating {
      font-size: 3em;
      font-weight: bold;
      color: #6F4E37;
      margin-bottom: 10px;
    }

    .rating-breakdown {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-top: 20px;
    }

    .rating-bar {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .rating-bar-fill {
      flex: 1;
      height: 8px;
      background: #f0f0f0;
      border-radius: 4px;
      overflow: hidden;
    }

    .rating-bar-progress {
      height: 100%;
      background: linear-gradient(90deg, #ffc107, #ff9800);
      transition: width 0.5s ease;
    }

    .back-btn {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 25px;
      margin-bottom: 20px;
      transition: background-color 0.3s ease;
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .character-count {
      text-align: right;
      font-size: 0.9em;
      color: #666;
      margin-top: 5px;
    }

    @media (max-width: 768px) {
      .review-content {
        grid-template-columns: 1fr;
      }

      .star-rating {
        justify-content: flex-start;
      }

      .review-header-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
      }
    }

    /* Animation classes */
    .fade-in {
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
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
  </style>
</head>

<body>
  <div class="review-container">
    <a href="<?php echo $product_id ? 'product_details.php?id=' . $product_id : 'menu.php'; ?>" class="back-btn">
      <i class="fas fa-arrow-left"></i>
      <?php echo $product_id ? 'Back to Product' : 'Back to Menu'; ?>
    </a>

    <div class="review-header fade-in">
      <h1><i class="fas fa-star"></i> Reviews & Ratings</h1>
      <p>Share your experience and help others discover great coffee!</p>
    </div>

    <!-- Rating Summary -->
    <div class="rating-summary fade-in">
      <div class="average-rating pulse">4.7</div>
      <div class="review-stars">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <p style="color: #666; margin: 10px 0;">Based on 127 reviews</p>

      <div class="rating-breakdown">
        <div class="rating-bar">
          <span>5★</span>
          <div class="rating-bar-fill">
            <div class="rating-bar-progress" style="width: 75%"></div>
          </div>
          <span>75%</span>
        </div>
        <div class="rating-bar">
          <span>4★</span>
          <div class="rating-bar-fill">
            <div class="rating-bar-progress" style="width: 15%"></div>
          </div>
          <span>15%</span>
        </div>
        <div class="rating-bar">
          <span>3★</span>
          <div class="rating-bar-fill">
            <div class="rating-bar-progress" style="width: 8%"></div>
          </div>
          <span>8%</span>
        </div>
        <div class="rating-bar">
          <span>2★</span>
          <div class="rating-bar-fill">
            <div class="rating-bar-progress" style="width: 2%"></div>
          </div>
          <span>2%</span>
        </div>
        <div class="rating-bar">
          <span>1★</span>
          <div class="rating-bar-fill">
            <div class="rating-bar-progress" style="width: 0%"></div>
          </div>
          <span>0%</span>
        </div>
      </div>
    </div>

    <div class="review-content">
      <!-- Rating Form -->
      <div class="rating-form fade-in">
        <h2 class="section-title">
          <i class="fas fa-edit"></i>
          Write a Review
        </h2>

        <?php if ($current_product): ?>
          <div class="product-info">
            <div class="product-name"><?php echo htmlspecialchars($current_product['name']); ?></div>
            <div class="product-price">$<?php echo number_format($current_product['price'], 2); ?></div>
          </div>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
          <div class="success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . ($product_id ? '?product_id=' . $product_id : ''); ?>" id="reviewForm">
          <div class="form-group">
            <label>Your Rating *</label>
            <div class="star-rating" id="starRating">
              <i class="fas fa-star star" data-rating="1"></i>
              <i class="fas fa-star star" data-rating="2"></i>
              <i class="fas fa-star star" data-rating="3"></i>
              <i class="fas fa-star star" data-rating="4"></i>
              <i class="fas fa-star star" data-rating="5"></i>
            </div>
            <input type="hidden" id="ratingInput" name="rating" value="<?php echo $rating; ?>">
            <?php if (!empty($rating_err)): ?>
              <div class="error"><?php echo $rating_err; ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="comment">Your Review *</label>
            <textarea id="comment" name="comment" placeholder="Tell us about your experience..." maxlength="500" required><?php echo $comment; ?></textarea>
            <div class="character-count">
              <span id="charCount">0</span>/500 characters
            </div>
            <?php if (!empty($comment_err)): ?>
              <div class="error"><?php echo $comment_err; ?></div>
            <?php endif; ?>
          </div>

          <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Submit Review
          </button>
        </form>
      </div>

      <!-- Existing Reviews -->
      <div class="existing-reviews fade-in">
        <h2 class="section-title">
          <i class="fas fa-comments"></i>
          Customer Reviews
        </h2>

        <?php foreach ($existing_reviews as $review): ?>
          <div class="review-item">
            <div class="review-header-info">
              <div class="reviewer-name"><?php echo htmlspecialchars($review['name']); ?></div>
              <div class="review-date"><?php echo date('M j, Y', strtotime($review['date'])); ?></div>
            </div>

            <div class="review-stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="fas fa-star" style="color: <?php echo $i <= $review['rating'] ? '#ffc107' : '#ddd'; ?>"></i>
              <?php endfor; ?>
            </div>

            <div class="review-comment">
              <?php echo htmlspecialchars($review['comment']); ?>
            </div>
          </div>
        <?php endforeach; ?>

        <div style="text-align: center; margin-top: 20px;">
          <button onclick="loadMoreReviews()" style="background: none; border: 2px solid #6F4E37; color: #6F4E37; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            Load More Reviews
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Star rating functionality
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    let currentRating = 0;

    stars.forEach((star, index) => {
      star.addEventListener('mouseover', () => {
        highlightStars(index + 1);
      });

      star.addEventListener('mouseout', () => {
        highlightStars(currentRating);
      });

      star.addEventListener('click', () => {
        currentRating = index + 1;
        ratingInput.value = currentRating;
        highlightStars(currentRating);

        // Add animation
        star.style.transform = 'scale(1.3)';
        setTimeout(() => {
          star.style.transform = 'scale(1.1)';
        }, 200);
      });
    });

    function highlightStars(rating) {
      stars.forEach((star, index) => {
        if (index < rating) {
          star.classList.add('active');
        } else {
          star.classList.remove('active');
        }
      });
    }

    // Character counter
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');

    commentTextarea.addEventListener('input', () => {
      const count = commentTextarea.value.length;
      charCount.textContent = count;

      if (count > 450) {
        charCount.style.color = '#dc3545';
      } else if (count > 400) {
        charCount.style.color = '#ffc107';
      } else {
        charCount.style.color = '#666';
      }
    });

    // Form validation
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
      let isValid = true;

      // Validate rating
      if (currentRating === 0) {
        showError('Please select a rating');
        isValid = false;
      }

      // Validate comment
      const comment = commentTextarea.value.trim();
      if (comment.length < 10) {
        showError('Review must be at least 10 characters long');
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
      }
    });

    function showError(message) {
      const errorDiv = document.createElement('div');
      errorDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #f8d7da;
                color: #721c24;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                z-index: 1000;
            `;
      errorDiv.textContent = message;
      document.body.appendChild(errorDiv);

      setTimeout(() => {
        errorDiv.remove();
      }, 3000);
    }

    function loadMoreReviews() {
      // Simulate loading more reviews
      alert('Loading more reviews... (This would fetch additional reviews in a real application)');
    }

    // Initialize character count
    charCount.textContent = commentTextarea.value.length;

    // Animate rating bars on load
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(() => {
        const progressBars = document.querySelectorAll('.rating-bar-progress');
        progressBars.forEach(bar => {
          const width = bar.style.width;
          bar.style.width = '0%';
          setTimeout(() => {
            bar.style.width = width;
          }, 500);
        });
      }, 1000);
    });
  </script>
</body>

</html>