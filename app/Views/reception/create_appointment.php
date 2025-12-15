<?= $this->extend('templates/reception_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Add New Appointment</h1>
            <p class="text-gray-600 mt-1">Schedule a new patient appointment</p>
        </div>
        <button onclick="window.history.back()" class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
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

    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <form action="<?= base_url('reception/appointments/store') ?>" method="POST" id="appointmentForm">
            <?= csrf_field() ?>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Patient <span class="text-red-500">*</span></label>
                            <select name="patient_id" id="patient_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Choose a patient...</option>
                                <?php foreach ($patients ?? [] as $patient): ?>
                                    <option value="<?= esc($patient['id']) ?>" data-phone="<?= esc($patient['phone'] ?? '') ?>" data-name="<?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                                        <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?> (<?= esc($patient['patient_id'] ?? $patient['id']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="appointment_date" placeholder="mm/dd/yyyy" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <i class="fas fa-calendar absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="scheduled" selected>Scheduled</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Notes</label>
                            <textarea name="notes" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional notes or instructions..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Patient Name</label>
                            <input type="text" name="patient_name" id="patient_name" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor <span class="text-red-500">*</span></label>
                            <select name="doctor_id" id="doctor_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Choose a doctor...</option>
                                <?php foreach ($doctors ?? [] as $doctor): ?>
                                    <option value="<?= esc($doctor['id']) ?>">
                                        <?= esc($doctor['name']) ?> <?= !empty($doctor['specialization']) ? ' - ' . esc($doctor['specialization']) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($doctors)): ?>
                                <div class="mt-2 text-sm text-gray-600">
                                    <p class="mb-1">Available Doctors:</p>
                                    <div class="space-y-1">
                                        <?php foreach ($doctors as $doctor): ?>
                                            <a href="#" class="text-blue-600 hover:text-blue-800 hover:underline" onclick="selectDoctor(<?= esc($doctor['id']) ?>); return false;">
                                                <?= esc($doctor['name']) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Click on a doctor's name to view their full schedule</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Time <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="time" name="appointment_time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <i class="fas fa-clock absolute right-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Room</label>
                            <select name="room" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No rooms available for this appointment type</option>
                                <?php foreach ($rooms ?? [] as $room): ?>
                                    <option value="<?= esc($room['room_number']) ?>">
                                        <?= esc($room['room_number']) ?> - <?= esc($room['room_type'] ?? 'Standard') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Optional: Select an OPD clinic room for outpatient appointment</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Buttons -->
            <div class="border-t border-gray-200 p-6 flex justify-between">
                <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </button>
                <div class="flex space-x-3">
                    <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Add Appointment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-fill patient name and contact number when patient is selected
    document.getElementById('patient_id')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const patientName = selectedOption.getAttribute('data-name') || '';
        const patientPhone = selectedOption.getAttribute('data-phone') || '';
        
        document.getElementById('patient_name').value = patientName;
        document.getElementById('contact_number').value = patientPhone;
    });

    // Select doctor function
    function selectDoctor(doctorId) {
        document.getElementById('doctor_id').value = doctorId;
        // Here you could add functionality to show doctor's schedule
        alert('Doctor selected. Schedule view functionality can be added here.');
    }
</script>
<?= $this->endSection() ?>

