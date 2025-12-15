<?= $this->extend('templates/reception_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Reception Dashboard Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Reception Dashboard</h2>
        <p class="text-gray-600 mb-6">Quick overview of today's patient flow and appointments</p>
        
        <!-- Receptionist Information Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= esc($user['name']) ?></h3>
                    <p class="text-gray-600"><?= esc($user['email']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Employee ID: <span class="font-semibold"><?= esc($user['employee_id'] ?? 'RC-0000-000') ?></span></p>
                    <p class="text-sm text-gray-600">Department: <span class="font-semibold">Reception</span></p>
                    <p class="text-sm text-gray-600">Shift: <span class="font-semibold">Morning</span></p>
                </div>
            </div>
        </div>
        
        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- New Patients Today -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">New Patients Today</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['new_patients_today'] ?? 0) ?></p>
                        <?php if (isset($stats['new_patients_change'])): ?>
                            <p class="text-sm <?= $stats['new_patients_change'] >= 0 ? 'text-green-600' : 'text-red-600' ?> mt-1">
                                <?= $stats['new_patients_change'] >= 0 ? '+' : '' ?><?= $stats['new_patients_change'] ?> from yesterday
                            </p>
                        <?php endif; ?>
                    </div>
                    <i class="fas fa-user-plus text-blue-500 text-3xl"></i>
                </div>
            </div>

            <!-- Appointments -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Appointments</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['appointments'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-calendar-alt text-green-500 text-3xl"></i>
                </div>
            </div>

            <!-- Walk-ins -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Walk-ins</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['walkins'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-walking text-orange-500 text-3xl"></i>
                </div>
            </div>

            <!-- Discharged -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Discharged</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['discharged'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-sign-out-alt text-purple-500 text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Tasks Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Today's Tasks</h2>
        <p class="text-sm text-gray-600 mb-4">Your assigned tasks for today</p>
        
        <div class="space-y-4">
            <!-- Patient Registration Task -->
            <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:bg-gray-50">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1">Patient Registration</h3>
                    <p class="text-sm text-gray-600">No new walk-ins</p>
                    <button onclick="openPatientTypeModal()" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">View</button>
                </div>
                <button class="text-gray-400 hover:text-gray-600 text-sm">CLEAR</button>
            </div>
            
            <!-- Appointment Confirmations Task -->
            <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:bg-gray-50">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1">Appointment Confirmations</h3>
                    <p class="text-sm text-gray-600">All appointments confirmed</p>
                    <a href="<?= base_url('reception/appointments') ?>" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">View</a>
                </div>
                <button class="text-gray-400 hover:text-gray-600 text-sm">CLEAR</button>
            </div>
        </div>
    </div>

    <!-- Patient Flow Overview Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Patient Flow Overview</h2>
        <p class="text-sm text-gray-600 mb-6">Current patient flow and appointment status</p>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Today's Appointments -->
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Today's Appointments</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">ACTIVE</span>
                </div>
                <p class="text-sm text-gray-600 mb-4"><?= number_format($appointments['total'] ?? 0) ?> appointments scheduled</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">Confirmed</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800"><?= number_format($appointments['confirmed'] ?? 0) ?></span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">Pending</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800"><?= number_format($appointments['pending'] ?? 0) ?></span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">Cancelled</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800"><?= number_format($appointments['cancelled'] ?? 0) ?></span>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Upcoming Appointments</h3>
                <p class="text-sm text-gray-600 mb-4">Next patients arriving</p>
                
                <?php if (empty($upcoming_appointments)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <p>No upcoming appointments.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach (array_slice($upcoming_appointments, 0, 3) as $appt): ?>
                            <div class="border-b border-gray-100 pb-3 last:border-b-0">
                                <p class="text-sm font-semibold text-gray-800"><?= esc($appt['patient_name'] ?? 'Unknown') ?></p>
                                <p class="text-xs text-gray-600">
                                    <?= isset($appt['appointment_date']) ? date('M d, Y', strtotime($appt['appointment_date'])) : 'N/A' ?>
                                    <?= isset($appt['appointment_time']) ? ' at ' . date('h:i A', strtotime($appt['appointment_time'])) : '' ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
