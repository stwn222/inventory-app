<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transactionsQuery = Transaction::query()
            ->when($request->input('tipe') && $request->input('tipe') !== 'semua', function ($query) use ($request) {
                return $query->where('tipe', $request->input('tipe'));
            })
            ->when($request->input('dari_tanggal') && $request->input('sampai_tanggal'), function ($query) use ($request) {
                return $query->whereBetween('tanggal', [$request->input('dari_tanggal'), $request->input('sampai_tanggal')]);
            })
            ->with('user')
            ->latest();

        if ($request->has('print')) {
            return response()->json(['transactions' => $transactionsQuery->get()]);
        }
        
        $transactions = $transactionsQuery->paginate(10)->withQueryString();

        return Inertia::render('Transaksi/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['tipe', 'dari_tanggal', 'sampai_tanggal']), 
        ]);
    }
    
    public function create(): Response
    {
        return Inertia::render('Transaksi/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        Transaction::create(array_merge($validated, [
            'user_id' => Auth::id(),
        ]));

        return redirect()->route('transaksi.index')->with('message', 'Transaksi berhasil ditambahkan.');
    }

    // --- PERBAIKAN DI SINI ---
    // Gunakan nama variabel $transaction agar cocok dengan nama parameter route
    public function show(Transaction $transaction)
    {
        return redirect()->route('transaksi.edit', $transaction);
    }
    
    public function edit(Transaction $transaction): Response
    {
        return Inertia::render('Transaksi/Edit', [
            'transaction' => $transaction,
        ]);
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        $transaction->update($validated);

        return redirect()->route('transaksi.index')->with('message', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();
        return redirect()->route('transaksi.index')->with('message', 'Transaksi berhasil dihapus.');
    }

    public function printPreview()
    {
        return inertia('Transaksi/Print');
    }
}