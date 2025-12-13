<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Admissions Management Section -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Admissions Management</h1>
        <p class="text-gray-600">Manage inpatient admissions and discharges.</p>
    </div>

    <!-- Error Message -->
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 mb-6 rounded">
            <p class="font-semibold">Error: <?= esc($error) ?></p>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Admissions -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Admissions</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_admissions']) ?></p>
                </div>
                <i class="fas fa-hospital text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Active Admissions -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Admissions</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['active_admissions']) ?></p>
                </div>
                <i class="fas fa-user-injured text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Admitted Today -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Admitted Today</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['admitted_today']) ?></p>
                </div>
                <i class="fas fa-calendar-day text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <!-- Discharged Today -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Discharged Today</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['discharged_today']) ?></p>
                </div>
                <i class="fas fa-sign-out-alt text-purple-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Inpatient Records Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Inpatient Records</h2>
        
        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <input 
                    type="text" 
                    id="searchAdmissions" 
                    placeholder="Search admissions..." 
                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- All Inpatients Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700">All Inpatients</h3>
            <span class="text-sm text-gray-600"><?= count($admissions) ?> total</span>
        </div>

        <!-- Admissions Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Admission Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Room</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Doctor</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Case Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Reason</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($admissions)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-500">
                                No inpatient admissions found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($admissions as $admission): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4"><?= esc($admission['id'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($admission['patient_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4">
                                    <?= isset($admission['admission_date']) ? date('M d, Y', strtotime($admission['admission_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4"><?= esc($admission['room'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($admission['doctor_name'] ?? 'Unknown') ?></td>
                                <td class="py-3 px-4"><?= esc($admission['case_type'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($admission['reason'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $admission['status'] ?? 'active';
                                    $statusClass = $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
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
                                        <button class="text-red-600 hover:text-red-800" title="Discharge">
                                            <i class="fas fa-sign-out-alt"></i>
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
    document.getElementById('searchAdmissions')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>

