# URL Configuration Guide

## Current Configuration

The baseURL has been set to: `http://localhost/HMS-ITE311-G7/public/`

## If You're Using Port 8080

If your XAMPP is configured to use port 8080, update the baseURL:

1. Open `app/Config/App.php`
2. Change line 19 to:
   ```php
   public string $baseURL = 'http://localhost:8080/HMS-ITE311-G7/public/';
   ```

OR update the `env` file:
```
app.baseURL = 'http://localhost:8080/HMS-ITE311-G7/public/'
```

## If You're Using a Virtual Host

If you have a virtual host set up (e.g., `hms.local`), update to:
```php
public string $baseURL = 'http://hms.local/';
```

## How to Access Your Application

1. **Standard XAMPP Setup:**
   - Homepage: `http://localhost/HMS-ITE311-G7/public/`
   - Login: `http://localhost/HMS-ITE311-G7/public/login`
   - Dashboard: `http://localhost/HMS-ITE311-G7/public/dashboard`

2. **With Port 8080:**
   - Homepage: `http://localhost:8080/HMS-ITE311-G7/public/`
   - Login: `http://localhost:8080/HMS-ITE311-G7/public/login`
   - Dashboard: `http://localhost:8080/HMS-ITE311-G7/public/dashboard`

3. **With Virtual Host:**
   - Homepage: `http://hms.local/`
   - Login: `http://hms.local/login`
   - Dashboard: `http://hms.local/dashboard`

## Important Notes

- Always access through the `/public/` folder in the URL
- The baseURL must end with a trailing slash `/`
- After changing the baseURL, clear your browser cache if you still see errors

