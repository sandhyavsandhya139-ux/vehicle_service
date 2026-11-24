# Vehicle Service Management System

A standalone PHP 8+ and MySQL application for XAMPP. Customers register vehicles and request appointments; administrators manage services, bookings, mechanics, and payments; mechanics update assigned jobs.

## Run with XAMPP

1. Copy the `vehicle_service` folder to `C:\xampp\htdocs\`.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`, import `database\vehicle_service_db.sql`.
4. If your MySQL credentials differ from the defaults, update `config\config.php`.
5. Open `http://localhost/vehicle_service/setup.php` and create the first administrator. The database script includes six sample service types.
6. Delete `setup.php` after setup, then visit `http://localhost/vehicle_service/`.

## Roles and workflow

- **Customer:** register, add vehicles, request a service, and view booking/payment status.
- **Admin:** approve/reject requests, assign mechanics, manage service types/mechanics, and record payments.
- **Mechanic:** view assigned jobs and update them to in progress or completed.

New users can register only as customers. Create the first admin through setup; admins can create mechanic accounts from **Mechanics**. All forms use session-based CSRF tokens, database writes use PDO prepared statements, and passwords are stored with PHP's password hashing API.

## Requirements and notes

- PHP 8.0+ with PDO MySQL enabled; MySQL 5.7+ or MariaDB 10.4+.
- This is a local/demo application. Configure HTTPS, secure database credentials, backups, and operational policies before deploying it publicly.
- Payments are records only; the app does not collect card data or connect to a payment provider.
- The home and services pages include original, locally stored SVG illustrations and responsive layouts; no external image service is required.
