<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import debounce from 'lodash/debounce';
import axios from 'axios';

const props = defineProps({
    transactions: Object, // Paginated object
    filters: Object,
});

const flash = computed(() => usePage().props.flash);

// State untuk filter
const tipe = ref(props.filters.tipe || 'semua');
const dariTanggal = ref(props.filters.dari_tanggal || '');
const sampaiTanggal = ref(props.filters.sampai_tanggal || '');

// Watch dengan debounce untuk auto-filter
watch([tipe, dariTanggal, sampaiTanggal], debounce(() => {
    router.get(route('transaksi.index'), {
        tipe: tipe.value,
        dari_tanggal: dariTanggal.value,
        sampai_tanggal: sampaiTanggal.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR', 
        minimumFractionDigits: 0 
    }).format(value);
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const destroy = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('transaksi.destroy', id), {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const printReport = async () => {
    try {
        // Ambil data terbaru sesuai filter TANPA paginasi
        const response = await axios.get(route('transaksi.index'), {
            params: {
                tipe: tipe.value,
                dari_tanggal: dariTanggal.value,
                sampai_tanggal: sampaiTanggal.value,
                print: true
            },
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const dataToPrint = response.data.transactions;

        if (!dataToPrint || dataToPrint.length === 0) {
            alert('Tidak ada data untuk dicetak sesuai filter yang Anda pilih.');
            return;
        }

        // Kirim semua data ke sessionStorage
        sessionStorage.setItem('printedTransactions', JSON.stringify(dataToPrint));
        
        // Buka halaman print
        const printUrl = route('transaksi.print');
        window.open(printUrl, '_blank');

    } catch (error) {
        console.error('Error detail:', error.response || error);
        alert('Terjadi kesalahan saat menyiapkan data untuk dicetak.');
    }
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
                        <!-- Filter Section -->
                        <div class="bg-gray-50 p-4 rounded-lg border mb-6">
                            <h3 class="font-bold text-lg mb-4">Filter Laporan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <InputLabel for="tipe" value="Tipe Transaksi" />
                                    <select 
                                        id="tipe" 
                                        v-model="tipe" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    >
                                        <option value="semua">Semua</option>
                                        <option value="pemasukan">Pemasukan</option>
                                        <option value="pengeluaran">Pengeluaran</option>
                                    </select>
                                </div>
                                <div>
                                    <InputLabel for="dari_tanggal" value="Dari Tanggal" />
                                    <TextInput 
                                        id="dari_tanggal" 
                                        type="date" 
                                        class="mt-1 block w-full" 
                                        v-model="dariTanggal" 
                                    />
                                </div>
                                <div>
                                    <InputLabel for="sampai_tanggal" value="Sampai Tanggal" />
                                    <TextInput 
                                        id="sampai_tanggal" 
                                        type="date" 
                                        class="mt-1 block w-full" 
                                        v-model="sampaiTanggal" 
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 mb-6">
                            <Link :href="route('transaksi.create')">
                                <PrimaryButton>Tambah Transaksi</PrimaryButton>
                            </Link>
                            <PrimaryButton @click="printReport" class="bg-gray-700 hover:bg-gray-900">
                                Cetak Laporan
                            </PrimaryButton>
                        </div>
                        
                        <!-- Flash Message -->
                        <div 
                            v-if="flash.message" 
                            class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" 
                            role="alert"
                        >
                            {{ flash.message }}
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="transactions.data.length === 0">
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                            Tidak ada data yang cocok dengan filter yang Anda pilih.
                                        </td>
                                    </tr>
                                    <tr v-else v-for="(item, index) in transactions.data" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ (transactions.current_page - 1) * transactions.per_page + index + 1 }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span 
                                                :class="{ 
                                                    'bg-green-100 text-green-800': item.tipe === 'pemasukan', 
                                                    'bg-red-100 text-red-800': item.tipe === 'pengeluaran' 
                                                }" 
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            >
                                                {{ item.tipe }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(item.tanggal) }}</td>
                                        <td class="px-6 py-4">{{ item.catatan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ formatCurrency(item.jumlah) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span 
                                                :class="item.status === 'selesai' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'" 
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            >
                                                {{ item.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <Link :href="route('transaksi.edit', item)">
                                                    <PrimaryButton>Edit</PrimaryButton>
                                                </Link>
                                                <DangerButton @click="destroy(item.id)">Hapus</DangerButton>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Component -->
                        <Pagination 
                            v-if="transactions.total > transactions.per_page" 
                            :pagination="transactions" 
                            class="mt-6"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>