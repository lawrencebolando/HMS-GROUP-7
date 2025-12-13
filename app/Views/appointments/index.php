<?= $this->extend('templates/dashboard_layout') ?>

<?= $this->section('content') ?>
<div>
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg p-8 mb-6 relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-bold text-blue-900 mb-3">Appointment Scheduling</h1>
            <p class="text-gray-600 text-lg mb-6">Efficient appointment management system. Schedule new appointments, view daily schedules, and manage patient bookings with our intuitive calendar interface.</p>
            <button onclick="document.getElementById('appointmentModal').classList.remove('hidden')" class="btn-primary inline-flex items-center" style="background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);">
                <i class="fas fa-plus mr-2"></i>Schedule Appointment
            </button>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
            <img src="https://via.placeholder.com/256x256?text=Calendar" alt="Calendar" class="w-full h-full object-contain">
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc(is_array($error) ? implode(', ', $error) : $error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Tabs and Filter Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="#" class="px-6 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-600">Today's Schedule</a>
                <a href="#" class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700">Upcoming</a>
                <a href="#" class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700">Calendar View</a>
            </nav>
        </div>
        
        <div class="p-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <i class="fas fa-calendar text-gray-400"></i>
                <span class="text-gray-700">Appointments for <?= date('Y-m-d', strtotime($filter_date)) ?></span>
                <input type="date" id="dateFilter" value="<?= esc($filter_date) ?>" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="window.location.href='<?= base_url('appointments') ?>?date=' + this.value">
            </div>
            <button class="btn-secondary inline-flex items-center">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <?php if (empty($appointments)): ?>
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-calendar-times text-6xl mb-4 text-gray-300"></i>
                <p class="text-lg">No appointments scheduled for this date</p>
            </div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($appointments as $apt): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($apt['appointment_id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div><?= esc($apt['patient_name'] ?? 'Unknown') ?></div>
                                <div class="text-xs text-gray-500"><?= esc($apt['patient_id_display'] ?? 'N/A') ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($apt['doctor_name'] ?? 'Unknown') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('h:i A', strtotime($apt['appointment_time'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $apt['status'] === 'scheduled' ? 'bg-blue-100 text-blue-800' : ($apt['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') ?>">
                                    <?= esc(ucfirst(str_replace('_', ' ', $apt['status']))) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" onclick="editAppointment(<?= $apt['id'] ?>)" class="btn-link mr-3">Edit</a>
                                <a href="<?= base_url('appointments/delete/' . $apt['id']) ?>" class="btn-danger inline-block" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Schedule Appointment Modal -->
<div id="appointmentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Schedule New Appointment</h3>
            <button onclick="document.getElementById('appointmentModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-6">Fill in the appointment details below to schedule a new appointment.</p>
        
        <form action="<?= base_url('appointments/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient Name *</label>
                    <select name="patient_id" id="patient_select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select patient</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient['id'] ?>" data-patient-id="<?= esc($patient['patient_id']) ?>">
                                <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?> (<?= esc($patient['patient_id']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient ID</label>
                    <input type="text" id="patient_id_display" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Doctor *</label>
                    <select name="doctor_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select doctor</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor['id'] ?>"><?= esc($doctor['full_name'] ?? $doctor['name'] ?? 'Unknown') ?></option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="appointment_date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time *</label>
                    <select name="appointment_time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select time</option>
                        <?php
                        // Generate time slots (9 AM to 5 PM, 30-minute intervals)
                        for ($hour = 9; $hour < 18; $hour++) {
                            for ($minute = 0; $minute < 60; $minute += 30) {
                                $time = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':00';
                                $display = date('h:i A', strtotime($time));
                                echo '<option value="' . $time . '">' . $display . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select type</option>
                        <option value="Consultation">Consultation</option>
                        <option value="Follow-up">Follow-up</option>
                        <option value="Check-up">Check-up</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (min)</label>
                    <input type="number" name="duration" value="30" min="15" step="15" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" placeholder="Any additional notes..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('appointmentModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Schedule Appointment</button>
            </div>
        </form>
    </div>
</div>

<script>
// Update Patient ID when patient is selected
document.getElementById('patient_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const patientId = selectedOption.getAttribute('data-patient-id');
    document.getElementById('patient_id_display').value = patientId || '';
});

function editAppointment(id) {
    // TODO: Implement edit functionality
    alert('Edit functionality coming soon!');
}
</script>
<?= $this->endSection() ?>

