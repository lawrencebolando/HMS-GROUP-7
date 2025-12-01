<?= $this->extend('templates/layout') ?>

<?= $this->section('content') ?>
<div class="gradient-bg min-h-screen">
    <!-- Header -->
    <header class="px-6 py-4 flex items-center justify-between">
        <!-- Logo and Brand -->
        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3V8zM4 6h10v2H4V6zm0 4h10v2H4v-2zm0 4h7v2H4v-2z"/>
                <path d="M2 4h14v16H2V4zm2 2v12h10V6H4z" fill="rgba(147, 51, 234, 0.1)"/>
            </svg>
            <h1 class="text-xl font-bold text-gray-800">GLOBAL HOSPITALS</h1>
        </div>
        
        <!-- Navigation -->
        <nav class="flex items-center space-x-6">
            <a href="<?= site_url('/') ?>" class="text-gray-800 hover:text-blue-600 font-medium">Home</a>
            <a href="#" class="text-gray-800 hover:text-blue-600 font-medium">About Us</a>
            <a href="#" class="text-gray-800 hover:text-blue-600 font-medium">Contact</a>
        </nav>
    </header>

    <!-- Main Content - Login Card -->
    <div class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Title -->
                <div class="px-6 pt-6 pb-4">
                    <h2 class="text-xl font-bold text-gray-800">Login</h2>
                </div>

                <!-- Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="px-6 pt-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline"><?= esc(session()->getFlashdata('error')) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form class="px-6 pb-6" action="auth/authenticate" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="role" id="roleInput" value="patient">
                    
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
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input 
                                id="remember_me" 
                                name="remember_me" 
                                type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-800">
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                Forgot password?
                            </a>
                        </div>
                    </div>
                    
                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium transition-colors"
                    >
                        Login
                    </button>
                    
                    <!-- Register Link -->
                    <div class="mt-4 text-center text-sm">
                        <span class="text-gray-800">Don't have an account?</span>
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500 ml-1">
                            Register
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Debug: Log form action URL
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        if (form) {
            console.log('Form action URL:', form.action);
            console.log('Current page URL:', window.location.href);
        }
    });
</script>
<?= $this->endSection() ?>

