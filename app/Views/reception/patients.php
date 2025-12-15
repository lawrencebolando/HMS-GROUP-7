<?= $this->extend('templates/reception_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Patient Registration Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Patient Registration</h2>
        <p class="text-gray-600 mb-6">Register new patients and view patient records.</p>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total Patients -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_patients'] ?? count($patients ?? [])) ?></p>
                    </div>
                    <i class="fas fa-users text-blue-500 text-3xl"></i>
                </div>
            </div>

            <!-- New Patients Today -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">New Patients Today</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['new_patients_today'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-user-plus text-green-500 text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Records Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Patient Records</h2>
            <button onclick="openPatientTypeModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                <i class="fas fa-plus mr-2"></i>Add Patient
            </button>
        </div>
        
        <!-- Search Bar -->
        <div class="mb-6">
            <input 
                type="text" 
                id="searchPatients" 
                placeholder="Search patients..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- Patient Records Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Name</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">AGE/GENDER</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">CONTACT</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="patientsTableBody">
                    <?php if (empty($patients)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">No patients found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($patients as $patient): ?>
                            <?php
                            $fullName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
                            $age = $patient['date_of_birth'] ? date_diff(date_create($patient['date_of_birth']), date_create('today'))->y : 'N/A';
                            $status = $patient['status'] ?? 'active';
                            ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($patient['patient_id'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4 font-semibold"><?= esc($fullName) ?></td>
                                <td class="py-3 px-4">
                                    <div class="text-gray-900"><?= esc($age) ?> years</div>
                                    <div class="text-gray-500 text-xs"><?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-gray-900"><?= esc($patient['phone'] ?? 'N/A') ?></div>
                                    <div class="text-gray-500 text-xs"><?= esc($patient['email'] ?? 'N/A') ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($status === 'active'): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">ACTIVE</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">INACTIVE</span>
                                    <?php endif; ?>
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

<script>
    // Search functionality
    document.getElementById('searchPatients').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#patientsTableBody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
