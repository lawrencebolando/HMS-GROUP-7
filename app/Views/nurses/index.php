<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
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
    
    <!-- Nurse Schedule Management Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Nurse Schedule Management</h1>
        <p class="text-gray-600">Manage nurse schedules and shift assignments.</p>
    </div>

    <!-- All Nurses Section -->
    <div class="mb-6">
        <!-- All Nurses Header Bar -->
        <div class="bg-gray-100 px-4 py-3 rounded-t-lg flex items-center justify-between mb-0">
            <h2 class="text-lg font-semibold text-gray-800">All Nurses</h2>
            <span class="text-sm text-gray-600"><?= count($nurses ?? []) ?> total</span>
        </div>

        <!-- Nurses List -->
        <div class="space-y-4">
            <?php if (empty($nurses)): ?>
                <div class="bg-white rounded-b-lg shadow-lg p-8 text-center">
                    <i class="fas fa-user-nurse text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-600 text-lg mb-2">No nurses found</p>
                    <p class="text-gray-500">Add nurses to the system to manage their schedules.</p>
                </div>
            <?php else: ?>
                <?php foreach ($nurses as $nurse): ?>
                    <?php if (!isset($nurse['id']) || !isset($nurse['name'])) continue; ?>
                    <div class="bg-white rounded-b-lg shadow-lg p-6">
                        <!-- Nurse Info Row -->
                        <div class="flex items-start justify-between mb-6">
                            <!-- Left: Avatar, Name, Email, Status -->
                            <div class="flex items-start flex-1">
                                <!-- Avatar -->
                                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4 flex-shrink-0">
                                    <?= esc($nurse['initials'] ?? 'NN') ?>
                                </div>
                                
                                <!-- Name, Email, Status -->
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-1"><?= esc($nurse['name'] ?? 'Unknown') ?></h3>
                                    <p class="text-sm text-gray-600 mb-2"><?= esc($nurse['email'] ?? '') ?></p>
                                    <!-- Status Tag -->
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded <?= ($nurse['status'] ?? 'inactive') === 'active' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' ?>">
                                        <?= strtoupper(esc($nurse['status'] ?? 'inactive')) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Right: Shift Statistics -->
                            <div class="flex items-center space-x-6">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-blue-600 mb-1"><?= $nurse['total_shifts'] ?? 0 ?></p>
                                    <p class="text-xs text-gray-600">Total Shifts</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-blue-600 mb-1"><?= $nurse['shift_types'] ?? 0 ?></p>
                                    <p class="text-xs text-gray-600">Shift Types</p>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Status -->
                        <div class="mb-4 text-center">
                            <?php if (!empty($nurse['has_schedule'])): ?>
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-calendar-check text-green-500 mr-2"></i>
                                    Schedule assigned
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No schedule assigned</p>
                            <?php endif; ?>
                        </div>

                        <!-- Activity Status -->
                        <div class="mb-6 text-center">
                            <?php if (!empty($nurse['activities_count'])): ?>
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                                    <?= $nurse['activities_count'] ?> activities recorded
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No activities recorded yet.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center space-x-3">
                            <?php if ($nurse['has_schedule']): ?>
                                <a href="<?= base_url('nurses/view-schedule/' . ($nurse['id'] ?? 0)) ?>" class="inline-block bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    <i class="fas fa-calendar-check mr-2"></i> View Schedule
                                </a>
                                <a href="<?= base_url('nurses/schedule/' . ($nurse['id'] ?? 0)) ?>" class="inline-block bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <i class="fas fa-plus mr-2"></i> Add Schedule
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('nurses/schedule/' . ($nurse['id'] ?? 0)) ?>" class="inline-block bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <i class="fas fa-plus mr-2"></i> Create Schedule
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

