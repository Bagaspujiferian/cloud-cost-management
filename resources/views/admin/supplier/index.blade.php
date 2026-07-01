@extends('layouts.admin')
@section('title', 'Supplier')
@section('page-title', 'Supplier')
@section('page-subtitle', 'Kelola daftar pemasok bahan baku')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h3 class="font-semibold text-gray-800">Daftar Supplier</h3>
        <button onclick="openModal('modal-tambah-supplier')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md hover:from-orange-600 hover:to-amber-600 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Supplier
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                <th class="px-5 py-3">#</th><th class="px-5 py-3">Nama Supplier</th><th class="px-5 py-3">Telepon</th><th class="px-5 py-3">Alamat</th><th class="px-5 py-3 text-center">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suppliers as $i => $supplier)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $supplier->nama }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $supplier->telepon ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600 max-w-xs truncate">{{ $supplier->alamat ?? '-' }}</td>
                    <td class="px-5 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="editSupplier({{ json_encode($supplier) }})" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.supplier.destroy', $supplier) }}" data-confirm-delete="Yakin ingin menghapus supplier &quot;{{ $supplier->nama }}&quot;? Data yang sudah dihapus tidak bisa dikembalikan.">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada supplier</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-supplier" class="modal-wrapper hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Tambah Supplier</h3>
            <button onclick="closeModal('modal-tambah-supplier')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="{{ route('admin.supplier.store') }}" class="p-6 space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label><input type="text" name="nama" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm" placeholder="Nama toko/supplier"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label><input type="text" name="telepon" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm" placeholder="08xxxxxxxxxx"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label><textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm" placeholder="Alamat lengkap"></textarea></div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-supplier" class="modal-wrapper hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Edit Supplier</h3>
            <button onclick="closeModal('modal-edit-supplier')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form id="form-edit-supplier" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label><input type="text" name="nama" id="edit-supplier-nama" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label><input type="text" name="telepon" id="edit-supplier-telepon" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label><textarea name="alamat" id="edit-supplier-alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm"></textarea></div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">Perbarui</button>
        </form>
    </div>
</div>

<script>
function editSupplier(s) {
    document.getElementById('form-edit-supplier').action = '/admin/supplier/' + s.id;
    document.getElementById('edit-supplier-nama').value = s.nama;
    document.getElementById('edit-supplier-telepon').value = s.telepon || '';
    document.getElementById('edit-supplier-alamat').value = s.alamat || '';
    openModal('modal-edit-supplier');
}
</script>
@endsection
