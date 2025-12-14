# HMS Setup Instructions

## Step 1: Run All Migrations

Create all database tables:

```bash
php spark migrate
```

This will create:
- `users` table
- `departments` table
- `patients` table
- `appointments` table

## Step 2: Create Admin Account

```bash
php spark db:seed AdminSeeder
```

This creates:
- Admin account: `admin@globalhospitals.com` / `admin123`

## Step 3: Add Sample Data

```bash
php spark db:seed HMSDataSeeder
```

This creates:
- 5 Departments (Cardiology, Neurology, Orthopedics, Pediatrics, General Medicine)
- 3 Doctors
- 1 Receptionist
- 6 Sample Patients

## Step 4: Access the System

1. Go to: `http://localhost/HMS-ITE311-G7/public/login`
2. Login with admin credentials
3. You'll be redirected to the dashboard

## Features Available

### Dashboard
- View statistics (Patients, Doctors, Admins, Receptionists)
- Real-time counts from database
- Links to management pages

### Patient Management
- View all patients
- Add new patients
- Edit patient information
- Delete patients
- Access: `http://localhost/HMS-ITE311-G7/public/patients`

### Doctor Management
- View all doctors
- Add new doctors
- Edit doctor accounts
- Delete doctors
- Access: `http://localhost/HMS-ITE311-G7/public/doctors`

### Department Management
- View all departments
- Add new departments
- Edit departments
- Delete departments
- Access: `http://localhost/HMS-ITE311-G7/public/departments`

## Database Structure

### Users Table
- Stores: Admins, Doctors, Receptionists, Patients (if they have accounts)
- Fields: id, name, email, password, role, status

### Patients Table
- Stores: Patient records
- Fields: id, patient_id, first_name, last_name, email, phone, date_of_birth, gender, address, blood_group, status

### Departments Table
- Stores: Hospital departments
- Fields: id, name, description, status

### Appointments Table
- Stores: Patient appointments
- Fields: id, appointment_id, patient_id, doctor_id, department_id, appointment_date, appointment_time, reason, status, notes

## Next Steps

You can now:
1. Add more patients, doctors, and departments
2. Create appointment management (can be added later)
3. Add more features as needed

The system is fully functional with database integration!

