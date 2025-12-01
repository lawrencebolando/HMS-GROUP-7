<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'HealthMS - Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .sidebar-hidden {
            margin-left: -250px;
        }
        .nav-active {
            background-color: #3b82f6;
            color: white;
            border: 2px solid white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .nav-item {
            color: white;
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: white;
            color: #3b82f6;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background: #3b82f6;
            color: white;
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        .btn-link {
            color: #3b82f6;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php
    $userModel = new \App\Models\UserModel();
    $currentPath = uri_string();
    ?>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white sidebar-transition">
            <!-- User Profile Section -->
            <div class="p-4 border-b border-gray-700">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-2">
                        <i class="fas fa-plus text-white text-2xl"></i>
                    </div>
                    <div class="text-center">
                        <div class="font-semibold"><?= esc($user['name'] ?? 'User') ?></div>
                        <div class="flex items-center justify-center mt-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-sm text-gray-300"><?= esc(ucfirst($user['role'] ?? 'User')) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <div class="text-xs font-semibold text-gray-400 uppercase mb-4">MAIN NAVIGATION</div>
                <ul class="space-y-2">
                    <li>
                        <a href="<?= base_url('dashboard') ?>" class="flex items-center px-4 py-3 nav-item <?= ($currentPath === 'dashboard' || $currentPath === '') ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-th-large w-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('patients') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'patients') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-users w-5 mr-3"></i>
                            <span>Patient Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('doctors') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'doctors') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-user-md w-5 mr-3"></i>
                            <span>Doctor Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('departments') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'departments') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-building w-5 mr-3"></i>
                            <span>Departments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('appointments') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'appointments') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-calendar-alt w-5 mr-3"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-file-medical w-5 mr-3"></i>
                            <span>Medical Records</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-credit-card w-5 mr-3"></i>
                            <span>Billing & Invoices</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-pills w-5 mr-3"></i>
                            <span>Pharmacy</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-flask w-5 mr-3"></i>
                            <span>Laboratory</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-exclamation-triangle w-5 mr-3"></i>
                            <span>Emergency</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-chart-bar w-5 mr-3"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-bell w-5 mr-3"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-wrench w-5 mr-3"></i>
                            <span>Equipment</span>
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
                        <button id="sidebarToggle" class="mr-4 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-bold text-gray-800">HealthMS</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-flag mr-2"></i>
                            <span>Admin</span>
                        </div>
                        <div class="relative group">
                            <div class="flex items-center text-gray-600 cursor-pointer">
                                <i class="fas fa-user-circle mr-2 text-xl"></i>
                                <span><?= esc($user['name'] ?? 'User') ?></span>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </div>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                                <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                <?= $this->renderSection('content') ?>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="flex justify-between text-sm text-gray-600">
                    <div>Copyright © 2016-2017 Suvidha Software Solutions.</div>
                    <div>HMS 1.0</div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-hidden');
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

