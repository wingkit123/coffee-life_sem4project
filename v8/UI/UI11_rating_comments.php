<?php
// UI11_rating_comments.php - Simple reviews and ratings system
require_once '../functions/functions.php';
require_once '../functions/function5_reviews_functions.php';
session_start();

$rating = $comment = $guest_name = "";
$errors = [];
$success_msg = "";

// Get current user info
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$ip_address = getUserIpAddress();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $rating = isset($_POST["rating"]) ? (int)$_POST["rating"] : 0;
  $comment = isset($_POST["comment"]) ? trim($_POST["comment"]) : "";
  $guest_name = isset($_POST["guest_name"]) ? trim($_POST["guest_name"]) : "";

  // Validate input
  $errors = validateReviewInput($rating, $comment, $guest_name);

  // Check if user has already reviewed recently (24 hour limit)
  if (empty($errors) && hasUserReviewed($user_id, $ip_address)) {
    $errors['general'] = "You have already submitted a review in the last 24 hours.";
  }

  // If guest user, require name
  if (empty($errors) && !$user_id && empty($guest_name)) {
    $errors['guest_name'] = "Please enter your name.";
  }

  // If no errors, save the review
  if (empty($errors)) {
    $review_id = addReview($user_id, $guest_name, $rating, $comment, $ip_address);

    if ($review_id) {
      $success_msg = "Thank you for your feedback! Your review has been submitted successfully.";
      // Clear form values after successful submission
      $rating = $comment = $guest_name = "";
    } else {
      $errors['general'] = "Sorry, there was an error submitting your review. Please try again.";
    }
  }
}

// Get review statistics
$review_stats = getReviewStats();

// Get the 5 newest reviews
$latest_reviews = getLatestReviews(5);

include '../header.php';
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="../css/reviews.css">

<body>
  <div class="review-container">
    <div class="reviews-header">
        <h1><i class="fas fa-star"></i> Customer Reviews</h1>
        <p>Your feedback helps us brew better coffee and serve you better.</p>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-card">
            <h3>Overall Rating</h3>
            <div class="overall-rating">
                <span class="rating-number"><?php echo number_format($review_stats['average_rating'], 1); ?></span>
                <div class="star-rating-display">
                    <?php echo generateStarRating($review_stats['average_rating']); ?>
                </div>
                <p><?php echo $review_stats['total_reviews']; ?> reviews</p>
            </div>
        </div>
    </div>

    <!-- Rating Form -->
    <div class="submit-review-section">
      <h2>
        <i class="fas fa-edit"></i>
        Write a Review
      </h2>

      <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
      <?php endif; ?>

      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($errors['general']); ?></div>
      <?php endif; ?>

      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="reviewForm">

        <?php if (!$user_id): ?>
          <!-- Guest Information -->
          <div class="form-group">
            <label for="guest_name">Your Name <span class="required-star">*</span></label>
            <input type="text" id="guest_name" name="guest_name"
              value="<?php echo htmlspecialchars($guest_name); ?>"
              maxlength="100" placeholder="Enter your name">
            <?php if (!empty($errors['guest_name'])): ?>
              <div class="error"><?php echo htmlspecialchars($errors['guest_name']); ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="form-group">
          <label>Your Rating <span class="required-star">*</span></label>
          <div class="star-rating" id="starRating">
            <i class="fas fa-star star" data-rating="1"></i>
            <i class="fas fa-star star" data-rating="2"></i>
            <i class="fas fa-star star" data-rating="3"></i>
            <i class="fas fa-star star" data-rating="4"></i>
            <i class="fas fa-star star" data-rating="5"></i>
          </div>
          <input type="hidden" id="ratingInput" name="rating" value="<?php echo $rating; ?>">
          <?php if (!empty($errors['rating'])): ?>
            <div class="error"><?php echo htmlspecialchars($errors['rating']); ?></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="comment">Your Review <span class="required-star">*</span></label>
          <textarea id="comment" name="comment"
            placeholder="Tell us about your experience..."
            maxlength="1000"><?php echo htmlspecialchars($comment); ?></textarea>
          <div class="character-count">
            <span id="charCount"><?php echo strlen($comment); ?></span>/1000 characters
          </div>
          <?php if (!empty($errors['comment'])): ?>
            <div class="error"><?php echo htmlspecialchars($errors['comment']); ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-paper-plane"></i> Submit Review
        </button>
      </form>
    </div>

    <!-- Latest Reviews -->
    <div class="reviews-section">
      <h2>
        <i class="fas fa-comments"></i>
        Latest Customer Reviews
      </h2>

      <?php if (empty($latest_reviews)): ?>
        <div class="no-reviews">
          <i class="fas fa-comment-slash"></i>
          <h3>No Reviews Yet</h3>
          <p>Be the first to share your experience!</p>
        </div>
      <?php else: ?>
        <div class="reviews-list">
          <?php foreach ($latest_reviews as $review): ?>
            <div class="review-item">
              <div class="review-header">
                <div class="reviewer-info">
                  <h4><?php echo getReviewDisplayName($review); ?></h4>
                  <div class="review-rating">
                    <?php echo generateStarRating($review['rating']); ?>
                  </div>
                </div>
                <div class="review-date"><?php echo date('M j, Y, g:i A', strtotime($review['created_at'])); ?></div>
              </div>
              <div class="review-content">
                <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <?php if ($review_stats['total_reviews'] > 5): ?>
          <div class="more-reviews-note">
            <p><i class="fas fa-info-circle"></i>
              Showing the 5 most recent reviews.
              Total reviews: <?php echo $review_stats['total_reviews']; ?>
            </p>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Star rating functionality
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    let currentRating = parseInt(ratingInput.value) || 0; // Initialize with current value from PHP or 0

    // Function to highlight stars
    function highlightStars(rating) {
      stars.forEach((star, index) => {
        if (index < rating) {
          star.classList.add('active');
        } else {
          star.classList.remove('active');
        }
      });
    }

    // Set initial star rating on page load
    if (currentRating > 0) {
      highlightStars(currentRating);
    }

    stars.forEach((star, index) => {
      star.addEventListener('mouseover', () => {
        highlightStars(index + 1);
      });

      star.addEventListener('mouseout', () => {
        highlightStars(currentRating);
      });

      star.addEventListener('click', () => {
        currentRating = index + 1;
        ratingInput.value = currentRating; // Ensure hidden input is updated
        highlightStars(currentRating);

        // Add animation
        star.style.transform = 'scale(1.3)';
        setTimeout(() => {
          star.style.transform = 'scale(1)';
        }, 200);
      });
    });

    // Character counter for textarea
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');

    // Update initial character count
    if (commentTextarea && charCount) {
        charCount.textContent = commentTextarea.value.length;
        // Apply initial color based on length
        const initialCount = commentTextarea.value.length;
        if (initialCount > 900) {
            charCount.style.color = '#dc3545';
        } else if (initialCount > 800) {
            charCount.style.color = '#ffc107';
        } else {
            charCount.style.color = '#666';
        }

        commentTextarea.addEventListener('input', () => {
            const count = commentTextarea.value.length;
            charCount.textContent = count;

            if (count > 900) {
                charCount.style.color = '#dc3545';
            } else if (count > 800) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#666';
            }
        });
    }


    // Form validation
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
      let isValid = true;

      // Clear previous errors from the form (if any were rendered from PHP)
      const formErrors = document.querySelectorAll('.form-group .error');
      formErrors.forEach(errorEl => errorEl.textContent = '');

      // Validate rating
      if (currentRating === 0) {
        showNotification('Please select a rating for your review.', 'error'); // Use global notification
        isValid = false;
      }

      // Validate comment
      const comment = commentTextarea.value.trim();
      if (comment.length < 10) {
        showNotification('Your review must be at least 10 characters long.', 'error'); // Use global notification
        isValid = false;
      }

      // Validate guest name if not logged in
      const guestNameInput = document.getElementById('guest_name');
      // Only validate guest name if the field exists and user is not logged in (user_id is not set)
      if (guestNameInput && guestNameInput.closest('.form-group').style.display !== 'none') { // Check if guest name field is visible
          if (!guestNameInput.value.trim()) {
              showNotification('Please enter your name for the guest review.', 'error');
              isValid = false;
          }
      }


      if (!isValid) {
        e.preventDefault(); // Prevent form submission if validation fails
      }
    });

  </script>
</body>

</html>

<?php include '../footer.php'; ?>
