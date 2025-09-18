<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router } from '@inertiajs/vue3';

import { ref, computed } from 'vue';

const props = defineProps({
    items: Array
});

// State pencarian
const search = ref("");

// Helper format harga
const formatPrice = (value) => {
    if (!value) return '';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value).replace('IDR', 'Rp').trim();
};

// Filter items berdasarkan keyword
const filteredItems = computed(() => {
    if (!search.value) return props.items;

    const keyword = search.value.toLowerCase();
    return props.items.filter(item => {
        return (
            (item.name && item.name.toLowerCase().includes(keyword)) ||
            (item.unit && item.unit.toLowerCase().includes(keyword)) ||
            (item.qty && item.qty.toString().toLowerCase().includes(keyword)) ||
            (item.price && item.price.toString().toLowerCase().includes(keyword))
        );
    });
});

// FUNGSI BARU UNTUK CETAK
const printReport = () => {
    // Simpan data yang sudah difilter ke sessionStorage
    sessionStorage.setItem('printedItems', JSON.stringify(filteredItems.value));
    
    // Buka halaman cetak di tab baru
    const url = route('items.print');
    window.open(url, '_blank');
};

const deleteItem = (id) => {
    // Tampilkan dialog konfirmasi
    if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
        // Kirim request DELETE ke server
        router.delete(route('items.destroy', id), {
            preserveScroll: true // Agar halaman tidak scroll ke atas
        });
    }
};

const goToCreatePage = () => {
    router.get(route('items.create'));
};
</script>

<template>
    <Head title="Daftar Barang" />
    <AuthenticatedLayout>  
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Daftar Barang
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-wrap gap-2 justify-between mb-4">
                            <div class="flex gap-2">
                                <PrimaryButton @click="goToCreatePage">
                                    Tambah Barang
                                </PrimaryButton>
                                <PrimaryButton @click="printReport">
                                    Cetak Laporan
                                </PrimaryButton>
                            </div>
                            <input 
                                type="text" 
                                v-model="search" 
                                placeholder="Cari barang..." 
                                class="border rounded px-3 py-2 w-full sm:w-1/3"
                            >
                        </div>

                        <div v-if="$page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-2 relative">
                            {{ $page.props.flash.success }}
                        </div>
                        <div v-if="$page.props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-2 relative">
                            {{ $page.props.flash.error }}
                        </div>

                        <table class="table-auto w-full mt-4 text-center">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Nama</th>
                                    <th class="px-4 py-2">Jumlah</th>
                                    <th class="px-4 py-2">Harga</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in filteredItems" :key="item.id">
                                    <td class="border px-4 py-2">{{ index + 1 }}</td>
                                    <td class="border px-4 py-2 text-left">{{ item.name }}</td>
                                    <td class="border px-4 py-2">{{ item.qty + ' ' + item.unit }}</td>
                                    <td class="border px-4 py-2 text-right">{{ formatPrice(item.price) }}</td>
                                    <td class="border px-4 py-2 flex justify-center gap-2 flex-wrap">
                                        <Link :href="route('items.edit', { id: item.id })">
                                            <PrimaryButton>Edit Barang</PrimaryButton>
                                        </Link>
                                        <Link :href="route('items.edit-stock', { id: item.id })">
                                            <PrimaryButton>Edit Stok</PrimaryButton>
                                        </Link>
                                        <Link :href="route('items.StockCard', { id: item.id })">
                                            <PrimaryButton>Riwayat Stok</PrimaryButton>
                                        </Link>
                                    <PrimaryButton 
                                        @click="deleteItem(item.id)" 
                                        class="bg-red-600 hover:bg-red-800 focus:bg-red-800 active:bg-red-900">
                                        Hapus
                                    </PrimaryButton>

                                    </td>
                                </tr>
                                <tr v-if="filteredItems.length === 0">
                                    <td colspan="5" class="text-gray-500 py-4">Barang tidak ditemukan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>