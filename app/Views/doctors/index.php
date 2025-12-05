<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
<<<<<<< HEAD
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Consultation Doctors</h1>
            <p class="text-gray-600 mt-1">Manage all doctor accounts</p>
        </div>
        <a href="<?= base_url('doctors/create') ?>" class="btn-primary inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Add New Doctor
        </a>
=======
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Doctor Management</h1>
            <p class="text-gray-600 mt-1">Manage hospital doctors and medical staff</p>
        </div>
        <button onclick="document.getElementById('addDoctorModal').classList.remove('hidden')" class="btn-primary inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Add Doctor
        </button>
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

<<<<<<< HEAD
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($doctors)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No doctors found. <a href="<?= base_url('doctors/create') ?>" class="text-blue-600 hover:underline">Add one now</a></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($doctor['name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($doctor['email']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $doctor['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= esc(ucfirst($doctor['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= base_url('doctors/edit/' . $doctor['id']) ?>" class="btn-link mr-3">Edit</a>
                                <a href="<?= base_url('doctors/delete/' . $doctor['id']) ?>" class="btn-danger inline-block" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

=======
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="text-3xl font-bold text-gray-800"><?= $stats['active_doctors'] ?></div>
            <div class="text-gray-600 text-sm mt-1">Active Doctors</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="text-3xl font-bold text-gray-800"><?= $stats['on_leave'] ?></div>
            <div class="text-gray-600 text-sm mt-1">On Leave</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="text-3xl font-bold text-gray-800"><?= $stats['patients_today'] ?></div>
            <div class="text-gray-600 text-sm mt-1">Patients Today</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="text-3xl font-bold text-gray-800"><?= $stats['departments'] ?></div>
            <div class="text-gray-600 text-sm mt-1">Departments</div>
        </div>
    </div>

    <!-- Medical Staff Directory -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Medical Staff Directory</h2>
        
        <!-- Search and Filter -->
        <div class="flex items-center space-x-4 mb-6">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <form method="GET" action="<?= base_url('doctors') ?>">
                    <input 
                        type="text" 
                        name="search" 
                        value="<?= esc($search ?? '') ?>"
                        placeholder="Search doctors by name, specialization, or department..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </form>
            </div>
            <button class="btn-secondary inline-flex items-center">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </div>

        <!-- Doctor Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if (empty($doctors)): ?>
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fas fa-user-md text-6xl mb-4 text-gray-300"></i>
                    <p class="text-lg">No doctors found</p>
                    <button onclick="document.getElementById('addDoctorModal').classList.remove('hidden')" class="mt-4 text-blue-600 hover:underline">Add your first doctor</button>
                </div>
            <?php else: ?>
                <?php foreach ($doctors as $doctor): ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                    <?php
                                    $names = explode(' ', $doctor['full_name']);
                                    $initials = '';
                                    foreach ($names as $name) {
                                        if (!empty($name)) {
                                            $initials .= strtoupper(substr($name, 0, 1));
                                        }
                                    }
                                    $initials = substr($initials, 0, 3);
                                    ?>
                                    <span class="text-blue-600 font-bold text-lg"><?= esc($initials) ?></span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800"><?= esc($doctor['full_name']) ?></h3>
                                    <p class="text-gray-600 text-sm"><?= esc($doctor['specialization']) ?></p>
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-star text-yellow-400 mr-2"></i>
                                <span class="font-semibold"><?= number_format($doctor['rating'], 1) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-building mr-2"></i>
                                <span><?= esc($doctor['department_name']) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-briefcase mr-2"></i>
                                <span><?= esc($doctor['years_of_experience'] ?? 0) ?> years</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users mr-2"></i>
                                <span><?= esc($doctor['patients_today']) ?> patients today</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-2"></i>
                                <span><?= esc($doctor['schedule'] ?? 'Not set') ?></span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $doctor['status'] === 'active' ? 'bg-blue-100 text-blue-800' : ($doctor['status'] === 'on_leave' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') ?>">
                                <?= esc(ucfirst(str_replace('_', ' ', $doctor['status']))) ?>
                            </span>
                            <div class="flex space-x-2">
                                <?php if ($doctor['phone']): ?>
                                    <a href="tel:<?= esc($doctor['phone']) ?>" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($doctor['email']): ?>
                                    <a href="mailto:<?= esc($doctor['email']) ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Doctor Modal -->
<div id="addDoctorModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Add New Doctor</h3>
                <p class="text-gray-600 text-sm mt-1">Add a new doctor to the hospital medical staff directory.</p>
            </div>
            <button onclick="document.getElementById('addDoctorModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form action="<?= base_url('doctors/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="full_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Specialization *</label>
                    <select name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select specialization</option>
                        <?php foreach ($doctor_types as $type): ?>
                            <option value="<?= esc($type['type_name']) ?>"><?= esc($type['type_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id'] ?>"><?= esc($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                    <input type="number" name="years_of_experience" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Schedule</label>
                    <input type="text" name="schedule" placeholder="e.g., 9:00 AM - 5:00 PM" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('addDoctorModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Add Doctor</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
