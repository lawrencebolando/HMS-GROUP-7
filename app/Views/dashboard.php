<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Dashboard Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-600 mt-2 text-lg">Welcome back! Here's what's happening at your hospital today. Monitor key metrics, track activities, and stay updated with real-time information.</p>
            </div>
            <div class="text-right">
                <button class="btn-primary inline-flex items-center mb-2">
                    <i class="fas fa-bolt mr-2"></i>Live Updates
                </button>
                <p class="text-sm text-gray-500">Last updated: 2 minutes ago</p>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Patients Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_patients']) ?></div>
                <div class="text-gray-600 text-sm mt-1">Total Patients</div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-green-600 font-semibold"><?= $stats['patient_change'] ?></span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>

        <!-- Today's Appointments Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-3xl font-bold text-gray-800"><?= number_format($stats['today_appointments']) ?></div>
                <div class="text-gray-600 text-sm mt-1">Today's Appointments</div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-green-600 font-semibold"><?= $stats['appointment_change'] ?></span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>

        <!-- Available Doctors Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-md text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_doctors']) ?></div>
                <div class="text-gray-600 text-sm mt-1">Available Doctors</div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-red-600 font-semibold"><?= $stats['doctor_change'] ?></span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>

        <!-- Occupied Beds Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bed text-orange-600 text-xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-3xl font-bold text-gray-800"><?= $stats['occupied_beds'] ?>/<?= $stats['total_beds'] ?></div>
                <div class="text-gray-600 text-sm mt-1">Occupied Beds</div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-green-600 font-semibold"><?= $stats['bed_percentage'] ?>%</span>
                <span class="text-gray-500 ml-1">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Upcoming Appointments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-bolt text-blue-500 mr-2"></i>Recent Activities
                </h2>
            </div>
            <div class="space-y-4">
                <?php if (empty($recent_activities)): ?>
                    <p class="text-gray-500 text-center py-4">No recent activities</p>
                <?php else: ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas <?= $activity['icon'] ?> text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800 text-sm"><?= esc($activity['message']) ?></p>
                                <p class="text-gray-500 text-xs mt-1"><?= esc($activity['time']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar text-green-500 mr-2"></i>Upcoming Appointments
                </h2>
            </div>
            <div class="space-y-4">
                <?php if (empty($upcoming_appointments)): ?>
                    <p class="text-gray-500 text-center py-4">No upcoming appointments</p>
                <?php else: ?>
                    <?php foreach ($upcoming_appointments as $apt): ?>
                        <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-green-600 font-bold"><?= date('d', strtotime($apt['appointment_date'])) ?></span>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800 font-semibold"><?= esc($apt['patient_name']) ?></p>
                                <p class="text-gray-600 text-sm"><?= esc($apt['doctor_name']) ?></p>
                                <p class="text-gray-500 text-xs mt-1"><?= date('h:i A', strtotime($apt['appointment_time'])) ?></p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                <?= esc(ucfirst($apt['reason'] ?? 'Consultation')) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bed Occupancy Status -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-bed text-orange-500 mr-2"></i>Bed Occupancy Status
            </h2>
        </div>
        <div class="space-y-6">
            <!-- ICU -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 font-semibold">ICU</span>
                    <span class="text-gray-600 text-sm">24/30 (80%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: 80%"></div>
                </div>
            </div>
            
            <!-- General Ward -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 font-semibold">General Ward</span>
                    <span class="text-gray-600 text-sm">145/160 (91%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: 91%"></div>
                </div>
            </div>
            
            <!-- Emergency -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 font-semibold">Emergency</span>
                    <span class="text-gray-600 text-sm">18/30 (60%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
