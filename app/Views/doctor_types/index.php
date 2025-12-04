<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Doctor Types</h1>
            <p class="text-gray-600 mt-1">Manage doctor types and specialties</p>
        </div>
        <a href="<?= base_url('doctor-types/create') ?>" class="btn-primary inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Add New Doctor Type
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($doctor_types)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No doctor types found. <a href="<?= base_url('doctor-types/create') ?>" class="text-blue-600 hover:underline">Add one now</a></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($doctor_types as $type): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($type['type_name']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= esc($type['description'] ?? 'No description') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $type['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= esc(ucfirst($type['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= base_url('doctor-types/edit/' . $type['id']) ?>" class="btn-link mr-3">Edit</a>
                                <a href="<?= base_url('doctor-types/delete/' . $type['id']) ?>" class="btn-danger inline-block" onclick="return confirm('Are you sure you want to delete this doctor type?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

