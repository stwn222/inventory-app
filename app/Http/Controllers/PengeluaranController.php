<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

// UBAH NAMA CLASS
class PengeluaranController extends Controller
{
    public function index(): Response
    {
        // UBAH VALUE where('tipe', ...)
        $pengeluaran = Transaction::where('tipe', 'pengeluaran')
            ->with('user')
            ->latest()
            ->paginate(10);
            
        // UBAH NAMA PROPS DAN PATH VIEW
        return Inertia::render('Keuangan/Pengeluaran/Index', [
            'pengeluaran' => $pengeluaran,
        ]);
    }

    public function create(): Response
    {
        // UBAH PATH VIEW
        return Inertia::render('Keuangan/Pengeluaran/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai',
        ]);

        Transaction::create([
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'],
            'jumlah' => $validated['jumlah'],
            'status' => $validated['status'],
            'tipe' => 'pengeluaran', // UBAH TIPE
            'user_id' => Auth::id(),
        ]);

        // UBAH ROUTE REDIRECT
        return redirect(route('pengeluaran.index'))->with('message', 'Data pengeluaran berhasil ditambahkan.');
    }

    // UBAH NAMA PROPS DAN PATH VIEW
    public function edit(Transaction $pengeluaran): Response
    {
        return Inertia::render('Keuangan/Pengeluaran/Edit', [
            'pengeluaran' => $pengeluaran,
        ]);
    }

    // UBAH NAMA PARAMETER
    public function update(Request $request, Transaction $pengeluaran): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai',
        ]);

        $pengeluaran->update($validated);

        // UBAH ROUTE REDIRECT
        return redirect(route('pengeluaran.index'))->with('message', 'Data pengeluaran berhasil diperbarui.');
    }

    // UBAH NAMA PARAMETER
    public function destroy(Transaction $pengeluaran): RedirectResponse
    {
        $pengeluaran->delete();

        // UBAH ROUTE REDIRECT
        return redirect(route('pengeluaran.index'))->with('message', 'Data pengeluaran berhasil dihapus.');
    }
}