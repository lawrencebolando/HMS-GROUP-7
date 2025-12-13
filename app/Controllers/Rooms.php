<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;

class Rooms extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('is_logged_in') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('login')->with('error', 'Access denied. Admin only.');
        }

        // Check if rooms table exists
        $tableExists = $this->db->tableExists('rooms');
        
        // Get statistics
        $stats = $this->getRoomStats($tableExists);
        
        // Get rooms list
        $rooms = $this->getRooms($tableExists);
        
        $data = [
            'title' => 'Rooms Management',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'stats' => $stats,
            'rooms' => $rooms
        ];
        
        return view('rooms/index', $data);
    }

    private function getRoomStats($tableExists)
    {
        if (!$tableExists) {
            return [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'occupied_rooms' => 0,
                'total_beds' => 0,
                'available_beds' => 0,
                'occupied_beds' => 0
            ];
        }

        try {
            $totalRooms = $this->db->table('rooms')->countAllResults();
            
            $availableRooms = $this->db->table('rooms')
                ->where('status', 'available')
                ->countAllResults();
            
            $occupiedRooms = $this->db->table('rooms')
                ->where('status', 'occupied')
                ->countAllResults();
            
            // Get bed statistics
            $totalBeds = $this->db->table('rooms')
                ->selectSum('bed_count')
                ->get()
                ->getRowArray();
            $totalBeds = $totalBeds['bed_count'] ?? 0;
            
            $availableBeds = $this->db->table('rooms')
                ->selectSum('available_beds')
                ->get()
                ->getRowArray();
            $availableBeds = $availableBeds['available_beds'] ?? 0;
            
            $occupiedBeds = $totalBeds - $availableBeds;

            return [
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms,
                'occupied_rooms' => $occupiedRooms,
                'total_beds' => $totalBeds,
                'available_beds' => $availableBeds,
                'occupied_beds' => $occupiedBeds
            ];
        } catch (\Exception $e) {
            return [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'occupied_rooms' => 0,
                'total_beds' => 0,
                'available_beds' => 0,
                'occupied_beds' => 0
            ];
        }
    }

    private function getRooms($tableExists)
    {
        if (!$tableExists) {
            return [];
        }

        try {
            $rooms = $this->db->table('rooms')
                ->orderBy('room_number', 'ASC')
                ->get()
                ->getResultArray();

            return $rooms;
        } catch (\Exception $e) {
            return [];
        }
    }
}

