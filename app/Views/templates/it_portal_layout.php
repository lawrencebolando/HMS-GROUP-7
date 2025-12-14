<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'IT Portal' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .nav-active {
            background-color: #3b82f6;
            color: white;
            border-radius: 8px;
        }
        .nav-item {
            color: white;
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php
    $currentPath = uri_string();
    ?>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">IT Portal</h1>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="<?= base_url('it/dashboard') ?>" class="flex items-center px-4 py-3 nav-item <?= strpos($currentPath, 'it/dashboard') !== false ? 'nav-active' : '' ?> rounded-lg">
                            <i class="fas fa-th-large w-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-server w-5 mr-3"></i>
                            <span>System Status</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-users-cog w-5 mr-3"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-database w-5 mr-3"></i>
                            <span>Backup</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-shield-alt w-5 mr-3"></i>
                            <span>Security</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-ticket-alt w-5 mr-3"></i>
                            <span>Support Tickets</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-cog w-5 mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('logout') ?>" class="flex items-center px-4 py-3 nav-item rounded-lg">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-8">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
</body>
</html>

