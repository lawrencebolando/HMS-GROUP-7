<?= $this->extend('templates/reception_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Reception Reports Section -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-chart-bar text-blue-500"></i>
            <h2 class="text-xl font-semibold text-gray-800">Reception Reports</h2>
        </div>
        <p class="text-sm text-gray-600">
            View patient registrations, appointments, and check-ins • Date: <?= date('F d, Y') ?>
        </p>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Report Filters</h3>
        <form method="GET" action="<?= base_url('reception/reports') ?>" class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select name="report_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="new_patients" <?= ($report_type ?? 'new_patients') === 'new_patients' ? 'selected' : '' ?>>New Patients</option>
                    <option value="appointments" <?= ($report_type ?? '') === 'appointments' ? 'selected' : '' ?>>Appointments</option>
                    <option value="checkins" <?= ($report_type ?? '') === 'checkins' ? 'selected' : '' ?>>Check-ins</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="<?= esc($date_from ?? date('Y-m-01')) ?>" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="<?= esc($date_to ?? date('Y-m-d')) ?>" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">New Patients</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($summary['new_patients'] ?? 0) ?></p>
                </div>
                <i class="fas fa-user-plus text-blue-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Appointments</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($summary['total_appointments'] ?? 0) ?></p>
                </div>
                <i class="fas fa-calendar-alt text-green-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Check-ins</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($summary['checkins'] ?? 0) ?></p>
                </div>
                <i class="fas fa-check-circle text-orange-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-users text-blue-500"></i>
            <h3 class="text-lg font-semibold text-gray-800">
                <?php if (($report_type ?? 'new_patients') === 'new_patients'): ?>
                    New Patients Report
                <?php elseif (($report_type ?? '') === 'appointments'): ?>
                    Appointments Report
                <?php else: ?>
                    Check-ins Report
                <?php endif; ?>
            </h3>
        </div>
        <p class="text-sm text-gray-600 mb-4">
            <?php if (($report_type ?? 'new_patients') === 'new_patients'): ?>
                Patient registrations from <?= date('M d, Y', strtotime($date_from ?? date('Y-m-01'))) ?> to <?= date('M d, Y', strtotime($date_to ?? date('Y-m-d'))) ?>
            <?php else: ?>
                Data from <?= date('M d, Y', strtotime($date_from ?? date('Y-m-01'))) ?> to <?= date('M d, Y', strtotime($date_to ?? date('Y-m-d'))) ?>
            <?php endif; ?>
        </p>
        
        <div class="overflow-x-auto">
            <?php if (($report_type ?? 'new_patients') === 'new_patients'): ?>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date Registered</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient ID</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Full Name</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Gender</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Age</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Contact</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient Type</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($report_data)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-500">
                                    No new patients found for the selected period
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($report_data as $patient): ?>
                                <?php
                                $fullName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
                                $age = $patient['date_of_birth'] ? date_diff(date_create($patient['date_of_birth']), date_create('today'))->y : 'N/A';
                                ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <?= isset($patient['created_at']) ? date('M d, Y', strtotime($patient['created_at'])) : 'N/A' ?>
                                    </td>
                                    <td class="py-3 px-4 font-semibold"><?= esc($patient['patient_id'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4 font-semibold"><?= esc($fullName) ?></td>
                                    <td class="py-3 px-4"><?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></td>
                                    <td class="py-3 px-4"><?= esc($age) ?> years</td>
                                    <td class="py-3 px-4"><?= esc($patient['phone'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4">Outpatient</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <?= esc(ucfirst($patient['status'] ?? 'active')) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center py-8 text-gray-500">Report type not implemented yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
