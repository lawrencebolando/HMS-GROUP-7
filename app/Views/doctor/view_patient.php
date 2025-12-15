<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Patient Details</h1>
        <a href="<?= base_url('doctor/appointments') ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
        </a>
    </div>

    <!-- Patient Information Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Patient Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Patient ID</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($patient['patient_id'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Full Name</p>
                <p class="text-lg font-medium text-gray-900"><?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Email</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($patient['email'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Phone</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($patient['phone'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Date of Birth</p>
                <p class="text-lg font-medium text-gray-900"><?= $patient['date_of_birth'] ? date('F d, Y', strtotime($patient['date_of_birth'])) : 'N/A' ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Gender</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($patient['gender'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Blood Group</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($patient['blood_group'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Room</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($patient['room'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <p class="text-lg font-medium text-gray-900">
                    <span class="px-2 py-1 rounded text-xs font-semibold <?= ($patient['status'] ?? '') === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                        <?= esc(ucfirst($patient['status'] ?? 'N/A')) ?>
                    </span>
                </p>
            </div>
            <div class="md:col-span-2 lg:col-span-3">
                <p class="text-sm text-gray-600 mb-1">Address</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($patient['address'] ?? 'N/A') ?></p>
            </div>
        </div>
    </div>

    <!-- Appointment History -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Appointment History</h2>
        <p class="text-sm text-gray-600 mb-4">All appointments with this patient</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Time</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Reason</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">No appointments found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $apt): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <?= isset($apt['appointment_date']) ? date('M d, Y', strtotime($apt['appointment_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($apt['appointment_time']) ? date('h:i A', strtotime($apt['appointment_time'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4"><?= esc($apt['reason'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold <?= 
                                        $apt['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($apt['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                        'bg-blue-100 text-blue-800') 
                                    ?>">
                                        <?= esc(ucfirst($apt['status'] ?? 'scheduled')) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4"><?= esc($apt['notes'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Medical History Section (Placeholder) -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Medical History</h2>
        <p class="text-sm text-gray-600 mb-4">Patient's medical records and history</p>
        
        <?php if (empty($medical_history)): ?>
            <p class="text-gray-500 text-center py-8">No medical history available</p>
        <?php else: ?>
            <div class="space-y-4">
                <!-- Medical history items would go here -->
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

