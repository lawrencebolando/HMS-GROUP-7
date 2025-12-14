<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('update-admin-name', 'UpdateAdmin::index');

// Pharmacy Management
$routes->get('pharmacy', 'Pharmacy::index');

// Patient Management Routes
$routes->get('patients', 'Patients::index');
$routes->get('patients/create', 'Patients::create');
$routes->post('patients/store', 'Patients::store');
$routes->get('patients/edit/(:num)', 'Patients::edit/$1');
$routes->post('patients/update/(:num)', 'Patients::update/$1');
$routes->get('patients/delete/(:num)', 'Patients::delete/$1');

// Doctor Management Routes
$routes->get('doctors', 'Doctors::index');
$routes->get('doctors/create', 'Doctors::create');
$routes->post('doctors/store', 'Doctors::store');
$routes->get('doctors/edit/(:num)', 'Doctors::edit/$1');
$routes->post('doctors/update/(:num)', 'Doctors::update/$1');
$routes->get('doctors/delete/(:num)', 'Doctors::delete/$1');

// Nurse Management Routes
$routes->get('nurses', 'Nurses::index');

// Admissions Management Routes
$routes->get('admissions', 'Admissions::index');

// Walk-In Management Routes
$routes->get('walk-in', 'WalkIn::index');
$routes->post('walk-in/store', 'WalkIn::store');

// Rooms Management Routes
$routes->get('rooms', 'Rooms::index');

// Billing & Payments Routes
$routes->get('billing', 'Billing::index');
$routes->post('billing/create-bills', 'Billing::createBills');
$routes->get('billing/export', 'Billing::export');

// Laboratory Routes
$routes->get('laboratory', 'Laboratory::index');

// Portal Routes
$routes->get('nurse/dashboard', 'NursePortal::index');
$routes->get('lab/dashboard', 'LabPortal::index');
$routes->get('accounts/dashboard', 'AccountsPortal::index');
$routes->get('it/dashboard', 'ITPortal::index');

// Doctor Types Routes
$routes->get('doctor-types', 'DoctorTypes::index');
$routes->get('doctor-types/create', 'DoctorTypes::create');
$routes->post('doctor-types/store', 'DoctorTypes::store');
$routes->get('doctor-types/edit/(:num)', 'DoctorTypes::edit/$1');
$routes->post('doctor-types/update/(:num)', 'DoctorTypes::update/$1');
$routes->get('doctor-types/delete/(:num)', 'DoctorTypes::delete/$1');
// Department Management Routes
$routes->get('departments', 'Departments::index');
$routes->post('departments/store', 'Departments::store');
$routes->post('departments/update/(:num)', 'Departments::update/$1');
$routes->get('departments/delete/(:num)', 'Departments::delete/$1');

// Appointment Management Routes
$routes->get('appointments', 'Appointments::index');
$routes->post('appointments/store', 'Appointments::store');
$routes->post('appointments/update/(:num)', 'Appointments::update/$1');
$routes->get('appointments/delete/(:num)', 'Appointments::delete/$1');

// Doctor Portal Routes
$routes->group('doctor', function($routes) {
    $routes->get('dashboard', 'Doctor::dashboard');
    $routes->get('patients', 'Doctor::patients');
    $routes->get('appointments', 'Doctor::appointments');
    $routes->get('inpatients', 'Doctor::inpatients');
    $routes->get('prescriptions', 'Doctor::prescriptions');
    $routes->get('schedule', 'Doctor::schedule');
    $routes->post('schedule/add', 'Doctor::addSchedule');
    $routes->post('schedule/update', 'Doctor::updateSchedule');
    $routes->get('consultations', 'Doctor::consultations');
    $routes->get('labs', 'Doctor::labs');
    $routes->get('settings', 'Doctor::settings');
    $routes->get('reports', 'Doctor::reports');
});

// Reception Portal Routes
$routes->group('reception', function($routes) {
    $routes->get('dashboard', 'Reception::dashboard');
    $routes->get('patients', 'Reception::patients');
    $routes->get('appointments', 'Reception::appointments');
    $routes->get('followups', 'Reception::followUps');
    $routes->get('follow-ups', 'Reception::followUps'); // Alias with hyphen
    $routes->get('reports', 'Reception::reports');
    $routes->get('settings', 'Reception::settings');
});

// Receptionist alias routes (for backward compatibility)
$routes->get('receptionist/dashboard', 'Reception::dashboard');
$routes->get('receptionist/patients', 'Reception::patients');
$routes->get('receptionist/appointments', 'Reception::appointments');
$routes->get('receptionist/followups', 'Reception::followUps');
$routes->get('receptionist/follow-ups', 'Reception::followUps'); // Alias with hyphen
$routes->get('receptionist/reports', 'Reception::reports');
$routes->get('receptionist/settings', 'Reception::settings');
