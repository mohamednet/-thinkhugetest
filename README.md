# Client Management System

This is a custom PHP MVC app I built for managing clients and their financial transactions. I created it from scratch without any major frameworks - just vanilla PHP, MySQL, and some Bootstrap for the UI. It lets you manage clients, track income/expenses, and generate some basic financial reports.

## What it does

- Login system for admins (nothing fancy but it's secure)
- Add/edit/delete clients and their info
- Record payments and expenses for each client
- Run reports with date filters to see how you're doing
- Simple API endpoint if you need to pull transaction data programmatically
- All the usual security stuff (XSS protection, CSRF tokens, etc.)

## What you need

- PHP 7.4+
- MySQL 5.7+
- Apache with mod_rewrite
- That's pretty much it!

## Setting it up

### Quick start with XAMPP

1. Get XAMPP running on your machine
2. Drop these files in your htdocs folder (or a subfolder like `testv1thinkhug`)
3. Create a database in phpMyAdmin
4. Run the SQL script from `app/database/sql/setup.sql`
5. Copy `.env.example` to `.env` and update your database settings
6. Hit the URL in your browser and you're good to go!

### Manual setup

If you're not using XAMPP:

1. Set up your vhost to point to the project root
2. Make sure mod_rewrite is enabled (for the pretty URLs)
3. Create your database and run the setup script:
   ```
   mysql -u yourusername -p yourdb < app/database/sql/setup.sql
   ```
4. Sort out your `.env` file with the right settings:
   ```
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=yourdb
   DB_USERNAME=yourusername
   DB_PASSWORD=yourpassword
   APP_URL=http://localhost/testv1thinkhug  # or your vhost URL
   ```
5. You might need to fix permissions on the storage folder:
   ```
   chmod -R 755 storage
   ```

## Logging in

Use these credentials for your first login:
- Email: `admin`
- Password: `admin123`

Obviously change this password ASAP!

## How it's built

I went with a pretty standard MVC structure:

```
app/
├── controllers/     # Handle requests, talk to services
├── core/            # Framework stuff - router, request handling, etc.
├── database/        # DB connection and setup scripts
├── models/          # Data models
├── repositories/    # Database operations
├── services/        # Business logic
└── views/           # Templates and UI
config/              # App config
public/              # Web root
storage/             # File storage (not really used much yet)
```

## API stuff

There's a basic API endpoint for getting client transactions:

`GET /api/clients/{id}/transactions?start_date=2023-01-01&end_date=2023-12-31`

It returns JSON with transaction data. You need to be logged in to use it - it uses the same auth as the web interface.

Example response:
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
        "description": "Monthly service fee",
        "date": "2023-06-01"
      }
    ],
    "balance": 1500.00,
    "count": 1
  }
}
```

## Security notes

I've implemented the usual security measures:
- Passwords are hashed with bcrypt
- Forms have CSRF tokens
- All database queries use prepared statements
- Output is escaped with htmlspecialchars()
- Authentication checks on all sensitive routes

## Troubleshooting

If you hit issues:

- 404 errors? Check your .htaccess and mod_rewrite setup
- Can't connect to DB? Double-check your .env settings
- Login problems? Make sure the setup.sql script ran correctly

Let me know if you run into anything else!
