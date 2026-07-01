<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Onde-Onde Stock Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 flex items-center justify-center p-4 font-sans">
    <!-- Decorative circles -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

    <div class="relative w-full max-w-md">
        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-orange-100/50 border border-white/50 p-8 space-y-8">
            <!-- Logo / Brand -->
            <div class="text-center space-y-3">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-orange-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200/50 transform hover:scale-105 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Onde-Onde</h1>
                    <p class="text-sm text-gray-500 font-medium">Sistem Manajemen Stok</p>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-600 font-medium">{{ $errors->first() }}</p>
                </div>
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                               placeholder="admin@ondeonde.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required
                               class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-lg shadow-orange-200/50 hover:shadow-xl hover:shadow-orange-300/50 hover:from-orange-600 hover:to-amber-600 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                    Masuk
                </button>
            </form>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Onde-Onde Stock Manager v1.0
            </p>
        </div>
    </div>
</body>
</html>
