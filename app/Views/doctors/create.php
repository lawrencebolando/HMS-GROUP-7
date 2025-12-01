<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Add New Doctor</h1>
        <p class="text-gray-600 mt-1">Register a new doctor account</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="<?= base_url('doctors/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required minlength="6">
                    <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="<?= base_url('doctors') ?>" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Add Doctor</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

