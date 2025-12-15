<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Lab Test Requests</h2>
        <p class="text-gray-600">Welcome, Dr. <?= esc($doctor_name) ?>, M.D. • Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- New Lab Test Request Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">New Lab Test Request</h3>

        <form id="labRequestForm" action="<?= base_url('doctor/labs/store') ?>" method="POST">
            <!-- Patient Information -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Patient Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Patient *</label>
                        <select id="labPatientSelect" name="patient_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select patient</option>
                            <?php foreach ($patients as $patient): ?>
                                <?php $fullName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')); ?>
                                <option value="<?= esc($patient['id']) ?>" 
                                        data-age="<?= $patient['date_of_birth'] ? date_diff(date_create($patient['date_of_birth']), date_create('today'))->y : '' ?>"
                                        data-gender="<?= esc($patient['gender'] ?? '') ?>">
                                    <?= esc($fullName) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                        <input type="text" id="labPatientAge" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="-" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <input type="text" id="labPatientGender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="-" readonly>
                    </div>
                </div>
            </div>

            <!-- Test Information -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Test Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Type *</label>
                        <select name="test_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select test type</option>
                            <option>Blood Test</option>
                            <option>Urine Test</option>
                            <option>X-Ray</option>
                            <option>CT Scan</option>
                            <option>MRI</option>
                            <option>ECG</option>
                            <option>Ultrasound</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes / Instructions (Optional)</label>
                    <textarea 
                        name="notes"
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Additional notes or special instructions for the lab staff..."
                    ></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Submit Lab Request
                </button>
            </div>
        </form>
    </div>

    <!-- Saved Lab Requests List -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-list mr-2"></i>Saved Lab Test Requests
        </h3>
        
        <?php if (empty($lab_requests)): ?>
            <p class="text-gray-500 text-center py-8">No lab test requests submitted yet. Create your first request above.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($lab_requests as $request): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= esc($request['lab_request_id'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($request['patient_name'] ?? 'Unknown') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($request['test_type'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= 
                                        strtolower($request['priority'] ?? '') === 'emergency' ? 'bg-red-100 text-red-800' : 
                                        (strtolower($request['priority'] ?? '') === 'urgent' ? 'bg-orange-100 text-orange-800' : 
                                        'bg-blue-100 text-blue-800') 
                                    ?>">
                                        <?= esc(ucfirst($request['priority'] ?? 'normal')) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= isset($request['requested_date']) ? date('M d, Y', strtotime($request['requested_date'])) : 'N/A' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= 
                                        ($request['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                                        (($request['status'] ?? '') === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                        (($request['status'] ?? '') === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-blue-100 text-blue-800')) 
                                    ?>">
                                        <?= esc(ucfirst($request['status'] ?? 'pending')) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="<?= base_url('doctor/labs/view/' . $request['id']) ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Patient selection handler
    document.getElementById('labPatientSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('labPatientAge').value = selectedOption.dataset.age || '-';
        document.getElementById('labPatientGender').value = selectedOption.dataset.gender || '-';
    });
</script>
<?= $this->endSection() ?>

