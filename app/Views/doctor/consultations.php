<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Consultations</h2>
        <p class="text-gray-600">View your completed patient consultations • Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-2">Total Consultations</p>
                <p class="text-3xl font-bold text-gray-800"><?= esc($stats['total']) ?></p>
                <p class="text-gray-500 text-sm mt-1">All time</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-2">This Month</p>
                <p class="text-3xl font-bold text-gray-800"><?= esc($stats['this_month']) ?></p>
                <p class="text-gray-500 text-sm mt-1">Current month</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-gray-600 text-sm mb-2">This Week</p>
                <p class="text-3xl font-bold text-gray-800"><?= esc($stats['this_week']) ?></p>
                <p class="text-gray-500 text-sm mt-1">Current week</p>
            </div>
        </div>
    </div>

    <!-- Consultation History -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Consultation History</h3>
        <p class="text-gray-600 mb-4">Your completed patient consultations</p>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prescription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($consultations)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No consultations found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($consultations as $consultation): ?>
                            <?php
                            $patient = $consultation['patient'] ?? null;
                            $patientName = $patient ? trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) : 'Unknown';
                            $age = $patient && isset($patient['date_of_birth']) && $patient['date_of_birth'] ? date_diff(date_create($patient['date_of_birth']), date_create('today'))->y : 'N/A';
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= date('M d, Y', strtotime($consultation['appointment_date'])) ?></div>
                                    <div class="text-sm text-gray-500"><?= date('g:i A', strtotime($consultation['appointment_time'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= esc($patientName) ?></div>
                                    <div class="text-sm text-gray-500">Age: <?= esc($age) ?> • <?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc(ucfirst($consultation['reason'] ?? 'Consultation')) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">COMPLETED</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($consultation['notes'] ?? '-') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No prescription</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
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

