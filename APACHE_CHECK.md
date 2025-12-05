# ⚠️ CRITICAL: Apache Must Be Running!

## The Error You're Seeing
`ERR_CONNECTION_REFUSED` at `localhost:8080/HMS-ITE311-G7/public/dashboard`

## This Means Apache is NOT Running

**You CANNOT access your website if Apache is not running!**

## Step-by-Step Fix

### Step 1: Open XAMPP Control Panel
1. Find XAMPP in your Start menu or desktop
2. Open XAMPP Control Panel

### Step 2: Check Apache Status
Look at the Apache row:
- **Green "Running"** = ✅ Apache is working
- **Red "Stopped"** = ❌ Apache is NOT running ← **THIS IS YOUR PROBLEM**

### Step 3: Start Apache
1. If Apache shows "Stopped", click the **Start** button
2. Wait 10-15 seconds
3. Apache should turn **green**
4. **Note the port number** shown (usually 8080)

### Step 4: Verify Apache is Working
1. Open your browser
2. Go to: `http://localhost:8080/`
3. **You should see the XAMPP dashboard page**
4. If you see XAMPP dashboard → ✅ Apache is working!
5. If you still get connection refused → Apache didn't start (see troubleshooting below)

### Step 5: Test Your Project
Once Apache is running:
1. Go to: `http://localhost:8080/HMS-ITE311-G7/public/`
2. You should see your homepage
3. Go to: `http://localhost:8080/HMS-ITE311-G7/public/login`
4. Login with admin credentials
5. It should redirect to dashboard

## If Apache Won't Start

### Check Port Conflict
1. In XAMPP, click **Config** next to Apache
2. Select **httpd.conf**
3. Look for `Listen 8080`
4. If port 8080 is in use, change to `Listen 8081` or `Listen 80`
5. Save and restart Apache

### Check Error Logs
1. In XAMPP, click **Logs** next to Apache
2. Click **Apache (error.log)**
3. Look for error messages
4. Common errors:
   - Port already in use
   - Permission denied
   - Configuration error

### Try Different Port
If port 8080 doesn't work:
1. Change Apache to port 80 (default)
2. Update `app/Config/App.php` line 19 to: `'http://localhost/HMS-ITE311-G7/public/'`
3. Access via: `http://localhost/HMS-ITE311-G7/public/`

## Quick Test Checklist

Answer these:
- [ ] Is Apache showing "Running" (green) in XAMPP?
- [ ] Can you access `http://localhost:8080/` and see XAMPP dashboard?
- [ ] What port number is Apache using?

## Remember

**You MUST have Apache running to access your website!**
- Connection Refused = Apache Not Running
- The code is fine - you just need to start the server
- Apache must stay running while you use your website

