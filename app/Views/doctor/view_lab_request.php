<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Lab Test Request Details</h1>
        <a href="<?= base_url('doctor/labs') ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Lab Requests
        </a>
    </div>

    <!-- Lab Request Information -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Request Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Request ID</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($lab_request['lab_request_id'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Patient</p>
                <p class="text-lg font-medium text-gray-900">
                    <?= $patient ? esc($patient['first_name'] . ' ' . $patient['last_name']) : 'Unknown' ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Test Type</p>
                <p class="text-lg font-medium text-gray-900"><?= esc($lab_request['test_type'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Priority</p>
                <p class="text-lg font-medium text-gray-900">
                    <span class="px-2 py-1 rounded text-xs font-semibold <?= 
                        strtolower($lab_request['priority'] ?? '') === 'emergency' ? 'bg-red-100 text-red-800' : 
                        (strtolower($lab_request['priority'] ?? '') === 'urgent' ? 'bg-orange-100 text-orange-800' : 
                        'bg-blue-100 text-blue-800') 
                    ?>">
                        <?= esc(ucfirst($lab_request['priority'] ?? 'normal')) ?>
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Requested Date</p>
                <p class="text-lg font-medium text-gray-900">
                    <?= isset($lab_request['requested_date']) ? date('F d, Y', strtotime($lab_request['requested_date'])) : 'N/A' ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <p class="text-lg font-medium text-gray-900">
                    <span class="px-2 py-1 rounded text-xs font-semibold <?= 
                        ($lab_request['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                        (($lab_request['status'] ?? '') === 'cancelled' ? 'bg-red-100 text-red-800' : 
                        (($lab_request['status'] ?? '') === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                        'bg-blue-100 text-blue-800')) 
                    ?>">
                        <?= esc(ucfirst($lab_request['status'] ?? 'pending')) ?>
                    </span>
                </p>
            </div>
            <?php if (!empty($lab_request['completed_date'])): ?>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Completed Date</p>
                    <p class="text-lg font-medium text-gray-900">
                        <?= date('F d, Y', strtotime($lab_request['completed_date'])) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Notes / Instructions -->
    <?php if (!empty($lab_request['notes'])): ?>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Notes / Instructions</h2>
            <p class="text-gray-700 whitespace-pre-wrap"><?= esc($lab_request['notes']) ?></p>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

