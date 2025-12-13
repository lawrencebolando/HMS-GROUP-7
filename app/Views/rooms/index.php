<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rooms Management</h1>
        <p class="text-gray-600">View and manage hospital rooms, beds, and occupancy</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Rooms -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Rooms</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_rooms']) ?></p>
                </div>
                <i class="fas fa-door-open text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Available Rooms</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['available_rooms']) ?></p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>

        <!-- Occupied Rooms -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Occupied Rooms</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['occupied_rooms']) ?></p>
                </div>
                <i class="fas fa-bed text-red-500 text-3xl"></i>
            </div>
        </div>

        <!-- Total Beds -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Beds</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total_beds']) ?></p>
                </div>
                <i class="fas fa-bed text-purple-500 text-3xl"></i>
            </div>
        </div>

        <!-- Available Beds -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Available Beds</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['available_beds']) ?></p>
                </div>
                <i class="fas fa-bed text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <!-- Occupied Beds -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Occupied Beds</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['occupied_beds']) ?></p>
                </div>
                <i class="fas fa-user-injured text-orange-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Rooms List Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <?php if (empty($rooms)): ?>
            <div class="text-center py-12">
                <i class="fas fa-door-open text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-600 text-lg">No rooms found.</p>
            </div>
        <?php else: ?>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">All Rooms</h2>
                <span class="text-sm text-gray-600"><?= count($rooms) ?> total</span>
            </div>

            <!-- Rooms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($rooms as $room): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-800">Room <?= esc($room['room_number']) ?></h3>
                            <span class="px-2 py-1 rounded text-xs font-semibold <?= $room['status'] === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= esc(ucfirst($room['status'])) ?>
                            </span>
                        </div>
                        
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Type:</span>
                                <span class="font-semibold"><?= esc($room['room_type'] ?? 'Standard') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Beds:</span>
                                <span class="font-semibold"><?= esc($room['available_beds'] ?? 0) ?>/<?= esc($room['bed_count'] ?? 0) ?> available</span>
                            </div>
                            <?php if (isset($room['floor'])): ?>
                                <div class="flex justify-between">
                                    <span>Floor:</span>
                                    <span class="font-semibold"><?= esc($room['floor']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

