# Setup Instructions

## Step 1: Configure Database

1. Open `env` file in the root directory
2. Uncomment and configure your database settings:
   ```
   database.default.hostname = localhost
   database.default.database = your_database_name
   database.default.username = your_username
   database.default.password = your_password
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

## Step 2: Run Migration

Run the migration to create the users table:

```bash
php spark migrate
```

## Step 3: Create Admin Account

Run the seeder to create the admin account:

```bash
php spark db:seed AdminSeeder
```

Or manually run the seeder using:

```bash
php spark db:seed "App\Database\Seeds\AdminSeeder"
```

## Step 4: Login Credentials

After running the seeder, you can login with:

- **Email:** admin@globalhospitals.com
- **Password:** admin123
- **Role:** Admin

## Step 5: Access Dashboard

1. Go to your homepage: `http://localhost/HMS-ITE311-G7/public/`
2. Click on "Login" or go to: `http://localhost/HMS-ITE311-G7/public/login`
3. Select "Patient" or "Doctor" role (admin can login with any role)
4. Enter the credentials above
5. You will be redirected to the admin dashboard

## Troubleshooting

If you encounter issues:

1. Make sure your database is created
2. Check that the migration ran successfully
3. Verify the seeder created the admin account
4. Check your database connection settings in the `env` file

