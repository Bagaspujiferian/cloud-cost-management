@extends('layouts.admin')
@section('title', 'Belanja Bahan')
@section('page-title', 'Belanja Bahan (Barang Masuk)')
@section('page-subtitle', 'Catat penerimaan barang dari supplier')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Form Input -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm sticky top-4">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">📦 Catat Belanja Baru</h3>
            </div>
            <form method="POST" action="{{ route('admin.barang-masuk.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400">
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400">
                </div>

                <div id="barang-rows" class="space-y-3">
                    <div class="p-3 bg-gray-50 rounded-xl space-y-2">
                        <select name="bahan_baku_id[]" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400/50">
                            <option value="">Pilih Bahan</option>
                            @foreach($bahanBakus as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan }})</option>
                            @endforeach
                        </select>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="jumlah[]" required step="0.01" min="0.01" placeholder="Jumlah" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400/50">
                            <input type="number" name="harga_satuan[]" step="0.01" min="0" placeholder="Harga/satuan" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400/50">
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addBarangRow()" class="w-full py-2 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-orange-400 hover:text-orange-500 transition-colors">+ Tambah Bahan</button>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400" placeholder="Catatan tambahan..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">💾 Simpan Penerimaan</button>
            </form>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Riwayat Penerimaan Barang</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="px-5 py-3">Kode</th><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Supplier</th><th class="px-5 py-3">Item</th><th class="px-5 py-3">Oleh</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($barangMasuks as $bm)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $bm->kode_masuk }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $bm->tanggal_masuk->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $bm->supplier->nama }}</td>
                            <td class="px-5 py-3">
                                @foreach($bm->details as $d)
                                <span class="inline-block mr-1 mb-1 px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs">{{ $d->bahanBaku->nama_bahan }} ({{ number_format($d->jumlah, 0) }})</span>
                                @endforeach
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $bm->user->name }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada riwayat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function addBarangRow() {
    const container = document.getElementById('barang-rows');
    const row = document.createElement('div');
    row.className = 'p-3 bg-gray-50 rounded-xl space-y-2 relative';
    row.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 p-1 text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        <select name="bahan_baku_id[]" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400/50">
            <option value="">Pilih Bahan</option>
            @foreach($bahanBakus as $b)<option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ $b->satuan }})</option>@endforeach
        </select>
        <div class="grid grid-cols-2 gap-2">
            <input type="number" name="jumlah[]" required step="0.01" min="0.01" placeholder="Jumlah" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400/50">
            <input type="number" name="harga_satuan[]" step="0.01" min="0" placeholder="Harga/satuan" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-400/50">
        </div>
    `;
    container.appendChild(row);
}
</script>
@endsection
