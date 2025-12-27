# 🛒 E-commerce Shopping Cart System

A modern, full-featured e-commerce shopping cart application built with Laravel 11, Livewire, and Tailwind CSS. This project demonstrates clean code practices, Laravel best practices, and real-world e-commerce functionality including cart management, order processing, automated notifications, and scheduled reporting.

## ✨ Features

### Core Functionality
- 🔐 **User Authentication** - Laravel Fortify with email verification
- 🛍️ **Product Browsing** - Responsive product grid with stock information
- 🛒 **Shopping Cart** - Add, update, and remove items with real-time updates
- 💳 **Checkout Process** - Complete order placement with inventory management
- 📦 **Order History** - View past orders with detailed breakdowns
- 📊 **Dashboard Widgets** - Inventory statistics and low stock monitoring

### Advanced Features
- 📧 **Low Stock Notifications** - Automated email alerts using Laravel Jobs/Queues
- 📈 **Daily Sales Reports** - Scheduled email reports using Laravel Scheduler
- 🎨 **Dark Mode** - Full dark mode support across the application
- ♿ **Responsive Design** - Mobile-first approach with Tailwind CSS
- ⚡ **Real-time UI** - Livewire for seamless user experience
- 🔄 **Database Transactions** - Ensures data consistency during checkout

## 🛠 Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Livewire 3
- **Styling:** Tailwind CSS v4
- **Database:** MySQL
- **Queue Driver:** Database
- **Mail:** Mailtrap (for testing)
- **UI Components:** Flux UI
- **Authentication:** Laravel Fortify

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for Vite/Tailwind compilation)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd shopping-cart
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy .env.example to .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shopping_cart
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Configure Mail (Mailtrap)
Update `.env` with your Mailtrap credentials:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@example.com"
```

### 6. Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

**Seeded Users:**
- admin@example.com / password
- john@example.com / password
- jane@example.com / password
- mike@example.com / password

**Seeded Products:** 19 gaming peripherals with varying stock levels

### 7. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Start the Application
```bash
php artisan serve
```

Visit: http://localhost:8000

## ⚙️ Queue & Scheduler Setup

### Running the Queue Worker

The application uses Laravel Queues for background job processing (Low Stock Notifications). To process queued jobs:

```bash
# For development (processes jobs and stops when empty)
php artisan queue:work --stop-when-empty

# For production (runs continuously)
php artisan queue:work
```

**Note:** Without the queue worker running, low stock notification emails won't be sent (jobs will queue but not process).

### Running the Scheduler

The application uses Laravel Scheduler for automated daily sales reports.

**For Development/Testing:**
```bash
# Manually trigger the daily sales report
php artisan report:daily-sales

# Run scheduler manually (simulates cron)
php artisan schedule:run
```

**For Production:**
Add this cron entry to your server:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

The daily sales report is scheduled to run every evening at 8:00 PM.

## 🧪 Testing the Application

### Testing Low Stock Notification

1. Start the queue worker:
   ```bash
   php artisan queue:work
   ```

2. Login to the application

3. Add products to cart and checkout (ensure stock drops to ≤5)

4. Check your Mailtrap inbox for the low stock notification email

**Recommended Test Product:**
- SteelSeries Apex Pro TKL (Stock: 6) - Buy 2+ to trigger notification

### Testing Daily Sales Report

```bash
# Run the command manually
php artisan report:daily-sales
```

Check your Mailtrap inbox for the daily sales report email.

### Testing Order History

1. Place some orders through the checkout process
2. Navigate to "Orders" in the sidebar
3. View your complete order history with item details

## 📁 Project Structure

```
app/
├── Console/Commands/
│   └── SendDailySalesReport.php    # Daily sales report command
├── Jobs/
│   └── SendLowStockNotification.php # Low stock notification job
├── Livewire/
│   ├── Cart.php                      # Shopping cart component
│   ├── OrderHistory.php              # Order history component
│   ├── Products.php                  # Product listing component
│   └── ProductShow.php               # Single product component
├── Mail/
│   ├── DailySalesReport.php          # Sales report email
│   └── LowStockNotification.php      # Low stock email
└── Models/
    ├── Cart.php
    ├── CartItem.php
    ├── Order.php
    ├── OrderItem.php
    └── Product.php

resources/views/
├── livewire/
│   ├── cart.blade.php
│   ├── order-history.blade.php
│   ├── products.blade.php
│   └── product-show.blade.php
└── emails/
    ├── daily-sales-report.blade.php
    └── low-stock.blade.php
```

## 🎯 Key Features Explained

### 1. Cart Management
- Cart data is stored in the database (not session/localStorage)
- Each cart is associated with the authenticated user
- Real-time quantity updates with stock validation
- Automatic stock checking to prevent overselling

### 2. Checkout Process
The checkout process implements several important features:
- **Database Transactions** - Ensures data consistency
- **Stock Validation** - Prevents checkout if insufficient stock
- **Automatic Stock Updates** - Decrements product stock quantities
- **Order Creation** - Creates Order and OrderItem records
- **Price Preservation** - Stores price at time of purchase in OrderItems
- **Cart Clearing** - Automatically clears cart after successful checkout
- **Low Stock Trigger** - Dispatches notification job if stock ≤ 5

### 3. Low Stock Notification System
**Implementation:** `app/Jobs/SendLowStockNotification.php`

When a product's stock drops to 5 or below during checkout:
1. A Job is dispatched to the queue
2. The queue worker processes the job
3. An email is sent to the admin with product details
4. Email includes: product name, current stock, and price

**Queue Configuration:**
- Driver: Database (jobs stored in `jobs` table)
- Requires `php artisan queue:work` to process

### 4. Daily Sales Report
**Implementation:** `app/Console/Commands/SendDailySalesReport.php`

Scheduled to run daily at 8:00 PM:
1. Fetches all orders placed today
2. Calculates total revenue and items sold
3. Sends comprehensive email report to admin
4. Email includes: order summaries, customer names, and totals

**Scheduler Configuration:**
- Defined in `bootstrap/app.php`
- Uses Laravel's task scheduler
- Can be tested manually with `php artisan report:daily-sales`

### 5. Order History
- Displays all orders for the authenticated user
- Shows order details: number, date, total, status
- Lists all items in each order with quantities and prices
- Includes pagination for performance
- Beautiful empty state when no orders exist

## 🎨 UI/UX Features

- **Dark Mode** - Complete dark mode support with smooth transitions
- **Responsive Design** - Mobile-first approach, works on all devices
- **Loading States** - Visual feedback during async operations
- **Flash Messages** - Success/error notifications for user actions
- **Empty States** - Helpful messages and CTAs when data is empty
- **Stock Badges** - Visual indicators for low stock items
- **Status Badges** - Color-coded order status indicators

## 📝 Laravel Best Practices Implemented

✅ **Eloquent Relationships** - Proper use of belongsTo, hasMany relationships
✅ **Eager Loading** - Prevents N+1 query problems
✅ **Database Transactions** - Ensures data integrity
✅ **Mass Assignment Protection** - Uses $fillable arrays
✅ **Route Organization** - Grouped routes with middleware
✅ **Livewire Components** - Clean, reusable components
✅ **Job Queues** - Background processing for emails
✅ **Task Scheduling** - Automated daily tasks
✅ **Migrations & Seeders** - Reproducible database setup
✅ **Environment Configuration** - Sensitive data in .env
✅ **Blueprint** - Used for rapid model generation

## 🔐 Security Features

- CSRF Protection (Laravel default)
- SQL Injection Prevention (Eloquent ORM)
- XSS Protection (Blade templating)
- Authentication required for all cart/order operations
- Stock validation prevents overselling
- Database transactions prevent data inconsistencies

## 📸 Screenshots

*Screenshots showcase:*
- Product listing page (Shop)
- Single product page
- Shopping cart with items
- Checkout process
- Order history
- Dashboard widgets
- Low stock email (Mailtrap)
- Daily sales report email (Mailtrap)

## ⏱️ Development Time

**Approximate time:** 4-5 hours

**Breakdown:**
- Initial setup & authentication: 30 min
- Product & Cart functionality: 1.5 hours
- Checkout & Order system: 1 hour
- Email notifications & Jobs: 1 hour
- Daily sales report & Scheduler: 45 min
- Order History page: 30 min
- UI polish & testing: 45 min

## 🚧 Future Enhancements

If this were a production application, potential improvements could include:

- **Testing** - Feature tests for cart operations and checkout flow
- **Payment Integration** - Stripe/PayPal for real payments
- **Product Categories** - Organize products by category
- **Search & Filters** - Advanced product filtering
- **Product Reviews** - Customer reviews and ratings
- **Wishlist** - Save items for later
- **Admin Panel** - Manage products, orders, and users
- **Inventory Alerts** - Multiple threshold levels for stock alerts
- **Order Tracking** - Shipping status updates
- **Coupon Codes** - Discount and promotion system

## 👨‍💻 Developer Notes

### Code Quality
- Followed Laravel naming conventions
- Used Livewire best practices
- Implemented proper error handling
- Added meaningful comments for complex logic
- Kept components focused and reusable

### Performance Considerations
- Eager loading to prevent N+1 queries
- Pagination for large datasets
- Database indexing on foreign keys
- Efficient query optimization

### Email Testing
- Mailtrap configured for safe email testing
- All emails include HTML templates
- Responsive email designs
- Clear call-to-actions in emails

## 📞 Support

For questions or issues, please contact:
- Email: milanoviclukaa23@gmail.com
- GitHub: @lucwi

---

**Built with ❤️ using Laravel, Livewire, and Tailwind CSS**

*This project was created as part of a technical assessment for Trustfactory.*
