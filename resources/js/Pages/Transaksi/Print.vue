<script setup>
import PrintLayout from '@/Layouts/PrintLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, nextTick, computed } from 'vue';

defineOptions({ layout: PrintLayout });

const transactionsToPrint = ref([]);
const printDate = new Date().toLocaleDateString('id-ID', {
    year: 'numeric', month: 'long', day: 'numeric'
});

// Menghitung total pemasukan
const totalPemasukan = computed(() => {
    return transactionsToPrint.value
        .filter(item => item.tipe === 'pemasukan')
        .reduce((sum, item) => sum + Number(item.jumlah), 0);
});

// Menghitung total pengeluaran
const totalPengeluaran = computed(() => {
    return transactionsToPrint.value
        .filter(item => item.tipe === 'pengeluaran')
        .reduce((sum, item) => sum + Number(item.jumlah), 0);
});

// Menghitung saldo akhir
const saldoAkhir = computed(() => {
    return totalPemasukan.value - totalPengeluaran.value;
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
};

onMounted(() => {
    const stored = sessionStorage.getItem('printedTransactions');
    if (stored) {
        transactionsToPrint.value = JSON.parse(stored);
        sessionStorage.removeItem('printedTransactions');
        nextTick(() => {
            window.print();
        });
    }
});
</script>

<template>
    <Head title="Cetak Laporan Transaksi" />
    <div class="p-8 font-sans">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Laporan Transaksi Keuangan</h1>
            <h3 class="text-lg">Tanggal Cetak: {{ printDate }}</h3>
        </div>
        <table class="min-w-full text-sm border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border text-left">No</th>
                    <th class="p-2 border text-left">Tanggal</th>
                    <th class="p-2 border text-left">Catatan</th>
                    <th class="p-2 border text-right">Pemasukan</th>
                    <th class="p-2 border text-right">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="transactionsToPrint.length === 0">
                    <td colspan="5" class="p-2 border text-center">Tidak ada data untuk dicetak.</td>
                </tr>
                <tr v-for="(item, index) in transactionsToPrint" :key="item.id">
                    <td class="p-2 border">{{ index + 1 }}</td>
                    <td class="p-2 border">{{ formatDate(item.tanggal) }}</td>
                    <td class="p-2 border">{{ item.catatan }}</td>
                    <td class="p-2 border text-right text-green-600">
                        {{ item.tipe === 'pemasukan' ? formatCurrency(item.jumlah) : '-' }}
                    </td>
                    <td class="p-2 border text-right text-red-600">
                        {{ item.tipe === 'pengeluaran' ? formatCurrency(item.jumlah) : '-' }}
                    </td>
                </tr>
            </tbody>
            <tfoot class="font-bold bg-gray-200">
                <tr>
                    <td colspan="3" class="p-2 border text-left">Total Pemasukan</td>
                    <td class="p-2 border text-right text-green-600" colspan="2">{{ formatCurrency(totalPemasukan) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="p-2 border text-left">Total Pengeluaran</td>
                    <td class="p-2 border text-right text-red-600" colspan="2">{{ formatCurrency(totalPengeluaran) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="p-2 border text-left">Saldo Akhir</td>
                    <td class="p-2 border text-right" colspan="2">{{ formatCurrency(saldoAkhir) }}</td>
                </tr>
            </tfoot>
        </table>
        <div class="mt-8 text-center print:hidden">
            <button @click="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded">Print Ulang</button>
            <button @click="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded ml-4">Tutup</button>
        </div>
    </div>
</template>