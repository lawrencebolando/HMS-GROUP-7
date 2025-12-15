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
    
    <div class="mb-6">
        <a href="<?= base_url('nurses') ?>" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Back to Nurses
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Schedule for <?= esc($nurse['name']) ?></h1>
        <p class="text-gray-600">View all scheduled shifts and assignments for this nurse.</p>
    </div>

    <!-- Nurse Info Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                    <?= esc($nurse['initials']) ?>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800"><?= esc($nurse['name']) ?></h3>
                    <p class="text-sm text-gray-600"><?= esc($nurse['email']) ?></p>
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded mt-2 <?= $nurse['status'] === 'active' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' ?>">
                        <?= strtoupper(esc($nurse['status'])) ?>
                    </span>
                </div>
            </div>
            <a href="<?= base_url('nurses/schedule/' . $nurse['id']) ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Add New Schedule
            </a>
        </div>
    </div>

    <!-- Schedules List -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Scheduled Shifts</h2>
        
        <?php if (empty($schedules)): ?>
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-600 text-lg mb-2">No schedules found</p>
                <p class="text-gray-500 mb-4">This nurse doesn't have any scheduled shifts yet.</p>
                <a href="<?= base_url('nurses/schedule/' . $nurse['id']) ?>" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Create First Schedule
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Shift Type</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Start Time</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">End Time</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Department</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Notes</th>
                            <th class="text-left py-3 px-4 text-gray-700 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules as $schedule): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <?= isset($schedule['shift_date']) ? date('M d, Y', strtotime($schedule['shift_date'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                        <?= esc(ucfirst(str_replace('_', ' ', $schedule['shift_type'] ?? 'N/A'))) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($schedule['start_time']) ? date('h:i A', strtotime($schedule['start_time'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?= isset($schedule['end_time']) ? date('h:i A', strtotime($schedule['end_time'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4"><?= esc($schedule['department'] ?? '-') ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= 
                                        ($schedule['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                                        (($schedule['status'] ?? '') === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                        'bg-blue-100 text-blue-800') 
                                    ?>">
                                        <?= esc(ucfirst($schedule['status'] ?? 'scheduled')) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4"><?= esc(substr($schedule['notes'] ?? '-', 0, 30)) ?><?= strlen($schedule['notes'] ?? '') > 30 ? '...' : '' ?></td>
                                <td class="py-3 px-4">
                                    <a href="<?= base_url('nurses/edit-schedule/' . $schedule['id']) ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-edit"></i> Edit
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
<?= $this->endSection() ?>

