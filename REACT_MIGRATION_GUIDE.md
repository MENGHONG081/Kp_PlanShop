# React Migration Guide - KP PlanShop

## Overview

This guide provides a complete migration from PHP/Laravel backend to a React frontend with API integration.

## Project Structure

```
Kp_PlanShop/
├── app/                    # Laravel backend (PHP)
├── frontend/               # New React frontend
├── routes/                 # API routes
├── database/               # Database files
└── ...
```

## Frontend Architecture

### Technology Stack

- **React 18** - UI library
- **TypeScript** - Type safety
- **Vite** - Build tool (fast development)
- **React Router v6** - Routing
- **Zustand** - State management
- **Axios** - HTTP client
- **Tailwind CSS** - Styling
- **QR Code** - Payment QR codes

## Component Overview

### Authentication Components

#### `LoginForm.tsx`
- Email/password validation
- Error handling
- Token storage
- Redirect to dashboard on success

#### `SignupForm.tsx`
- Full name, email, phone, password validation
- Password strength indicator
- Email validation
- Phone number validation
- Automatic login after signup

### Payment Components

#### `PaymentForm.tsx`
- KHQR QR code generation
- Real-time payment status polling
- Order information display
- Payment success notification

### Page Components

#### `Dashboard.tsx`
- User account information
- Orders list/table
- Order status filtering
- Links to individual payment pages

#### `Payment.tsx`
- Order details display
- Payment form integration
- Transaction information

## API Integration

### Endpoints Used

```
POST   /api/login                    # User login
POST   /api/register                 # User registration
POST   /api/logout                   # User logout
GET    /api/orders                   # List user orders
GET    /api/orders/:id               # Get order details
POST   /api/payment/generate-khqr    # Generate payment QR
GET    /api/payment/status/:orderId  # Check payment status
```

### Request/Response Examples

#### Login Request
```json
POST /api/login
{
  "email": "user@example.com",
  "password": "password123"
}

Response:
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "fullname": "John Doe",
      "email": "user@example.com",
      "phone": "+855..."
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer"
  }
}
```

#### Payment QR Request
```json
POST /api/payment/generate-khqr
{
  "order_id": "ORDER123",
  "amount": 50.00
}

Response:
{
  "success": true,
  "data": {
    "qr_code": "00020126...",
    "order_id": "ORDER123",
    "amount": 50.00
  }
}
```

## State Management (Zustand)

### Authentication Store

```typescript
const { user, token, login, register, logout } = useAuthStore();
```

## Routing

```typescript
Public Routes:
  /login        - Login page
  /signup       - Sign up page
  /             - Redirects to dashboard or login

Protected Routes (require authentication):
  /dashboard    - User dashboard
  /payment/:id  - Payment page
```

## Form Validation

Utility functions in `src/utils/validation.ts`:

- `validateEmail(email)` - Email format validation
- `validatePassword(password)` - Password strength validation
- `validatePhone(phone)` - Phone format validation
- `validateName(name)` - Name length validation

## Environment Variables

```env
VITE_API_URL=http://localhost:8000/api
VITE_APP_NAME=KP PlanShop
VITE_APP_URL=http://localhost:5173
```

## Development Workflow

### 1. Start Development Server

```bash
cd frontend
npm run dev
```

### 2. Start Backend Server

```bash
# In root directory
php artisan serve
```

### 3. Access Application

- Frontend: http://localhost:5173
- Backend API: http://localhost:8000/api

## Building for Production

```bash
cd frontend
npm run build
```

Output in `dist/` directory ready for deployment.

## Deployment Options

### Option 1: Vercel (Recommended)

```bash
# Install Vercel CLI
npm i -g vercel

# Deploy
vercel
```

### Option 2: Docker

```bash
# Build
docker build -t planshop-frontend .

# Run
docker run -p 5173:5173 planshop-frontend
```

### Option 3: Traditional Hosting

```bash
# Build
npm run build

# Upload dist/ folder to your hosting
```

## Security Considerations

1. **Token Storage**: Tokens stored in localStorage
   - Consider using secure HTTP-only cookies for better security
   
2. **HTTPS Required**: Always use HTTPS in production

3. **CORS**: Backend must allow frontend origin

4. **Input Validation**: All inputs validated before sending to API

5. **Protected Routes**: Routes require authentication token

## Common Tasks

### Adding a New Page

1. Create component in `src/pages/`
2. Add route in `App.tsx`
3. Wrap with `<ProtectedRoute>` if authenticated

### Adding API Call

```typescript
import apiClient from '@utils/api';

const response = await apiClient.get('/endpoint');
```

### Adding Form Validation

```typescript
import { validateEmail } from '@utils/validation';

if (!validateEmail(email)) {
  setErrors({ email: 'Invalid email' });
}
```

## Troubleshooting

### CORS Issues

Ensure Laravel backend has CORS middleware enabled:

```php
// config/cors.php
'allowed_origins' => ['http://localhost:5173'],
```

### Token Expiration

If token expires, user is redirected to login automatically via axios interceptor.

### API Connection Issues

Check:
1. Backend server is running
2. API URL is correct in `.env`
3. CORS is enabled
4. No firewall blocking requests

## Performance Optimization

1. **Code Splitting**: React Router automatically code-splits routes
2. **Lazy Loading**: Use React.lazy() for large components
3. **Caching**: API responses can be cached with axios interceptors
4. **Tree Shaking**: Unused code is automatically removed in build

## Testing

### Manual Testing Checklist

- [ ] Login with valid credentials
- [ ] Signup with new account
- [ ] View dashboard and orders
- [ ] Generate payment QR code
- [ ] Simulate payment completion
- [ ] Logout and verify redirect to login
- [ ] Test responsive design on mobile
- [ ] Test on different browsers

## Next Steps

1. Setup development environment
2. Review component structure
3. Test authentication flow
4. Test payment flow
5. Deploy to production

## Support

For issues:
1. Check error messages in browser console
2. Check network requests in DevTools
3. Review API responses
4. Open GitHub issue with details

## Resources

- [React Documentation](https://react.dev)
- [Vite Documentation](https://vitejs.dev)
- [Tailwind CSS](https://tailwindcss.com)
- [React Router](https://reactrouter.com)
- [Zustand](https://github.com/pmndrs/zustand)
