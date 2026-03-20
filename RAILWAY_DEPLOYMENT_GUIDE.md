# Railway Deployment Guide for Kp_PlanShop

This guide provides step-by-step instructions to deploy your Kp_PlanShop PHP application, including its Nginx web server and PostgreSQL database, to Railway. Railway is a modern hosting platform that simplifies the deployment of applications and databases.

## 1. Prerequisites

Before you begin, ensure you have the following:

-   A **GitHub account** with your Kp_PlanShop project pushed to it.
-   A **Railway account** (sign up at [railway.app](https://railway.app/)).
-   **Railway CLI** installed locally (optional, but recommended for advanced usage).
-   Your **API keys and credentials** for Bakong, PayWay, Gemini, and Telegram bot.

## 2. Project Structure Changes

I have made the following changes to your project to facilitate Railway deployment:

-   **`Dockerfile`**: Configured to use `php:8.2-fpm-alpine` with Nginx, installing all necessary PHP extensions and system dependencies for your application and PostgreSQL.
-   **`nginx.conf`**: Global Nginx configuration.
-   **`default.conf`**: Nginx server block configuration to serve your PHP application from the `User_Page` directory and handle PHP requests via PHP-FPM. It also includes security directives to deny access to sensitive files like `.env`.
-   **`railway.json`**: This file defines how Railway should build and deploy your application, including environment variables and service linking.

## 3. Deployment Steps

Follow these steps to deploy your application to Railway:

### Step 3.1: Push Changes to GitHub

Ensure all the new configuration files (`Dockerfile`, `nginx.conf`, `default.conf`, `railway.json`) are committed and pushed to your GitHub repository. I have already done this for you in the previous steps.

### Step 3.2: Create a New Project on Railway

1.  Log in to your Railway account.
2.  Click on **"New Project"**.
3.  Select **"Deploy from GitHub Repo"**.
4.  Connect your GitHub account (if not already connected) and select the `MENGHONG081/Kp_PlanShop` repository.
5.  Choose the `main` branch for deployment.

### Step 3.3: Add PostgreSQL Database

Your application uses PostgreSQL. You need to add a PostgreSQL service to your Railway project:

1.  In your Railway project dashboard, click **"New"** -> **"Database"** -> **"PostgreSQL"**.
2.  Railway will automatically provision a PostgreSQL database and expose its connection details as environment variables (e.g., `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD`). These will be automatically linked to your application service via `railway.json`.

### Step 3.4: Configure Environment Variables

Your application relies on several environment variables for payment gateways, AI chatbot, and Telegram bot. These need to be set in Railway:

1.  In your application service settings on Railway, navigate to the **"Variables"** tab.
2.  Add the following variables, filling in your actual credentials. You can refer to the `.env.example` file in your repository for a complete list.

    | Variable Name         | Description                                                                 | Example Value                                        |
    | :-------------------- | :-------------------------------------------------------------------------- | :--------------------------------------------------- |
    | `DB_DRIVER`           | Database driver (should be `pgsql`)                                         | `pgsql`                                              |
    | `DB_HOST`             | PostgreSQL host (auto-filled by Railway from linked PostgreSQL service)     | `${{PostgreSQL.HOST}}`                               |
    | `DB_PORT`             | PostgreSQL port (auto-filled by Railway)                                    | `${{PostgreSQL.PORT}}`                               |
    | `DB_NAME`             | PostgreSQL database name (auto-filled by Railway)                           | `${{PostgreSQL.DATABASE}}`                           |
    | `DB_USER`             | PostgreSQL username (auto-filled by Railway)                                | `${{PostgreSQL.USERNAME}}`                           |
    | `DB_PASS`             | PostgreSQL password (auto-filled by Railway)                                | `${{PostgreSQL.PASSWORD}}`                           |
    | `BAKONG_TOKEN`        | Your Bakong API Token                                                       | `your_bakong_token_here`                             |
    | `BAKONG_ACCOUNT`      | Your Bakong Account ID                                                      | `your_bakong_account_id_here`                        |
    | `BAKONG_API_BASE`     | Bakong API Base URL                                                         | `https://api-bakong.nbc.gov.kh`                      |
    | `PAYWAY_MERCHANT_ID`  | Your ABA PayWay Merchant ID                                                 | `your_merchant_id_here`                              |
    | `PAYWAY_PUBLIC_KEY`   | Your ABA PayWay Public Key (for HMAC signature)                             | `your_public_key_here`                               |
    | `PAYWAY_PRIVATE_KEY`  | Your ABA PayWay Private Key (for RSA signature, if applicable)              | `your_private_key_here`                              |
    | `PAYWAY_RETURN_URL`   | URL for successful PayWay payments                                          | `https://your-railway-app.up.railway.app/User_Page/payment/success` |
    | `PAYWAY_CANCEL_URL`   | URL for cancelled PayWay payments                                           | `https://your-railway-app.up.railway.app/User_Page/payment/cancel`  |
    | `PAYWAY_ENV`          | PayWay environment (`sandbox` or `production`)                              | `production`                                         |
    | `GEMINI_API_KEY`      | Your Google Gemini API Key                                                  | `your_gemini_api_key_here`                           |
    | `TELEGRAM_BOT_TOKEN`  | Your Telegram Bot Token                                                     | `your_telegram_bot_token_here`                       |
    | `SITE_URL`            | The base URL of your deployed application on Railway                        | `https://your-railway-app.up.railway.app/User_Page`  |

    **Important**: For `PAYWAY_RETURN_URL`, `PAYWAY_CANCEL_URL`, and `SITE_URL`, replace `https://your-railway-app.up.railway.app` with the actual domain provided by Railway for your service.

### Step 3.5: Deploy the Application

1.  Once the environment variables are set, Railway will automatically start building and deploying your application using the `Dockerfile` and `railway.json`.
2.  Monitor the deployment logs in the Railway dashboard for any errors.
3.  After a successful deployment, Railway will provide you with a public URL for your application.

## 4. Post-Deployment Configuration

### 4.1: Database Initialization

If your database is empty, you will need to run the `database.sql` script to create the necessary tables. You can do this by:

1.  Connecting to your Railway PostgreSQL database using a client like `psql` or `DBeaver` with the credentials provided by Railway.
2.  Executing the SQL commands from `database.sql`.

### 4.2: Telegram Webhook

If you are using the Telegram bot (`bot.php`), you need to set up the webhook to point to your deployed application:

1.  Replace `https://your-railway-app.up.railway.app/User_Page/bot.php` with your actual deployed URL.
2.  Send a request to Telegram to set the webhook:
    ```
    https://api.telegram.org/bot<YOUR_TELEGRAM_BOT_TOKEN>/setWebhook?url=https://your-railway-app.up.railway.app/User_Page/bot.php
    ```
    Replace `<YOUR_TELEGRAM_BOT_TOKEN>` with your actual Telegram bot token and `https://your-railway-app.up.railway.app` with your Railway domain.

## 5. Troubleshooting

-   **Deployment Failed**: Check the build logs on Railway for specific error messages. Common issues include missing dependencies or incorrect `Dockerfile` syntax.
-   **Application Not Running**: Verify that your environment variables are correctly set and that the Nginx and PHP-FPM processes are starting as expected (check runtime logs).
-   **Database Connection Issues**: Ensure your `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASS` variables are correctly linked to your Railway PostgreSQL service.
-   **Payment Gateway Errors**: Double-check your PayWay and Bakong credentials in the environment variables. Ensure the return/cancel URLs are correctly configured with your Railway domain.
-   **AI Chatbot Not Responding**: Confirm that `GEMINI_API_KEY` is set and valid. Check the application logs for any errors from the Gemini API.

By following these steps, you should be able to successfully deploy your Kp_PlanShop project to Railway. If you encounter any issues, refer to the troubleshooting section or consult Railway's official documentation.
