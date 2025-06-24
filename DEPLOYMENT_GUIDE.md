# BeanMarket - Quick Deployment Guide

## 🚀 QUICK START (5 Minutes)

### Prerequisites

- Web server with PHP 7.4+ (XAMPP, WAMP, or LAMP)
- MySQL database
- Modern web browser

### Step 1: Setup Web Server

1. **XAMPP Users**:

   - Copy entire project to `C:\xampp\htdocs\beanmarket\`
   - Start Apache and MySQL in XAMPP Control Panel

2. **Other Servers**:
   - Copy files to your web server's document root
   - Ensure PHP and MySQL services are running

### Step 2: Database Setup (Choose One)

#### Option A: Automatic Setup (Recommended)

1. Navigate to `http://localhost/beanmarket/setup_database.php`
2. Click "Setup Database" button
3. Database will be created automatically with sample data

#### Option B: Manual Setup

1. Open phpMyAdmin (`http://localhost/phpmyadmin`)
2. Create new database named `beans_cafe`
3. Import `beans_cafe.sql` file
4. Update database credentials in `config.php` if needed

### Step 3: Test the Website

1. **Customer Site**: `http://localhost/beanmarket/`
2. **Admin Panel**: `http://localhost/beanmarket/admin/login.php`
   - Username: `admin`
   - Password: `admin123`

## 🎯 TESTING WALKTHROUGH

### Customer Experience Test

1. **Home Page**: Check multimedia, navigation, hero section
2. **Menu**: Browse products, test category filtering
3. **Add to Cart**: Select product, customize options, add to cart
4. **View Cart**: Check cart contents, update quantities
5. **Checkout**: Test both user login and guest checkout
6. **Contact**: Submit contact form, check map display
7. **Registration**: Create new user account
8. **Login**: Test user authentication
9. **Dashboard**: Check user profile and order history
10. **Reviews**: Submit rating and comment

### Admin Panel Test

1. **Login**: Access admin dashboard with credentials above
2. **Dashboard**: Check statistics and overview
3. **Manage Staff**: Add, edit, delete staff members
4. **Manage Members**: View and manage customer accounts
5. **Manage Orders**: Process and track orders
6. **Sales Report**: View analytics tables
7. **Manage Products**: Add, edit, delete products
8. **Manage Categories**: Organize product categories

## 📋 VERIFICATION CHECKLIST

### ✅ Technical Requirements

- [ ] External CSS files loading (`style.css`, `home_page.css`)
- [ ] Internal CSS visible in page source (`<style>` tags)
- [ ] Inline CSS applied (`style=""` attributes)
- [ ] Lists displaying properly (navigation menus, product lists)
- [ ] Tables showing correctly (sales reports, admin panels)
- [ ] Multimedia working (audio controls, video player)
- [ ] Forms functional (login, registration, contact)
- [ ] JavaScript interactive (validation, dynamic content)

### ✅ Core Features

- [ ] Home page loads with all content
- [ ] Menu displays products with categories
- [ ] Shopping cart fully functional
- [ ] User registration and login working
- [ ] Admin panel accessible and operational
- [ ] Database connection established
- [ ] Session management working
- [ ] Navigation links all functional

### ✅ Content Verification

- [ ] Coffee menu complete with prices
- [ ] Product details pages accessible
- [ ] Business information accurate
- [ ] Contact form submitting
- [ ] About page informative
- [ ] Reviews system operational

## 🔧 TROUBLESHOOTING

### Common Issues & Solutions

#### Database Connection Failed

```
Error: Could not connect to database
Solution:
1. Check XAMPP MySQL is running
2. Verify config.php credentials
3. Ensure beans_cafe database exists
```

#### Page Not Found (404)

```
Error: Page cannot be displayed
Solution:
1. Check file paths are correct
2. Ensure .php extensions in URLs
3. Verify web server is running
```

#### Add to Cart Not Working

```
Error: Items not adding to cart
Solution:
1. Check session_start() is called
2. Verify functions.php is accessible
3. Test with browser developer tools
```

#### Admin Login Failed

```
Error: Invalid credentials
Solution:
1. Use default: admin / admin123
2. Check database has admin user
3. Verify admin table exists
```

### File Permissions

Ensure these files are writable by web server:

- `uploads/` directory (for image uploads)
- Session storage directory

## 📁 PROJECT STRUCTURE OVERVIEW

```
beanmarket/
├── index.php              # Home page
├── menu.php               # Product catalog
├── view_cart.php          # Shopping cart
├── user_login.php         # Customer login
├── register.php           # Customer registration
├── about_us.php           # About page
├── contact_us.php         # Contact page
├── config.php             # Database config
├── functions.php          # PHP utilities
├── style.css              # Main styles
├── home_page.css          # Home page styles
├── beans_cafe.sql         # Database schema
├── setup_database.php     # Auto database setup
├── admin/                 # Admin panel
│   ├── dashboard.php      # Admin overview
│   ├── login.php          # Admin login
│   ├── manage_*.php       # Management pages
└── uploads/               # File uploads
    └── images/            # Product images
```

## 🎨 CUSTOMIZATION GUIDE

### Changing Colors

Edit CSS variables in `style.css`:

```css
:root {
  --primary-color: #6f4e37; /* Coffee brown */
  --secondary-color: #f5f5dc; /* Cream */
  --accent-color: #ffd700; /* Gold */
}
```

### Adding Products

1. Access admin panel
2. Go to "Manage Products"
3. Click "Add New Product"
4. Fill form and upload image

### Modifying Business Info

Edit contact details in:

- `contact_us.php` (contact page)
- `about_us.php` (about page)
- `config.php` (database settings)

## 📞 SUPPORT & DOCUMENTATION

### Additional Resources

- `final_test_checklist.md` - Complete testing guide
- `project_completion_summary.md` - Development summary
- `database_setup_instructions.md` - Detailed DB setup
- `FIXES_COMPLETED.md` - Bug fixes log

### Feature Documentation

Each PHP file includes inline comments explaining functionality. Key files:

- `functions.php` - All utility functions documented
- `add_to_cart.php` - Cart management logic
- `admin/dashboard.php` - Admin functionality

## 🎯 SUCCESS CONFIRMATION

After setup, verify these key features work:

1. **Navigation**: All menu links functional
2. **Shopping**: Add products to cart successfully
3. **Checkout**: Complete purchase process
4. **Admin**: Access and manage all admin features
5. **Database**: Data persists across sessions
6. **Responsive**: Site works on mobile devices

## 🎉 DEPLOYMENT COMPLETE

Your BeanMarket coffee shop website is now ready for use! The site includes:

- ✅ Complete e-commerce functionality
- ✅ Professional admin management system
- ✅ Mobile-responsive design
- ✅ Comprehensive product catalog
- ✅ User registration and authentication
- ✅ Modern, attractive interface

**Next Steps**: Customize content, add real business information, and start serving customers!

---

**Need Help?** Check the troubleshooting section above or review the comprehensive documentation files included with the project.
