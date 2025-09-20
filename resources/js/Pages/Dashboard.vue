<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    pemasukanTerbaru: Array,
    pengeluaranTerbaru: Array,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// Data untuk kartu statistik agar lebih mudah di-loop
const statCards = computed(() => [
    { title: 'Pendapatan Bulan Ini', value: formatCurrency(props.stats.pendapatanBulanIni), icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01' },
    { title: 'Pengeluaran Bulan Ini', value: formatCurrency(props.stats.pengeluaranBulanIni), icon: 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' },
    { title: 'Total Transaksi', value: props.stats.totalTransaksi, icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M6 16h.01' },
    { title: 'Total User', value: props.stats.totalUser, icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197' },
]);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
                <p class="text-sm text-gray-500">Pages / Dashboard</p>
            </div>
        </template>

        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="card in statCards" :key="card.title" class="bg-white p-6 rounded-lg shadow-md flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="card.icon"></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ card.title }}</p>
                        <p class="text-2xl font-bold">{{ card.value }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Pemasukan Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Catatan</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="pemasukanTerbaru.length === 0">
                                    <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada pemasukan terbaru.</td>
                                </tr>
                                <tr v-for="item in pemasukanTerbaru" :key="item.id" class="border-b">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ formatDate(item.tanggal) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ item.catatan }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-green-600 font-semibold">{{ formatCurrency(item.jumlah) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ item.user.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Pengeluaran Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Catatan</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr v-if="pengeluaranTerbaru.length === 0">
                                    <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada pengeluaran terbaru.</td>
                                </tr>
                                <tr v-for="item in pengeluaranTerbaru" :key="item.id" class="border-b">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ formatDate(item.tanggal) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ item.catatan }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-red-600 font-semibold">{{ formatCurrency(item.jumlah) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ item.user.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>