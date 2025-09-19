<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    items: Object, // Sekarang berupa paginated object
    filters: Object
});

// State pencarian dari filters yang ada
const search = ref(props.filters.search || '');

// Watcher untuk search dengan debounce
watch(search, debounce(() => {
    router.get(route('items.index'), {
        search: search.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

// Helper format harga
const formatPrice = (value) => {
    if (!value) return '';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value).replace('IDR', 'Rp').trim();
};

// FUNGSI UNTUK CETAK - menggunakan data dari pagination
const printReport = () => {
    // Ambil semua data tanpa filter untuk dicetak
    sessionStorage.setItem('printedItems', JSON.stringify(props.items.data));
    
    const url = route('items.print');
    window.open(url, '_blank');
};

const deleteItem = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
        router.delete(route('items.destroy', id), {
            preserveScroll: true
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

                        <div class="overflow-x-auto">
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
                                    <tr v-for="(item, index) in items.data" :key="item.id">
                                        <td class="border px-4 py-2">
                                            {{ (items.current_page - 1) * items.per_page + index + 1 }}
                                        </td>
                                        <td class="border px-4 py-2 text-left">{{ item.name }}</td>
                                        <td class="border px-4 py-2">{{ item.qty + ' ' + item.unit }}</td>
                                        <td class="border px-4 py-2 text-right">{{ formatPrice(item.price) }}</td>
                                        <td class="border px-4 py-2">
                                            <div class="flex justify-center gap-2 flex-wrap">
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
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="items.data.length === 0">
                                        <td colspan="5" class="text-gray-500 py-4">Barang tidak ditemukan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Component -->
                        <Pagination 
                            v-if="items.total > items.per_page" 
                            :pagination="items" 
                            class="mt-6"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>