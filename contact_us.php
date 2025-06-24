<?php
// contact_us.php
require_once 'functions.php';
session_start();

$name = $email = $subject = $message = "";
$name_err = $email_err = $subject_err = $message_err = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate name
  if (empty(trim($_POST["name"]))) {
    $name_err = "Please enter your name.";
  } else {
    $name = trim($_POST["name"]);
  }

  // Validate email
  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter your email.";
  } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
    $email_err = "Please enter a valid email format.";
  } else {
    $email = trim($_POST["email"]);
  }

  // Validate subject
  if (empty(trim($_POST["subject"]))) {
    $subject_err = "Please enter a subject.";
  } else {
    $subject = trim($_POST["subject"]);
  }

  // Validate message
  if (empty(trim($_POST["message"]))) {
    $message_err = "Please enter your message.";
  } else {
    $message = trim($_POST["message"]);
  }

  // If no errors, process the contact form
  if (empty($name_err) && empty($email_err) && empty($subject_err) && empty($message_err)) {
    // In a real application, you would send an email or save to database
    $success_msg = "Thank you for contacting us! We'll get back to you soon.";
    $name = $email = $subject = $message = ""; // Clear form
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Beans Cafe</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Specific styles for contact_us.php */
    .shop-name-nav {
      position: fixed;
      top: 20px;
      right: 20px;
      background: rgba(255, 255, 255, 0.95);
      padding: 10px 20px;
      border-radius: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      transition: all 0.3s ease;
    }

    .shop-name-nav:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .shop-name-nav a {
      color: #6F4E37;
      text-decoration: none;
      font-weight: bold;
      font-size: 1.2em;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .shop-name-nav a:hover {
      color: #8B4513;
    }

    .contact-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
      min-height: 100vh;
    }

    .contact-header {
      text-align: center;
      color: white;
      margin-bottom: 40px;
    }

    .contact-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .contact-info {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 10px;
      border-left: 5px solid #6F4E37;
    }

    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      font-size: 16px;
    }

    .info-item i {
      color: #6F4E37;
      font-size: 20px;
      margin-right: 15px;
      width: 25px;
    }

    .contact-form {
      background: white;
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

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #6F4E37;
      box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
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

    .map-container {
      grid-column: 1 / -1;
      margin-top: 30px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .business-hours {
      background: #fff3cd;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
      border-left: 5px solid #ffc107;
    }

    .hours-list {
      list-style: none;
      padding: 0;
    }

    .hours-list li {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px dashed #ddd;
    }

    .hours-list li:last-child {
      border-bottom: none;
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

    @media (max-width: 768px) {
      .contact-content {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <!-- Shop Name Navigation -->
  <div class="shop-name-nav">
    <a href="index.php">
      <i class="fas fa-coffee"></i>
      BeanMarket
    </a>
  </div>

  <div class="contact-container">

    <div class="contact-header">
      <h1><i class="fas fa-coffee"></i> Contact Beans Cafe</h1>
      <p>We'd love to hear from you! Get in touch with us.</p>
    </div>

    <div class="contact-content">
      <div class="contact-info">
        <h2><i class="fas fa-info-circle"></i> Get in Touch</h2>

        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <strong>Address:</strong><br>
            123 Coffee Street, Bean District<br>
            Kuala Lumpur, Malaysia 50450
          </div>
        </div>

        <div class="info-item">
          <i class="fas fa-phone"></i>
          <div>
            <strong>Phone:</strong><br>
            +60 3-1234 5678
          </div>
        </div>

        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <div>
            <strong>Email:</strong><br>
            hello@beanscafe.com
          </div>
        </div>

        <div class="info-item">
          <i class="fas fa-globe"></i>
          <div>
            <strong>Website:</strong><br>
            www.beanscafe.com
          </div>
        </div>

        <div class="business-hours">
          <h3><i class="fas fa-clock"></i> Business Hours</h3>
          <ul class="hours-list">
            <li><span>Monday - Friday</span><span>7:00 AM - 10:00 PM</span></li>
            <li><span>Saturday</span><span>8:00 AM - 11:00 PM</span></li>
            <li><span>Sunday</span><span>8:00 AM - 9:00 PM</span></li>
            <li><span>Public Holidays</span><span>9:00 AM - 8:00 PM</span></li>
          </ul>
        </div>
      </div>

      <div class="contact-form">
        <h2><i class="fas fa-paper-plane"></i> Send us a Message</h2>

        <?php if (!empty($success_msg)): ?>
          <div class="success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="contactForm">
          <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <?php if (!empty($name_err)): ?>
              <div class="error"><?php echo $name_err; ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <?php if (!empty($email_err)): ?>
              <div class="error"><?php echo $email_err; ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="subject">Subject *</label>
            <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" required>
            <?php if (!empty($subject_err)): ?>
              <div class="error"><?php echo $subject_err; ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="message">Your Message *</label>
            <textarea id="message" name="message" required><?php echo $message; ?></textarea>
            <?php if (!empty($message_err)): ?>
              <div class="error"><?php echo $message_err; ?></div>
            <?php endif; ?>
          </div>

          <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>

      <!-- Google Maps Integration -->
      <div class="map-container">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.8158556960455!2d101.68673261475367!3d3.139003397694952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc49c701efeae7%3A0xf4d98e5b2f1c287d!2sKuala%20Lumpur%2C%20Federal%20Territory%20of%20Kuala%20Lumpur%2C%20Malaysia!5e0!3m2!1sen!2smy!4v1635123456789!5m2!1sen!2smy"
          width="100%"
          height="300"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    // Enhanced form validation with JavaScript
    document.getElementById('contactForm').addEventListener('submit', function(e) {
      var isValid = true;

      // Clear previous JavaScript errors only
      var existingJsErrors = document.querySelectorAll('.js-error');
      existingJsErrors.forEach(function(error) {
        error.remove();
      });

      // Validate name
      var name = document.getElementById('name').value.trim();
      if (name.length < 2) {
        showError('name', 'Name must be at least 2 characters long');
        isValid = false;
      }

      // Validate email
      var email = document.getElementById('email').value.trim();
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showError('email', 'Please enter a valid email address');
        isValid = false;
      }

      // Validate message length
      var message = document.getElementById('message').value.trim();
      if (message.length < 10) {
        showError('message', 'Message must be at least 10 characters long');
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
      }
    });

    function showError(fieldId, message) {
      var field = document.getElementById(fieldId);
      var errorDiv = document.createElement('div');
      errorDiv.className = 'error js-error';
      errorDiv.textContent = message;
      field.parentNode.appendChild(errorDiv);
    }

    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
      var formGroups = document.querySelectorAll('.form-group');
      formGroups.forEach(function(group, index) {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        setTimeout(function() {
          group.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
          group.style.opacity = '1';
          group.style.transform = 'translateY(0)';
        }, index * 100);
      });
    });
  </script>
</body>

</html>