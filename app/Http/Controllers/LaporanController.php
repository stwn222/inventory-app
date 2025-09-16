<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Receivable;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
// Langsung panggil class inti dari library mPDF
use Mpdf\Mpdf; 

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
                $viewName = 'laporan.laporan_transaksi';
                break;
            
            case 'utang':
                $data = Debt::whereBetween('tanggal_utang', [$dari, $sampai])
                    ->with('user')
                    ->get();
                $viewName = 'laporan.laporan_utang';
                break;

            case 'piutang':
                $data = Receivable::whereBetween('tanggal_piutang', [$dari, $sampai])
                    ->with('user')
                    ->get();
                $viewName = 'laporan.laporan_piutang';
                break;
        }

        // Data yang akan dikirim ke semua view
        $dataForView = [
            'data' => $data,
            'tipe' => $tipe,
            'dari' => $dari,
            'sampai' => $sampai,
            'total' => $data->sum('jumlah')
        ];

        // --- INI ADALAH BAGIAN UTAMA YANG BERUBAH ---

        // 3. Render template Blade menjadi string HTML biasa
        $html = view($viewName, $dataForView)->render();

        // 4. Buat objek mPDF baru secara manual
        $mpdf = new Mpdf();

        // 5. Tulis string HTML ke dalam objek mPDF
        $mpdf->WriteHTML($html);
        
        // 6. Siapkan nama file
        $fileName = 'laporan-' . $tipe . '-' . $dari . '_sd_' . $sampai . '.pdf';

        // 7. Kirim PDF ke browser untuk diunduh
        // Argumen kedua 'D' berarti "Download"
        return $mpdf->Output($fileName, 'D');
    }
}