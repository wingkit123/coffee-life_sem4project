<?php
// UI10_contact_us.php

require_once '../functions/functions.php';
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

include '../header.php';
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="../css/contact-us.css">

<div class="contact-container">

  <div class="contact-header">
    <h1 style="color: rgb(111, 78, 55);"><i class="fas fa-coffee"></i> Contact Beans Cafe</h1>
    <p style="color: rgb(111, 78, 55);">We'd love to hear from you! Get in touch with us.</p>
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
          <div id="name_error" class="error"></div>
        </div>

        <div class="form-group">
          <label for="email">Email Address *</label>
          <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
          <?php if (!empty($email_err)): ?>
            <div class="error"><?php echo $email_err; ?></div>
          <?php endif; ?>
          <div id="email_error" class="error"></div>
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
          <div id="message_error" class="error"></div>
        </div>
        <?php if (!empty($message_err)): ?>
          <div class="error"><?php echo $message_err; ?></div>
        <?php endif; ?>

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
      showFormError('name', 'Name must be at least 2 characters long');
      isValid = false;
    }

    // Validate email
    var email = document.getElementById('email').value.trim();
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showFormError('email', 'Please enter a valid email address');
      isValid = false;
    }

    // Validate message length
    var message = document.getElementById('message').value.trim();

    if (message.length < 10) {
      showFormError('message', 'Message must be at least 10 characters long');
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
    }
  });

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

  // Helper to show JS error below field
  function showFormError(field, message) {
    var errorDiv = document.getElementById(field + '_error');
    if (errorDiv) {
      errorDiv.textContent = message;
      errorDiv.classList.add('js-error');
    }
  }
</script>
</body>

</html>
</body>

</html>