<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Receivable;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade as PDF;

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
     * Membuat dan mengunduh laporan dalam format PDF.
     */
    public function cetak(Request $request)
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
        $viewName = ''; // Nama template Blade yang akan digunakan

        // 2. Mengambil data dan menentukan view berdasarkan tipe laporan
        switch ($tipe) {
            case 'pemasukan':
            case 'pengeluaran':
                $data = Transaction::where('tipe', $tipe)
                    ->whereBetween('tanggal', [$dari, $sampai])
                    ->with('user')
                    ->get();
                $viewName = 'laporan.laporan_transaksi'; // Template untuk Pemasukan & Pengeluaran
                break;
            
            case 'utang':
                $data = Debt::whereBetween('tanggal_utang', [$dari, $sampai])
                    ->with('user')
                    ->get();
                $viewName = 'laporan.laporan_utang'; // Template khusus Utang
                break;

            case 'piutang':
                $data = Receivable::whereBetween('tanggal_piutang', [$dari, $sampai])
                    ->with('user')
                    ->get();
                $viewName = 'laporan.laporan_piutang'; // Template khusus Piutang
                break;
        }

        // Data yang akan dikirim ke semua view
        $dataForView = [
            'data' => $data,
            'tipe' => $tipe,
            'dari' => $dari,
            'sampai' => $sampai,
            'total' => $data->sum('jumlah') // Menghitung total jumlah
        ];

        // 3. Membuat PDF dari data (INI BAGIAN YANG DIPERBAIKI)
        $pdf = app('dompdf')->loadView($viewName, $dataForView);
        
        // 4. Memberikan nama file dan mengizinkan unduhan
        $fileName = 'laporan-' . $tipe . '-' . $dari . '_sd_' . $sampai . '.pdf';
        return $pdf->download($fileName);
    }
}