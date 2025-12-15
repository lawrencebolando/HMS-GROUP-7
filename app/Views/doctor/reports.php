<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Medical Reports</h2>
        <p class="text-gray-600">View your appointments, prescriptions, and lab requests • Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Report Filters</h3>
        <form method="GET" action="<?= base_url('doctor/reports') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select name="report_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="appointments" <?= ($report_type ?? 'appointments') === 'appointments' ? 'selected' : '' ?>>Appointments</option>
                    <option value="prescriptions" <?= ($report_type ?? '') === 'prescriptions' ? 'selected' : '' ?>>Prescriptions</option>
                    <option value="lab_requests" <?= ($report_type ?? '') === 'lab_requests' ? 'selected' : '' ?>>Lab Requests</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="<?= esc($date_from ?? date('Y-m-01')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="<?= esc($date_to ?? date('Y-m-d')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Apply Filters</button>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-2">Total Appointments</p>
                <p class="text-3xl font-bold text-gray-800"><?= $stats['total_appointments'] ?? 0 ?></p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-2">Total Prescriptions</p>
                <p class="text-3xl font-bold text-gray-800"><?= $stats['total_prescriptions'] ?? 0 ?></p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-2">Total Lab Requests</p>
                <p class="text-3xl font-bold text-gray-800"><?= $stats['total_lab_requests'] ?? 0 ?></p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-2">Total Patients</p>
                <p class="text-3xl font-bold text-gray-800"><?= $stats['total_patients'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <!-- Appointments Report -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Appointments Report</h3>
        <p class="text-gray-600 mb-4">Appointments from <?= isset($date_from) ? date('M d, Y', strtotime($date_from)) : date('M d, Y', strtotime(date('Y-m-01'))) ?> to <?= isset($date_to) ? date('M d, Y', strtotime($date_to)) : date('M d, Y') ?></p>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No appointments found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <?php
                            $patient = $appointment['patient'] ?? null;
                            $patientName = $patient ? trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) : 'Unknown';
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M d, Y', strtotime($appointment['appointment_date'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($patientName) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($patient['patient_id'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc(strtolower($appointment['reason'] ?? 'consultation')) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $status = strtolower($appointment['status'] ?? 'scheduled');
                                    $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-blue-100 text-blue-800');
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                        <?= strtoupper($appointment['status'] ?? 'SCHEDULED') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($appointment['notes'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

