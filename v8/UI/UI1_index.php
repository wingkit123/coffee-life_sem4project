<?php
// index.php
session_start();
include '../header.php';
?>

<!-- New: Announcement and Contact Section -->
<section class="announcement-section">
  <h2>Welcome <br>Your Best Choice for Affordable Coffee and Snacks!</h2>
  <p style="color: #8B4513; font-weight: bold; font-size: 1.2em;">
    At BeanMarket, we believe great coffee should be a necessity, No Coffee, No LIFE! Discover our range of expertly crafted beverages and delicious snacks <br>- your perfect alternative to expensive coffee chains.
  </p>

  <section class="video-section">
    <div class="video-container">
      <h2><i class="fas fa-play-circle"></i> Experience Our Coffee Making Process</h2>
      <p>Watch our master baristas craft the perfect cup of coffee</p>

      <div class="video-player">
        <iframe width="100%" height="400"
          src="https://www.youtube.com/embed/Jz1sJHzk2To"
          title="Coffee Making Process"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowfullscreen>
        </iframe>
      </div>
  </section>

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
</section>

<?php include '../footer.php'; ?>