@extends('layouts.owner')
@section('title', 'Kelola Karyawan')
@section('page-title', 'Kelola Karyawan')
@section('page-subtitle', 'Manajemen akun Admin Gudang dan staf operasional')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div class="w-full sm:max-w-xs relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <form action="{{ route('owner.karyawan.index') }}" method="GET">
            <input type="text" name="search" value="{{ $searchQuery }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-white transition-shadow" placeholder="Cari nama atau email...">
        </form>
    </div>
    
    <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-700 transition-colors text-sm shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun Staf
    </button>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Daftar Admin Aktif & Nonaktif
        </h3>
        <span class="text-xs font-medium text-gray-500 bg-gray-200 px-2.5 py-1 rounded-full">{{ $karyawans->count() }} Karyawan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-4 font-semibold">Nama & Email</th>
                    <th class="px-5 py-4 font-semibold">Role</th>
                    <th class="px-5 py-4 font-semibold text-center">Status</th>
                    <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($karyawans as $k)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 font-bold flex items-center justify-center text-sm shadow-sm border border-blue-200/50">
                                {{ strtoupper(substr($k->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $k->name }}</p>
                                <p class="text-xs text-gray-500">{{ $k->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 capitalize">
                            {{ $k->role }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($k->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="openEditModal({{ $k->id }}, '{{ addslashes($k->name) }}', '{{ addslashes($k->email) }}', {{ $k->is_active ? 'true' : 'false' }})" class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form action="{{ route('owner.karyawan.destroy', $k->id) }}" method="POST" class="inline" data-confirm-delete="Apakah Anda yakin ingin menghapus permanen akun admin ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Permanen">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <p class="text-gray-500 font-medium">Belum ada akun karyawan yang terdaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Karyawan -->
<div id="modal-form" class="hidden fixed inset-0 z-[60] items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeFormModal()"></div>
    <div id="modal-form-content" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-200">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 id="modal-title" class="text-lg font-bold text-gray-800">Tambah Akun Karyawan</h3>
            <button type="button" onclick="closeFormModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form id="karyawan-form" method="POST" action="{{ route('owner.karyawan.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="input-name" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-gray-50 focus:bg-white transition-colors text-sm" placeholder="Contoh: Budi Santoso">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Karyawan</label>
                    <input type="email" name="email" id="input-email" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-gray-50 focus:bg-white transition-colors text-sm" placeholder="Contoh: budi@onde.com">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span id="password-hint" class="text-xs font-normal text-gray-400 hidden">(Kosongkan jika tidak ingin mengubah password)</span></label>
                    <input type="password" name="password" id="input-password" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-gray-50 focus:bg-white transition-colors text-sm" placeholder="Minimal 8 karakter">
                </div>

                <div id="status-container" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Akun</label>
                    <select name="is_active" id="input-status" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 bg-gray-50 focus:bg-white transition-colors text-sm">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeFormModal()" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">Batal</button>
                <button type="submit" id="btn-submit" class="flex-1 py-2.5 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-700 transition-colors shadow-sm text-sm">Simpan Karyawan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modal-title').textContent = 'Tambah Akun Karyawan';
        document.getElementById('karyawan-form').action = "{{ route('owner.karyawan.store') }}";
        document.getElementById('form-method').value = 'POST';
        
        document.getElementById('input-name').value = '';
        document.getElementById('input-email').value = '';
        document.getElementById('input-password').value = '';
        document.getElementById('input-password').required = true;
        
        document.getElementById('password-hint').classList.add('hidden');
        document.getElementById('status-container').classList.add('hidden');
        document.getElementById('btn-submit').textContent = 'Simpan Karyawan';
        
        showModal();
    }

    function openEditModal(id, name, email, isActive) {
        document.getElementById('modal-title').textContent = 'Edit Akun Karyawan';
        document.getElementById('karyawan-form').action = `/owner/karyawan/${id}`;
        document.getElementById('form-method').value = 'PUT';
        
        document.getElementById('input-name').value = name;
        document.getElementById('input-email').value = email;
        document.getElementById('input-password').value = '';
        document.getElementById('input-password').required = false;
        
        document.getElementById('input-status').value = isActive ? "1" : "0";
        
        document.getElementById('password-hint').classList.remove('hidden');
        document.getElementById('status-container').classList.remove('hidden');
        document.getElementById('btn-submit').textContent = 'Update Data';
        
        showModal();
    }

    function showModal() {
        const modal = document.getElementById('modal-form');
        const content = document.getElementById('modal-form-content');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeFormModal() {
        const modal = document.getElementById('modal-form');
        const content = document.getElementById('modal-form-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>
@endsection
