<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Walk In - Lab Tests</h1>
        <p class="text-gray-600">Manage lab test requests for walk-in patients (without doctor consultation)</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Walk-In Requests -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Walk-In Requests</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['total']) ?></p>
                </div>
                <i class="fas fa-clipboard-list text-blue-500 text-3xl"></i>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['pending']) ?></p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['in_progress']) ?></p>
                </div>
                <i class="fas fa-spinner text-orange-500 text-3xl"></i>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['completed']) ?></p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Create Walk-In Lab Request Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Create Walk-In Lab Request</h2>
        <button onclick="openModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>New Walk-In Request
        </button>
    </div>

    <!-- Walk-In Lab Requests Table Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Walk-In Lab Requests</h2>
            <span class="text-sm text-gray-600"><?= count($requests) ?> total</span>
        </div>

        <!-- Requests Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Date</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Patient</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Contact</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Test Type</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Priority</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No walk-in lab requests found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $request): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4"><?= esc($request['request_id'] ?? $request['id']) ?></td>
                                <td class="py-3 px-4">
                                    <?= isset($request['request_date']) ? date('M d, Y', strtotime($request['request_date'])) : date('M d, Y', strtotime($request['created_at'])) ?>
                                </td>
                                <td class="py-3 px-4"><?= esc($request['patient_name'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($request['contact'] ?? $request['phone'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4"><?= esc($request['test_type'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $priority = $request['priority'] ?? 'normal';
                                    $priorityClass = $priority === 'high' ? 'bg-red-100 text-red-800' : ($priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $priorityClass ?>">
                                        <?= esc(ucfirst($priority)) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $status = $request['status'] ?? 'pending';
                                    $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'in_progress' ? 'bg-orange-100 text-orange-800' : 
                                                   'bg-yellow-100 text-yellow-800');
                                    ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                        <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-600 text-xs">
                                        <?= esc(substr($request['notes'] ?? '', 0, 50)) ?>
                                        <?= strlen($request['notes'] ?? '') > 50 ? '...' : '' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Walk-In Request Modal -->
<div id="walkInModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">New Walk-In Lab Request</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="walkInForm" action="<?= base_url('walk-in/store') ?>" method="POST" class="p-6">
            <?= csrf_field() ?>
            
            <!-- Patient Field -->
            <div class="mb-6">
                <label for="patient_name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Patient <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="patient_name" 
                    name="patient_name" 
                    placeholder="Enter patient name" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Contact Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        placeholder="Phone number" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Email address" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <!-- Test Type Field -->
            <div class="mb-6">
                <label for="test_type" class="block text-sm font-semibold text-gray-700 mb-2">
                    Test Type <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select 
                        id="test_type" 
                        name="test_type" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white pr-10"
                    >
                    <option value="">Select Test Type</option>
                    <option value="Complete Blood Count (CBC)">Complete Blood Count (CBC)</option>
                    <option value="Blood Glucose Test">Blood Glucose Test</option>
                    <option value="Lipid Profile">Lipid Profile</option>
                    <option value="Liver Function Test">Liver Function Test</option>
                    <option value="Kidney Function Test">Kidney Function Test</option>
                    <option value="Thyroid Function Test">Thyroid Function Test</option>
                    <option value="Urine Analysis">Urine Analysis</option>
                    <option value="Stool Analysis">Stool Analysis</option>
                    <option value="X-Ray">X-Ray</option>
                    <option value="Ultrasound">Ultrasound</option>
                    <option value="ECG">ECG</option>
                    <option value="CT Scan">CT Scan</option>
                    <option value="MRI">MRI</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Priority Field -->
            <div class="mb-6">
                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                    Priority
                </label>
                <div class="relative">
                    <select 
                        id="priority" 
                        name="priority" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white pr-10"
                    >
                    <option value="low">Low</option>
                    <option value="normal" selected>Normal</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Notes Field -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                    Notes
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="4" 
                    placeholder="Additional notes or instructions..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y"
                ></textarea>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <button 
                    type="button" 
                    onclick="closeModal()" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    Create Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('walkInModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('walkInModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('walkInForm').reset();
    }

    // Close modal when clicking outside
    document.getElementById('walkInModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Handle form submission
    document.getElementById('walkInForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                location.reload();
            } else {
                alert(data.message || 'Error creating request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
</script>
<?= $this->endSection() ?>

