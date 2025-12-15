<!-- Patient Type Selection Modal -->
<div id="patientTypeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gradient-to-br from-purple-600 via-blue-600 to-purple-800 opacity-75 backdrop-blur-sm" onclick="closePatientTypeModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-teal-500 to-green-500 p-8 relative">
                <button type="button" onclick="closePatientTypeModal()" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center text-white hover:text-gray-200 hover:bg-white hover:bg-opacity-20 rounded-full transition-all z-10" title="Close">
                    <i class="fas fa-times text-2xl font-bold"></i>
                </button>
                
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-2">Register New Patient</h1>
                    <p class="text-white text-opacity-90 text-lg">Select the type of patient you're registering</p>
                </div>
            </div>

            <!-- Content Section -->
            <div class="bg-white p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Outpatient Card -->
                    <a href="<?= base_url('patients/create?type=outpatient') ?>" class="block">
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-8 shadow-lg hover:border-blue-400 hover:shadow-xl cursor-pointer h-full transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-stethoscope text-blue-600 text-3xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Outpatient</h2>
                                <p class="text-green-600 font-semibold mb-4 text-sm">WALK-IN / CONSULTATION</p>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    For patients who will visit for consultation, check-up, or minor procedures and go home the same day.
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Inpatient Card -->
                    <a href="<?= base_url('patients/create?type=inpatient') ?>" class="block">
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-8 shadow-lg hover:border-pink-400 hover:shadow-xl cursor-pointer h-full transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-bed text-pink-600 text-3xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Inpatient</h2>
                                <p class="text-green-600 font-semibold mb-4 text-sm">ADMISSION / EMERGENCY</p>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    For patients requiring hospital admission, overnight stay, surgery, or emergency care.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Cancel Button -->
                <div class="mt-8 flex justify-center">
                    <button type="button" onclick="closePatientTypeModal()" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openPatientTypeModal() {
        document.getElementById('patientTypeModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePatientTypeModal() {
        document.getElementById('patientTypeModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePatientTypeModal();
        }
    });
</script>

