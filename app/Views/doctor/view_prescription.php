<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Prescription Details</h1>
        <a href="<?= base_url('doctor/prescriptions') ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Prescriptions
        </a>
    </div>

    <!-- Prescription Information -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Prescription Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Prescription ID</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($prescription['prescription_id'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Patient</p>
                <p class="text-lg font-medium text-gray-900">
                    <?= $patient ? esc($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown' ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Prescribed Date</p>
                <p class="text-lg font-medium text-gray-900">
                    <?= isset($prescription['prescribed_date']) ? date('F d, Y', strtotime($prescription['prescribed_date'])) : 'N/A' ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <p class="text-lg font-medium text-gray-900">
                    <span class="px-2 py-1 rounded text-xs font-semibold <?= 
                        ($prescription['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                        (($prescription['status'] ?? '') === 'cancelled' ? 'bg-red-100 text-red-800' : 
                        'bg-blue-100 text-blue-800') 
                    ?>">
                        <?= esc(ucfirst($prescription['status'] ?? 'active')) ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Diagnosis -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Diagnosis / Notes</h2>
        <p class="text-gray-700 whitespace-pre-wrap"><?= esc($prescription['diagnosis'] ?? 'N/A') ?></p>
        <?php if (!empty($prescription['notes'])): ?>
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-600 mb-1">Additional Notes</p>
                <p class="text-gray-700 whitespace-pre-wrap"><?= esc($prescription['notes']) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Medications -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Medications</h2>
        
        <?php if (empty($medications)): ?>
            <p class="text-gray-500 text-center py-8">No medications in this prescription</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Medication</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Dosage</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Frequency</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Meal Instruction</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Duration</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Quantity</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medications as $med): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($med['medication_name'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($med['dosage'] ?? '-') ?></td>
                                <td class="py-3 px-4"><?= esc($med['frequency'] ?? '-') ?></td>
                                <td class="py-3 px-4"><?= esc($med['meal_instruction'] ?? '-') ?></td>
                                <td class="py-3 px-4"><?= esc($med['duration'] ?? '-') ?></td>
                                <td class="py-3 px-4"><?= esc($med['quantity'] ?? '1') ?></td>
                                <td class="py-3 px-4"><?= esc($med['notes'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

