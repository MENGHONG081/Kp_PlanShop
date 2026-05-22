# React Migration Guide for KP PlanShop

## Overview
This guide documents the migration of KP PlanShop from a PHP/Laravel monolithic application to a modern React-based frontend with a REST API backend.

## Project Structure

```
Kp_PlanShop/
├── backend/              # Laravel API (existing app)
│   ├── app/
│   ├── config/
│   ├── routes/api.php    # ← Define all API endpoints here
│   ├── composer.json
│   └── ...
├── frontend/             # New React application
│   ├── src/
│   ├── package.json
│   ├── vite.config.js
│   └── ...
└── README.md
```

## Phase 1: Backend Setup (Laravel API)

### 1. Update CORS Configuration

Edit `config/cors.php`:
```php
'allowed_origins' => ['http://localhost:3000', 'https://yourdomain.com'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

### 2. Setup API Routes

Update `routes/api.php` with endpoints:
```php
Route::middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    // Add more endpoints as needed
});
```

### 3. Update Controllers

Ensure controllers return JSON responses:
```php
return response()->json([
    'success' => true,
    'data' => $data,
    'message' => 'Success message'
]);
```

## Phase 2: Frontend Setup (React)

### 1. Development Server

```bash
cd frontend
npm install
npm run dev
```

### 2. Create `.env` file

```
VITE_API_URL=http://localhost:8000/api
VITE_APP_NAME=KP PlanShop
```

## Phase 3: Component Migration

### Blade Templates → React Components

1. Identify all Blade templates in `resources/views/`
2. Convert to React components in `frontend/src/pages/` and `frontend/src/components/`
3. Replace server-side rendering with client-side routing

### Example Migration:

**Laravel Blade (Old):**
```php
// resources/views/products/index.blade.php
@foreach($products as $product)
    <div class="product">{{ $product->name }}</div>
@endforeach
```

**React (New):**
```jsx
// frontend/src/pages/Products.jsx
function Products() {
  const [products, setProducts] = useState([])
  
  useEffect(() => {
    api.get('/products').then(res => setProducts(res.data))
  }, [])
  
  return (
    <div>
      {products.map(product => (
        <div key={product.id} className="product">{product.name}</div>
      ))}
    </div>
  )
}
```

## Phase 4: Running Both Servers

### Terminal 1 - Laravel Backend
```bash
cd backend
php artisan serve
# Runs on http://localhost:8000
```

### Terminal 2 - React Frontend
```bash
cd frontend
npm run dev
# Runs on http://localhost:3000
```

## API Integration Checklist

- [ ] Update CORS configuration in Laravel
- [ ] Convert all views to JSON API endpoints
- [ ] Create API service layer in React (`src/services/api.js`)
- [ ] Implement authentication with JWT/Bearer tokens
- [ ] Create custom hooks for API calls (`useAuth`, `useProducts`, etc.)
- [ ] Implement error handling and loading states
- [ ] Add request/response interceptors
- [ ] Test all API endpoints from React frontend

## Database

The existing PostgreSQL database (`config/database.php`) remains unchanged.

**Connection Details:**
- Host: `localhost`
- Port: `5432`
- Database: `kp_planshop`
- User: `postgres`

## Deployment

### Frontend (Vercel/Netlify)
```bash
npm run build
# Deploy 'dist' folder
```

### Backend (Railway/Heroku)
```bash
composer install
php artisan migrate
php artisan serve
```

## Common Issues & Solutions

### CORS Errors
- Ensure `config/cors.php` includes your frontend URL
- Check that API requests include correct headers

### 401 Unauthorized
- Verify token is stored in `localStorage`
- Check token expiration
- Ensure `Authorization` header is sent

### API Returns HTML Instead of JSON
- Verify routes are in `routes/api.php` (not `routes/web.php`)
- Check controllers return `response()->json()`

## Resources

- [React Documentation](https://react.dev)
- [Vite Documentation](https://vitejs.dev)
- [Laravel API Documentation](https://laravel.com/docs/api-resources)
- [React Router v6](https://reactrouter.com)

## Next Steps

1. Review this branch: `react-migration`
2. Install frontend dependencies: `npm install`
3. Configure `.env` file
4. Start both servers
5. Begin migrating pages and components
6. Test API integration thoroughly
