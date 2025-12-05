<?= $this->extend('templates/layout') ?>

<?= $this->section('content') ?>
<div class="gradient-bg min-h-screen">
    <!-- Header -->
<<<<<<< HEAD
    <header class="px-6 py-4 flex items-center justify-between">
        <!-- Logo and Brand -->
        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3V8zM4 6h10v2H4V6zm0 4h10v2H4v-2zm0 4h7v2H4v-2z"/>
                <path d="M2 4h14v16H2V4zm2 2v12h10V6H4z" fill="rgba(147, 51, 234, 0.1)"/>
            </svg>
            <h1 class="text-xl font-bold text-gray-800">GLOBAL HOSPITALS</h1>
=======
    <header class="px-6 py-4 flex items-center justify-between relative z-10">
        <!-- Logo and Brand -->
        <div class="flex items-center space-x-3">
            <img src="<?= base_url('images/logo.svg') ?>" alt="St. Elizabeth Hospital Logo" class="w-12 h-12 rounded-full">
            <h1 class="text-xl font-bold text-white">St. Elizabeth Hospital, Inc.</h1>
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        </div>
        
        <!-- Navigation -->
        <nav class="flex items-center space-x-6">
<<<<<<< HEAD
            <a href="<?= site_url('/') ?>" class="text-gray-800 hover:text-blue-600 font-medium">Home</a>
            <a href="#" class="text-gray-800 hover:text-blue-600 font-medium">About Us</a>
            <a href="#" class="text-gray-800 hover:text-blue-600 font-medium">Contact</a>
=======
            <a href="<?= site_url('/') ?>" class="text-white font-medium hover:underline">Home</a>
            <a href="#" class="text-white font-medium hover:underline">About Us</a>
            <a href="#" class="text-white font-medium hover:underline">Contact</a>
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
        </nav>
    </header>

    <!-- Main Content - Login Card -->
<<<<<<< HEAD
    <div class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Title -->
                <div class="px-6 pt-6 pb-4">
                    <h2 class="text-xl font-bold text-gray-800">Login</h2>
                </div>

                <!-- Success Message -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="px-6 pt-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline"><?= esc(session()->getFlashdata('success')) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="px-6 pt-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline"><?= esc(session()->getFlashdata('error')) ?></span>
=======
    <div class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4 relative z-10">
        <div class="w-full max-w-md">
            <div class="glass-card rounded-2xl overflow-hidden">
                <!-- Title Section with Icon -->
                <div class="px-8 pt-8 pb-6 bg-gradient-to-r from-blue-500 to-purple-600">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-circle text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
                    </div>
                    <p class="text-blue-100 text-sm">Sign in to access your account</p>
                </div>

                <!-- Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="px-8 pt-6">
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span><?= esc(session()->getFlashdata('error')) ?></span>
                            </div>
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
<<<<<<< HEAD
                <form class="px-6 pb-6" action="auth/authenticate" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    
                    <!-- Email Field -->
                    <div class="mb-4">
                        <label class="block text-gray-800 text-sm font-medium mb-2" for="email">
                            Email
                        </label>
                        <input 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            id="email" 
                            type="email" 
                            name="email" 
                            placeholder="you@example.com"
                            required
                        >
                    </div>
                    
                    <!-- Password Field -->
                    <div class="mb-4">
                        <label class="block text-gray-800 text-sm font-medium mb-2" for="password">
                            Password
                        </label>
                        <input 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            id="password" 
                            type="password" 
                            name="password" 
                            required
                        >
=======
                <form class="px-8 pb-8 pt-6" action="auth/authenticate" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="role" id="roleInput" value="admin">
                    
                    <!-- Email Field -->
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                            Email Address
                        </label>
                        <div class="input-group">
                            <input 
                                class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                id="email" 
                                type="email" 
                                name="email" 
                                placeholder="you@example.com"
                                required
                            >
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                            Password
                        </label>
                        <div class="input-group">
                            <input 
                                class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                id="password" 
                                type="password" 
                                name="password" 
                                placeholder="Enter your password"
                                required
                            >
                            <i class="fas fa-lock input-icon"></i>
                        </div>
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input 
                                id="remember_me" 
                                name="remember_me" 
                                type="checkbox" 
<<<<<<< HEAD
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-800">
=======
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer">
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
<<<<<<< HEAD
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
=======
                            <a href="#" class="font-semibold text-purple-600 hover:text-purple-700 transition-colors">
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
                                Forgot password?
                            </a>
                        </div>
                    </div>
                    
                    <!-- Login Button -->
                    <button 
                        type="submit" 
<<<<<<< HEAD
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium transition-colors"
                    >
                        Login
                    </button>
                    
                    <!-- Register Link -->
                    <div class="mt-4 text-center text-sm">
                        <span class="text-gray-800">Don't have an account?</span>
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500 ml-1">
                            Register
=======
                        class="btn-login w-full text-white py-3.5 px-4 rounded-xl font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 relative z-10"
                    >
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </span>
                    </button>
                    
                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <span class="text-gray-600 text-sm">Don't have an account?</span>
                        <a href="#" class="font-semibold text-purple-600 hover:text-purple-700 ml-1 transition-colors">
                            Create Account
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<<<<<<< HEAD
<?= $this->endSection() ?>
=======

<?= $this->endSection() ?>
>>>>>>> 3bfa254a216ebb6a1c45607fb87bcfe8a1c479b4
