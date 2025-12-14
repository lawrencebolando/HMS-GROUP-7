<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;

class ITPortal extends BaseController
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
        $userRole = $this->session->get('user_role');
        if (!$this->session->get('is_logged_in') || !in_array($userRole, ['it', 'it_staff', 'it_admin'])) {
            return redirect()->to('login')->with('error', 'Access denied. IT staff only.');
        }

        $systemStats = $this->getSystemStats();
        $systemHealth = $this->getSystemHealth();
        $supportTickets = $this->getSupportTickets();

        $data = [
            'title' => 'IT Portal - Dashboard',
            'user' => [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ],
            'system_stats' => $systemStats,
            'system_health' => $systemHealth,
            'support_tickets' => $supportTickets
        ];

        return view('it_portal/dashboard', $data);
    }

    private function getSystemStats()
    {
        // Mock system stats
        return [
            'system_status' => 'Online',
            'active_users' => 24,
            'pending_tickets' => 5,
            'last_backup' => '2 hours ago'
        ];
    }

    private function getSystemHealth()
    {
        // Mock system health data
        return [
            [
                'component' => 'Database Server',
                'status' => 'ONLINE',
                'uptime' => '99.9%',
                'performance' => 'Excellent',
                'last_check' => '2 minutes ago'
            ],
            [
                'component' => 'Web Server',
                'status' => 'ONLINE',
                'uptime' => '99.8%',
                'performance' => 'Good',
                'last_check' => '1 minute ago'
            ],
            [
                'component' => 'File Storage',
                'status' => 'ONLINE',
                'uptime' => '100%',
                'performance' => 'Excellent',
                'last_check' => '3 minutes ago'
            ],
            [
                'component' => 'Email Server',
                'status' => 'DEGRADED',
                'uptime' => '95.2%',
                'performance' => 'Fair',
                'last_check' => '5 minutes ago'
            ]
        ];
    }

    private function getSupportTickets()
    {
        // Mock data - would need support_tickets table
        return [];
    }
}

