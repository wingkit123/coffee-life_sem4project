<?php
// about_us.php
// For about_us.php, we don't actually need database connection since it's mostly static content
// Only include functions.php if it doesn't cause database connection errors
try {
    require_once 'functions.php';
} catch (Exception $e) {
    // If database connection fails, continue without functions.php for this static page
    error_log("Database connection failed in about_us.php: " . $e->getMessage());
}

// Start session if necessary, though not strictly needed for this static page content
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - BeanMarket</title>
    <link rel="stylesheet" href="style.css"> <!-- Use your main style.css for general styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6F4E37 0%, #8B4513 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

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

        .about-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 25px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .about-container h1 {
            color: #343a40;
            margin-bottom: 25px;
            font-size: 2.5em;
        }

        .about-content {
            text-align: left;
            margin-bottom: 30px;
        }

        .about-content p {
            font-size: 1.1em;
            line-height: 1.8;
            color: #555;
            margin-bottom: 15px;
        }

        .social-media {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #e0e0e0;
        }

        .social-media h2 {
            font-size: 1.8em;
            color: #6F4E37;
            /* Coffee brown */
            margin-bottom: 20px;
        }

        .social-links a {
            display: inline-block;
            margin: 0 15px;
            color: #4A2B1D;
            /* Dark coffee color */
            font-size: 2.5em;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .social-links a:hover {
            color: #8B4513;
            /* SaddleBrown */
            transform: translateY(-3px);
        }

        .social-links a:nth-child(1) {
            color: #1877F2;
        }

        /* Facebook Blue */
        .social-links a:nth-child(2) {
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Instagram Gradient */
        .social-links a:nth-child(3) {
            color: #1DA1F2;
        }

        /* Twitter Blue */

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .about-container {
                margin: 20px auto;
                padding: 15px;
            }

            .about-container h1 {
                font-size: 2em;
            }

            .about-content p {
                font-size: 1em;
            }

            .social-links a {
                font-size: 2em;
                margin: 0 10px;
            }
        }

        @media (max-width: 480px) {
            .social-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .social-links a {
                font-size: 1.8em;
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

    <div class="container about-container">
        <h1>About BeanMarket</h1>

        <div class="about-content">
            <p>
                Welcome to <strong>BeanMarket</strong>, your cozy corner for exceptional coffee and delightful desserts in Kuala Lumpur! We believe that a great cup of coffee has the power to transform your day, and a sweet treat can make any moment special.
            </p>
            <p>
                <strong>Why we do the café?</strong> Our passion for coffee runs deep. We started BeanMarket with a simple mission: to share the joy of truly exquisite coffee. We meticulously source beans from sustainable farms around the world, prioritizing ethical practices and superior quality. Every bean tells a story, and we're here to brew it into your cup, providing a rich, aromatic experience that awakens your senses. Beyond coffee, we craft desserts that are a perfect harmony of flavor and artistry, designed to complement your drink and sweeten your life. We are more than just a café; we are a community hub where connections are brewed, and memories are made.
            </p>
            <p>
                At BeanMarket, we are committed to sustainability, quality, and creating a welcoming atmosphere for everyone. Come and experience the difference!
            </p>
        </div>

        <div class="social-media">
            <h2>Connect With Us!</h2>
            <div class="social-links">
                <a href="https://facebook.com/yourbeanmarket" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://instagram.com/yourbeanmarket" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://twitter.com/yourbeanmarket" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <!-- You can add more social media links here -->
            </div>
            <p style="margin-top: 20px; font-size: 0.9em; color: #777;">
                Find us on social media for daily updates, special offers, and behind-the-scenes content!
            </p>
        </div>
    </div>
</body>

</html>