<?php

namespace App\Http\Controllers;

use App\Models\item;
use App\Models\StockCard;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = item::all();

        return inertia('Items/Index', [
            'items' => $items,
        ]);
    }
    
    public function create()
    {
        return inertia('Items/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|numeric|digits_between:1,4',
            'unit' => 'required|string|max:30',
            'price' => 'required|numeric|max:9999999999999'
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')->with('success', 'Berhasil Menambahkan Barang');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);

        return inertia('Items/Edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:30',
            'price' => 'required|numeric|max:9999999999999',
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'Berhasil mengubah Stok');
    }

    public function editStock($id)
    {
        $item = Item::findOrFail($id);

        return inertia('Items/EditStock', [
            'item' => $item,
        ]);
    }
public function updateStock(Request $request, $id)
{
    $request->validate([
        'qty' => 'required|numeric|digits_between:1,4',
        'note' => 'required|in:in,out',
        'vendor' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);
    
    $item = Item::findOrFail($id);
    
    if ($request->note === 'in'){
        $item->increment('qty', $request->qty);
    }
    else if($request->note === 'out'){
        if($item->qty < $request->qty){
            return redirect()->route('items.index')->with('error', 'Stok yang dimasukan melebihi jumlah yang tersedia');
        }
        $item->decrement('qty', $request->qty);
    }

    // Perbaiki field vendor dan description
    StockCard::create([
        'items_id' => $item->id,
        'qty' => $request->qty,
        'note' => $request->note,
        'description' => $request->description,
        'vendor' => $request->vendor,
    ]);

    return redirect()->route('items.index')->with('success', 'Stok Item Berhasil dirubah');
}

    public function viewStockCard($id)
    {
        // Load stockCards dengan urutan terbaru pertama
        $item = Item::findOrFail($id)->loadMissing(['stockCards' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return inertia('Items/StockCard', [
            'item' => $item,
        ]);
    }

    public function printPreview()
{
    // Method ini hanya menampilkan halaman Vue untuk print.
    // Datanya sudah dikirim melalui client-side (sessionStorage).
    return inertia('Items/Print');
}

public function destroy($id)
{
    $item = item::findOrFail($id);

    // Hapus semua riwayat stok (data anak) yang terhubung dengan item ini
    $item->stockCards()->delete();

    // Setelah data anak dihapus, baru hapus data induknya
    $item->delete();

    return redirect()->route('items.index')->with('success', 'Barang dan semua riwayatnya berhasil dihapus.');
}
}
