# Login Troubleshooting Guide

## Issue: Connection Refused Error

If you're getting `ERR_CONNECTION_REFUSED` when trying to log in, follow these steps:

## Step 1: Verify Apache is Running

1. Open XAMPP Control Panel
2. Make sure **Apache** is running (green status)
3. Check the port - it should be **8080** (or whatever port you configured)

## Step 2: Check Your Access URL

Make sure you're accessing the site through the correct URL:

✅ **CORRECT:**
- `http://localhost:8080/HMS-ITE311-G7/public/`
- `http://localhost:8080/HMS-ITE311-G7/public/login`

❌ **WRONG:**
- `http://localhost:8080/index.php/...`
- `http://localhost:8080/HMS-ITE311-G7/...` (missing `/public/`)

## Step 3: Verify .env File Exists

CodeIgniter needs a `.env` file (with a dot) in the project root:

1. Check if `.env` file exists in `C:\xampp\htdocs\HMS-ITE311-G7\`
2. If not, copy the `env` file and rename it to `.env`
3. Make sure it has:
   ```
   app.baseURL = 'http://localhost:8080/HMS-ITE311-G7/public/'
   ```

## Step 4: Check Form Action URL

1. Go to the login page
2. Right-click → View Page Source
3. Find the `<form>` tag
4. Check the `action` attribute - it should be:
   ```
   action="http://localhost:8080/HMS-ITE311-G7/public/auth/authenticate"
   ```

If it shows something different, the baseURL isn't being read correctly.

## Step 5: Verify Database and Admin Account

Make sure:
1. Database `ite-hms-g7` exists
2. `users` table exists (run migrations)
3. Admin account exists (run seeder)

**Check admin account:**
```sql
SELECT * FROM users WHERE email = 'admin@globalhospitals.com';
```

**If admin doesn't exist, run:**
```bash
php spark db:seed AdminSeeder
```

## Step 6: Test Login Credentials

- **Email:** `admin@globalhospitals.com`
- **Password:** `admin123`
- **Role:** Select either "Patient" or "Doctor" (admin can login with any role)

## Step 7: Check Browser Console

1. Press F12 to open Developer Tools
2. Go to Console tab
3. Try to log in
4. Check for any JavaScript errors

## Step 8: Check Apache Error Logs

1. In XAMPP Control Panel, click "Logs" next to Apache
2. Check for any PHP errors
3. Look for database connection errors

## Common Issues

### Issue: "Connection Refused"
- **Cause:** Apache not running or wrong port
- **Fix:** Start Apache in XAMPP, check port configuration

### Issue: "404 Not Found"
- **Cause:** Wrong URL or .htaccess not working
- **Fix:** Make sure you're accessing through `/public/` folder

### Issue: "Invalid email or password"
- **Cause:** Admin account doesn't exist or wrong password
- **Fix:** Run the seeder to create admin account

### Issue: Form submits but nothing happens
- **Cause:** CSRF token issue or session problem
- **Fix:** Clear browser cache, check session configuration

## Still Not Working?

1. **Clear browser cache** (Ctrl+Shift+Delete)
2. **Restart Apache** in XAMPP
3. **Check PHP version** - should be 8.1 or higher
4. **Verify file permissions** - make sure files are readable

