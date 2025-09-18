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
    public function index(Request $request): Response
    {
        $transactions = Transaction::with('user')
            ->when($request->input('tipe'), function ($query, $tipe) {
                if ($tipe !== 'semua') { // Tambahkan kondisi agar 'semua' tidak memfilter apa pun
                    return $query->where('tipe', $tipe);
                }
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Transaksi/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['tipe']), 
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

    public function show(Transaction $transaksi)
    {
        // Di Laravel 11, parameter harus sama. Kita ganti $transaksi menjadi $transaction
        return redirect()->route('transaksi.edit', $transaksi);
    }
    
    // UBAH NAMA PARAMETER MENJADI $transaction
    public function edit(Transaction $transaction): Response
    {
        return Inertia::render('Transaksi/Edit', [
            'transaction' => $transaction,
        ]);
    }

    // UBAH NAMA PARAMETER MENJADI $transaction
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

    // UBAH NAMA PARAMETER MENJADI $transaction
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