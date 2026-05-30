# KP PlanShop Frontend - Setup Guide

## Prerequisites

- Node.js 16+ (recommended 18+)
- npm 8+ or yarn
- Git

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/MENGHONG081/Kp_PlanShop.git
cd Kp_PlanShop/frontend
```

### 2. Install Dependencies

```bash
npm install
# or
yarn install
```

### 3. Environment Setup

Create `.env` file from `.env.example`:

```bash
cp .env.example .env
```

Update `.env` with your API URL:

```env
VITE_API_URL=http://localhost:8000/api
VITE_APP_NAME=KP PlanShop
VITE_APP_URL=http://localhost:5173
```

## Development

### Start Development Server

```bash
npm run dev
```

The application will be available at `http://localhost:5173`

### Type Checking

```bash
npm run type-check
```

### Linting

```bash
npm run lint
```

## Building

### Production Build

```bash
npm run build
```

Output will be in the `dist` directory.

### Preview Build

```bash
npm run preview
```

## Project Structure

```
frontend/
├── src/
│   ├── components/        # Reusable React components
│   ├── pages/             # Page components
│   ├── store/             # Zustand state management
│   ├── hooks/             # Custom React hooks
│   ├── utils/             # Utility functions (API, validation)
│   ├── types/             # TypeScript type definitions
│   ├── App.tsx            # Main App component
│   ├── main.tsx           # Entry point
│   └── index.css          # Global styles
├── index.html             # HTML template
├── vite.config.ts         # Vite configuration
├── tsconfig.json          # TypeScript configuration
├── tailwind.config.js     # Tailwind CSS configuration
├── postcss.config.js      # PostCSS configuration
└── package.json           # Dependencies
```

## Key Technologies

- **React 18** - UI library
- **TypeScript** - Type safety
- **Vite** - Fast build tool
- **React Router** - Client-side routing
- **Zustand** - State management
- **Axios** - HTTP client
- **Tailwind CSS** - Utility-first CSS
- **qrcode.react** - QR code generation

## Available Routes

### Public Routes
- `/` - Redirects to login or dashboard
- `/login` - Login page
- `/signup` - Sign up page

### Protected Routes
- `/dashboard` - User dashboard (requires authentication)
- `/payment/:orderId` - Payment page for specific order

## API Integration

API calls are made using `axios` with automatic token injection:

```typescript
import apiClient from '@utils/api';

// Automatically includes Authorization header
const response = await apiClient.get('/endpoint');
```

## State Management

Using Zustand for simple, scalable state management:

```typescript
import { useAuthStore } from '@/store/authStore';

const { user, token, login, logout } = useAuthStore();
```

## Validation

Utility functions for form validation:

```typescript
import { validateEmail, validatePassword, validatePhone } from '@utils/validation';

if (!validateEmail(email)) {
  // Show error
}
```

## Deployment

### Vercel (Recommended)

1. Push code to GitHub
2. Create project on [Vercel](https://vercel.com)
3. Connect your GitHub repository
4. Set environment variables in Vercel dashboard
5. Deploy automatically

### Docker

See `../Dockerfile` for containerized deployment.

### Environment Variables

Required for production:

```env
VITE_API_URL=https://your-api.com/api
VITE_APP_NAME=KP PlanShop
VITE_APP_URL=https://your-domain.com
```

## Troubleshooting

### Port Already in Use

```bash
# Use a different port
npm run dev -- --port 5174
```

### Clear Dependencies

```bash
rm -rf node_modules package-lock.json
npm install
```

### Type Errors

```bash
npm run type-check
```

## Contributing

1. Create a feature branch (`git checkout -b feature/amazing-feature`)
2. Commit changes (`git commit -m 'Add amazing feature'`)
3. Push to branch (`git push origin feature/amazing-feature`)
4. Open a Pull Request

## License

MIT License - see LICENSE file for details

## Support

For issues and questions, please open an issue on GitHub.
