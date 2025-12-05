# Quick Start Guide - HMS System

## 🚀 Setup Steps

### 1. Run Migrations
```bash
php spark migrate
```
This creates all database tables.

### 2. Create Admin Account
```bash
php spark db:seed AdminSeeder
```
Creates admin: `admin@globalhospitals.com` / `admin123`

### 3. Add Sample Data (Optional)
```bash
php spark db:seed HMSDataSeeder
```
Adds sample departments, doctors, patients.

## 📋 System Features

### ✅ Completed Features

1. **User Authentication**
   - Login system with role-based access
   - Session management
   - Admin, Doctor, Receptionist, Patient roles

2. **Dashboard**
   - Real-time statistics
   - Summary cards with counts
   - Navigation to all modules

3. **Patient Management**
   - View all patients
   - Add new patients
   - Edit patient information
   - Delete patients
   - Patient ID auto-generation

4. **Doctor Management**
   - View all doctors
   - Add new doctors
   - Edit doctor accounts
   - Delete doctors

5. **Department Management**
   - View all departments
   - Add new departments
   - Edit departments
   - Delete departments

## 🗄️ Database Tables

- `users` - All user accounts (admin, doctors, receptionists)
- `patients` - Patient records
- `departments` - Hospital departments
- `appointments` - Patient appointments (ready for future use)

## 🔐 Login Credentials

**Admin:**
- Email: `admin@globalhospitals.com`
- Password: `admin123`

**Sample Doctors (after running HMSDataSeeder):**
- `sarah.johnson@hospital.com` / `doctor123`
- `michael.chen@hospital.com` / `doctor123`
- `emily.davis@hospital.com` / `doctor123`

**Sample Receptionist:**
- `maria.garcia@hospital.com` / `reception123`

## 📍 Access URLs

- Homepage: `http://localhost/HMS-ITE311-G7/public/`
- Login: `http://localhost/HMS-ITE311-G7/public/login`
- Dashboard: `http://localhost/HMS-ITE311-G7/public/dashboard`
- Patients: `http://localhost/HMS-ITE311-G7/public/patients`
- Doctors: `http://localhost/HMS-ITE311-G7/public/doctors`
- Departments: `http://localhost/HMS-ITE311-G7/public/departments`

## 🎯 Next Steps (Future Enhancements)

- Appointment scheduling system
- Medical records management
- Billing system
- Reports and analytics
- Email notifications
- Search and filtering

The system is now fully functional with database integration!

