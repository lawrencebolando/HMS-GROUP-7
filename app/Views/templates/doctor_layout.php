<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Doctor Portal - HMS' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .nav-active {
            background-color: rgba(59, 130, 246, 0.3);
            border-left: 4px solid #3b82f6;
        }
        .nav-item {
            color: white;
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php
    $currentPath = uri_string();
    ?>
    <div class="flex h-screen overflow-hidden">
        <!-- Left Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white sidebar-transition">
            <!-- Doctor Portal Header -->
            <div class="p-4 border-b border-blue-700">
                <h2 class="text-xl font-bold">Doctor Portal</h2>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <ul class="space-y-1">
                    <li>
                        <a href="<?= base_url('doctor/dashboard') ?>" class="flex items-center px-4 py-3 nav-item <?= ($currentPath === 'doctor/dashboard' || $currentPath === 'doctor') ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-th-large w-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/patients') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/patients') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-users w-5 mr-3"></i>
                            <span>Patient Records</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/appointments') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/appointments') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-calendar-alt w-5 mr-3"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-bed w-5 mr-3"></i>
                            <span>Inpatients</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/prescriptions') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/prescriptions') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-prescription w-5 mr-3"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/labs') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/labs') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-flask w-5 mr-3"></i>
                            <span>Lab Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/consultations') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/consultations') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-stethoscope w-5 mr-3"></i>
                            <span>Consultations</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/schedule') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/schedule') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-calendar-check w-5 mr-3"></i>
                            <span>My Schedule</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/reports') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/reports') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-file-medical w-5 mr-3"></i>
                            <span>Medical Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctor/settings') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctor/settings') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-cog w-5 mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="pt-4 border-t border-blue-700">
                        <a href="<?= base_url('logout') ?>" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-flag mr-2"></i>
                            <span>Doctor</span>
                        </div>
                        <div class="relative group">
                            <div class="flex items-center text-gray-600 cursor-pointer" onclick="toggleUserMenu()">
                                <i class="fas fa-user-circle mr-2 text-xl"></i>
                                <span><?= esc($user['name'] ?? 'User') ?></span>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </div>
                            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden border border-gray-200">
                                <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-white p-6">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script>
        // User menu toggle
        function toggleUserMenu() {
            const userMenu = document.getElementById('userMenu');
            if (userMenu) {
                userMenu.classList.toggle('hidden');
            }
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userMenuTrigger = event.target.closest('.group');
            
            if (userMenu && !userMenuTrigger) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

