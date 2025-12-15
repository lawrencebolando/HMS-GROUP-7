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
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Create Schedule for <?= esc($nurse['name']) ?></h1>
        <p class="text-gray-600">Set up shift schedules and assignments for this nurse.</p>
    </div>

    <!-- Nurse Info Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
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
    </div>

    <!-- Schedule Form -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Schedule Information</h2>
        
        <form id="scheduleForm" action="<?= base_url('nurses/schedule/store') ?>" method="POST">
            <input type="hidden" name="nurse_id" value="<?= esc($nurse['id']) ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shift Date *</label>
                    <input type="date" name="shift_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shift Type *</label>
                    <select name="shift_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select shift type</option>
                        <option value="morning">Morning Shift (6 AM - 2 PM)</option>
                        <option value="afternoon">Afternoon Shift (2 PM - 10 PM)</option>
                        <option value="night">Night Shift (10 PM - 6 AM)</option>
                        <option value="full_day">Full Day (6 AM - 6 PM)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                    <input type="time" name="start_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                    <input type="time" name="end_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department/Unit</label>
                    <input type="text" name="department" placeholder="e.g. Emergency, ICU, General Ward" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" placeholder="Additional notes or special instructions..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="<?= base_url('nurses') ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Save Schedule
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

