<?php

namespace App\Http\Controllers;

use App\Models\item;
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
            'price' => 'required|numeric|max:9999999999999'
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

        return redirect()->route('items.index')->with('success', 'Stok Item Berhasil dirubah');
    }
}