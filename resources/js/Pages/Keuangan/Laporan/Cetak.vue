
<script setup>
import PrintLayout from '@/Layouts/PrintLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

// Mengatur agar halaman ini menggunakan layout minimalis untuk print
defineOptions({ layout: PrintLayout });

// Menerima data yang dikirim dari LaporanController
const props = defineProps({
    data: Array,
    tipe: String,
    dari: String,
    sampai: String,
    total: Number,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// Fungsi ini akan berjalan otomatis saat halaman selesai dimuat
onMounted(() => {
    window.print();
});
</script>

<template>
    <Head :title="`Laporan ${tipe}`" />

    <div class="p-8 font-sans">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Laporan {{ tipe.charAt(0).toUpperCase() + tipe.slice(1) }}</h1>
            <h3 class="text-lg">Periode: {{ formatDate(dari) }} s/d {{ formatDate(sampai) }}</h3>
        </div>

        <table class="min-w-full text-sm border border-gray-300">
            <thead class="bg-gray-200">
                <tr v-if="tipe === 'pemasukan' || tipe === 'pengeluaran'">
                    <th class="p-2 border text-left">No</th>
                    <th class="p-2 border text-left">Tanggal</th>
                    <th class="p-2 border text-left">Catatan</th>
                    <th class="p-2 border text-left">User</th>
                    <th class="p-2 border text-right">Jumlah</th>
                </tr>
                <tr v-if="tipe === 'utang'">
                    <th class="p-2 border text-left">No</th>
                    <th class="p-2 border text-left">Tanggal Utang</th>
                    <th class="p-2 border text-left">Pemberi Utang</th>
                    <th class="p-2 border text-left">Jatuh Tempo</th>
                    <th class="p-2 border text-left">Status</th>
                    <th class="p-2 border text-right">Jumlah</th>
                </tr>
                <tr v-if="tipe === 'piutang'">
                    <th class="p-2 border text-left">No</th>
                    <th class="p-2 border text-left">Tanggal Piutang</th>
                    <th class="p-2 border text-left">Peminjam</th>
                    <th class="p-2 border text-left">Jatuh Tempo</th>
                    <th class="p-2 border text-left">Status</th>
                    <th class="p-2 border text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                 <tr v-if="data.length === 0">
                    <td colspan="6" class="p-2 border text-center text-gray-500">
                        Tidak ada data untuk periode ini.
                    </td>
                </tr>
                <template v-if="tipe === 'pemasukan' || tipe === 'pengeluaran'">
                    <tr v-for="(item, index) in data" :key="item.id">
                        <td class="p-2 border">{{ index + 1 }}</td>
                        <td class="p-2 border">{{ formatDate(item.tanggal) }}</td>
                        <td class="p-2 border">{{ item.catatan }}</td>
                        <td class="p-2 border">{{ item.user.name }}</td>
                        <td class="p-2 border text-right">{{ formatCurrency(item.jumlah) }}</td>
                    </tr>
                </template>
                <template v-if="tipe === 'utang'">
                     <tr v-for="(item, index) in data" :key="item.id">
                        <td class="p-2 border">{{ index + 1 }}</td>
                        <td class="p-2 border">{{ formatDate(item.tanggal_utang) }}</td>
                        <td class="p-2 border">{{ item.pemberi_utang }}</td>
                        <td class="p-2 border">{{ item.tanggal_jatuh_tempo ? formatDate(item.tanggal_jatuh_tempo) : '-' }}</td>
                        <td class="p-2 border">{{ item.status.replace('_', ' ') }}</td>
                        <td class="p-2 border text-right">{{ formatCurrency(item.jumlah) }}</td>
                    </tr>
                </template>
                <template v-if="tipe === 'piutang'">
                     <tr v-for="(item, index) in data" :key="item.id">
                        <td class="p-2 border">{{ index + 1 }}</td>
                        <td class="p-2 border">{{ formatDate(item.tanggal_piutang) }}</td>
                        <td class="p-2 border">{{ item.peminjam }}</td>
                        <td class="p-2 border">{{ item.tanggal_jatuh_tempo ? formatDate(item.tanggal_jatuh_tempo) : '-' }}</td>
                        <td class="p-2 border">{{ item.status.replace('_', ' ') }}</td>
                        <td class="p-2 border text-right">{{ formatCurrency(item.jumlah) }}</td>
                    </tr>
                </template>
            </tbody>
            <tfoot class="font-bold bg-gray-200">
                <tr>
                    <td :colspan="tipe === 'pemasukan' || tipe === 'pengeluaran' ? 4 : 5" class="p-2 border text-left">Total</td>
                    <td class="p-2 border text-right">{{ formatCurrency(total) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-8 text-center print:hidden">
            <button @click="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded shadow-lg hover:bg-blue-700">
                <i class="fas fa-print mr-2"></i> Print Ulang
            </button>
            <button @click="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded shadow-lg hover:bg-gray-700 ml-4">
                Tutup Halaman
            </button>
        </div>
    </div>
</template>