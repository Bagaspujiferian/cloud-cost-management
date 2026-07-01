<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        Supplier::create($request->only(['nama', 'telepon', 'alamat']));

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $supplier->update($request->only(['nama', 'telepon', 'alamat']));

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->barangMasuks()->exists()) {
            return redirect()->route('admin.supplier.index')
                ->with('error', 'Supplier tidak bisa dihapus karena sudah digunakan dalam transaksi.');
        }

        $supplier->delete();
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil dihapus!');
    }
}
