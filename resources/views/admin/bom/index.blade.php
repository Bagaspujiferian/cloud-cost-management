@extends('layouts.admin')
@section('title', 'Buku Resep (BOM)')
@section('page-title', 'Buku Resep (BOM)')
@section('page-subtitle', 'Kelola komposisi bahan per produk')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Resep Produk</h2>
        <button onclick="openBomBaruModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Menu Baru + Resep
        </button>
    </div>

    @foreach($produks as $produk)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-amber-500 rounded-xl flex items-center justify-center text-white text-xs font-bold">OO</div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $produk->nama_produk }}</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-xs text-gray-400">{{ $produk->kode_produk }} · Versi: {{ $produk->activeBom->versi ?? '-' }}</p>
                        @if($produk->activeBom && $produk->activeBom->jenis_resep)
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 capitalize">{{ $produk->activeBom->jenis_resep }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <button onclick="openBomUpdateModal({{ $produk->id }}, '{{ addslashes($produk->nama_produk) }}', '{{ $produk->activeBom->jenis_resep ?? 'standar' }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 text-orange-600 text-sm font-semibold rounded-xl hover:bg-orange-100 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ $produk->activeBom ? 'Update Resep' : 'Buat Resep' }}
            </button>
        </div>
        @if($produk->activeBom && $produk->activeBom->details->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                    <th class="px-5 py-3">Bahan Baku</th><th class="px-5 py-3">Kategori</th><th class="px-5 py-3 text-right">Kuantitas per Pcs</th><th class="px-5 py-3">Satuan</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($produk->activeBom->details as $detail)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $detail->bahanBaku->nama_bahan }}</td>
                        <td class="px-5 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $detail->bahanBaku->kategori == 'bahan_utama' ? 'bg-blue-100 text-blue-700' : ($detail->bahanBaku->kategori == 'bahan_isi' ? 'bg-purple-100 text-purple-700' : 'bg-teal-100 text-teal-700') }}">{{ str_replace('_', ' ', ucfirst($detail->bahanBaku->kategori)) }}</span></td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-800">{{ number_format($detail->kuantitas, 1) }}</td>
                        <td class="px-5 py-3 text-gray-600 capitalize">{{ $detail->bahanBaku->satuan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-400 text-sm">Belum ada resep. Klik "Buat Resep" untuk menambahkan komposisi.</div>
        @endif
    </div>
    @endforeach
</div>

<!-- Modal BOM Baru -->
<div id="modal-bom-baru" class="modal-wrapper hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 opacity-0 transition-all duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Buat Menu Baru & Resep</h3>
            <button onclick="closeModal('modal-bom-baru')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="{{ route('admin.bom.store') }}" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            <input type="hidden" name="produk_id" value="new">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu/Produk Baru</label>
                <input type="text" name="nama_produk_baru" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm" placeholder="Contoh: Onde-onde Coklat Keju">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Resep</label>
                <select name="jenis_resep" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
                    <option value="standar">Resep Standar (Utama)</option>
                    <option value="alternatif">Resep Alternatif</option>
                    <option value="khusus">Resep Khusus (Custom)</option>
                </select>
            </div>

            <hr class="border-gray-100 my-2">
            <h4 class="text-sm font-semibold text-gray-700">Komposisi Bahan</h4>

            <div id="bom-rows-baru" class="space-y-3">
                <!-- Dynamic rows added by JS -->
            </div>
            <button type="button" onclick="addBomRow('baru')" class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-orange-400 hover:text-orange-500 transition-colors">+ Tambah Komposisi</button>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">Simpan Resep Baru</button>
        </form>
    </div>
</div>

<!-- Modal BOM Update -->
<div id="modal-bom-update" class="modal-wrapper hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 opacity-0 transition-all duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800" id="bom-update-modal-title">Update Resep</h3>
            <button onclick="closeModal('modal-bom-update')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="{{ route('admin.bom.store') }}" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            <input type="hidden" name="produk_id" id="bom-update-produk-id">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Resep</label>
                <select name="jenis_resep" id="bom-update-jenis-resep" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all text-sm">
                    <option value="standar">Resep Standar (Utama)</option>
                    <option value="alternatif">Resep Alternatif</option>
                    <option value="khusus">Resep Khusus (Custom)</option>
                </select>
            </div>

            <hr class="border-gray-100 my-2">
            <h4 class="text-sm font-semibold text-gray-700">Komposisi Bahan</h4>

            <div id="bom-rows-update" class="space-y-3">
                <!-- Dynamic rows added by JS -->
            </div>
            <button type="button" onclick="addBomRow('update')" class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-orange-400 hover:text-orange-500 transition-colors">+ Tambah Komposisi</button>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">Perbarui Resep</button>
        </form>
    </div>
</div>

<script>
const bahanBakuList = @json($bahanBakus);

function openBomBaruModal() {
    document.getElementById('bom-rows-baru').innerHTML = '';
    addBomRow('baru');
    openModal('modal-bom-baru');
}

function openBomUpdateModal(produkId, produkNama, jenisResep = 'standar') {
    document.getElementById('bom-update-modal-title').textContent = 'Update Resep: ' + produkNama;
    document.getElementById('bom-update-produk-id').value = produkId;
    document.getElementById('bom-update-jenis-resep').value = jenisResep;
    
    document.getElementById('bom-rows-update').innerHTML = '';
    addBomRow('update');
    openModal('modal-bom-update');
}

function addBomRow(type) {
    const container = document.getElementById('bom-rows-' + type);
    const options = bahanBakuList.map(b => `<option value="${b.id}">${b.nama_bahan} (${b.satuan})</option>`).join('');
    const row = document.createElement('div');
    row.className = 'flex items-end gap-3';
    row.innerHTML = `
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-600 mb-1">Bahan Baku</label>
            <select name="bahan_baku_id[]" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400">${options}</select>
        </div>
        <div class="w-28">
            <label class="block text-xs font-medium text-gray-600 mb-1">Kuantitas</label>
            <input type="number" name="kuantitas[]" required step="0.01" min="0.01" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400" placeholder="0">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
    `;
    container.appendChild(row);
}
</script>
@endsection
