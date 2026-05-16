# Eclothing - E-Commerce Platform

A full-featured e-commerce web application built with Laravel, featuring an Amazon/Daraz-inspired UI design.

## Features

### Customer Features
- **Product Browsing** — Search, filter, and view products with detailed pages
- **Shopping Cart** — Add/remove products, update quantities
- **Checkout** — Cash on Delivery (COD) and EMI payment options
- **User Dashboard** — View orders, manage profile, track order status
- **Order History** — Detailed order tracking with status updates
- **Responsive Design** — Works on desktop, tablet, and mobile

### Admin Panel
- **Dashboard** — Sales overview, recent orders, quick stats
- **Product Management** — Create, edit, delete products with images and discounts
- **Order Management** — View, update order status (pending, processing, shipped, delivered, cancelled)
- **User Management** — View and manage registered users
- **Page Management** — Create/edit static pages (About Us, Contact, etc.)
- **Settings** — Site name, logo, favicon, contact info, social links, payment methods

## Tech Stack

- **Backend** — Laravel 12 (PHP)
- **Frontend** — Blade Templates, Custom CSS (Amazon/Daraz style)
- **Database** — MySQL
- **Icons** — Font Awesome 6
- **Fonts** — Inter, Roboto (Google Fonts)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yasirraheel/Eclothing.git
   cd Eclothing
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Set up database**
   - Create a MySQL database named `eclothing`
   - Update `.env` with your database credentials:
     ```
     DB_DATABASE=eclothing
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Create storage link**
   ```bash
   php artisan storage:link
   ```

7. **Run the application**
   ```bash
   php artisan serve
   ```
   Visit `http://127.0.0.1:8000`

## Project Structure

```
├── app/
│   ├── Http/Controllers/      # Admin & Frontend controllers
│   ├── Models/                 # Eloquent models (Product, Order, User, etc.)
│   └── Mail/                   # Email templates (OTP)
├── resources/views/
│   ├── admin/                  # Admin panel views
│   ├── auth/                   # Login, Register, Verify pages
│   ├── components/             # Shared Blade components (header, top-bar, sidebar)
│   ├── orders/                 # User order pages
│   ├── profile/                # User profile edit
│   └── *.blade.php             # Frontend pages (welcome, product, cart, checkout)
├── public/
│   ├── css/style.css           # Frontend styles (Amazon/Daraz UI)
│   ├── css/admin.css           # Admin panel styles
│   └── images/                 # Static images
├── routes/web.php              # All application routes
└── database/migrations/        # Database schema
```

## Screenshots

- Homepage with hero slider and product grid
- Admin dashboard with order management
- Product detail page with EMI options
- User dashboard with order history

## Author

**Yasir Raheel** — [GitHub](https://github.com/yasirraheel)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
