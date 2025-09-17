<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Receivable;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
// 'use Mpdf\Mpdf;' sudah dihapus karena tidak lagi digunakan

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman form untuk filter laporan.
     */
    public function index(): Response
    {
        return Inertia::render('Keuangan/Laporan/Index');
    }

    /**
     * Merender halaman print-preview.
     */
    public function cetak(Request $request): Response
    {
        // 1. Validasi input dari form
        $validated = $request->validate([
            'tipe' => 'required|in:pemasukan,pengeluaran,utang,piutang',
            'dari_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        $tipe = $validated['tipe'];
        $dari = $validated['dari_tanggal'];
        $sampai = $validated['sampai_tanggal'];
        $data = [];

        // 2. Mengambil data
        switch ($tipe) {
            case 'pemasukan':
            case 'pengeluaran':
                $data = Transaction::where('tipe', $tipe)
                    ->whereBetween('tanggal', [$dari, $sampai])
                    ->with('user')
                    ->get();
                break;
            
            case 'utang':
                $data = Debt::whereBetween('tanggal_utang', [$dari, $sampai])
                    ->with('user')
                    ->get();
                break;

            case 'piutang':
                $data = Receivable::whereBetween('tanggal_piutang', [$dari, $sampai])
                    ->with('user')
                    ->get();
                break;
        }

        // 3. Render halaman Inertia untuk dicetak
        return Inertia::render('Keuangan/Laporan/Cetak', [
            'data' => $data,
            'tipe' => $tipe,
            'dari' => $dari,
            'sampai' => $sampai,
            'total' => $data->sum('jumlah')
        ]);
    }
}