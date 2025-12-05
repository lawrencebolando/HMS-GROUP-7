# Final Fix for Login Connection Error

## The Problem
Connection refused when submitting login form - the URL `localhost:8080/HMS-ITE311-G7/public/auth/authenticate` can't be reached.

## What I Changed

1. **Form Action**: Changed to absolute path `/HMS-ITE311-G7/public/auth/authenticate` instead of using `site_url()`
2. **Index Page**: Removed `index.php` from URLs by setting `$indexPage = ''` in App config

## Critical Steps to Fix

### Step 1: Verify Apache is Running
1. Open **XAMPP Control Panel**
2. Check if **Apache** shows green "Running"
3. If not, click **Start**
4. **Check the port** - look at the Apache port (should be 8080)

### Step 2: Check Apache Port Configuration

If Apache is running on a different port:

**Option A: Check XAMPP Apache Port**
1. In XAMPP Control Panel, click **Config** next to Apache
2. Select **httpd.conf**
3. Look for `Listen 8080` (or whatever port it shows)
4. Note the port number

**Option B: Try Different Ports**
- Try: `http://localhost/HMS-ITE311-G7/public/login` (port 80)
- Try: `http://localhost:8080/HMS-ITE311-G7/public/login` (port 8080)
- Try: `http://127.0.0.1:8080/HMS-ITE311-G7/public/login`

### Step 3: Test the Connection

Access this test page:
```
http://localhost:8080/HMS-ITE311-G7/public/test_connection.php
```

If you get connection refused on this too, **Apache is not running or wrong port**.

### Step 4: Verify .env File Exists

1. Go to: `C:\xampp\htdocs\HMS-ITE311-G7\`
2. Check if `.env` file exists (with a dot)
3. If not:
   - Copy `env` file
   - Rename to `.env`
   - Make sure it has: `app.baseURL = 'http://localhost:8080/HMS-ITE311-G7/public/'`

### Step 5: Alternative - Use Port 80

If port 8080 doesn't work, try using port 80:

1. Update `app/Config/App.php` line 19:
   ```php
   public string $baseURL = 'http://localhost/HMS-ITE311-G7/public/';
   ```

2. Update `.env` file:
   ```
   app.baseURL = 'http://localhost/HMS-ITE311-G7/public/'
   ```

3. Access: `http://localhost/HMS-ITE311-G7/public/login`

### Step 6: Check Apache Error Log

1. In XAMPP Control Panel, click **Logs** next to Apache
2. Click **Apache (error.log)**
3. Look for any errors related to your project

## Quick Test

Try accessing these URLs in order:

1. `http://localhost:8080/` - Should show XAMPP dashboard
2. `http://localhost:8080/HMS-ITE311-G7/public/` - Should show your homepage
3. `http://localhost:8080/HMS-ITE311-G7/public/test_connection.php` - Should show test page
4. `http://localhost:8080/HMS-ITE311-G7/public/login` - Should show login page

If #1 doesn't work, Apache isn't running or wrong port.
If #1 works but #2 doesn't, there's a path issue.
If #2 works but #3 doesn't, there's a PHP/CodeIgniter issue.

## Still Not Working?

The connection refused error means the browser can't reach the server at all. This is **NOT a CodeIgniter issue** - it's a server configuration issue.

**Check:**
- Is Apache actually running? (Green in XAMPP)
- What port is Apache using? (Check XAMPP control panel)
- Are you using the correct port in the URL?
- Is Windows Firewall blocking the connection?
- Are there any other applications using port 8080?

