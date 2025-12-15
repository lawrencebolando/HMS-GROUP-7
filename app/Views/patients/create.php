<?php
$isInpatient = ($patient_type ?? 'outpatient') === 'inpatient';
$userRole = session()->get('user_role') ?? 'admin';
// Use reception layout if user is receptionist, otherwise use dashboard layout for admin
$layout = ($userRole === 'receptionist') ? 'templates/reception_layout' : 'templates/dashboard_layout';
?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?= $isInpatient ? 'Add New Inpatient' : 'Add New Outpatient' ?></h1>
            <p class="text-gray-600 mt-1"><?= $isInpatient ? 'Register a new inpatient for admission' : 'Register a new outpatient in the system' ?></p>
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

    <div class="bg-white rounded-lg shadow-lg border border-pink-200">
        <form action="<?= base_url('patients/store') ?>" method="POST" id="patientForm">
            <?= csrf_field() ?>
            <input type="hidden" name="patient_type" value="<?= esc($patient_type ?? 'outpatient') ?>">
            
            <div class="p-6 max-h-[600px] overflow-y-auto">
                <!-- Patient Personal Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Patient Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                            <input type="text" name="middle_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="date_of_birth" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                            <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Blood Type</label>
                            <select name="blood_group" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Blood Type</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" placeholder="09XX XXX XXXX" pattern="09[0-9]{9}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">Philippine mobile number (09XX XXX XXXX)</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <?php if ($isInpatient): ?>
                <!-- Patient Address Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Patient Address Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Province <span class="text-red-500">*</span></label>
                            <select name="province" id="province" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Province</option>
                                <option value="Metro Manila">Metro Manila</option>
                                <option value="Cavite">Cavite</option>
                                <option value="Laguna">Laguna</option>
                                <option value="Batangas">Batangas</option>
                                <option value="Rizal">Rizal</option>
                                <option value="Quezon">Quezon</option>
                                <option value="Bulacan">Bulacan</option>
                                <option value="Pampanga">Pampanga</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Patient and Medical Details -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Patient and Medical Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City/Municipality <span class="text-red-500">*</span></label>
                            <select name="city" id="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Barangay <span class="text-red-500">*</span></label>
                            <select name="barangay" id="barangay" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Patient Type <span class="text-red-500">*</span></label>
                            <select name="patient_type_select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" disabled>
                                <option value="inpatient" selected>Inpatient</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Insurance Provider</label>
                            <select name="insurance_provider" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Insurance Provider</option>
                                <option value="PhilHealth">PhilHealth</option>
                                <option value="Maxicare">Maxicare</option>
                                <option value="Medicard">Medicard</option>
                                <option value="Intellicare">Intellicare</option>
                                <option value="Cocolife">Cocolife</option>
                                <option value="None">None</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Concern <span class="text-red-500">*</span></label>
                            <textarea name="medical_concern" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Describe the patient's medical condition, symptoms, or reason for admission..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Admission Details -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Admission Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admission Date & Time <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="date" name="admission_date" value="<?= date('Y-m-d') ?>" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <input type="time" name="admission_time" value="<?= date('H:i') ?>" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admission Type <span class="text-red-500">*</span></label>
                            <select name="admission_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Type</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Scheduled">Scheduled</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Observation">Observation</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Attending Doctor <span class="text-red-500">*</span></label>
                            <select name="attending_doctor" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Doctor</option>
                                <?php foreach ($doctors ?? [] as $doctor): ?>
                                    <option value="<?= esc($doctor['id']) ?>"><?= esc($doctor['name']) ?> <?= !empty($doctor['specialization']) ? ' - ' . esc($doctor['specialization']) : '' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Room / Ward <span class="text-red-500">*</span></label>
                            <select name="room_ward" id="room_ward" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Room / Ward</option>
                                <?php foreach ($rooms ?? [] as $room): ?>
                                    <option value="<?= esc($room['room_number']) ?>" data-beds="<?= esc($room['available_beds'] ?? 0) ?>">
                                        <?= esc($room['room_number']) ?> - <?= esc($room['room_type'] ?? 'Standard') ?> (<?= esc($room['available_beds'] ?? 0) ?> beds available)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bed Number</label>
                            <select name="bed_number" id="bed_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Bed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Vital Signs</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Temperature (°C)</label>
                            <input type="number" name="temperature" step="0.1" value="36.7" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Blood Pressure</label>
                            <input type="text" name="blood_pressure" value="120/80" placeholder="120/80" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heart Rate (bpm)</label>
                            <input type="number" name="heart_rate" value="72" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Oxygen Saturation (%)</label>
                            <input type="number" name="oxygen_saturation" value="98" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Emergency Contact</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person Name</label>
                            <input type="text" name="emergency_contact_name" placeholder="Full name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <input type="text" name="emergency_contact_phone" placeholder="09XX XXXX XXXX" pattern="09[0-9]{9}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Relationship to Patient</label>
                            <input type="text" name="emergency_contact_relationship" placeholder="Parent / Spouse / Sibling" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <!-- Outpatient Form -->
                <!-- Patient Address Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Patient Address Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Province <span class="text-red-500">*</span></label>
                            <select name="province" id="province_outpatient" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Province</option>
                                <option value="Metro Manila">Metro Manila</option>
                                <option value="Cavite">Cavite</option>
                                <option value="Laguna">Laguna</option>
                                <option value="Batangas">Batangas</option>
                                <option value="Rizal">Rizal</option>
                                <option value="Quezon">Quezon</option>
                                <option value="Bulacan">Bulacan</option>
                                <option value="Pampanga">Pampanga</option>
                                <option value="Nueva Ecija">Nueva Ecija</option>
                                <option value="Tarlac">Tarlac</option>
                                <option value="Zambales">Zambales</option>
                                <option value="Bataan">Bataan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City/Municipality <span class="text-red-500">*</span></label>
                            <select name="city" id="city_outpatient" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Barangay <span class="text-red-500">*</span></label>
                            <select name="barangay" id="barangay_outpatient" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Patient and Medical Details -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Patient and Medical Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Patient Type <span class="text-red-500">*</span></label>
                            <select name="patient_type_select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" disabled>
                                <option value="outpatient" selected>Outpatient</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Concern <span class="text-red-500">*</span></label>
                            <textarea name="medical_concern" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Describe the patient's medical condition, symptoms, or reason for visit..."></textarea>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="border-t border-gray-200 p-6 flex justify-end space-x-3">
                <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Save Patient
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Bed number population based on room selection
    document.getElementById('room_ward')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const availableBeds = parseInt(selectedOption.getAttribute('data-beds') || 0);
        const bedSelect = document.getElementById('bed_number');
        
        if (bedSelect) {
            bedSelect.innerHTML = '<option value="">Select Bed</option>';
            for (let i = 1; i <= availableBeds; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Bed ${i}`;
                bedSelect.appendChild(option);
            }
        }
    });


    // City/barangay population for inpatient
    document.getElementById('province')?.addEventListener('change', function() {
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');
        
        if (citySelect) {
            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
            // Add sample cities based on province
            const cities = {
                'Metro Manila': ['Manila', 'Quezon City', 'Makati', 'Pasig', 'Taguig', 'Mandaluyong', 'San Juan', 'Marikina'],
                'Cavite': ['Bacoor', 'Imus', 'Dasmarinas', 'Tagaytay', 'Cavite City', 'Trece Martires'],
                'Laguna': ['Calamba', 'San Pedro', 'Santa Rosa', 'Los Baños', 'Biñan', 'San Pablo'],
                'Batangas': ['Batangas City', 'Lipa', 'Tanauan', 'Calaca', 'Nasugbu'],
                'Rizal': ['Antipolo', 'Taytay', 'Cainta', 'Angono', 'Binangonan'],
                'Quezon': ['Lucena', 'Tayabas', 'Gumaca', 'Sariaya'],
                'Bulacan': ['Malolos', 'Meycauayan', 'San Jose del Monte', 'Baliuag'],
                'Pampanga': ['San Fernando', 'Angeles', 'Mabalacat', 'Apalit']
            };
            
            const provinceCities = cities[this.value] || [];
            provinceCities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }
        
        if (barangaySelect) {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        }
    });

    document.getElementById('city')?.addEventListener('change', function() {
        const barangaySelect = document.getElementById('barangay');
        if (barangaySelect && this.value) {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            // Add sample barangays
            const sampleBarangays = ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6', 'Barangay 7', 'Barangay 8'];
            sampleBarangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
        }
    });

    // City/barangay population for outpatient
    document.getElementById('province_outpatient')?.addEventListener('change', function() {
        const citySelect = document.getElementById('city_outpatient');
        const barangaySelect = document.getElementById('barangay_outpatient');
        
        if (citySelect) {
            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
            // Add sample cities based on province
            const cities = {
                'Metro Manila': ['Manila', 'Quezon City', 'Makati', 'Pasig', 'Taguig', 'Mandaluyong', 'San Juan', 'Marikina'],
                'Cavite': ['Bacoor', 'Imus', 'Dasmarinas', 'Tagaytay', 'Cavite City', 'Trece Martires'],
                'Laguna': ['Calamba', 'San Pedro', 'Santa Rosa', 'Los Baños', 'Biñan', 'San Pablo'],
                'Batangas': ['Batangas City', 'Lipa', 'Tanauan', 'Calaca', 'Nasugbu'],
                'Rizal': ['Antipolo', 'Taytay', 'Cainta', 'Angono', 'Binangonan'],
                'Quezon': ['Lucena', 'Tayabas', 'Gumaca', 'Sariaya'],
                'Bulacan': ['Malolos', 'Meycauayan', 'San Jose del Monte', 'Baliuag'],
                'Pampanga': ['San Fernando', 'Angeles', 'Mabalacat', 'Apalit'],
                'Nueva Ecija': ['Cabanatuan', 'Gapan', 'San Jose', 'Palayan'],
                'Tarlac': ['Tarlac City', 'Concepcion', 'Capas', 'Bamban'],
                'Zambales': ['Olongapo', 'Subic', 'Iba', 'Castillejos'],
                'Bataan': ['Balanga', 'Mariveles', 'Dinalupihan', 'Orion']
            };
            
            const provinceCities = cities[this.value] || [];
            provinceCities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }
        
        if (barangaySelect) {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        }
    });

    document.getElementById('city_outpatient')?.addEventListener('change', function() {
        const barangaySelect = document.getElementById('barangay_outpatient');
        if (barangaySelect && this.value) {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            // Add sample barangays
            const sampleBarangays = ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6', 'Barangay 7', 'Barangay 8'];
            sampleBarangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
        }
    });
</script>
<?= $this->endSection() ?>
