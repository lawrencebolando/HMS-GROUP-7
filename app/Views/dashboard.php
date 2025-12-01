<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Dashboard Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">DashBoard</h1>
        <p class="text-gray-600 mt-1">it all starts here</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Patients Card -->
        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-4xl font-bold"><?= esc($stats['total_patients']) ?></div>
                <div class="text-cyan-100 text-sm mt-1">Total Patients</div>
            </div>
            <a href="<?= base_url('patients') ?>" class="inline-flex items-center text-white text-sm font-medium hover:underline">
                More info
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Consultation Doctors Card -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-stethoscope text-2xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-4xl font-bold"><?= esc($stats['total_doctors']) ?></div>
                <div class="text-green-100 text-sm mt-1">Consultation Doctors</div>
            </div>
            <a href="<?= base_url('doctors') ?>" class="inline-flex items-center text-white text-sm font-medium hover:underline">
                More info
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Admin Accounts Card -->
        <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-bullseye text-2xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-4xl font-bold"><?= esc($stats['total_admins']) ?></div>
                <div class="text-orange-100 text-sm mt-1">Admin Accounts</div>
            </div>
            <a href="#" class="inline-flex items-center text-white text-sm font-medium hover:underline">
                More info
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Reception Accounts Card -->
        <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-user-tie text-2xl"></i>
                </div>
            </div>
            <div class="mb-2">
                <div class="text-4xl font-bold"><?= esc($stats['total_receptionists']) ?></div>
                <div class="text-red-100 text-sm mt-1">Reception Accounts</div>
            </div>
            <a href="#" class="inline-flex items-center text-white text-sm font-medium hover:underline">
                More info
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Additional Content Area (Empty for now) -->
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Additional dashboard content can go here -->
    </div>
</div>
<?= $this->endSection() ?>

