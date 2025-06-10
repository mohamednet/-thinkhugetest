# Database Setup

This directory contains SQL scripts for setting up the database for the Finance App.

## Setup Instructions

1. To create the database and tables, run the `setup.sql` script:

```bash
# Using MySQL command line
mysql -u root -p < setup.sql

# Or import through phpMyAdmin
```

2. The script will:
   - Create the database if it doesn't exist
   - Create all necessary tables (users, clients, transactions)
   - Add an initial admin user (username: admin, password: admin123)
   - Add some sample clients and transactions

## Default Admin Credentials
- Username: admin
- Password: admin123
