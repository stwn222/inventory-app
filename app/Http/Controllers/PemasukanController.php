<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Baris ini mengambil data dan mendefinisikan variabel $pemasukan
        $pemasukan = Transaction::where('tipe', 'pemasukan')
            ->with('user')
            ->latest()
            ->paginate(10);

        // 2. Baris ini menggunakan variabel $pemasukan untuk dikirim ke frontend
        return Inertia::render('Keuangan/Pemasukan/Index', [
            'pemasukan' => $pemasukan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Keuangan/Pemasukan/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai',
        ]);

        // 2. Tambahkan data tipe dan user_id, lalu simpan
        Transaction::create([
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'],
            'jumlah' => $validated['jumlah'],
            'status' => $validated['status'],
            'tipe' => 'pemasukan', // Atur tipe secara otomatis
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
        ]);

        // 3. Kembalikan ke halaman index dengan pesan sukses
        return redirect(route('pemasukan.index'))->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $pemasukan)
    {
         return Inertia::render('Keuangan/Pemasukan/Edit', [
            'pemasukan' => $pemasukan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Transaction $pemasukan)
    {
        // 1. Validasi data yang masuk dari form
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai',
        ]);

        // 2. Update data yang ada
        $pemasukan->update($validated);

        // 3. Kembalikan ke halaman index dengan pesan sukses
        return redirect(route('pemasukan.index'))->with('success', 'Data pemasukan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $pemasukan)
    {
        // 1. Hapus data
        $pemasukan->delete();

        // 2. Kembalikan ke halaman index dengan pesan sukses
        return redirect(route('pemasukan.index'))->with('success', 'Data pemasukan berhasil dihapus.');
    }
}
