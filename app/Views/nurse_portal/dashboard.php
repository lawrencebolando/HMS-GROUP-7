<?= $this->extend('templates/nurse_portal_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <span class="text-gray-600">Nurse</span>
        </div>
    </div>

    <!-- Nurse Dashboard Section -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Nurse Dashboard</h2>
        <p class="text-gray-600 mb-4">Quick overview of today's assigned tasks and patients.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Assigned Patients -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Assigned Patients</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['assigned_patients'] ?? 0) ?></p>
                </div>
                <i class="fas fa-users text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Pending Medications -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Medications</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['pending_medications'] ?? 0) ?></p>
                </div>
                <i class="fas fa-pills text-orange-500 text-3xl"></i>
            </div>
        </div>

        <!-- Vital Checks Due -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Vital Checks Due</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['vital_checks_due'] ?? 0) ?></p>
                </div>
                <i class="fas fa-heartbeat text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <!-- Discharges Today -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Discharges Today</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['discharges_today'] ?? 0) ?></p>
                </div>
                <i class="fas fa-sign-out-alt text-green-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Tasks -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Today's Tasks</h2>
            <p class="text-sm text-gray-600 mb-4">Med administrations and follow-ups.</p>
            <div class="text-center py-8 text-gray-500">
                <?php if (empty($tasks)): ?>
                    No tasks assigned yet. Check back later or contact your supervisor.
                <?php else: ?>
                    <!-- Task list would go here -->
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <button class="bg-white border-2 border-blue-500 text-blue-600 px-4 py-3 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                    Update Vitals
                </button>
                <button class="bg-white border-2 border-blue-500 text-blue-600 px-4 py-3 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                    Update Treatment
                </button>
                <button class="bg-white border-2 border-blue-500 text-blue-600 px-4 py-3 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                    My Schedule
                </button>
                <button class="bg-white border-2 border-blue-500 text-blue-600 px-4 py-3 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                    Assign Patient
                </button>
            </div>
        </div>
    </div>

    <!-- Patients Under Care -->
    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Patients Under Care</h2>
        <p class="text-sm text-gray-600 mb-4">Patients currently assigned to you.</p>
        <div class="text-center py-8 text-gray-500">
            <?php if (empty($patients)): ?>
                No patients assigned yet. Patients will appear here once assigned by the doctor or administrator.
            <?php else: ?>
                <!-- Patient list would go here -->
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

