@extends('layouts.admin')
@section('title', 'Bahan Baku')
@section('page-title', 'Bahan Baku')
@section('page-subtitle', 'Kelola daftar bahan mentah gudang')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h3 class="font-semibold text-gray-800">Daftar Bahan Baku</h3>
        <button onclick="openModal('modal-tambah-bahan')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md hover:from-orange-600 hover:to-amber-600 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Bahan
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                <th class="px-5 py-3">Kode</th><th class="px-5 py-3">Nama Bahan</th><th class="px-5 py-3">Kategori</th><th class="px-5 py-3">Satuan</th><th class="px-5 py-3 text-right">Stok</th><th class="px-5 py-3 text-right">Batas Min</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Dipakai</th><th class="px-5 py-3 text-center">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bahanBakus as $bahan)
                @php $isUsed = $bahan->bom_details_count > 0 || $bahan->barang_masuk_details_count > 0; @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $bahan->kode_bahan }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $bahan->nama_bahan }}</td>
                    <td class="px-5 py-3"><span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $bahan->kategori == 'bahan_utama' ? 'bg-blue-100 text-blue-700' : ($bahan->kategori == 'bahan_isi' ? 'bg-purple-100 text-purple-700' : 'bg-teal-100 text-teal-700') }}">{{ str_replace('_', ' ', ucfirst($bahan->kategori)) }}</span></td>
                    <td class="px-5 py-3 text-gray-600 capitalize">{{ $bahan->satuan }}</td>
                    <td class="px-5 py-3 text-right font-semibold {{ $bahan->isStokRendah() ? 'text-red-600' : 'text-gray-800' }}">{{ number_format($bahan->stok_saat_ini, 0) }}</td>
                    <td class="px-5 py-3 text-right text-gray-500">{{ number_format($bahan->batas_minimum, 0) }}</td>
                    <td class="px-5 py-3">
                        @if($bahan->isStokRendah())
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium"><span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>Rendah</span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aman</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($isUsed)
                        <div class="flex flex-wrap gap-1">
                            @if($bahan->bom_details_count > 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-orange-100 text-orange-700 rounded text-[10px] font-medium">📋 Resep</span>
                            @endif
                            @if($bahan->barang_masuk_details_count > 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-medium">📦 Transaksi</span>
                            @endif
                        </div>
                        @else
                        <span class="text-xs text-gray-300">Belum dipakai</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="editBahan({{ json_encode($bahan) }})" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            @if($isUsed)
                            <span class="p-1.5 rounded-lg text-gray-300 cursor-not-allowed" title="Tidak bisa dihapus — sudah dipakai di resep/transaksi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            @else
                            <form method="POST" action="{{ route('admin.bahan-baku.destroy', $bahan) }}" data-confirm-delete="Yakin ingin menghapus bahan &quot;{{ $bahan->nama_bahan }}&quot;? Data yang sudah dihapus tidak bisa dikembalikan.">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-5 py-8 text-center text-gray-400">Belum ada bahan baku</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-bahan" class="modal-wrapper hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Tambah Bahan Baku</h3>
            <button onclick="closeModal('modal-tambah-bahan')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="{{ route('admin.bahan-baku.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan</label>
                <input type="text" name="nama_bahan" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm" placeholder="Contoh: Tepung Ketan">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
                    <option value="bahan_utama">Bahan Utama</option>
                    <option value="bahan_isi">Bahan Isi</option>
                    <option value="bahan_pelengkap">Bahan Pelengkap</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <select name="satuan" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
                    <option value="gram">Gram</option>
                    <option value="kg">Kg</option>
                    <option value="pcs">Pcs</option>
                    <option value="liter">Liter</option>
                    <option value="ml">Ml</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum</label>
                <input type="number" name="batas_minimum" required step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm" placeholder="0">
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md hover:from-orange-600 hover:to-amber-600 transition-all duration-200">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-bahan" class="modal-wrapper hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Edit Bahan Baku</h3>
            <button onclick="closeModal('modal-edit-bahan')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form id="form-edit-bahan" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan</label>
                <input type="text" name="nama_bahan" id="edit-nama-bahan" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori" id="edit-kategori" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
                    <option value="bahan_utama">Bahan Utama</option>
                    <option value="bahan_isi">Bahan Isi</option>
                    <option value="bahan_pelengkap">Bahan Pelengkap</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <select name="satuan" id="edit-satuan" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
                    <option value="gram">Gram</option>
                    <option value="kg">Kg</option>
                    <option value="pcs">Pcs</option>
                    <option value="liter">Liter</option>
                    <option value="ml">Ml</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum</label>
                <input type="number" name="batas_minimum" id="edit-batas-minimum" required step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">Perbarui</button>
        </form>
    </div>
</div>

<script>
function editBahan(bahan) {
    document.getElementById('form-edit-bahan').action = '/admin/bahan-baku/' + bahan.id;
    document.getElementById('edit-nama-bahan').value = bahan.nama_bahan;
    document.getElementById('edit-kategori').value = bahan.kategori;
    document.getElementById('edit-satuan').value = bahan.satuan;
    document.getElementById('edit-batas-minimum').value = bahan.batas_minimum;
    openModal('modal-edit-bahan');
}
</script>
@endsection
