# Deployment Guide - KP PlanShop

## Table of Contents

1. [Environment Setup](#environment-setup)
2. [Vercel Deployment](#vercel-deployment)
3. [Docker Deployment](#docker-deployment)
4. [Traditional Hosting](#traditional-hosting)
5. [Production Checklist](#production-checklist)
6. [Monitoring & Maintenance](#monitoring--maintenance)

## Environment Setup

### Backend Requirements

- PHP 8.1+
- Laravel 11
- MySQL 8.0+
- Composer

### Frontend Requirements

- Node.js 16+
- npm 8+

## Vercel Deployment

### Step 1: Prepare Repository

```bash
git add .
git commit -m "Prepare for production deployment"
git push origin main
```

### Step 2: Create Vercel Account

1. Go to https://vercel.com
2. Sign up with GitHub account
3. Authorize GitHub integration

### Step 3: Deploy Frontend

1. Click "New Project"
2. Select your repository
3. Set root directory to `frontend`
4. Configure environment variables:
   ```
   VITE_API_URL=https://your-api.com/api
   VITE_APP_NAME=KP PlanShop
   VITE_APP_URL=https://your-domain.vercel.app
   ```
5. Click "Deploy"

### Step 4: Configure Backend API

Update CORS in Laravel:

```php
// config/cors.php
'allowed_origins' => [
    'https://your-domain.vercel.app',
    'http://localhost:5173',
],
```

## Docker Deployment

### Step 1: Build Docker Image

```bash
docker build -t kp-planshop-frontend:latest ./frontend
```

### Step 2: Run Container

```bash
docker run -d \
  -p 5173:5173 \
  -e VITE_API_URL=https://your-api.com/api \
  --name planshop-frontend \
  kp-planshop-frontend:latest
```

### Step 3: Docker Compose (Full Stack)

```bash
docker-compose up -d
```

This will start:
- Frontend (port 5173)
- Backend (port 8000)
- MySQL (port 3306)

## Traditional Hosting

### Step 1: Build Frontend

```bash
cd frontend
npm run build
```

### Step 2: Upload to Hosting

```bash
# Using FTP/SFTP
sftp user@host:/public_html
put -r dist/*
```

### Step 3: Configure Web Server

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;

    root /var/www/public_html/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

#### Apache

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/public_html/dist

    <Directory /var/www/public_html/dist>
        RewriteEngine On
        RewriteBase /
        RewriteRule ^index\.html$ - [L]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . /index.html [L]
    </Directory>
</VirtualHost>
```

## Production Checklist

### Frontend

- [ ] Environment variables configured
- [ ] API URL points to production backend
- [ ] HTTPS enabled
- [ ] CSP headers configured
- [ ] Error tracking setup (Sentry)
- [ ] Analytics setup (Google Analytics)
- [ ] Performance monitoring
- [ ] Image optimization
- [ ] Code minification/compression
- [ ] Service worker for offline support

### Backend

- [ ] HTTPS enabled
- [ ] CORS properly configured
- [ ] Database backups automated
- [ ] Environment variables set
- [ ] Rate limiting enabled
- [ ] Error logging configured
- [ ] CSRF protection enabled
- [ ] Security headers set
- [ ] Database transactions optimized
- [ ] API documentation updated

### Database

- [ ] Regular backups scheduled
- [ ] Backup testing automated
- [ ] Monitoring enabled
- [ ] Query optimization
- [ ] Index optimization
- [ ] Connection pooling configured
- [ ] Replication setup (optional)

### Monitoring

- [ ] Uptime monitoring
- [ ] Performance monitoring
- [ ] Error tracking
- [ ] Log aggregation
- [ ] Alert notifications
- [ ] Health check endpoints

## Monitoring & Maintenance

### Health Checks

```bash
# Frontend health check
curl https://your-domain.com/

# Backend health check
curl https://api.your-domain.com/api/health
```

### Database Backup

```bash
# Daily backup
0 2 * * * mysqldump -u root -p'password' planshop > /backup/planshop_$(date +\%Y\%m\%d).sql
```

### Log Monitoring

```bash
# Frontend errors
tail -f /var/log/frontend/error.log

# Backend errors
tail -f storage/logs/laravel.log
```

### Performance Tuning

1. **CDN Setup**: Use CloudFlare or similar for static assets
2. **Caching**: Enable Redis for session/cache
3. **Compression**: Enable gzip compression
4. **Database**: Add indexes on frequently queried columns

## SSL Certificate

### Using Let's Encrypt with Certbot

```bash
# Install certbot
sudo apt-get install certbot python3-certbot-nginx

# Generate certificate
sudo certbot certonly --nginx -d your-domain.com

# Auto-renewal
sudo certbot renew --dry-run
```

## Scaling

For high traffic:

1. **Load Balancer**: Setup load balancing across multiple servers
2. **Database Replication**: Setup master-slave replication
3. **Caching**: Implement Redis caching
4. **CDN**: Use CloudFlare or AWS CloudFront
5. **Microservices**: Consider breaking into microservices

## Troubleshooting

### High Memory Usage

```bash
# Check processes
ps aux | grep -E 'php|node|nginx'

# Kill high memory process
kill -9 <PID>
```

### Database Connection Issues

```bash
# Check MySQL status
mysqladmin -u root -p ping

# Check connection limit
mysql> SHOW VARIABLES LIKE 'max_connections';
```

### SSL Certificate Issues

```bash
# Check certificate validity
openssl x509 -in /path/to/cert.pem -text -noout
```

## Support

For deployment issues:
1. Check error logs
2. Review application health checks
3. Contact hosting provider support
4. Open GitHub issue with details
