<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Reports</h1>
    
    <!-- Report Filters -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Report Filters</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select name="report_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="overview" <?= $report_type === 'overview' ? 'selected' : '' ?>>Overview</option>
                    <option value="patients" <?= $report_type === 'patients' ? 'selected' : '' ?>>Patients</option>
                    <option value="appointments" <?= $report_type === 'appointments' ? 'selected' : '' ?>>Appointments</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="<?= esc($date_from) ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="<?= esc($date_to) ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Generate Report</button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_patients'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Appointments</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_appointments'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Doctors</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_doctors'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Departments</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_departments'] ?? 0) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Data Table -->
    <?php if ($report_type !== 'overview' && !empty($report_data)): ?>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <?= ucfirst($report_type) ?> Report (<?= date('M d, Y', strtotime($date_from)) ?> - <?= date('M d, Y', strtotime($date_to)) ?>)
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <?php if ($report_type === 'patients'): ?>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient ID</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Name</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Email</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Phone</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date Created</th>
                            <?php elseif ($report_type === 'appointments'): ?>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Appointment ID</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Time</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                                <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_data as $item): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <?php if ($report_type === 'patients'): ?>
                                    <td class="py-3 px-4"><?= esc($item['patient_id'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4"><?= esc(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? '')) ?></td>
                                    <td class="py-3 px-4"><?= esc($item['email'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4"><?= esc($item['phone'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4"><?= isset($item['created_at']) ? date('M d, Y', strtotime($item['created_at'])) : 'N/A' ?></td>
                                <?php elseif ($report_type === 'appointments'): ?>
                                    <td class="py-3 px-4"><?= esc($item['appointment_id'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4"><?= isset($item['appointment_date']) ? date('M d, Y', strtotime($item['appointment_date'])) : 'N/A' ?></td>
                                    <td class="py-3 px-4"><?= isset($item['appointment_time']) ? date('h:i A', strtotime($item['appointment_time'])) : 'N/A' ?></td>
                                    <td class="py-3 px-4"><?= esc($item['patient_id'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                            <?= esc(ucfirst($item['status'] ?? 'scheduled')) ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif ($report_type === 'overview'): ?>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">System Overview</h3>
            <p class="text-gray-600">Select a report type and date range to generate detailed reports.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <p class="text-gray-600 text-center py-8">No data found for the selected criteria.</p>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

