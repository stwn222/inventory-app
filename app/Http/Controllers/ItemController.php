<?php

namespace App\Http\Controllers;

use App\Models\item;
use App\Models\StockCard;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unit', 'like', "%{$search}%")
                  ->orWhere('qty', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");
            });
        }

        // Paginate dengan 10 item per halaman
        $items = $query->paginate(10)->appends($request->all());

        return inertia('Items/Index', [
            'items' => $items,
            'filters' => $request->only(['search']),
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

        StockCard::create([
            'items_id' => $item->id,
            'qty' => $request->qty,
            'note' => $request->note,
            'description' => $request->description,
            'vendor' => $request->vendor,
        ]);

        return redirect()->route('items.index')->with('success', 'Stok Item Berhasil dirubah');
    }

    public function viewStockCard(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        // Mulai query untuk stock cards
        $stockCardsQuery = $item->stockCards()
            ->orderBy('created_at', 'desc');

        // Terapkan filter jika ada
        if ($request->filled('search')) {
            $search = $request->search;
            $stockCardsQuery->where(function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                      ->orWhere('vendor', 'like', "%{$search}%");
            });
        }

        if ($request->filled('note') && $request->note != 'semua') {
            $stockCardsQuery->where('note', $request->note);
        }

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $stockCardsQuery->whereBetween('created_at', [$request->dari_tanggal . ' 00:00:00', $request->sampai_tanggal . ' 23:59:59']);
        }
        
        // Paginate hasil dengan 15 item per halaman
        $stockCards = $stockCardsQuery->paginate(15)->appends($request->all());

        return inertia('Items/StockCard', [
            'item' => $item,
            'stockCards' => $stockCards,
            'filters' => $request->all(['search', 'note', 'dari_tanggal', 'sampai_tanggal']),
        ]);
    }

    public function printPreview()
    {
        return inertia('Items/Print');
    }

    public function destroy($id)
    {
        $item = item::findOrFail($id);

        $item->stockCards()->delete();
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang dan semua riwayatnya berhasil dihapus.');
    }
}