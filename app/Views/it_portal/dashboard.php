<?= $this->extend('templates/it_portal_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        </div>
    </div>

    <!-- IT Dashboard Section -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">IT Dashboard</h2>
        <p class="text-gray-600 mb-4">Welcome back, IT Administrator. Here's your system overview for today.</p>
    </div>

    <!-- System Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- System Status -->
        <div class="bg-gray-100 rounded-lg shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-1">System Status</p>
            <p class="text-2xl font-bold text-blue-600"><?= esc($system_stats['system_status'] ?? 'Online') ?></p>
        </div>

        <!-- Active Users -->
        <div class="bg-gray-100 rounded-lg shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-1">Active Users</p>
            <p class="text-2xl font-bold text-blue-600"><?= number_format($system_stats['active_users'] ?? 0) ?></p>
        </div>

        <!-- Pending Tickets -->
        <div class="bg-gray-100 rounded-lg shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-1">Pending Tickets</p>
            <p class="text-2xl font-bold text-blue-600"><?= number_format($system_stats['pending_tickets'] ?? 0) ?></p>
        </div>

        <!-- Last Backup -->
        <div class="bg-gray-100 rounded-lg shadow-lg p-6">
            <p class="text-sm text-gray-600 mb-1">Last Backup</p>
            <p class="text-2xl font-bold text-blue-600"><?= esc($system_stats['last_backup'] ?? 'N/A') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="#" class="block text-blue-600 hover:text-blue-800 underline">System Status</a>
                <a href="#" class="block text-blue-600 hover:text-blue-800 underline">User Management</a>
                <a href="#" class="block text-blue-600 hover:text-blue-800 underline">Backup</a>
                <a href="#" class="block text-blue-600 hover:text-blue-800 underline">System Security Logs</a>
            </div>
        </div>

        <!-- Support Tickets -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Support Tickets</h2>
            <div class="text-center py-8 text-gray-500">
                <?php if (empty($support_tickets)): ?>
                    No support tickets available.
                <?php else: ?>
                    <!-- Support tickets list would go here -->
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">System Health</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Component</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Uptime</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Performance</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Last Check</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($system_health)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                No system health data available.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($system_health as $component): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($component['component'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $component['status'] ?? 'UNKNOWN';
                                    $statusClass = $status === 'ONLINE' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'DEGRADED' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-red-100 text-red-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc($status) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4"><?= esc($component['uptime'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($component['performance'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($component['last_check'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

