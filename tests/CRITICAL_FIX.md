# CRITICAL FIX - Connection Refused Error

## The Problem
Form submits to `localhost:8080/HMS-ITE311-G7/public/auth/authenticate` but gets "ERR_CONNECTION_REFUSED"

## Root Cause
This error means **Apache is NOT running** or the **port is wrong**.

## IMMEDIATE FIX

### Step 1: Check Apache Status
1. **Open XAMPP Control Panel**
2. Look at Apache - what does it show?
   - ✅ **Green "Running"** = Apache is working
   - ❌ **Red "Stopped"** = Apache is NOT running
   - ⚠️ **Yellow/Orange** = Apache has an error

### Step 2: Start Apache
If Apache shows "Stopped":
1. Click **Start** next to Apache
2. Wait for it to turn green
3. **Note the port number** shown (usually 8080 or 80)

### Step 3: Check Port Number
Look at the XAMPP Control Panel - what port is Apache using?
- If it shows **8080** → Use: `http://localhost:8080/HMS-ITE311-G7/public/`
- If it shows **80** → Use: `http://localhost/HMS-ITE311-G7/public/`
- If it shows **another number** → Use that port in your URL

### Step 4: Test Basic Access
Try these URLs in order:

1. **First test:** `http://localhost:8080/`
   - ✅ If this works → Apache is running
   - ❌ If this doesn't work → Apache is NOT running or wrong port

2. **Second test:** `http://localhost:8080/HMS-ITE311-G7/public/`
   - ✅ If this works → Your project is accessible
   - ❌ If this doesn't work → Check the path

3. **Third test:** `http://localhost:8080/HMS-ITE311-G7/public/debug.php`
   - This will show detailed diagnostic information

### Step 5: What I Changed
I changed the form action to use a **relative URL** instead of `base_url()`:
- **Before:** `action="<?= base_url('auth/authenticate') ?>"`
- **After:** `action="auth/authenticate"`

This uses a relative path that should work regardless of the baseURL configuration.

## If Apache Won't Start

If Apache shows an error or won't start:

1. **Check if port is in use:**
   - Another application might be using port 8080
   - Try changing Apache port in XAMPP config

2. **Check Windows Firewall:**
   - Windows might be blocking Apache
   - Try temporarily disabling firewall

3. **Check Apache Error Log:**
   - In XAMPP, click **Logs** next to Apache
   - Look for error messages

## Alternative: Use Port 80

If port 8080 doesn't work, try using port 80 (default):

1. **Update App.php:**
   - Change `baseURL` to: `http://localhost/HMS-ITE311-G7/public/`

2. **Access via:**
   - `http://localhost/HMS-ITE311-G7/public/login`

## Still Not Working?

**The connection refused error is 100% a server issue, not a code issue.**

If you can't access `http://localhost:8080/` at all, then:
- Apache is not running
- Port is wrong
- Firewall is blocking
- Another issue with XAMPP installation

**Please confirm:**
1. Is Apache showing "Running" (green) in XAMPP?
2. What port number is shown?
3. Can you access `http://localhost:8080/`?

