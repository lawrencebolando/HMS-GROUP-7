<?= $this->extend('templates/reception_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Appointments Management Section -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Appointments Management</h2>
        <p class="text-sm text-gray-600 mb-4">Manage patient appointments and check-ins.</p>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Today's Appointments -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['today_appointments'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-calendar-day text-blue-500 text-3xl"></i>
                </div>
            </div>

            <!-- Pending Check-ins -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Pending Check-ins</p>
                        <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['pending_checkins'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-clock text-orange-500 text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Today's Appointments</h2>
            </div>
            <a href="<?= base_url('reception/appointments/create') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                <i class="fas fa-plus mr-2"></i>New Appointment
            </a>
        </div>
        
        <div class="mb-4">
            <input 
                type="text" 
                id="searchTodayAppointments" 
                placeholder="Search appointments..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
        
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-600">Patient appointments for <?= date('F d, Y') ?></p>
            <p class="text-sm text-gray-600 font-semibold"><?= count($today_appointments ?? []) ?> appointments</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Time</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Doctor</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Room</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($today_appointments)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No appointments scheduled for today
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($today_appointments as $appt): ?>
                            <?php
                            $patient = $appt['patient'] ?? null;
                            $patientName = $patient ? trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) : 'Unknown';
                            $doctor = $appt['doctor'] ?? null;
                            $doctorName = $doctor ? $doctor['name'] : 'Unknown';
                            ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <?= isset($appt['appointment_time']) ? date('h:i A', strtotime($appt['appointment_time'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4 font-semibold"><?= esc($patientName) ?></td>
                                <td class="py-3 px-4"><?= esc($doctorName) ?></td>
                                <td class="py-3 px-4"><?= esc($appt['room'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($appt['appointment_type'] ?? 'General') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $appt['status'] ?? 'pending';
                                    $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800');
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
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Upcoming Appointments</h2>
        <p class="text-sm text-gray-600 mb-4">All scheduled appointments.</p>
        
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-600">All upcoming appointments</p>
            <p class="text-sm text-gray-600 font-semibold"><?= count($upcoming_appointments ?? []) ?> appointments</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Time</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Doctor</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($upcoming_appointments)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No upcoming appointments
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($upcoming_appointments as $appt): ?>
                            <?php
                            $patient = $appt['patient'] ?? null;
                            $patientName = $patient ? trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) : 'Unknown';
                            $doctor = $appt['doctor'] ?? null;
                            $doctorName = $doctor ? $doctor['name'] : 'Unknown';
                            ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?= esc($appt['appointment_id'] ?? $appt['id']) ?></td>
                                <td class="py-3 px-4">
                                    <?= isset($appt['appointment_date']) ? date('M d, Y', strtotime($appt['appointment_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($appt['appointment_time']) ? date('h:i A', strtotime($appt['appointment_time'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4 font-semibold"><?= esc($patientName) ?></td>
                                <td class="py-3 px-4"><?= esc($doctorName) ?></td>
                                <td class="py-3 px-4"><?= esc($appt['appointment_type'] ?? 'General') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $appt['status'] ?? 'pending';
                                    $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800');
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
    // Search functionality for today's appointments
    document.getElementById('searchTodayAppointments')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
