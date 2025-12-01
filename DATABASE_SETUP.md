# Database Setup Instructions

## Step 1: Create the Database

1. Open phpMyAdmin: `http://localhost:8080/phpmyadmin/`
2. Click on "New" in the left sidebar
3. Database name: `hms_ite311_g7`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

## Step 2: Verify Database Configuration

The database settings are now configured in the `env` file:
- Hostname: `localhost`
- Database: `hms_ite311_g7`
- Username: `root`
- Password: (empty - XAMPP default)
- Port: `3306`

## Step 3: Create .env File

1. Copy the `env` file and rename it to `.env` (with a dot at the beginning)
2. Or manually create `.env` file with the database settings

## Step 4: Run Migrations

After creating the database, run:
```bash
php spark migrate
```

## Step 5: Create Admin Account

Run the seeder:
```bash
php spark db:seed AdminSeeder
```

## If You Have a MySQL Password

If your XAMPP MySQL has a password set, update the `env` file:
```
database.default.password = your_password_here
```

## Troubleshooting

If you get "Access denied" error:
1. Check if MySQL is running in XAMPP Control Panel
2. Verify the database name exists in phpMyAdmin
3. Check if username/password are correct
4. Make sure the `.env` file exists (not just `env`)

