<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Doctor Dashboard Section -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-lightbulb text-yellow-500"></i>
            <h2 class="text-xl font-semibold text-gray-800">Doctor Dashboard</h2>
        </div>
        <p class="text-gray-600">Quick overview of today's appointments and patient care.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Appointments -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Today's Appointments</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['today_appointments'] ?? 0) ?></p>
                    <?php if (isset($stats['appointments_change'])): ?>
                        <p class="text-sm <?= $stats['appointments_change'] >= 0 ? 'text-green-600' : 'text-red-600' ?> mt-1">
                            <?= $stats['appointments_change'] >= 0 ? '+' : '' ?><?= $stats['appointments_change'] ?> from yesterday
                        </p>
                    <?php endif; ?>
                </div>
                <i class="fas fa-calendar-check text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_patients'] ?? 0) ?></p>
                </div>
                <i class="fas fa-users text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Reports</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['pending_reports'] ?? 0) ?></p>
                    <?php if (isset($stats['pending_reports_change'])): ?>
                        <p class="text-sm <?= $stats['pending_reports_change'] >= 0 ? 'text-green-600' : 'text-red-600' ?> mt-1">
                            <?= $stats['pending_reports_change'] >= 0 ? '+' : '' ?><?= $stats['pending_reports_change'] ?> from yesterday
                        </p>
                    <?php endif; ?>
                </div>
                <i class="fas fa-file-medical text-orange-500 text-3xl"></i>
            </div>
        </div>

        <!-- Revenue This Month -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Revenue This Month</p>
                    <p class="text-3xl font-bold text-gray-800">₱<?= number_format($stats['revenue_this_month'] ?? 0, 2) ?></p>
                    <?php if (isset($stats['revenue_change'])): ?>
                        <p class="text-sm text-green-600 mt-1">
                            +<?= $stats['revenue_change'] ?>% from last month
                        </p>
                    <?php endif; ?>
                </div>
                <i class="fas fa-dollar-sign text-purple-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Today's Appointments Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Today's Appointments</h2>
        <p class="text-sm text-gray-600 mb-4">Your scheduled appointments for today.</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Time</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Notes</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($today_appointments)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No appointments scheduled for today
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($today_appointments as $appt): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <?= isset($appt['appointment_time']) ? date('h:i A', strtotime($appt['appointment_time'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4 font-semibold"><?= esc($appt['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($appt['appointment_type'] ?? 'General') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $appt['status'] ?? 'pending';
                                    $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc(ucfirst($status)) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-600"><?= esc(substr($appt['notes'] ?? 'N/A', 0, 30)) ?><?= strlen($appt['notes'] ?? '') > 30 ? '...' : '' ?></span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Appointments Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Recent Appointments</h2>
        <p class="text-sm text-gray-600 mb-4">Your recent appointments from the past week.</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Time</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Notes</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_appointments)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No recent appointments
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_appointments as $appt): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($appt['appointment_id'] ?? $appt['id']) ?></td>
                                <td class="py-3 px-4">
                                    <?= isset($appt['appointment_date']) ? date('M d, Y', strtotime($appt['appointment_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($appt['appointment_time']) ? date('h:i A', strtotime($appt['appointment_time'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4 font-semibold"><?= esc($appt['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($appt['appointment_type'] ?? 'General') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $appt['status'] ?? 'pending';
                                    $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc(ucfirst($status)) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-600"><?= esc(substr($appt['notes'] ?? 'N/A', 0, 30)) ?><?= strlen($appt['notes'] ?? '') > 30 ? '...' : '' ?></span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
