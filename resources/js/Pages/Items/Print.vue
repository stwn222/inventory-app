<script setup>
import PrintLayout from '@/Layouts/PrintLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, nextTick } from 'vue';

// Menggunakan layout minimalis untuk print
defineOptions({ layout: PrintLayout });

const itemsToPrint = ref([]);
const printDate = new Date().toLocaleDateString('id-ID', {
    year: 'numeric', month: 'long', day: 'numeric'
});

const formatPrice = (value) => {
    if (!value) return '';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value).replace('IDR', 'Rp').trim();
};

// Saat halaman dimuat, ambil data dan panggil dialog print
onMounted(() => {
    const storedItems = sessionStorage.getItem('printedItems');
    if (storedItems) {
        itemsToPrint.value = JSON.parse(storedItems);
        // Hapus data dari session storage setelah diambil
        sessionStorage.removeItem('printedItems');

        // Tunggu DOM selesai di-update sebelum print
        nextTick(() => {
            window.print();
        });
    }
});
</script>

<template>
    <Head title="Cetak Laporan Barang" />
    
    <div class="p-8 font-sans">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Laporan Daftar Barang</h1>
            <h3 class="text-lg">Tanggal Cetak: {{ printDate }}</h3>
        </div>

        <table class="min-w-full text-sm border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border text-left">No</th>
                    <th class="p-2 border text-left">Nama Barang</th>
                    <th class="p-2 border text-center">Jumlah</th>
                    <th class="p-2 border text-right">Harga Satuan</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="itemsToPrint.length === 0">
                    <td colspan="4" class="p-2 border text-center text-gray-500">
                        Tidak ada data untuk dicetak.
                    </td>
                </tr>
                <tr v-for="(item, index) in itemsToPrint" :key="item.id">
                    <td class="p-2 border">{{ index + 1 }}</td>
                    <td class="p-2 border">{{ item.name }}</td>
                    <td class="p-2 border text-center">{{ item.qty }} {{ item.unit }}</td>
                    <td class="p-2 border text-right">{{ formatPrice(item.price) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-8 text-center print:hidden">
            <button @click="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded shadow-lg hover:bg-blue-700">
                Print Ulang
            </button>
            <button @click="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded shadow-lg hover:bg-gray-700 ml-4">
                Tutup Halaman
            </button>
        </div>
    </div>
</template>