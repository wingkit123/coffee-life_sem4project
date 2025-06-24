# 🗄️ Database Setup Instructions for BeanMarket

## Method 1: Using phpMyAdmin (Recommended for beginners)

1. **Start XAMPP:**

   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL** services

2. **Open phpMyAdmin:**

   - Go to your browser and visit: `http://localhost/phpmyadmin`
   - You should see the phpMyAdmin interface

3. **Create the Database:**

   - Click on "New" in the left sidebar
   - Enter database name: `beans_cafe`
   - Click "Create"

4. **Import the SQL file:**
   - Select the `beans_cafe` database from the left sidebar
   - Click on the "Import" tab
   - Click "Choose File" and select `beans_cafe.sql` from your project folder
   - Click "Import" at the bottom

## Method 2: Using MySQL Command Line

1. **Open Command Prompt as Administrator**

2. **Navigate to MySQL bin directory:**

   ```bash
   cd C:\xampp\mysql\bin
   ```

3. **Connect to MySQL:**

   ```bash
   mysql -u root -p
   ```

   (Press Enter when prompted for password, as XAMPP default has no password)

4. **Create the database:**

   ```sql
   CREATE DATABASE beans_cafe;
   USE beans_cafe;
   ```

5. **Import the SQL file:**
   ```bash
   source C:\Users\241DC240PJ\Downloads\latest_version\beans_cafe.sql
   ```

## Method 3: Quick Setup Script

Create this PHP script to automatically set up your database:

**File: `setup_database.php`**

```php
<?php
$host = "localhost";
$username = "root";
$password = "";

// Connect without selecting database first
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS beans_cafe";
if ($conn->query($sql) === TRUE) {
    echo "Database 'beans_cafe' created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db("beans_cafe");

// Read and execute SQL file
$sqlFile = 'beans_cafe.sql';
if (file_exists($sqlFile)) {
    $sql = file_get_contents($sqlFile);

    // Split SQL commands and execute them
    $commands = explode(';', $sql);
    foreach ($commands as $command) {
        $command = trim($command);
        if (!empty($command)) {
            if ($conn->query($command) === TRUE) {
                echo "Command executed successfully<br>";
            } else {
                echo "Error: " . $conn->error . "<br>";
            }
        }
    }
    echo "<h2>Database setup completed!</h2>";
} else {
    echo "SQL file not found!";
}

$conn->close();
?>
```

## 🚀 Verification Steps

After setting up the database:

1. **Check if database exists:**

   - Go to phpMyAdmin
   - You should see `beans_cafe` in the left sidebar

2. **Verify tables:**

   - Click on `beans_cafe` database
   - You should see tables: `admins`, `products`, etc.

3. **Test your website:**
   - Visit `http://localhost/latest_version/about_us.php`
   - The error should be gone

## 🔧 Troubleshooting

**If you still get errors:**

1. **Check XAMPP services:** Make sure both Apache and MySQL are running
2. **Check database name:** Ensure it's exactly `beans_cafe` (case-sensitive on some systems)
3. **Check file path:** Make sure `beans_cafe.sql` is in the correct location
4. **Check MySQL credentials:** Default XAMPP uses username: `root`, password: (empty)

## 📋 Default Admin Login (After Database Setup)

- **Username:** test123
- **Password:** test123
- **Admin URL:** `http://localhost/latest_version/admin/login.php`

---

Once you complete the database setup, all pages including the About Us page will work perfectly! 🎉
