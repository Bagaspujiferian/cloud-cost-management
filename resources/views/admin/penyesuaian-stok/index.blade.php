@extends('layouts.admin')
@section('title', 'Penyesuaian Stok')
@section('page-title', 'Penyesuaian Stok')
@section('page-subtitle', 'Koreksi stok tanpa menghapus data — menjaga audit trail')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Form -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm sticky top-4">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">🔧 Penyesuaian Baru</h3>
            </div>
            <form method="POST" action="{{ route('admin.penyesuaian-stok.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Item</label>
                    <select name="tipe" id="tipe-penyesuaian" required onchange="updateItemList()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400">
                        <option value="bahan_baku">Bahan Baku</option>
                        <option value="produk_jadi">Produk Jadi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Item</label>
                    <select name="item_id" id="item-select" required onchange="showCurrentStock()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400">
                        <option value="">Pilih item...</option>
                    </select>
                </div>

                <div id="stok-info" class="hidden p-3 bg-blue-50 rounded-xl">
                    <p class="text-xs text-blue-600">Stok Tercatat Saat Ini: <span id="stok-tercatat" class="font-bold">-</span></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Aktual (Fisik) <span class="text-xs font-normal text-red-500">(Hanya untuk mengurangi)</span></label>
                    <input type="number" id="input-stok-aktual" name="stok_aktual" required step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400" placeholder="Harus lebih kecil dari stok tercatat">
                    <p class="text-[11px] text-gray-500 mt-1">⚠️ Fitur ini hanya untuk mencatat barang hilang/rusak (mengurangi stok). Untuk **menambah**, gunakan Belanja/Produksi.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan <span class="text-red-500">*</span></label>
                    <textarea name="keterangan" required rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400" placeholder="Contoh: Koreksi salah input produksi, Bahan rusak, dll."></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">📝 Simpan Penyesuaian</button>
            </form>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Riwayat Penyesuaian Stok</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="px-5 py-3">Waktu</th><th class="px-5 py-3">Item</th><th class="px-5 py-3 text-right">Tercatat</th><th class="px-5 py-3 text-right">Aktual</th><th class="px-5 py-3 text-right">Selisih</th><th class="px-5 py-3">Keterangan</th><th class="px-5 py-3">Oleh</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($penyesuaians as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">
                                {{ $p->tipe === 'bahan_baku' ? ($p->bahanBaku->nama_bahan ?? '-') : ($p->produk->nama_produk ?? '-') }}
                                <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] {{ $p->tipe === 'bahan_baku' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">{{ $p->tipe === 'bahan_baku' ? 'Bahan' : 'Produk' }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-gray-600">{{ number_format($p->stok_tercatat, 0) }}</td>
                            <td class="px-5 py-3 text-right text-gray-600">{{ number_format($p->stok_aktual, 0) }}</td>
                            <td class="px-5 py-3 text-right font-semibold {{ $p->selisih >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $p->selisih >= 0 ? '+' : '' }}{{ number_format($p->selisih, 0) }}</td>
                            <td class="px-5 py-3 text-gray-600 max-w-xs truncate">{{ $p->keterangan }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->user->name }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada penyesuaian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const bahanBakus = @json($bahanBakus);
const produks = @json($produks);

function updateItemList() {
    const tipe = document.getElementById('tipe-penyesuaian').value;
    const select = document.getElementById('item-select');
    select.innerHTML = '<option value="">Pilih item...</option>';
    const items = tipe === 'bahan_baku' ? bahanBakus : produks;
    items.forEach(item => {
        const name = tipe === 'bahan_baku' ? item.nama_bahan : item.nama_produk;
        select.innerHTML += `<option value="${item.id}" data-stok="${tipe === 'bahan_baku' ? item.stok_saat_ini : item.stok_jadi}">${name}</option>`;
    });
    document.getElementById('stok-info').classList.add('hidden');
}

function showCurrentStock() {
    const select = document.getElementById('item-select');
    const opt = select.options[select.selectedIndex];
    const inputAktual = document.getElementById('input-stok-aktual');
    
    if (opt.value) {
        const stokTercatat = parseFloat(opt.dataset.stok);
        document.getElementById('stok-tercatat').textContent = stokTercatat.toLocaleString();
        document.getElementById('stok-info').classList.remove('hidden');
        
        // Prevent increasing stock via HTML5 validation
        // Must be less than current stock
        inputAktual.max = stokTercatat - 0.01;
    } else {
        document.getElementById('stok-info').classList.add('hidden');
        inputAktual.removeAttribute('max');
    }
}

// Initialize
updateItemList();
</script>
@endsection
