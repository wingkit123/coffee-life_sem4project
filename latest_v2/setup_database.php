<?php
// setup_database.php - Automatic Database Setup for BeanMarket
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BeanMarket Database Setup</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 800px;
      margin: 50px auto;
      padding: 20px;
      background: #f8f9fa;
    }

    .container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .success {
      color: #28a745;
    }

    .error {
      color: #dc3545;
    }

    .info {
      color: #007bff;
    }

    h1 {
      color: #6F4E37;
    }

    .btn {
      background: #6F4E37;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
      margin: 10px 5px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>🗄️ BeanMarket Database Setup</h1>

    <?php
    if (isset($_POST['setup'])) {
      $host = "localhost";
      $username = "root";
      $password = "";

      echo "<h2>Setting up database...</h2>";

      try {
        // Connect without selecting database first
        $conn = new mysqli($host, $username, $password);

        if ($conn->connect_error) {
          throw new Exception("Connection failed: " . $conn->connect_error);
        }

        echo "<p class='info'>✓ Connected to MySQL server</p>";

        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS beans_cafe";
        if ($conn->query($sql) === TRUE) {
          echo "<p class='success'>✓ Database 'beans_cafe' created successfully</p>";
        } else {
          echo "<p class='error'>✗ Error creating database: " . $conn->error . "</p>";
        }

        // Select the database
        $conn->select_db("beans_cafe");
        echo "<p class='info'>✓ Selected beans_cafe database</p>";

        // Read and execute SQL file
        $sqlFile = 'beans_cafe.sql';
        if (file_exists($sqlFile)) {
          echo "<p class='info'>✓ Found SQL file: $sqlFile</p>";

          $sql = file_get_contents($sqlFile);

          // Remove comments and split by semicolon
          $sql = preg_replace('/--.*$/m', '', $sql); // Remove single line comments
          $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments

          $commands = explode(';', $sql);
          $successCount = 0;
          $errorCount = 0;

          foreach ($commands as $command) {
            $command = trim($command);
            if (!empty($command) && strlen($command) > 5) {
              if ($conn->query($command)) {
                $successCount++;
              } else {
                $errorCount++;
                echo "<p class='error'>✗ Error executing command: " . $conn->error . "</p>";
              }
            }
          }

          echo "<p class='success'>✓ Executed $successCount SQL commands successfully</p>";
          if ($errorCount > 0) {
            echo "<p class='error'>✗ $errorCount commands had errors</p>";
          }

          echo "<h2 class='success'>🎉 Database setup completed!</h2>";
          echo "<p>Your BeanMarket database is now ready to use.</p>";

          echo "<h3>Default Admin Account:</h3>";
          echo "<ul>";
          echo "<li><strong>Username:</strong> test123</li>";
          echo "<li><strong>Password:</strong> test123</li>";
          echo "</ul>";

          echo "<h3>Next Steps:</h3>";
          echo "<a href='index.html' class='btn'>Visit Homepage</a>";
          echo "<a href='admin/login.php' class='btn'>Admin Login</a>";
          echo "<a href='about_us.php' class='btn'>Test About Us</a>";
        } else {
          echo "<p class='error'>✗ SQL file 'beans_cafe.sql' not found!</p>";
          echo "<p>Make sure the file is in the same directory as this setup script.</p>";
        }

        $conn->close();
      } catch (Exception $e) {
        echo "<p class='error'>✗ Setup failed: " . $e->getMessage() . "</p>";
        echo "<h3>Troubleshooting:</h3>";
        echo "<ul>";
        echo "<li>Make sure XAMPP is running (Apache and MySQL)</li>";
        echo "<li>Check if MySQL service is started in XAMPP Control Panel</li>";
        echo "<li>Verify that 'beans_cafe.sql' file exists in this directory</li>";
        echo "</ul>";
      }
    } else {
    ?>
      <p>This script will automatically set up the BeanMarket database for you.</p>

      <h3>Prerequisites:</h3>
      <ul>
        <li>✓ XAMPP is installed and running</li>
        <li>✓ Apache and MySQL services are started</li>
        <li>✓ beans_cafe.sql file is in the same directory</li>
      </ul>

      <h3>What this will do:</h3>
      <ul>
        <li>Create the 'beans_cafe' database</li>
        <li>Create all necessary tables (admins, products, etc.)</li>
        <li>Insert sample data</li>
        <li>Set up default admin account</li>
      </ul>

      <form method="post">
        <button type="submit" name="setup" class="btn" style="font-size: 18px; padding: 15px 30px;">
          🚀 Setup Database Now
        </button>
      </form>
    <?php
    }
    ?>
  </div>
</body>

</html>