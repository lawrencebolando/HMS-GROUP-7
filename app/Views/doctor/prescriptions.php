<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Doctor Dashboard - Prescription Management</h2>
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

    <!-- New Prescription Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-file-prescription mr-2"></i>New Prescription Form
        </h3>

        <form id="prescriptionForm" action="<?= base_url('doctor/prescriptions/store') ?>" method="POST">
            <!-- Patient Information -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Patient Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Patient *</label>
                        <select id="patientSelect" name="patient_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                        <input type="text" id="patientAge" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="-" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                        <input type="text" id="patientGender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="-" readonly>
                    </div>
                </div>
            </div>

            <!-- Diagnosis / Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis / Notes *</label>
                <textarea 
                    id="diagnosis" 
                    name="diagnosis"
                    rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Enter diagnosis, symptoms, or general instructions..."
                    required
                ></textarea>
            </div>

            <!-- Medication Table -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Medication Table</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medication</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosage</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frequency</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Meal Instruction</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="medicationTableBody">
                            <tr>
                                <td class="px-4 py-3">
                                    <input type="text" name="medications[0][medication_name]" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Medication name" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="medications[0][dosage]" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="e.g. 1 capsule">
                                </td>
                                <td class="px-4 py-3">
                                    <select name="medications[0][frequency]" class="w-full px-3 py-2 border border-gray-300 rounded">
                                        <option value="">Select...</option>
                                        <option>Once daily</option>
                                        <option>Twice daily</option>
                                        <option>Three times daily</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <select name="medications[0][meal_instruction]" class="w-full px-3 py-2 border border-gray-300 rounded">
                                        <option value="">Select</option>
                                        <option>Before meal</option>
                                        <option>After meal</option>
                                        <option>With meal</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="medications[0][duration]" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="e.g. 7 days">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="medications[0][quantity]" class="w-full px-3 py-2 border border-gray-300 rounded" value="1" min="1">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="medications[0][medication_notes]" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Additional notes...">
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button" class="text-red-600 hover:text-red-800" onclick="removeMedicationRow(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" onclick="addMedicationRow()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Add New Medication
                </button>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Prescription
                </button>
            </div>
        </form>
    </div>

    <!-- Saved Prescriptions List -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-list mr-2"></i>Saved Prescriptions
        </h3>
        
        <?php if (empty($prescriptions)): ?>
            <p class="text-gray-500 text-center py-8">No prescriptions saved yet. Create your first prescription above.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prescription ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diagnosis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medications</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($prescriptions as $prescription): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= esc($prescription['prescription_id'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($prescription['patient_name'] ?? 'Unknown') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= isset($prescription['prescribed_date']) ? date('M d, Y', strtotime($prescription['prescribed_date'])) : 'N/A' ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?= esc(substr($prescription['diagnosis'] ?? 'N/A', 0, 50)) ?><?= strlen($prescription['diagnosis'] ?? '') > 50 ? '...' : '' ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php if (!empty($prescription['medications'])): ?>
                                        <?= count($prescription['medications']) ?> medication(s)
                                    <?php else: ?>
                                        No medications
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= 
                                        ($prescription['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                                        (($prescription['status'] ?? '') === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                        'bg-blue-100 text-blue-800') 
                                    ?>">
                                        <?= esc(ucfirst($prescription['status'] ?? 'active')) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="<?= base_url('doctor/prescriptions/view/' . $prescription['id']) ?>" class="text-blue-600 hover:text-blue-800 mr-3">
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
    document.getElementById('patientSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('patientAge').value = selectedOption.dataset.age || '-';
        document.getElementById('patientGender').value = selectedOption.dataset.gender || '-';
    });

    function addMedicationRow() {
        const tbody = document.getElementById('medicationTableBody');
        const newRow = tbody.rows[0].cloneNode(true);
        const rowIndex = tbody.rows.length;
        
        // Update name attributes with new index
        newRow.querySelectorAll('input, select').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
            }
            // Clear input values
            if (input.type !== 'number') input.value = '';
            else input.value = '1';
        });
        tbody.appendChild(newRow);
    }

    function removeMedicationRow(button) {
        const tbody = document.getElementById('medicationTableBody');
        if (tbody.rows.length > 1) {
            button.closest('tr').remove();
        }
    }
</script>
<?= $this->endSection() ?>

