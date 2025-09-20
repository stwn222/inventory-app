<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $now = Carbon::now();

        // Ambil data untuk kartu statistik
        $pendapatanBulanIni = Transaction::where('tipe', 'pemasukan')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('jumlah');

        $pengeluaranBulanIni = Transaction::where('tipe', 'pengeluaran')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('jumlah');

        $totalTransaksi = Transaction::count();
        $totalUser = User::count();

        // Ambil 5 transaksi terbaru untuk tabel
        $pemasukanTerbaru = Transaction::with('user')
            ->where('tipe', 'pemasukan')
            ->latest()
            ->take(5)
            ->get();

        $pengeluaranTerbaru = Transaction::with('user')
            ->where('tipe', 'pengeluaran')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'pendapatanBulanIni' => $pendapatanBulanIni,
                'pengeluaranBulanIni' => $pengeluaranBulanIni,
                'totalTransaksi' => $totalTransaksi,
                'totalUser' => $totalUser,
            ],
            'pemasukanTerbaru' => $pemasukanTerbaru,
            'pengeluaranTerbaru' => $pengeluaranTerbaru,
        ]);
    }
}