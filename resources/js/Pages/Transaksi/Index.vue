<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, usePage, router, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    transactions: [Object, Array], // Bisa berupa object paginator atau array kosong
    filters: Object,
});

const flash = computed(() => usePage().props.flash);

// Gunakan useForm untuk mengelola semua state filter
const filterForm = useForm({
    tipe: props.filters.tipe || 'semua',
    dari_tanggal: props.filters.dari_tanggal || '',
    sampai_tanggal: props.filters.sampai_tanggal || '',
});

// Fungsi untuk menerapkan filter dan mengambil data
const applyFilters = () => {
    filterForm.get(route('transaksi.index'), {
        preserveState: true,
        replace: true,
    });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const destroy = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('transaksi.destroy', id), {
            preserveState: true, // Agar filter tidak hilang setelah hapus
            preserveScroll: true,
        });
    }
};

const printReport = () => {
    // Pastikan ada data untuk dicetak
    const dataToPrint = Array.isArray(props.transactions) ? props.transactions : props.transactions.data;
    if (dataToPrint.length === 0) {
        alert('Tidak ada data untuk dicetak. Silakan tampilkan data terlebih dahulu.');
        return;
    }
    sessionStorage.setItem('printedTransactions', JSON.stringify(dataToPrint));
    const url = route('transaksi.print');
    window.open(url, '_blank');
};
</script>

<template>
    <Head title="Laporan Transaksi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Transaksi Keuangan</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <h3 class="font-bold text-lg mb-4">Filter Laporan</h3>
                            <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <InputLabel for="tipe" value="Tipe Transaksi" />
                                    <select id="tipe" v-model="filterForm.tipe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="semua">Semua</option>
                                        <option value="pemasukan">Pemasukan</option>
                                        <option value="pengeluaran">Pengeluaran</option>
                                    </select>
                                </div>
                                <div>
                                    <InputLabel for="dari_tanggal" value="Dari Tanggal" />
                                    <TextInput id="dari_tanggal" type="date" class="mt-1 block w-full" v-model="filterForm.dari_tanggal" />
                                </div>
                                <div>
                                    <InputLabel for="sampai_tanggal" value="Sampai Tanggal" />
                                    <TextInput id="sampai_tanggal" type="date" class="mt-1 block w-full" v-model="filterForm.sampai_tanggal" />
                                </div>
                                <div class="flex gap-2">
                                    <PrimaryButton type="submit" :disabled="filterForm.processing">
                                        Tampilkan Data
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        <div class="flex gap-2 mt-6">
                            <Link :href="route('transaksi.create')">
                                <PrimaryButton>Tambah Transaksi</PrimaryButton>
                            </Link>
                            <PrimaryButton @click="printReport" class="bg-gray-700 hover:bg-gray-900">
                                Cetak Laporan
                            </PrimaryButton>
                        </div>
                        
                        <div v-if="flash.message" class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                            {{ flash.message }}
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 mt-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="!transactions.data">
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        Silakan pilih filter di atas dan klik "Tampilkan Data" untuk melihat hasil.
                                    </td>
                                </tr>
                                <tr v-else-if="transactions.data.length === 0">
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada data yang cocok dengan filter yang Anda pilih.
                                    </td>
                                </tr>
                                <tr v-else v-for="item in transactions.data" :key="item.id">
                                    <td class="px-6 py-4">
                                        <span :class="{ 'bg-green-100 text-green-800': item.tipe === 'pemasukan', 'bg-red-100 text-red-800': item.tipe === 'pengeluaran' }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                            {{ item.tipe }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(item.tanggal) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ item.catatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ formatCurrency(item.jumlah) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="item.status === 'selesai' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                            {{ item.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <Link :href="route('transaksi.edit', item)">
                                            <PrimaryButton>Edit</PrimaryButton>
                                        </Link>
                                        <DangerButton @click="destroy(item.id)">Hapus</DangerButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="mt-4" v-if="transactions.data && transactions.data.length > 0">
                            <Pagination :links="transactions.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>