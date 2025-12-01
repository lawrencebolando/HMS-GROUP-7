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