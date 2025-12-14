<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Inpatients Section -->
    <div class="mb-6">
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-center space-x-2 mb-2">
                <i class="fas fa-bed text-purple-600"></i>
                <h2 class="text-xl font-semibold text-gray-800">Inpatients</h2>
            </div>
            <p class="text-gray-600">
                Your current inpatient assignments • Date: <?= date('F d, Y') ?>
            </p>
        </div>
    </div>

    <!-- Inpatient List Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Inpatient List</h2>
        <p class="text-sm text-gray-600 mb-4">Patients admitted under your care</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Admission Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Room</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Case Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Reason</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inpatients)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No inpatients assigned
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inpatients as $inpatient): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <?= isset($inpatient['admission_date']) ? date('M d, Y', strtotime($inpatient['admission_date'])) : 'N/A' ?>
                                    <?php if (isset($inpatient['admission_time'])): ?>
                                        <div class="text-xs text-gray-500"><?= date('h:i A', strtotime($inpatient['admission_time'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 font-semibold"><?= esc($inpatient['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($inpatient['room'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($inpatient['case_type'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-600"><?= esc(substr($inpatient['reason'] ?? 'N/A', 0, 40)) ?><?= strlen($inpatient['reason'] ?? '') > 40 ? '...' : '' ?></span>
                                </td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $inpatient['status'] ?? 'active';
                                    $statusClass = $status === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'discharged' ? 'bg-gray-100 text-gray-800' : 
                                                   'bg-blue-100 text-blue-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc(ucfirst($status)) ?>
                                    </span>
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

