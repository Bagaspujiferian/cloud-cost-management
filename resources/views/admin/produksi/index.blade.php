@extends('layouts.admin')
@section('title', 'Catat Produksi')
@section('page-title', 'Catat Hasil Masak (Produksi)')
@section('page-subtitle', 'Laporkan hasil produksi — stok bahan otomatis terpotong')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Form -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm sticky top-4">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">🔥 Catat Produksi Baru</h3>
            </div>
            <form id="form-produksi" method="POST" action="{{ route('admin.produksi.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produk Jadi</label>
                    <select name="produk_id" id="select-produk" required onchange="showBomPreview()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400">
                        <option value="">Pilih Produk</option>
                        @foreach($produks as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Produksi</label>
                    <input type="date" name="tanggal_produksi" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Produksi (Pcs)</label>
                    <input type="number" name="jumlah_produksi" id="input-jumlah" required min="1" oninput="calculateDeduction()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400" placeholder="Contoh: 100">
                </div>

                <!-- BOM Preview -->
                <div id="bom-preview" class="hidden">
                    <p class="text-xs font-medium text-gray-500 mb-2">📋 Estimasi Bahan Terpakai:</p>
                    <div id="bom-preview-content" class="space-y-1"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan <span class="text-red-500">*</span></label>
                    <textarea name="catatan" required rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400" placeholder="Catatan wajib diisi untuk keperluan audit..."></textarea>
                </div>

                <div id="stock-warning" class="hidden p-3 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-xs font-bold text-red-600 mb-1">⚠️ Stok Tidak Cukup:</p>
                    <div id="stock-warning-content" class="text-xs text-red-600"></div>
                </div>
                <button type="button" id="btn-produksi" onclick="confirmProduksi()" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-teal-600 transition-all duration-200">⚡ PROSES PRODUKSI</button>
            </form>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Riwayat Produksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="px-5 py-3">Kode</th><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Produk</th><th class="px-5 py-3 text-right">Jumlah</th><th class="px-5 py-3">Bahan Terpakai</th><th class="px-5 py-3">Oleh</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($produksis as $pr)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $pr->kode_produksi }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $pr->tanggal_produksi->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $pr->produk->nama_produk }}</td>
                            <td class="px-5 py-3 text-right font-bold text-emerald-600">{{ number_format($pr->jumlah_produksi) }} pcs</td>
                            <td class="px-5 py-3">
                                @foreach($pr->details as $d)
                                <span class="inline-block mr-1 mb-1 px-2 py-0.5 bg-red-50 text-red-700 rounded text-xs">{{ $d->bahanBaku->nama_bahan }} (-{{ number_format($d->jumlah_terpakai, 0) }})</span>
                                @endforeach
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $pr->user->name }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada riwayat produksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const produks = @json($produks);
const bahanStoks = @json(\App\Models\BahanBaku::pluck('stok_saat_ini', 'id'));

function showBomPreview() {
    const produkId = document.getElementById('select-produk').value;
    const preview = document.getElementById('bom-preview');
    if (!produkId) { preview.classList.add('hidden'); return; }
    preview.classList.remove('hidden');
    calculateDeduction();
}

function calculateDeduction() {
    const produkId = document.getElementById('select-produk').value;
    const jumlah = parseInt(document.getElementById('input-jumlah').value) || 0;
    const content = document.getElementById('bom-preview-content');
    const produk = produks.find(p => p.id == produkId);
    const stockWarning = document.getElementById('stock-warning');
    const stockWarningContent = document.getElementById('stock-warning-content');

    if (!produk || !produk.active_bom) {
        content.innerHTML = '<p class="text-xs text-red-500">⚠️ Produk ini belum memiliki resep aktif!</p>';
        stockWarning.classList.add('hidden');
        return;
    }

    let html = '';
    let warnings = [];
    produk.active_bom.details.forEach(d => {
        const total = d.kuantitas * jumlah;
        const stokNow = parseFloat(bahanStoks[d.bahan_baku_id] || 0);
        const isInsufficient = stokNow < total && jumlah > 0;
        const bgColor = isInsufficient ? 'bg-red-50 border border-red-200' : 'bg-orange-50';
        const textColor = isInsufficient ? 'text-red-600 font-bold' : 'text-orange-600';
        
        html += `<div class="flex justify-between items-center px-3 py-1.5 ${bgColor} rounded-lg text-xs">
            <span class="text-gray-700">${d.bahan_baku.nama_bahan}</span>
            <span class="font-semibold ${textColor}">-${total.toLocaleString()} ${d.bahan_baku.satuan}</span>
        </div>`;
        
        if (isInsufficient) {
            warnings.push(`${d.bahan_baku.nama_bahan}: butuh ${total.toLocaleString()} ${d.bahan_baku.satuan}, tersedia ${stokNow.toLocaleString()} ${d.bahan_baku.satuan}`);
        }
    });
    content.innerHTML = html || '<p class="text-xs text-gray-400">Masukkan jumlah produksi</p>';
    
    if (warnings.length > 0) {
        stockWarning.classList.remove('hidden');
        stockWarningContent.innerHTML = warnings.map(w => `<p>• ${w}</p>`).join('');
    } else {
        stockWarning.classList.add('hidden');
    }
}

function confirmProduksi() {
    const form = document.getElementById('form-produksi');
    const produkId = document.getElementById('select-produk').value;
    const jumlah = document.getElementById('input-jumlah').value;
    
    if (!produkId) {
        alert('Pilih produk terlebih dahulu!');
        return;
    }
    if (!jumlah || jumlah < 1) {
        alert('Masukkan jumlah produksi!');
        return;
    }
    
    // Use the global delete modal but repurpose it for production confirmation
    deleteFormTarget = form;
    document.getElementById('delete-modal-message').textContent = 'Yakin proses produksi ' + jumlah + ' pcs? Stok bahan baku akan otomatis terpotong sesuai resep.';
    document.querySelector('#modal-confirm-delete h3').textContent = 'Proses Produksi?';
    document.querySelector('#modal-confirm-delete .bg-red-100').classList.replace('bg-red-100', 'bg-emerald-100');
    document.querySelector('#modal-confirm-delete .text-red-500').classList.replace('text-red-500', 'text-emerald-500');
    document.querySelector('#modal-confirm-delete svg path').setAttribute('d', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z');
    const confirmBtn = document.getElementById('btn-confirm-delete');
    confirmBtn.textContent = 'Ya, Proses!';
    confirmBtn.classList.replace('from-red-500', 'from-emerald-500');
    confirmBtn.classList.replace('to-red-600', 'to-emerald-600');
    confirmBtn.classList.replace('hover:from-red-600', 'hover:from-emerald-600');
    confirmBtn.classList.replace('hover:to-red-700', 'hover:to-emerald-700');
    openDeleteModal();
}
</script>
@endsection
