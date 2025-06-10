# Client Management System

A professional PHP MVC application for managing clients and their financial transactions. Built with a custom MVC framework and MySQL, this application allows administrators to track clients, record income and expenses, and generate financial reports.

## Features

- **Authentication System**: Secure login for administrators
- **Client Management**: Create, view, update, and delete client records
- **Transaction Management**: Record income and expenses for each client
- **Financial Reporting**: Generate reports with date range filters
- **API Access**: RESTful API endpoint for accessing client transactions
- **Security**: Protection against XSS, CSRF, and SQL injection attacks

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server with mod_rewrite enabled
- Composer (optional, for future dependency management)

## Installation Guide

### 1. Server Setup

#### Using XAMPP/WAMP/MAMP

1. Install [XAMPP](https://www.apachefriends.org/), [WAMP](https://www.wampserver.com/), or [MAMP](https://www.mamp.info/)
2. Clone or extract this repository to your htdocs/www folder:
   ```
   git clone https://github.com/mohamednet/-thinkhugetest.git testv1thinkhug
   ```
   or place the files in a subdirectory like `htdocs/testv1thinkhug`

#### Using a Custom Server

1. Set up a virtual host pointing to the project's root directory
2. Ensure the Apache `mod_rewrite` module is enabled
3. Make sure PHP has PDO and PDO_MySQL extensions enabled

### 2. Database Setup

1. Create a new MySQL database for the application
2. Import the database schema and initial data:
   - Use the SQL script located at `app/database/sql/setup.sql`
   - Via command line:
     ```
     mysql -u username -p database_name < app/database/sql/setup.sql
     ```
   - Or using phpMyAdmin:
     - Open phpMyAdmin
     - Select your database
     - Go to the "Import" tab
     - Upload and execute the `app/database/sql/setup.sql` file

### 3. Configuration

1. Copy the `.env.example` file to `.env`:
   ```
   cp .env.example .env
   ```

2. Edit the `.env` file with your database credentials and application settings:
   ```
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   APP_URL=http://localhost/testv1thinkhug
   ```

### 4. Directory Permissions

Ensure the web server has write permissions to the `storage` directory:
```
chmod -R 755 storage
```

### 5. Access the Application

1. Open your web browser and navigate to:
   - If using XAMPP/WAMP/MAMP: `http://localhost/testv1thinkhug`
   - If using a virtual host: Your configured domain

2. Login with the default administrator account:
   - Username: `admin@example.com`
   - Password: `admin123`

3. **Important:** Change the default password after your first login for security.

## Project Structure

```
app/
├── controllers/       # Controllers for handling requests
├── core/              # Core framework components
├── database/          # Database setup and migrations
│   └── sql/           # SQL setup scripts
├── mappers/           # Data mappers for DB to Model conversion
├── models/            # Domain models
├── repositories/      # Data access layer
│   └── interfaces/    # Repository interfaces
├── routes/            # Route definitions
├── services/          # Business logic layer
└── views/             # View templates
config/                # Configuration files
public/                # Web root with assets
storage/               # File storage
```

## API Documentation

The application provides a RESTful API endpoint for accessing client transactions:

### Get Client Transactions

**Endpoint:** `GET /api/clients/{id}/transactions`

**Parameters:**
- `start_date` (optional): Filter transactions from this date (YYYY-MM-DD)
- `end_date` (optional): Filter transactions until this date (YYYY-MM-DD)

**Authentication:**
- Requires the same authentication as the web interface

**Response Example:**
```json
{
  "success": true,
  "data": {
    "client_id": 1,
    "transactions": [
      {
        "id": 5,
        "type": "income",
        "amount": 1500.00,
        "formatted_amount": "$1,500.00",
        "description": "Monthly service fee",
        "date": "2023-06-01",
        "formatted_date": "Jun 1, 2023"
      }
    ],
    "balance": 1500.00,
    "formatted_balance": "1,500.00",
    "count": 1
  }
}
```

## Security Features

- **Password Hashing**: Secure password storage using bcrypt
- **CSRF Protection**: Cross-Site Request Forgery prevention on all forms
- **SQL Injection Prevention**: PDO prepared statements for all database queries
- **XSS Protection**: Output escaping with `htmlspecialchars()` throughout the application
- **Authentication**: Session-based authentication with secure session handling

## Troubleshooting

### URL Rewriting Issues

If you encounter 404 errors or routing problems:

1. Ensure Apache's `mod_rewrite` module is enabled
2. Check that `.htaccess` files are being processed (AllowOverride must be set to All)
3. Verify the project is in the correct subdirectory as configured in your environment

### Database Connection Issues

1. Verify your database credentials in the `.env` file
2. Ensure the MySQL server is running
3. Check that the database has been created and the setup script has been executed

### Login Issues

If you cannot log in with the default credentials:

1. Verify the database setup was completed successfully
2. Check the `users` table to ensure the admin user exists

## License

[MIT License](LICENSE)
