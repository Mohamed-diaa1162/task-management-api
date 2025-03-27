# Installation Guide

This document provides detailed step-by-step instructions for setting up the Task Management API on your local development environment.

## Prerequisites

Before you begin, ensure you have the following software installed on your system:

- **PHP 8.1+** with required extensions (BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)
- **Composer** (dependency manager for PHP)
- **MySQL** 5.7+ / **PostgreSQL** 10+ / **SQLite** 3 (choose one)
- **Git** (version control)
- **Node.js & npm** (optional - for frontend assets)

## Step 1: Clone the Repository

```bash
git clone https://github.com/your-username/task-management-api.git
cd task-management-api
```

## Step 2: Install PHP Dependencies

```bash
composer install
```

This will install all required PHP packages listed in the `composer.json` file.

## Step 3: Environment Configuration

1. Create a copy of the environment example file:

   ```bash
   cp .env.example .env
   ```

2. Open the `.env` file in your favorite text editor and configure the following:

   - **Application settings**:
     ```
     APP_NAME="Task Management API"
     APP_ENV=local
     APP_DEBUG=true
     APP_URL=http://localhost:8000
     ```

   - **Database settings** (Choose ONE of the following options):

     - **Option A: MySQL**
       ```
       DB_CONNECTION=mysql
       DB_HOST=127.0.0.1
       DB_PORT=3306
       DB_DATABASE=task_management
       DB_USERNAME=your_mysql_username
       DB_PASSWORD=your_mysql_password
       ```

     - **Option B: PostgreSQL**
       ```
       DB_CONNECTION=pgsql
       DB_HOST=127.0.0.1
       DB_PORT=5432
       DB_DATABASE=task_management
       DB_USERNAME=your_postgresql_username
       DB_PASSWORD=your_postgresql_password
       ```

     - **Option C: SQLite** (simplest for development)
       ```
       DB_CONNECTION=sqlite
       ```
       Then create the database file:
       ```bash
       touch database/database.sqlite
       ```

   - **Mail settings** (for task notifications):
     ```
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.mailtrap.io  # Use mailtrap.io for testing or your SMTP server
     MAIL_PORT=2525
     MAIL_USERNAME=your_mailtrap_username
     MAIL_PASSWORD=your_mailtrap_password
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS="noreply@taskmanagement.com"
     MAIL_FROM_NAME="${APP_NAME}"
     ```

   - **Queue settings** (for background processing):
     ```
     QUEUE_CONNECTION=database
     ```

## Step 4: Generate Application Keys

1. Generate the Laravel application key:

   ```bash
   php artisan key:generate
   ```

2. Generate JWT secret for authentication:

   ```bash
   php artisan jwt:secret
   ```

## Step 5: Database Setup

1. Run the database migrations to create the required tables:

   ```bash
   php artisan migrate
   ```

2. (Optional) Seed the database with sample data:

   ```bash
   php artisan db:seed
   ```

## Step 6: Configure Queue Worker (Optional but Recommended)

For email notifications and other background processes:

1. Start the queue worker:

   ```bash
   php artisan queue:work
   ```

   For production environments, consider setting up a process manager like Supervisor to keep the queue worker running.

## Step 7: Start the Development Server

```bash
php artisan serve
```

This will start the development server at `http://localhost:8000`.

## Step 8: Test the Installation

1. Check if the API is accessible:

   ```bash
   curl http://localhost:8000/api/v1/health
   ```

   You should receive a response indicating that the API is up and running.

2. Run the automated tests:

   ```bash
   php artisan test
   ```

## Troubleshooting

### Common Issues and Solutions

1. **Permission denied errors**:
   ```bash
   chmod -R 777 storage bootstrap/cache
   ```

2. **Database connection issues**:
   - Verify your database credentials in the `.env` file
   - Ensure the database exists and is accessible

3. **Class not found errors after updating dependencies**:
   ```bash
   composer dump-autoload
   ```

4. **JWT errors**:
   - Ensure the JWT secret is generated correctly
   - Check the tymon/jwt-auth package is properly installed

## Next Steps

Once you have completed the installation, you can:

1. Read the API documentation in the README.md file
2. Explore the available endpoints
3. Configure additional services as needed

## Additional Configuration Options

### Setting up Redis for Caching

1. Install Redis on your system
2. Update your `.env` file:
   ```
   CACHE_DRIVER=redis
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   ```

### Setting up CORS for Frontend Integration

If you're developing a separate frontend application, configure CORS in the `.env` file:

```
CORS_ALLOWED_ORIGINS=http://localhost:3000,https://yourdomain.com
```

## For Production Deployment

Additional steps for production deployment:

1. Set `APP_ENV=production` and `APP_DEBUG=false` in your `.env` file
2. Optimize the application:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. Set up a proper web server (Nginx/Apache) with SSL
4. Configure a process manager for queue workers
5. Set up database backups 
