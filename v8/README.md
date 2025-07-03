# Coffee's Life - Project Structure

## Overview
Coffee's Life is a PHP-based coffee shop web application with a clean, organized structure.

## Directory Structure

### Root Directory (`/`)
- `index.php` - Main entry point (redirects to UI)
- `config.php` - Database configuration
- `header.php` - Shared header component
- `footer.php` - Shared footer component
- `shared_utils.php` - Shared utility functions
- `system_status.php` - System status and diagnostics
- `execute_db_setup.php` - Database setup script
- `database_setup.sql` - SQL schema file
- `logo1.png` - Main logo image

### User Interface (`/UI/`)
All user-facing pages are organized here:
- `UI1_index.php` - Homepage
- `UI2_menu.php` - Product menu
- `UI3_product_details.php` - Product details
- `UI4_view_cart.php` - Shopping cart
- `UI5_user_login.php` - User login
- `UI6_register.php` - User registration
- `UI7_user_profile.php` - User profile
- `UI8_guest_checkout.php` - Guest checkout
- `UI9_about_us.php` - About page
- `UI10_contact_us.php` - Contact page
- `UI11_rating_comments.php` - Reviews page
- `user_logout.php` - Logout handler

### Backend Functions (`/functions/`)
- `functions.php` - Core functions (products, users, cart)
- `function1_remove_from_cart.php` - Remove cart items
- `function2_add_to_cart.php` - Add cart items
- `function3_update_cart.php` - Update cart quantities
- `function4_clear_cart.php` - Clear cart
- `function5_reviews_functions.php` - Review system functions

### Admin Panel (`/admin/`)
- `admin_dashboard.php` - Admin dashboard
- `admin_login.php` - Admin login
- `admin_logout.php` - Admin logout
- `admin_products.php` - Product management
- `admin_users.php` - User management
- `admin_admins.php` - Admin management

### Styles (`/css/`)
- `global.css` - Global styles
- `components.css` - Reusable components
- `header.css` - Header styles
- `menu.css` - Menu page styles
- `cart.css` - Cart styles
- `user-login.css` - Login/register styles
- `profile.css` - Profile styles
- `admin.css` - Admin panel styles
- Other page-specific CSS files

### Scripts (`/js/`)
- `global.js` - Global JavaScript functions

### Uploads (`/uploads/`)
- `images/` - Product images

## Key Features
- **Clean separation**: UI, functions, and admin are separated
- **Shared utilities**: Common functions in `shared_utils.php`
- **Responsive design**: Modern CSS with coffee shop theme
- **User system**: Registration, login, profiles
- **Shopping cart**: Add, update, remove items
- **Admin panel**: Product and user management
- **Review system**: Customer ratings and comments

## Getting Started
1. Download XAMPP Control Panel https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/xampp-windows-x64-8.2.12-0-VS16-installer.exe
2. Run Apache and MySQL
3. Access the project via `localhost/v8/`
4. Admin panel accessible via `localhost/v8/admin/admin_login.php`

## Navigation Flow
- **Main entry**: `index.php` → `UI/UI1_index.php`
- **All pages**: Accessible through UI directory
- **No duplicate files**: Clean, single-source structure
