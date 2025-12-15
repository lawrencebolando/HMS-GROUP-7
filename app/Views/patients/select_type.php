<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Patient - HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .gradient-header {
            background: linear-gradient(135deg, #14b8a6 0%, #10b981 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body>
    <div class="w-full max-w-5xl">
        <!-- Header Section -->
        <div class="gradient-header rounded-t-2xl p-8 relative">
            <button onclick="window.history.back()" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">Register New Patient</h1>
                <p class="text-white text-opacity-90">Select the type of patient you're registering</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-b-2xl p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Outpatient Card -->
                <a href="<?= base_url('patients/create?type=outpatient') ?>" class="card-hover">
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-8 shadow-lg hover:border-blue-400 cursor-pointer h-full">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user-md text-blue-600 text-3xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Outpatient</h2>
                            <p class="text-green-600 font-semibold mb-4">WALK-IN / CONSULTATION</p>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                For patients who will visit for consultation, check-up, or minor procedures and go home the same day.
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Inpatient Card -->
                <a href="<?= base_url('patients/create?type=inpatient') ?>" class="card-hover">
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-8 shadow-lg hover:border-pink-400 cursor-pointer h-full">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hospital text-pink-600 text-3xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Inpatient</h2>
                            <p class="text-green-600 font-semibold mb-4">ADMISSION / EMERGENCY</p>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                For patients requiring hospital admission, overnight stay, surgery, or emergency care.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>

