<?= $this->extend('templates/doctor_layout') ?>

<?= $this->section('content') ?>
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Settings</h2>
        <p class="text-gray-600">Configure clinic hours, telemedicine, and notifications - Date: <?= date('F d, Y') ?></p>
    </div>

    <!-- Clinic Hours -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Clinic Hours</h3>
        <p class="text-gray-600 mb-4">Default availability shown to reception and patients</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                <input type="time" value="09:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                <input type="time" value="17:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slot Duration (minutes)</label>
                <input type="number" value="30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Telemedicine & Notifications -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Telemedicine & Notifications</h3>
        <p class="text-gray-600 mb-4">Enable video consults and automatic reminders</p>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telemedicine Sessions</label>
                    <p class="text-sm text-gray-500">Allow patients to book video consultations</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Enabled</span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Auto Notify Patients</label>
                    <p class="text-sm text-gray-500">Send automatic appointment reminders</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Yes</span>
            </div>
        </div>
    </div>

    <!-- Signature Block -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Signature Block</h3>
        <p class="text-gray-600 mb-4">Used on prescriptions, reports, and referrals</p>
        
        <div class="border-l-4 border-blue-500 pl-4">
            <p class="text-lg font-semibold text-gray-800">Dr. <?= esc($doctor_name) ?></p>
            <p class="text-gray-600">MediCare Hospital</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

