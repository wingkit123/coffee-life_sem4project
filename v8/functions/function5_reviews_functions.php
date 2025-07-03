<?php
// function5_reviews_functions.php - Simple rating and reviews system

require_once __DIR__ . '/../config.php';

/**
 * Add a new review/rating to the database
 */
function addReview($user_id, $guest_name, $rating, $comment, $ip_address)
{
  global $pdo;

  try {
    $sql = "INSERT INTO reviews (user_id, guest_name, rating, comment, ip_address, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'approved', NOW())";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
      $user_id ?: null,
      $guest_name,
      $rating,
      $comment,
      $ip_address
    ]);

    if ($result) {
      return $pdo->lastInsertId();
    }
    return false;
  } catch (PDOException $e) {
    error_log("Error adding review: " . $e->getMessage());
    return false;
  }
}

/**
 * Get the newest reviews
 */
function getLatestReviews($limit = 5)
{
  global $pdo;

  try {
    $sql = "SELECT r.*, u.username 
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.status = 'approved' 
                ORDER BY r.created_at DESC 
                LIMIT ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$limit]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    error_log("Error getting reviews: " . $e->getMessage());
    return [];
  }
}

/**
 * Get basic review statistics
 */
function getReviewStats()
{
  global $pdo;

  try {
    $sql = "SELECT 
                    COUNT(*) as total_reviews,
                    AVG(rating) as average_rating
                FROM reviews 
                WHERE status = 'approved'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stats && $stats['total_reviews'] > 0) {
      $stats['average_rating'] = round($stats['average_rating'], 1);
    } else {
      $stats = [
        'total_reviews' => 0,
        'average_rating' => 0
      ];
    }

    return $stats;
  } catch (PDOException $e) {
    error_log("Error getting review stats: " . $e->getMessage());
    return [
      'total_reviews' => 0,
      'average_rating' => 0
    ];
  }
}

/**
 * Check if user/IP has already reviewed recently (24 hour limit)
 */
function hasUserReviewed($user_id, $ip_address)
{
  global $pdo;

  try {
    if ($user_id) {
      $sql = "SELECT COUNT(*) FROM reviews WHERE user_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$user_id]);
    } else {
      $sql = "SELECT COUNT(*) FROM reviews WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$ip_address]);
    }

    return $stmt->fetchColumn() > 0;
  } catch (PDOException $e) {
    error_log("Error checking user review: " . $e->getMessage());
    return false;
  }
}

/**
 * Validate review input
 */
function validateReviewInput($rating, $comment, $guest_name = '')
{
  $errors = [];

  // Validate rating
  if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
    $errors['rating'] = 'Please select a valid rating between 1 and 5 stars.';
  }

  // Validate comment
  $comment = trim($comment);
  if (empty($comment)) {
    $errors['comment'] = 'Please enter your review comment.';
  } elseif (strlen($comment) < 10) {
    $errors['comment'] = 'Review comment must be at least 10 characters long.';
  } elseif (strlen($comment) > 1000) {
    $errors['comment'] = 'Review comment cannot exceed 1000 characters.';
  }

  // Validate guest name if provided
  if (!empty($guest_name)) {
    if (strlen($guest_name) < 2) {
      $errors['guest_name'] = 'Name must be at least 2 characters long.';
    } elseif (strlen($guest_name) > 100) {
      $errors['guest_name'] = 'Name cannot exceed 100 characters.';
    }
  }

  return $errors;
}

/**
 * Get user's IP address
 */
function getUserIpAddress()
{
  return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

/**
 * Format review display name
 */
function getReviewDisplayName($review)
{
  if (!empty($review['username'])) {
    return htmlspecialchars($review['username']);
  } elseif (!empty($review['guest_name'])) {
    return htmlspecialchars($review['guest_name']);
  } else {
    return 'Anonymous Customer';
  }
}

/**
 * Generate star rating HTML
 */
function generateStarRating($rating)
{
  $html = '<div class="star-rating-display">';

  for ($i = 1; $i <= 5; $i++) {
    if ($i <= $rating) {
      $html .= '<i class="fas fa-star"></i>';
    } else {
      $html .= '<i class="far fa-star"></i>';
    }
  }

  $html .= '</div>';
  return $html;
}
