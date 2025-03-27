# Task Management API

A RESTful API for managing tasks, with features including task assignment, commenting, and notifications.

## 📋 Features

- User authentication using JWT
- Task management (create, read, update, delete)
- Task assignment to multiple users
- Task status tracking
- Task commenting system with nested replies
- Notifications for task assignments
- Request caching for improved performance

## 📚 Documentation

For detailed information about the project, please refer to these documentation files:

- [Installation Guide](INSTALLATION.md) - Comprehensive instructions for setting up the project
- [API Documentation](API_DOCUMENTATION.md) - Complete API reference with all endpoints and request/response formats

## 🔧 Requirements

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL or SQLite
- Node.js & npm (for frontend development, if needed)

## 🚀 Quick Start

1. Clone the repository:
```bash
git clone https://github.com/your-username/task-management-api.git
cd task-management-api
```

2. Install dependencies:
```bash
composer install
```

3. Copy the environment file and generate key:
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Set up the database in `.env` and run migrations:
```bash
php artisan migrate --seed
```

5. Start the development server:
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

For complete setup instructions, see the [Installation Guide](INSTALLATION.md).

## 🔐 Authentication

The API uses JWT for authentication. Here's how to authenticate:

1. Register a user or login to get a token
2. Include the token in the Authorization header: `Bearer YOUR_TOKEN`

For detailed API usage, see the [API Documentation](API_DOCUMENTATION.md).

## 🧪 Testing

Run tests with PHPUnit:
```bash
php artisan test
```

## 📧 Email Configuration

For task notifications, configure SMTP settings in your `.env` file. Example for using Mailtrap:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

## 🔄 Queue Configuration

Task notifications are queued. Run the queue worker:
```bash
php artisan queue:work
```

## 🧰 Project Structure

- `app/Http/Controllers/Api/V1` - API controllers
- `app/Models` - Eloquent models
- `app/Actions` - Business logic actions
- `app/Jobs` - Background jobs
- `app/Notifications` - Notification classes
- `database/migrations` - Database structure
- `routes/v1` - API routes
- `tests` - Test suites

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.
