# Finance Management Application

A professional-grade PHP MVC application for managing clients and their financial transactions. Built with vanilla PHP and MySQL, following best practices and a clean architecture.

## Features

- Custom MVC architecture with routing
- Admin authentication with secure password hashing
- CRUD operations for clients and transactions
- Financial reporting with date range filters
- RESTful API endpoint for client transactions
- Clean, maintainable codebase following SOLID principles

## Technology Stack

- PHP 7.4+ (OOP with namespaces)
- MySQL database with PDO
- Custom Router supporting GET/POST methods and URL parameters
- Service Container for dependency injection
- Repository pattern for data access
- Bootstrap 5 for UI

## Project Structure

```
app/
├── controllers/       # Controllers for handling requests
├── core/             # Core framework components
├── mappers/          # Data mappers for DB to Model conversion
├── models/           # Domain models
├── repositories/     # Data access layer
│   └── interfaces/   # Repository interfaces
├── routes/           # Route definitions
├── services/         # Business logic layer
└── views/            # View templates
config/               # Configuration files
public/               # Web root
├── css/              # CSS files
├── js/               # JavaScript files
└── index.php         # Application entry point
sql/                  # SQL schema and migrations
storage/              # File storage
```

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Configure your web server to point to the `public` directory

3. Copy `.env.example` to `.env` and update the configuration:
   ```
   cp .env.example .env
   ```

4. Update the `.env` file with your database credentials and application settings

5. Run the setup script to initialize the database:
   ```
   php setup.php
   ```

6. Access the application in your browser

## Default Login

- Username: `admin`
- Password: `admin123`

**Important:** Change the default password after first login for security.

## Architecture Overview

This application follows a custom MVC architecture with:

- **Models**: Domain objects representing business entities
- **Views**: Templates for rendering HTML
- **Controllers**: Handle HTTP requests and coordinate responses
- **Services**: Business logic layer
- **Repositories**: Data access layer with interfaces for abstraction
- **Mappers**: Convert database rows to model objects

## Security Features

- Password hashing with bcrypt
- CSRF protection for forms
- PDO prepared statements for SQL injection prevention
- Input sanitization
- Session-based authentication

## License

[MIT License](LICENSE)
