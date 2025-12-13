<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Dashboard Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Dashboard</h1>
                <p class="text-gray-600">High-level overview of hospital operations and system status</p>
                <p class="text-gray-500 text-sm mt-1">Date: <?= date('F d, Y') ?></p>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators (8 Cards) -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">System metrics and statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Patients -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Patients</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_patients'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Updated today</p>
            </div>

            <!-- Total Doctors -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Doctors</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_doctors'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-user-md text-green-500 text-2xl"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Updated today</p>
            </div>

            <!-- Total Nurses -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Nurses</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_nurses'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-user-nurse text-purple-500 text-2xl"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Updated today</p>
            </div>

            <!-- Today's Appointments -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Today's Appointments</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['today_appointments'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-calendar-day text-yellow-500 text-2xl"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Updated today</p>
            </div>

            <!-- Pending Appointments -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Pending Appointments</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['pending_appointments'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-clock text-orange-500 text-2xl"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Updated today</p>
            </div>

            <!-- Active Lab Tests -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Active Lab Tests</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['active_lab_tests'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-flask text-indigo-500 text-2xl"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Updated today</p>
            </div>

            <!-- Low Stock Medicines -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Low Stock Medicines</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['low_stock_medicines'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-pills text-red-500 text-2xl"></i>
                </div>
                <p class="text-xs text-gray-500 mt-2">Updated today</p>
            </div>

            <!-- Unpaid Bills -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-pink-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Unpaid Bills</p>
                        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['unpaid_bills'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-file-invoice-dollar text-pink-500 text-2xl"></i>
                </div>
                <p class="text-xs text-red-500 mt-2">Updated today</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Appointments Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Appointment statistics and recent bookings</h2>
            
            <!-- Statistics -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-blue-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">Upcoming</p>
                    <p class="text-2xl font-bold text-blue-600"><?= $appointments['upcoming'] ?? 0 ?></p>
                </div>
                <div class="bg-green-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">This Week</p>
                    <p class="text-2xl font-bold text-green-600"><?= $appointments['this_week'] ?? 0 ?></p>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Patient</th>
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Doctor</th>
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Date</th>
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Time</th>
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments['list'] ?? [])): ?>
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">No appointments found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments['list'] as $apt): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 px-3"><?= esc($apt['patient_name']) ?></td>
                                    <td class="py-2 px-3"><?= esc($apt['doctor_name']) ?></td>
                                    <td class="py-2 px-3"><?= date('M d, Y', strtotime($apt['date'])) ?></td>
                                    <td class="py-2 px-3"><?= date('h:i A', strtotime($apt['time'])) ?></td>
                                    <td class="py-2 px-3">
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                            <?= esc(ucfirst($apt['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Laboratory Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Lab test statistics and recent requests</h2>
            
            <!-- Statistics -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="bg-yellow-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600"><?= $lab['pending'] ?? 0 ?></p>
                </div>
                <div class="bg-green-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">Completed Today</p>
                    <p class="text-2xl font-bold text-green-600"><?= $lab['completed_today'] ?? 0 ?></p>
                </div>
                <div class="bg-red-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">Critical</p>
                    <p class="text-2xl font-bold text-red-600"><?= $lab['critical'] ?? 0 ?></p>
                </div>
            </div>

            <!-- Lab Tests Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Patient</th>
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Test Type</th>
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Priority</th>
                            <th class="text-left py-2 px-3 text-gray-700 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lab['tests'] ?? [])): ?>
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">No lab requests found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lab['tests'] as $test): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 px-3"><?= esc($test['patient_name']) ?></td>
                                    <td class="py-2 px-3"><?= esc($test['test_type']) ?></td>
                                    <td class="py-2 px-3">
                                        <span class="px-2 py-1 rounded text-xs font-semibold <?= $test['priority'] === 'high' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= esc(ucfirst($test['priority'])) ?>
                                        </span>
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                            <?= esc(ucfirst($test['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Pharmacy, Activity, and Billing -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pharmacy & Inventory Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Stock levels and inventory movements</h2>
            
            <!-- Statistics -->
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-yellow-50 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-600 mb-1">Low Stock</p>
                    <p class="text-xl font-bold text-yellow-600"><?= $pharmacy['low_stock'] ?? 0 ?></p>
                </div>
                <div class="bg-red-50 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-600 mb-1">Expiring Soon</p>
                    <p class="text-xl font-bold text-red-600"><?= $pharmacy['expiring_soon'] ?? 0 ?></p>
                </div>
                <div class="bg-blue-50 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-600 mb-1">Movements Today</p>
                    <p class="text-xl font-bold text-blue-600"><?= $pharmacy['movements_today'] ?? 0 ?></p>
                </div>
            </div>

            <!-- Stock Movements Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Medicine</th>
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Movement Type</th>
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Quantity Change</th>
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pharmacy['movements'] ?? [])): ?>
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500 text-xs">No stock movements found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pharmacy['movements'] as $movement): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 px-2 text-xs"><?= esc($movement['medicine']) ?></td>
                                    <td class="py-2 px-2 text-xs"><?= esc($movement['type']) ?></td>
                                    <td class="py-2 px-2 text-xs"><?= esc($movement['quantity']) ?></td>
                                    <td class="py-2 px-2 text-xs"><?= esc($movement['date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Chronological feed of latest system events</h2>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                <?php if (empty($recent_activities ?? [])): ?>
                    <p class="text-gray-500 text-center py-8 text-sm">No recent activities</p>
                <?php else: ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas <?= $activity['icon'] ?> text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800 text-sm"><?= esc($activity['message']) ?></p>
                                <p class="text-gray-500 text-xs mt-1"><?= esc($activity['time']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Billing & Payments Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Financial metrics and payment transactions</h2>
            
            <!-- Statistics -->
            <div class="grid grid-cols-1 gap-3 mb-4">
                <div class="bg-green-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600 mb-1">Revenue This Month</p>
                    <p class="text-2xl font-bold text-green-600">₱<?= number_format($billing['revenue_this_month'] ?? 0, 2) ?></p>
                </div>
                <div class="bg-red-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600 mb-1">Outstanding Invoices</p>
                    <p class="text-2xl font-bold text-red-600"><?= number_format($billing['outstanding_invoices'] ?? 0) ?></p>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Patient</th>
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Amount</th>
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Date</th>
                            <th class="text-left py-2 px-2 text-gray-700 font-semibold text-xs">Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($billing['payments'] ?? [])): ?>
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500 text-xs">No payments found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($billing['payments'] as $payment): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 px-2 text-xs"><?= esc($payment['patient_name']) ?></td>
                                    <td class="py-2 px-2 text-xs">₱<?= number_format($payment['amount'], 2) ?></td>
                                    <td class="py-2 px-2 text-xs"><?= esc($payment['date']) ?></td>
                                    <td class="py-2 px-2 text-xs"><?= esc($payment['method']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
