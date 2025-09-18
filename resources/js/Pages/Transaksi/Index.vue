<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue'; // <-- IMPORT KOMPONEN PAGINATION
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    transactions: Object,
    filters: Object,
});

// ... (sisa <script setup> Anda sudah benar)

const flash = computed(() => usePage().props.flash);
const filterTipe = ref(props.filters.tipe || 'semua');

watch(filterTipe, (newValue) => {
    router.get(route('transaksi.index'), { tipe: newValue === 'semua' ? '' : newValue }, {
        preserveState: true,
        replace: true,
    });
});

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
            preserveScroll: true,
        });
    }
};

const printReport = () => {
    sessionStorage.setItem('printedTransactions', JSON.stringify(props.transactions.data));
    const url = route('transaksi.print');
    window.open(url, '_blank');
};
</script>

<template>
    <Head title="Semua Transaksi" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-wrap gap-2 justify-between items-center mb-4">
                            <div class="flex gap-2">
                                <Link :href="route('transaksi.create')">
                                    <PrimaryButton>Tambah Transaksi</PrimaryButton>
                                </Link>
                                <PrimaryButton @click="printReport" class="bg-gray-700 hover:bg-gray-900">
                                    Cetak Laporan
                                </PrimaryButton>
                            </div>
                            <div class="flex items-center gap-2">
                                <label for="filter" class="text-sm font-medium">Tampilkan:</label>
                                <select id="filter" v-model="filterTipe" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    <option value="semua">Semua</option>
                                    <option value="pemasukan">Pemasukan</option>
                                    <option value="pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="flash.message" class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            {{ flash.message }}
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 mt-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase text-right">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="transactions.data.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td>
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
                        
                        <div class="mt-4">
                            <Pagination :links="transactions.links" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>