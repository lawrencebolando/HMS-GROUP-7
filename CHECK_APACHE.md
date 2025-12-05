# CRITICAL: Apache Must Be Running!

## The Error
`ERR_CONNECTION_REFUSED` when accessing `localhost:8080/HMS-ITE311-G7/public/dashboard`

## This Means Apache is NOT Running

The "connection refused" error means your browser **cannot connect to Apache**. This is NOT a code issue - it's a server issue.

## IMMEDIATE ACTION REQUIRED

### Step 1: Check XAMPP Control Panel

1. **Open XAMPP Control Panel**
2. **Look at Apache** - what does it show?
   - ✅ **Green "Running"** = Apache is working
   - ❌ **Red "Stopped"** = Apache is NOT running ← **THIS IS YOUR PROBLEM**
   - ⚠️ **Yellow/Orange** = Apache has an error

### Step 2: Start Apache

If Apache shows "Stopped":
1. Click **Start** next to Apache
2. Wait 5-10 seconds
3. It should turn **green**
4. **Note the port number** shown (usually 8080 or 80)

### Step 3: Verify Apache is Running

After starting Apache, test:
1. Open browser
2. Go to: `http://localhost:8080/`
3. You should see the **XAMPP dashboard**
4. If you see XAMPP dashboard → Apache is working ✅
5. If you still get connection refused → Apache didn't start (check error logs)

### Step 4: Test Your Project

Once Apache is running:
1. Go to: `http://localhost:8080/HMS-ITE311-G7/public/`
2. You should see your homepage
3. Then try: `http://localhost:8080/HMS-ITE311-G7/public/login`
4. Login and it should redirect to dashboard

## If Apache Won't Start

If you click Start but Apache doesn't turn green:

1. **Check Port Conflict:**
   - Another program might be using port 8080
   - In XAMPP, click **Config** → **httpd.conf**
   - Look for `Listen 8080`
   - Try changing to `Listen 8081` or `Listen 80`

2. **Check Error Log:**
   - In XAMPP, click **Logs** next to Apache
   - Click **Apache (error.log)**
   - Look for error messages

3. **Check Windows Firewall:**
   - Windows might be blocking Apache
   - Try temporarily disabling firewall

## Quick Test

**Answer these questions:**

1. **Is Apache showing "Running" (green) in XAMPP?**
   - [ ] Yes → Then the issue is something else
   - [ ] No → **START APACHE FIRST!**

2. **Can you access `http://localhost:8080/`?**
   - [ ] Yes → Apache is working
   - [ ] No → Apache is not running or wrong port

3. **What port is Apache using?**
   - Check XAMPP Control Panel
   - Use that port in your URLs

## Remember

- **Connection Refused = Apache Not Running**
- **You MUST have Apache running to access your website**
- **The code is fine - you just need to start the server**

