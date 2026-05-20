# Laravel Setup Guide - KP PlanShop

## Installation Steps

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with:
- Database credentials
- App URL
- Mail configuration

### 3. Database Setup
```bash
# Create database
createdb kp_planshop

# Run migrations
php artisan migrate

# Optional: Seed dummy data
php artisan db:seed
```

### 4. Build Assets
```bash
npm run dev
```

### 5. Start Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Project Structure

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── ...
├── resources/
│   ├── views/
│   └── css/
├── routes/
│   └── web.php
├── storage/
├── vendor/
└── ...
```

## Available Routes

- `GET /` - Home page
- `GET /products/{id}` - Product details
- `GET /category/{id}` - Products by category
- `GET /search` - Search products
- `GET /cart` - Shopping cart
- `POST /cart/add/{product}` - Add to cart
- `GET /login` - Login page
- `GET /register` - Registration page
- `GET /orders` - User orders (authenticated)
- `GET /orders/{id}` - Order details (authenticated)

## Database Tables

- `admins` - Admin users
- `users` - Customer users
- `categories` - Product categories
- `products` - Products
- `orders` - Customer orders
- `order_items` - Order line items
- `payments` - Payment records
- `discounts` - Product discounts
- `customer_feedback` - Customer reviews

## Key Models

- `App\Models\User`
- `App\Models\Product`
- `App\Models\Order`
- `App\Models\Category`
- `App\Models\Payment`
- `App\Models\Discount`
- `App\Models\CustomerFeedback`

## Next Steps

1. Create Blade templates in `resources/views/`
2. Implement admin panel controllers
3. Add API routes for payment integration
4. Set up email notifications
5. Configure file storage for product images
