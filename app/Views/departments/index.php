<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Departments</h1>
            <p class="text-gray-600 mt-1">Manage hospital departments</p>
        </div>
        <button onclick="document.getElementById('addDeptModal').classList.remove('hidden')" class="btn-primary inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Add Department
        </button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if (empty($departments)): ?>
            <div class="col-span-full text-center text-gray-500 py-8">No departments found. Add one to get started.</div>
        <?php else: ?>
            <?php foreach ($departments as $dept): ?>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= esc($dept['name']) ?></h3>
                    <p class="text-gray-600 text-sm mb-4"><?= esc($dept['description'] ?? 'No description') ?></p>
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 text-xs rounded-full <?= $dept['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= esc(ucfirst($dept['status'])) ?>
                        </span>
                        <div>
                            <button onclick="editDept(<?= $dept['id'] ?>, '<?= esc($dept['name']) ?>', '<?= esc($dept['description'] ?? '') ?>', '<?= $dept['status'] ?>')" class="btn-link text-sm mr-2">Edit</button>
                            <a href="<?= base_url('departments/delete/' . $dept['id']) ?>" class="btn-danger inline-block text-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add Department Modal -->
<div id="addDeptModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold mb-4">Add New Department</h3>
        <form action="<?= base_url('departments/store') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('addDeptModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>

<script>
function editDept(id, name, description, status) {
    // Create edit form (simplified - you can enhance this)
    const newName = prompt('Enter new name:', name);
    if (newName) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('departments/update/') ?>' + id;
        form.innerHTML = `
            <?= csrf_field() ?>
            <input type="hidden" name="name" value="${newName}">
            <input type="hidden" name="description" value="${description}">
            <input type="hidden" name="status" value="${status}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>

