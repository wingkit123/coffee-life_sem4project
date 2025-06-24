<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BeanMarket - Home</title>
  <link rel="stylesheet" href="home_page.css" />
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <!-- Header -->
  <header class="hero">
    <div class="hero-content">
      <h1 style="text-shadow: 3px 3px 6px rgba(0,0,0,0.8); letter-spacing: 3px;">Bean Market</h1>
      <p style="font-size: 1.6em; text-shadow: 2px 2px 4px rgba(0,0,0,0.7); margin-bottom: 20px;"> -- No Coffee, No LIFE --</p>
      <p style="font-size: 1.2em; background: rgba(111,78,55,0.8); padding: 10px 20px; border-radius: 25px; display: inline-block; margin-top: 15px;">
        Your Best Choice for Affordable Coffee and Snacks! ☕
      </p>

      <!-- Background Music Control -->
      <div class="music-control">
        <button id="musicToggle" class="music-btn" onclick="toggleMusic()">
          <i class="fas fa-music" id="musicIcon"></i>
          <span id="musicText">Play Cafe Ambience</span>
        </button>
        <audio id="backgroundMusic" loop>
          <source src="https://www.soundjay.com/misc/sounds/cafe-ambience.mp3" type="audio/mpeg">
          <source src="https://www.bensound.com/bensound-music/bensound-jazzyfrenchy.mp3" type="audio/mpeg">
        </audio>
      </div>
    </div>
  </header>

  <!-- Toggle Sidebar Button - Positioned on the right -->
  <button class="menu-toggle" onclick="toggleMenu()">☰ Menu</button> <!-- Sidebar Menu - Positioned on the right -->
  <nav class="side-menu" id="sideMenu">
    <ul>
      <li><a href="menu.php"><i class="fas fa-coffee"></i> Menu</a></li>
      <li><a href="about_us.php"><i class="fas fa-info-circle"></i> About Us</a></li>
      <li><a href="contact_us.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
      <li><a href="rating_comments.php"><i class="fas fa-star"></i> Reviews</a></li>
      <li><a href="user_login.php"><i class="fas fa-sign-in-alt"></i> Login / Register</a></li>
      <li><a href="user_dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a></li>
      <li><a href="view_cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
      <li><a href="user_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
  
  <!-- Featured Video Section -->
  <section class="video-section">
    <div class="video-container">
      <h2><i class="fas fa-play-circle"></i> Experience Our Coffee Making Process</h2>
      <p>Watch our master baristas craft the perfect cup of coffee</p>

      <div class="video-player">
        <video id="coffeeVideo" width="100%" height="400" controls poster="https://placehold.co/800x400/6F4E37/ffffff?text=Coffee+Brewing+Video">
          <source src="/latest_version/image/waterdrop.mp4" type="video/mp4">
          
          <!-- <source src="https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4" type="video/mp4"> -->
          Your browser does not support the video tag.
        </video>

        <!-- <div class="video-controls">
          <button onclick="playPauseVideo()" class="video-btn">
            <i class="fas fa-play" id="playIcon"></i> <span id="playText">Play</span>
          </button>
          <button onclick="muteVideo()" class="video-btn">
            <i class="fas fa-volume-up" id="volumeIcon"></i> <span id="volumeText">Mute</span>
          </button>
          <button onclick="fullscreenVideo()" class="video-btn">
            <i class="fas fa-expand"></i> Fullscreen
          </button>
        </div>
      </div> -->

      <!-- <div class="video-playlist">
        <h3>More Coffee Videos</h3>
        <ul class="playlist">
          <li onclick="changeVideo('video1')">
            <i class="fas fa-play"></i> How to Make Perfect Espresso
          </li>
          <li onclick="changeVideo('video2')">
            <i class="fas fa-play"></i> Latte Art Techniques
          </li>
          <li onclick="changeVideo('video3')">
            <i class="fas fa-play"></i> Coffee Bean Origins
          </li>
        </ul>
      </div> -->
    </div>
  </section>
  <!-- New: Announcement and Contact Section -->
  <section class="announcement-section">
    <h2>--Welcome to BeanMarket-- <br>Your Best Choice for Affordable Coffee and Snacks!</h2>
    <p style="color: #8B4513; font-weight: bold; font-size: 1.2em;">
      At BeanMarket, we believe great coffee should be a necessity, No Coffee, No LIFE! Discover our range of expertly crafted beverages and delicious snacks <br>- your perfect alternative to expensive coffee chains.
    </p>
    <p>
      <strong style="color: #6F4E37;">🔥 Our Signature Coffee Beverages:</strong><br>
      <span style="background-color: #fff3cd; padding: 3px 8px; border-radius: 5px; margin: 2px;">Premium Latte (RM 11.90)</span>
      <span style="background-color: #fff3cd; padding: 3px 8px; border-radius: 5px; margin: 2px;">Classic Cappuccino (RM 12.90)</span>
      <span style="background-color: #fff3cd; padding: 3px 8px; border-radius: 5px; margin: 2px;">Bold Americano (RM 9.90)</span>
      <span style="background-color: #fff3cd; padding: 3px 8px; border-radius: 5px; margin: 2px;">Caramel Macchiato (RM 14.90)</span>
      <span style="background-color: #fff3cd; padding: 3px 8px; border-radius: 5px; margin: 2px;">Mocha Delight (RM 13.90)</span>
    </p>
    <p>
      <strong style="color: #6F4E37;">🥐 Fresh Snacks & Pastries:</strong><br>
      Enjoy our selection of <em style="color: #8B4513;">curry puffs, chicken rolls, sausage rolls, and tuna puffs</em> alongside freshly baked
      <em style="color: #8B4513;">blueberry muffins, chocolate chip muffins, and buttery croissants</em>. Perfect companions for your coffee!
    </p>
    <p style="border-left: 5px solid #6F4E37; padding-left: 10px; background: #f8f9fa; margin: 20px 0;">
      <strong>Why Choose BeanMarket?</strong><br>
      ✓ Premium single-origin coffee beans<br>
      ✓ Affordable prices without compromising quality<br>
      ✓ Fresh daily-baked pastries and local favorites<br>
      ✓ Perfect alternative to overpriced coffee chains
    </p>
    <div class="contact-info">
      <div>
        <i class="fas fa-map-marker-alt"></i>
        <span>123 Coffee Lane, Kuala Lumpur, Malaysia</span>
      </div>
      <div>
        <i class="fas fa-phone"></i>
        <span>+60 12-345 6789</span>
      </div>
      <div>
        <i class="fas fa-envelope"></i>
        <span>info@beanmarket.com</span>
      </div>
      <div>
        <i class="fas fa-clock"></i>
        <span>Mon-Fri: 8 AM - 8 PM, Sat-Sun: 9 AM - 7 PM</span>
      </div>
    </div>
  </section>
  <!-- New: Barista Spotlight Section -->
  <section class="barista-spotlight">
    <h2>Today's Coffee Spotlight & Fresh Selections!</h2>
    <div class="spotlight-content">
      <div class="barista-duty">
        <h3 style="color: #6F4E37; text-decoration: underline;">Featured Barista: Marcus L.</h3>
        <p>
          Marcus is our expert barista specializing in espresso-based drinks. Today he's crafting our signature <strong style="background: #fff3cd; padding: 2px 6px; border-radius: 4px;">Caramel Macchiato</strong>
          and perfecting our <strong style="background: #e8f5e8; padding: 2px 6px; border-radius: 4px;">Premium Latte</strong> with locally-sourced milk.
          Ask him about our coffee bean origins and brewing techniques!
        </p>
        <p style="font-style: italic; color: #666; border-top: 1px dashed #ccc; padding-top: 10px; margin-top: 15px;">
          "Every cup tells a story. At BeanMarket, we make sure it's a great one!" - Marcus
        </p>
      </div>
      <div class="special-coffee">
        <h3 style="color: #8B4513; text-decoration: underline;">Today's Special: Ethiopian Single Origin Americano</h3>
        <p>
          Experience the bold, clean taste of our <span style="font-weight: bold; color: #6F4E37;">Ethiopian Single Origin Americano</span>
          featuring beans from the highlands of Sidamo. This light-to-medium roast offers bright acidity with notes of citrus and chocolate.
          <span style="background: #d4edda; padding: 3px 8px; border-radius: 5px; color: #155724;">Available today only at RM 9.90!</span>
        </p>
        <p style="color: #666;">
          Perfect with our fresh <strong>curry puffs</strong> or <strong>banana walnut muffins</strong> for a complete Malaysian coffee experience.
        </p>
      </div>
    </div>
  </section>

  <!-- Promotions (Existing section, you can update content if needed) -->
  <section class="promotion">
    <h2>Fresh Beans, Pure Taste</h2>
    <div class="promo-image">
      <img src="https://placehold.co/800x450/A0522D/ffffff?text=Special+Coffee+Offer" alt="Promo Coffee Beans">
    </div>
  </section>

  <!-- Enhanced Lists Section -->
  <section class="features-list">
    <h3><i class="fas fa-list"></i> Why Choose Beans Cafe?</h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 30px;">
      <!-- Ordered List Example -->
      <div>
        <h4 style="color: #6F4E37; margin-bottom: 15px;"><i class="fas fa-sort-numeric-up"></i> Our Process</h4>
        <ol class="ordered-features" style="border: 2px solid #6F4E37; border-radius: 10px; padding: 20px; background: linear-gradient(45deg, #fefefe, #f8f9fa);">
          <li style="margin-bottom: 10px;"><strong style="color: #8B4513;">Source</strong> - We import premium beans from Ethiopia, Colombia, and Brazil</li>
          <li style="margin-bottom: 10px;"><strong style="color: #8B4513;">Roast</strong> - Our master roasters carefully craft each batch to perfection</li>
          <li style="margin-bottom: 10px;"><strong style="color: #8B4513;">Grind</strong> - Fresh grinding ensures maximum flavor and aroma in every cup</li>
          <li style="margin-bottom: 10px;"><strong style="color: #8B4513;">Brew</strong> - Expert baristas create your Latte, Cappuccino, or Americano</li>
          <li style="margin-bottom: 0;"><strong style="color: #8B4513;">Serve</strong> - Enjoy with our fresh curry puffs, muffins, or pastries</li>
        </ol>
      </div>

      <!-- Unordered List Example -->
      <div>
        <h4 style="color: #6F4E37; margin-bottom: 15px;"><i class="fas fa-star"></i> Special Features</h4>
        <ul class="unordered-features">
          <li>Premium Latte, Cappuccino & Americano available daily</li>
          <li>Fresh curry puffs, chicken rolls & sausage rolls</li>
          <li>Daily-baked muffins: blueberry, chocolate chip & banana walnut</li>
          <li>Free Wi-Fi and comfortable seating for remote work</li>
          <li>Affordable prices - quality coffee as a necessity, not luxury</li>
          <li>Quick takeaway service for busy professionals</li>
        </ul>
      </div>

      <!-- Image Bullet List Example -->
      <div>
        <h4 style="color: #6F4E37; margin-bottom: 15px;"><i class="fas fa-coffee"></i> Coffee Varieties</h4>
        <ul class="image-bullet-list">
          <li>Espresso-based Drinks - Latte, Cappuccino, Macchiato, Americano</li>
          <li>Cold Coffee Options - Iced Latte, Frappe, Cold Brew</li>
          <li>Malaysian Local Pastries - Curry Puff, Chicken Roll, Tuna Puff</li>
          <li>Western Bakery Items - Muffins, Croissants, Sausage Rolls</li>
          <li>Healthy Options - Smoothie Bowls, Iced Tea, Fresh Fruits</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Menu Pricing Table Section -->
  <section style="background: white; padding: 40px 20px; margin: 40px auto; max-width: 1200px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: #6F4E37; margin-bottom: 30px;">
      <i class="fas fa-table"></i> Today's Menu & Pricing
    </h2>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">
      Affordable quality coffee and snacks - because great taste shouldn't break the bank!
    </p>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; margin: 20px 0; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
        <thead>
          <tr style="background: linear-gradient(135deg, #6F4E37, #8B4513); color: white;">
            <th style="padding: 15px; text-align: left; font-weight: bold; border-bottom: 2px solid #5a3a29;">
              <i class="fas fa-coffee"></i> Beverage
            </th>
            <th style="padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #5a3a29;">
              Size
            </th>
            <th style="padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #5a3a29;">
              Price (RM)
            </th>
            <th style="padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #5a3a29;">
              <i class="fas fa-star"></i> Popular
            </th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #6F4E37;">Premium Latte</td>
            <td style="padding: 12px 15px; text-align: center;">12oz</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">11.90</td>
            <td style="padding: 12px 15px; text-align: center;">⭐⭐⭐⭐⭐</td>
          </tr>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #6F4E37;">Classic Cappuccino</td>
            <td style="padding: 12px 15px; text-align: center;">10oz</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">12.90</td>
            <td style="padding: 12px 15px; text-align: center;">⭐⭐⭐⭐⭐</td>
          </tr>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #6F4E37;">Bold Americano</td>
            <td style="padding: 12px 15px; text-align: center;">12oz</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">9.90</td>
            <td style="padding: 12px 15px; text-align: center;">⭐⭐⭐⭐</td>
          </tr>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #6F4E37;">Caramel Macchiato</td>
            <td style="padding: 12px 15px; text-align: center;">12oz</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">14.90</td>
            <td style="padding: 12px 15px; text-align: center;">⭐⭐⭐⭐</td>
          </tr>
        </tbody>
      </table>

      <table style="width: 100%; border-collapse: collapse; margin: 30px 0 20px 0; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
        <thead>
          <tr style="background: linear-gradient(135deg, #8B4513, #A0522D); color: white;">
            <th style="padding: 15px; text-align: left; font-weight: bold; border-bottom: 2px solid #7a3f1a;">
              <i class="fas fa-cookie-bite"></i> Snacks & Pastries
            </th>
            <th style="padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #7a3f1a;">
              Type
            </th>
            <th style="padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #7a3f1a;">
              Price (RM)
            </th>
            <th style="padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #7a3f1a;">
              <i class="fas fa-heart"></i> Recommended
            </th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #8B4513;">Curry Puff</td>
            <td style="padding: 12px 15px; text-align: center;">Malaysian Savory</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">4.50</td>
            <td style="padding: 12px 15px; text-align: center;">💛 Local Favorite</td>
          </tr>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #8B4513;">Blueberry Muffin</td>
            <td style="padding: 12px 15px; text-align: center;">Fresh Baked</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">6.50</td>
            <td style="padding: 12px 15px; text-align: center;">💛 Best Seller</td>
          </tr>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #8B4513;">Chicken Roll</td>
            <td style="padding: 12px 15px; text-align: center;">Savory Pastry</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">5.90</td>
            <td style="padding: 12px 15px; text-align: center;">💛 Perfect Pair</td>
          </tr>
          <tr style="border-bottom: 1px solid #e9ecef; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <td style="padding: 12px 15px; font-weight: bold; color: #8B4513;">Croissant</td>
            <td style="padding: 12px 15px; text-align: center;">French Pastry</td>
            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #28a745;">8.90</td>
            <td style="padding: 12px 15px; text-align: center;">💛 Premium Choice</td>
          </tr>
        </tbody>
      </table>
    </div>

    <p style="text-align: center; color: #666; font-style: italic; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 20px;">
      * All prices include SST!
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <p>&copy; 2025 BeanMarket. All rights reserved.</p>
      <p>Follow us on social media for updates and deals.</p>
      <p>&copy; 2025 Cozy Café, Kuala Lumpur</p>
    </div>
  </footer>

  <!-- JavaScript -->
  <script>
    const menu = document.getElementById('sideMenu');

    function toggleMenu() {
      // Toggle the 'active' class on the menu for sliding in/out
      menu.classList.toggle('active');
    }

    // Close menu if clicked outside (optional, but good for UX)
    document.addEventListener('click', function(event) {
      if (!menu.contains(event.target) && !event.target.classList.contains('menu-toggle')) {
        menu.classList.remove('active');
      }
    });


    // Slide functionality
    let currentSlide = 0;
    const slides = [
      'https://placehold.co/1200x675/6F4E37/ffffff?text=Premium+Coffee+Beans',
      'https://placehold.co/1200x675/8B4513/ffffff?text=Fresh+Roasted+Daily',
      'https://placehold.co/1200x675/A0522D/ffffff?text=Artisan+Desserts'
    ];

    function nextSlide() {
      currentSlide = (currentSlide + 1) % slides.length;
      document.getElementById('slideImage').src = slides[currentSlide];
    }

    function prevSlide() {
      currentSlide = (currentSlide - 1 + slides.length) % slides.length;
      document.getElementById('slideImage').src = slides[currentSlide];
    }

    // Auto-advance slides
    setInterval(nextSlide, 5000);

    // Music functionality
    let musicPlaying = false;
    const music = document.getElementById('backgroundMusic');

    function toggleMusic() {
      const musicIcon = document.getElementById('musicIcon');
      const musicText = document.getElementById('musicText');

      if (musicPlaying) {
        music.pause();
        musicIcon.className = 'fas fa-music';
        musicText.textContent = 'Play Ambience';
        musicPlaying = false;
      } else {
        // Create a more realistic coffee shop ambience
        music.volume = 0.3; // Set comfortable volume
        music.play().catch(function(error) {
          console.log('Audio play failed:', error);
          // Fallback for browsers that require user interaction
          alert('Click anywhere on the page first, then try playing music again.');
        });
        musicIcon.className = 'fas fa-pause';
        musicText.textContent = 'Pause Ambience';
        musicPlaying = true;
      }
    }

    // Video functionality
    const video = document.getElementById('coffeeVideo');
    let isPlaying = false;

    function playPauseVideo() {
      const playIcon = document.getElementById('playIcon');
      const playText = document.getElementById('playText');

      if (isPlaying) {
        video.pause();
        playIcon.className = 'fas fa-play';
        playText.textContent = 'Play';
        isPlaying = false;
      } else {
        video.play();
        playIcon.className = 'fas fa-pause';
        playText.textContent = 'Pause';
        isPlaying = true;
      }
    }

    function muteVideo() {
      const volumeIcon = document.getElementById('volumeIcon');
      const volumeText = document.getElementById('volumeText');

      if (video.muted) {
        video.muted = false;
        volumeIcon.className = 'fas fa-volume-up';
        volumeText.textContent = 'Mute';
      } else {
        video.muted = true;
        volumeIcon.className = 'fas fa-volume-mute';
        volumeText.textContent = 'Unmute';
      }
    }

    function fullscreenVideo() {
      if (video.requestFullscreen) {
        video.requestFullscreen();
      } else if (video.webkitRequestFullscreen) {
        video.webkitRequestFullscreen();
      } else if (video.msRequestFullscreen) {
        video.msRequestFullscreen();
      }
    }

    function changeVideo(videoId) {
      // Simulate changing videos
      const videos = {
        'video1': 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
        'video2': 'https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4',
        'video3': 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4'
      };

      if (videos[videoId]) {
        video.src = videos[videoId];
        video.load();
      }
    }

    // Auto-play music when page loads (with user interaction)
    document.addEventListener('click', function() {
      if (!musicPlaying) {
        // Don't auto-play, just make it easier for user to start
        document.getElementById('musicToggle').style.animation = 'pulse 2s infinite';
      }
    }, {
      once: true
    });

    // Add sound effects for interactions
    function playClickSound() {
      const clickSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwjBi5+zu3CdUAKFl604O5vGQlOmNz11H4yBTJ9yO1xIQhNmttztJ4cCj2FxPBkJw5WrN3jqGQgBzdvws3CdHIjBjqLx+2CQQw0vN9OOsO2fCwJ')
      clickSound.volume = 0.1;
      clickSound.play().catch(() => {});
    }

    // Add click sounds to buttons
    document.querySelectorAll('button, .nav-link, .btn').forEach(element => {
      element.addEventListener('click', playClickSound);
    });
  </script>

</body>

</html>