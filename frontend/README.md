# KP PlanShop - React Frontend

Modern React frontend for KP PlanShop application.

## Setup

1. Install dependencies:
```bash
cd frontend
npm install
```

2. Create `.env` file:
```bash
cp .env.example .env
```

3. Start development server:
```bash
npm run dev
```

The app will run on `http://localhost:3000`

## Build

```bash
npm run build
```

## Project Structure

```
src/
├── components/    # Reusable components
├── pages/         # Page components
├── services/      # API services
├── hooks/         # Custom React hooks
├── styles/        # CSS files
├── App.jsx
└── main.jsx
```

## API Integration

The frontend communicates with the Laravel backend API at:
`http://localhost:8000/api`

Update `VITE_API_URL` in `.env` to change the API endpoint.
