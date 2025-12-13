<?= $this->extend('templates/layout') ?>

<?= $this->section('content') ?>
<div class="gradient-bg min-h-screen">
    <!-- Header -->
    <header class="px-6 py-4 flex items-center justify-between relative z-10">
        <!-- Logo and Brand -->
        <div class="flex items-center space-x-3">
            <img src="<?= base_url('images/logo.svg') ?>" alt="St. Elizabeth Hospital Logo" class="w-12 h-12 rounded-full">
            <h1 class="text-xl font-bold text-white">St. Elizabeth Hospital, Inc.</h1>
        </div>
        
        <!-- Navigation -->
        <nav class="flex items-center space-x-6">
            <a href="<?= site_url('/') ?>" class="text-white font-medium hover:underline">Home</a>
            <a href="#" class="text-white font-medium hover:underline">About Us</a>
            <a href="#" class="text-white font-medium hover:underline">Contact</a>
        </nav>
    </header>

    <!-- Main Content - Login Card -->
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

                <!-- Success Message -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="px-8 pt-6">
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span><?= esc(session()->getFlashdata('success')) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="px-8 pt-6">
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span><?= esc(session()->getFlashdata('error')) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form class="px-8 pb-8 pt-6" action="auth/authenticate" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    
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
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input 
                                id="remember_me" 
                                name="remember_me" 
                                type="checkbox" 
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-semibold text-purple-600 hover:text-purple-700 transition-colors">
                                Forgot password?
                            </a>
                        </div>
                    </div>
                    
                    <!-- Login Button -->
                    <button 
                        type="submit" 
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
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
