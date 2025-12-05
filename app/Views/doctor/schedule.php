<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            <i class="fas fa-calendar-check mr-2"></i>My Schedule
        </h2>
        <p class="text-gray-600">Manage your monthly availability schedule - <?= date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)) ?></p>
    </div>

    <!-- Success Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <div class="flex space-x-2">
            <button onclick="navigateMonth('prev')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                ← Prev
            </button>
            <button onclick="navigateMonth('today')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Today
            </button>
            <button onclick="navigateMonth('next')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Next →
            </button>
            <button onclick="openEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Schedule
            </button>
        </div>
        <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Schedule
        </button>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-7 gap-2">
            <!-- Day Headers -->
            <div class="text-center font-semibold text-gray-700 py-2">MON</div>
            <div class="text-center font-semibold text-gray-700 py-2">TUE</div>
            <div class="text-center font-semibold text-gray-700 py-2">WED</div>
            <div class="text-center font-semibold text-gray-700 py-2">THU</div>
            <div class="text-center font-semibold text-gray-700 py-2">FRI</div>
            <div class="text-center font-semibold text-gray-700 py-2">SAT</div>
            <div class="text-center font-semibold text-gray-700 py-2">SUN</div>

            <!-- Calendar Days -->
            <?php
            $firstDay = date('Y-m-01', mktime(0, 0, 0, $current_month, 1, $current_year));
            $lastDay = date('Y-m-t', mktime(0, 0, 0, $current_month, 1, $current_year));
            $startDay = date('w', strtotime($firstDay));
            $startDay = $startDay == 0 ? 6 : $startDay - 1; // Monday = 0
            
            $daysInMonth = date('t', strtotime($firstDay));
            $today = date('Y-m-d');
            
            // Generate calendar days
            $days = [];
            // Empty cells for days before month starts
            for ($i = 0; $i < $startDay; $i++) {
                $days[] = null;
            }
            // Days of the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $days[] = $day;
            }
            
            foreach ($days as $index => $day):
                $isWeekend = ($index % 7) >= 5;
                $dateStr = $day ? sprintf('%04d-%02d-%02d', $current_year, $current_month, $day) : '';
                $isToday = $dateStr === $today;
                $dayAppointments = isset($appointments_by_date[$dateStr]) ? $appointments_by_date[$dateStr] : [];
            ?>
                <div 
                    class="border border-gray-200 rounded-lg p-2 min-h-[100px] cursor-pointer hover:bg-gray-50 transition-colors <?= $isToday ? 'border-blue-500 border-2 bg-blue-50' : '' ?>"
                    onclick="<?= $day ? "openDayModal('$dateStr', $day)" : '' ?>"
                >
                    <?php if ($day): ?>
                        <div class="text-sm font-semibold text-gray-700 mb-1"><?= $day ?></div>
                        <?php if (!$isWeekend): ?>
                            <div class="bg-green-100 text-green-800 text-xs p-1 rounded mb-1">6:00 PM - 6:00 AM</div>
                        <?php else: ?>
                            <div class="bg-red-100 text-red-800 text-xs p-1 rounded mb-1">Not Available</div>
                        <?php endif; ?>
                        
                        <!-- Show appointments for this day -->
                        <?php if (!empty($dayAppointments)): ?>
                            <?php foreach (array_slice($dayAppointments, 0, 2) as $appt): ?>
                                <?php
                                $patient = $appt['patient'] ?? null;
                                $patientName = $patient ? trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) : 'Unknown';
                                $shortName = strlen($patientName) > 15 ? substr($patientName, 0, 15) . '...' : $patientName;
                                ?>
                                <div class="bg-gray-100 text-gray-700 text-xs p-1 rounded mt-1">
                                    <i class="fas fa-user mr-1"></i><?= esc($shortName) ?><br>
                                    <?= date('g:i A', strtotime($appt['appointment_time'])) ?>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($dayAppointments) > 2): ?>
                                <div class="text-xs text-gray-500 mt-1">+<?= count($dayAppointments) - 2 ?> more</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div id="addScheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Add Schedule</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="addScheduleForm" action="<?= base_url('doctor/schedule/add') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="schedule_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input type="time" name="start_time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <input type="time" name="end_time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available</label>
                    <select name="is_available" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div id="editScheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Edit Schedule Settings</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editScheduleForm" action="<?= base_url('doctor/schedule/update') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Start Time</label>
                    <input type="time" name="default_start_time" value="18:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default End Time</label>
                    <input type="time" name="default_end_time" value="06:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Weekend Availability</label>
                    <select name="weekend_available" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="0">Not Available</option>
                        <option value="1">Available</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Day Details Modal -->
<div id="dayModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="dayModalTitle">Schedule Details</h3>
            <button onclick="closeDayModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="dayModalContent">
            <!-- Content will be loaded here -->
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeDayModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    const currentMonth = <?= $current_month ?>;
    const currentYear = <?= $current_year ?>;

    function navigateMonth(direction) {
        let month = currentMonth;
        let year = currentYear;
        
        if (direction === 'prev') {
            month--;
            if (month < 1) {
                month = 12;
                year--;
            }
        } else if (direction === 'next') {
            month++;
            if (month > 12) {
                month = 1;
                year++;
            }
        } else if (direction === 'today') {
            month = <?= date('m') ?>;
            year = <?= date('Y') ?>;
        }
        
        window.location.href = `<?= base_url('doctor/schedule') ?>?month=${month}&year=${year}`;
    }

    function openAddModal() {
        document.getElementById('addScheduleModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addScheduleModal').classList.add('hidden');
    }

    function openEditModal() {
        document.getElementById('editScheduleModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editScheduleModal').classList.add('hidden');
    }

    function openDayModal(dateStr, day) {
        const modal = document.getElementById('dayModal');
        const title = document.getElementById('dayModalTitle');
        const content = document.getElementById('dayModalContent');
        
        const monthName = '<?= date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)) ?>';
        title.textContent = `Schedule - ${day} ${monthName}`;
        
        // Get appointments for this day
        const appointments = <?= json_encode($appointments_by_date) ?>;
        const dayAppts = appointments[dateStr] || [];
        
        let html = `<p class="text-gray-600 mb-4">Schedule for ${dateStr}</p>`;
        
        if (dayAppts.length > 0) {
            html += '<div class="space-y-2">';
            dayAppts.forEach(appt => {
                const patientName = appt.patient ? 
                    `${appt.patient.first_name || ''} ${appt.patient.last_name || ''}`.trim() : 
                    'Unknown';
                const time = new Date('2000-01-01 ' + appt.appointment_time).toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit'});
                html += `
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">${patientName}</p>
                                <p class="text-sm text-gray-600">${time}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded ${appt.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">${appt.status}</span>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
        } else {
            html += '<p class="text-gray-500">No appointments scheduled for this day.</p>';
        }
        
        content.innerHTML = html;
        modal.classList.remove('hidden');
    }

    function closeDayModal() {
        document.getElementById('dayModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById('addScheduleModal');
        const editModal = document.getElementById('editScheduleModal');
        const dayModal = document.getElementById('dayModal');
        
        if (event.target === addModal) {
            closeAddModal();
        }
        if (event.target === editModal) {
            closeEditModal();
        }
        if (event.target === dayModal) {
            closeDayModal();
        }
    }
</script>
<?= $this->endSection() ?>
