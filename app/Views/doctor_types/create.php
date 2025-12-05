<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Add New Doctor Type</h1>
        <p class="text-gray-600 mt-1">Register a new doctor type</p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc(is_array($error) ? implode(', ', $error) : $error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="<?= base_url('doctor-types/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type Name *</label>
                    <input type="text" name="type_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="e.g., Cardiologist, Neurologist, Pediatrician">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief description of this doctor type"></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="<?= base_url('doctor-types') ?>" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Add Doctor Type</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

