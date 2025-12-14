# Troubleshooting Connection Error

## The Error
`ERR_CONNECTION_REFUSED` when trying to login - the form submits to `localhost:8080/index.php/auth/authenticate`

## Root Cause
The URL is missing the project path `/HMS-ITE311-G7/public/`

## Solutions (Try in order):

### Solution 1: Create .env File (IMPORTANT!)

CodeIgniter 4 reads configuration from a `.env` file (with a dot), not `env`.

1. In your project root folder (`C:\xampp\htdocs\HMS-ITE311-G7\`), create a new file named `.env`
   - **Important:** The filename must start with a dot: `.env` (not `env`)
   - In Windows, you might need to create it as `env` first, then rename it to `.env`

2. Copy this content into the `.env` file:
   ```
   app.baseURL = 'http://localhost:8080/HMS-ITE311-G7/public/'
   ```

3. Save the file

### Solution 2: Verify Your Access URL

Make sure you're accessing the site correctly:

✅ **CORRECT:**
- `http://localhost:8080/HMS-ITE311-G7/public/`
- `http://localhost:8080/HMS-ITE311-G7/public/login`

❌ **WRONG:**
- `http://localhost:8080/index.php/...`
- `http://localhost:8080/HMS-ITE311-G7/...` (missing `/public/`)

### Solution 3: Check Apache Configuration

1. Open XAMPP Control Panel
2. Make sure Apache is running on port 8080
3. If it's on a different port, update the baseURL in `app/Config/App.php` line 19

### Solution 4: Test the Base URL

1. Go to: `http://localhost:8080/HMS-ITE311-G7/public/`
2. View the page source
3. Check the form action - it should show:
   `action="http://localhost:8080/HMS-ITE311-G7/public/auth/authenticate"`

If it shows something different, the baseURL isn't being read correctly.

### Solution 5: Manual URL Fix (Temporary)

If nothing else works, you can temporarily hardcode the form action:

1. Open `app/Views/login.php`
2. Find line 55: `<form class="px-6 pb-6" action="<?= base_url('auth/authenticate') ?>" method="POST">`
3. Replace with: `<form class="px-6 pb-6" action="http://localhost:8080/HMS-ITE311-G7/public/auth/authenticate" method="POST">`

Do the same for `app/Views/home.php` line 55.

## After Fixing:

1. **Clear browser cache** (Ctrl+Shift+Delete)
2. **Restart Apache** in XAMPP
3. **Access the site** through the correct URL
4. **Try logging in** again

## Still Not Working?

Check:
- Is Apache running on port 8080?
- Can you access `http://localhost:8080/HMS-ITE311-G7/public/` at all?
- Check browser console (F12) for any JavaScript errors
- Check Apache error logs in XAMPP

