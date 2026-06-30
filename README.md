# SolarConnect

SolarConnect is a responsive rooftop solar installation website built with HTML5, CSS3, Bootstrap 5, JavaScript, PHP, and MySQL. It is compatible with XAMPP and includes:

- A modern homepage with hero section, navigation, package cards, and footer
- An enquiry form with JavaScript and PHP validation
- Secure database storage using prepared statements
- A protected admin panel with search, delete, and logout
- A clean folder structure with separate CSS, JavaScript, images, and PHP folders

## Folder Structure

```text
SolarConnect/
  admin/
  assets/
    css/
    images/
    js/
  config/
  includes/
  php/
  database.sql
  index.php
  README.md
```

## Requirements

- XAMPP with Apache and MySQL enabled
- PHP 8+ recommended
- MySQL / MariaDB

## Deployment Prep

This project now supports environment variables through a local `.env` file or Render's dashboard settings.

Copy `.env.example` to `.env` for local development and fill in the values you need.

Typical variables:

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `ADMIN_USERNAME`
- `ADMIN_PASSWORD_HASH`

## Setup Steps in XAMPP

1. Copy this project folder into your XAMPP `htdocs` directory.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open **phpMyAdmin**.
4. Import the `database.sql` file.
5. Make sure the database `solarconnect` is created.
6. Open the website in your browser:
   - If you keep the folder name as `Solar connect`, use `http://localhost/Solar%20connect/index.php`
   - If you rename it to `SolarConnect`, use `http://localhost/SolarConnect/index.php`

## Admin Login

Set `ADMIN_USERNAME` and `ADMIN_PASSWORD_HASH` in your `.env` file for local use, or in Render environment variables for deployment.

Open the admin panel here:

- `http://localhost/Solar%20connect/admin/login.php` or `http://localhost/SolarConnect/admin/login.php` depending on your folder name

## Features

### Homepage
- Responsive hero section
- Clean navbar
- Solar package cards for 3kW, 5kW, and 10kW
- Enquiry form section

### Enquiry Form
- Full Name
- Phone Number with 10-digit validation
- Monthly Electricity Bill
- Package dropdown with exact options
- JavaScript validation
- PHP validation
- Secure insertion into MySQL using prepared statements

### Admin Panel
- Session-protected login
- View all enquiries in a responsive table
- Search by customer name or phone number
- Delete enquiries securely
- Logout functionality

## Database

The project does not include demo data.
The table structure is created in `database.sql`.

## Notes

- Bootstrap 5 is loaded through CDN.
- For deployment, keep credentials out of committed code and set them through `.env` or Render environment variables.
- The site uses session flash messages for enquiry and admin feedback.
