<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Lab Test Requests</h2>
        <p class="text-gray-600">Welcome, Dr. <?= esc($doctor_name) ?>, M.D. • Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- New Lab Test Request Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">New Lab Test Request</h3>

        <form id="labRequestForm">
            <!-- Patient Information -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Patient Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Patient *</label>
                        <select id="labPatientSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Normal</option>
                            <option>Urgent</option>
                            <option>Emergency</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes / Instructions (Optional)</label>
                    <textarea 
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

