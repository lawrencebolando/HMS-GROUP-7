<?php

namespace App\Controllers;

class Pharmacy extends BaseController
{
    public function index()
    {
        $session = session();

        // Allow both admin and pharmacy staff to view
        if (!$session->get('is_logged_in')) {
            return redirect()->to('login')->with('error', 'Please login to continue');
        }

        // Allow admin to view (but not add medications)
        // Only pharmacy staff can add medications (when pharmacy role is implemented)
        $userRole = $session->get('user_role');
        if ($userRole !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Demo data – replace with real DB queries later
        $data = [
            'title' => 'Pharmacy Management',
            'user' => [
                'name'  => $session->get('user_name'),
                'email' => $session->get('user_email'),
                'role'  => $session->get('user_role'),
            ],
            'summary' => [
                'total_medications'    => 3,
                'low_stock_items'      => 2,
                'pending_prescriptions'=> 1,
                'total_dispensed'      => 1,
            ],
            'medications' => [
                [
                    'name'          => 'Amoxicillin 500mg',
                    'generic'       => 'Amoxicillin',
                    'category'      => 'Antibiotic',
                    'stock_level'   => 120,
                    'min_stock'     => 50,
                    'unit_price'    => 2.50,
                    'expiry_date'   => '2025-06-15',
                    'status'        => 'in_stock',
                ],
                [
                    'name'          => 'Ibuprofen 400mg',
                    'generic'       => 'Ibuprofen',
                    'category'      => 'Analgesic',
                    'stock_level'   => 25,
                    'min_stock'     => 30,
                    'unit_price'    => 1.80,
                    'expiry_date'   => '2024-12-30',
                    'status'        => 'low_stock',
                ],
                [
                    'name'          => 'Aspirin 325mg',
                    'generic'       => 'Acetylsalicylic Acid',
                    'category'      => 'Analgesic',
                    'stock_level'   => 0,
                    'min_stock'     => 40,
                    'unit_price'    => 1.20,
                    'expiry_date'   => '2025-03-20',
                    'status'        => 'out_of_stock',
                ],
            ],
        ];

        return view('pharmacy/index', $data);
    }
}


