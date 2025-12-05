# Quick Database Fix

## The Problem
Database connection error: "Access denied for user ''@'localhost'"

## Solution (3 Steps)

### Step 1: Create the Database

**Option A: Using phpMyAdmin (Easiest)**
1. Open: `http://localhost:8080/phpmyadmin/`
2. Click "New" in the left sidebar
3. Database name: `hms_ite311_g7`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

**Option B: Using SQL**
1. Open phpMyAdmin
2. Click on "SQL" tab
3. Run this command:
   ```sql
   CREATE DATABASE IF NOT EXISTS `hms_ite311_g7` 
   CHARACTER SET utf8mb4 
   COLLATE utf8mb4_general_ci;
   ```

### Step 2: Create .env File

**IMPORTANT:** CodeIgniter needs a `.env` file (with a dot), not just `env`.

1. Copy the `env` file in your project root
2. Rename it to `.env` (with a dot at the beginning)
   - In Windows: You might need to use command: `copy env .env`
   - Or create a new file named `.env` and copy the contents

The `.env` file should have these database settings (already configured):
```
database.default.hostname = localhost
database.default.database = hms_ite311_g7
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### Step 3: Run Migration

After creating the database and .env file:
```bash
php spark migrate
```

Then create admin account:
```bash
php spark db:seed AdminSeeder
```

## If Your MySQL Has a Password

If you set a password for MySQL root user, update the `.env` file:
```
database.default.password = your_password_here
```

## Verify It Works

After running migrations, you should see:
- "Running all new migrations..."
- "Done. All migrations have been run."

If you still get errors, check:
1. Is MySQL running in XAMPP Control Panel?
2. Does the database `hms_ite311_g7` exist?
3. Does the `.env` file exist (not just `env`)?
4. Are the credentials correct?

