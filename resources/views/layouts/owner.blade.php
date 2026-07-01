<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Onde-Onde Stock Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50 text-gray-800 antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-sidebar-dark text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
            <!-- Brand -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-base leading-tight">Onde-Onde</h2>
                    <p class="text-[11px] text-gray-400">Stock Manager</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg shadow-orange-500/25' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard Eksekutif
                </a>

                <!-- Pantau Aset Gudang -->
                <a href="{{ route('owner.aset.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('owner.aset.*') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg shadow-orange-500/25' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Pantau Aset Gudang
                </a>

                <!-- Laporan & Audit -->
                <a href="{{ route('owner.laporan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('owner.laporan.*') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg shadow-orange-500/25' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan & Audit
                </a>

                <!-- Kelola Karyawan -->
                <a href="{{ route('owner.karyawan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('owner.karyawan.*') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg shadow-orange-500/25' : 'text-gray-300 hover:bg-sidebar-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Kelola Karyawan
                </a>
            </nav>

            <!-- User Info -->
            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button id="sidebar-toggle" class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-xs text-gray-500">@yield('page-subtitle', 'Selamat datang kembali!')</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-full text-xs font-medium">
                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></span>
                        Online
                    </span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert-auto-dismiss mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                @endif
                @if(session('error'))
                <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="text-sm font-bold">❌ Gagal!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                @if(session('warning'))
                <div class="mb-4 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="text-sm font-bold">⚠️ Perhatian</p>
                        <p class="text-sm">{{ session('warning') }}</p>
                    </div>
                </div>
                @endif
                @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                        <p class="text-sm font-bold">Terdapat kesalahan pada input:</p>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-7">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal (Global) -->
    <div id="modal-confirm-delete" class="hidden fixed inset-0 z-[60] items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div id="modal-confirm-content" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200 text-center">
            <div class="px-6 pt-8 pb-2">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Hapus Data?</h3>
                <p id="delete-modal-message" class="text-sm text-gray-500">Apakah kamu yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="flex gap-3 p-6">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200 text-sm">Batal</button>
                <button type="button" id="btn-confirm-delete" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-red-700 shadow-sm hover:shadow-md transition-all duration-200 text-sm">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script>
    let deleteFormTarget = null;

    // Attach to all forms with data-confirm-delete
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form[data-confirm-delete]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                deleteFormTarget = this;
                const msg = this.dataset.confirmDelete || 'Apakah kamu yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
                document.getElementById('delete-modal-message').textContent = msg;
                openDeleteModal();
            });
        });

        document.getElementById('btn-confirm-delete').addEventListener('click', function() {
            if (deleteFormTarget) {
                deleteFormTarget.removeAttribute('data-confirm-delete');
                deleteFormTarget.submit();
            }
        });
    });

    function openDeleteModal() {
        const modal = document.getElementById('modal-confirm-delete');
        const content = document.getElementById('modal-confirm-content');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modal-confirm-delete');
        const content = document.getElementById('modal-confirm-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            deleteFormTarget = null;
        }, 200);
    }
    </script>
</body>
</html>
