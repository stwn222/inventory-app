<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    items: Array
});

// state pencarian
const search = ref("");


// helper format harga
const formatPrice = (value) => {
    if (!value) return '';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value).replace('IDR', 'Rp').trim();
};

// filter items berdasarkan keyword
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
                        <div class="flex justify-between mb-4">
                            <PrimaryButton>
                                <a href="/items/create" class="text-white">
                                    Tambah Barang
                                </a>
                            </PrimaryButton>
                            <input 
                                type="text" 
                                v-model="search" 
                                placeholder="Cari barang..." 
                                class="border rounded px-3 py-2 w-1/3"
                            >
                        </div>

                        <!-- flash message -->
                        <div v-if="$page.props.flash.success" class="bg-green-100 border border-green-400 text-black-700 px-4 py-3 rounded m-2 relative">
                            {{ $page.props.flash.success }}
                        </div>
                        <div v-if="$page.props.flash.error" class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative">
                            {{ $page.props.flash.error }}
                        </div>

                        <table class="table-auto w-full mt-4 text-center">
                          <thead>
                            <tr>
                              <th class="text-xl px-4 py-2">No</th>
                              <th class="text-xl px-4 py-2">Nama</th>
                              <th class="text-xl px-4 py-2">Jumlah</th>
                              <th class="text-xl px-4 py-2">Harga</th>
                              <th class="text-xl px-4 py-2">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(item, index) in filteredItems" :key="item.id">
                              <td class="text-xl border px=4 py-2">{{ index + 1 }}</td>
                              <td class="text-xl border px=4 py-2">{{ item.name }}</td>
                              <td class="text-xl border px=4 py-2">{{ item.qty + ' ' + item.unit }}</td>
                              <td class="text-xl border px=4 py-2">{{ formatPrice(item.price) }}</td>
                              <td class="text-xl border px=4 py-2">
                                  <PrimaryButton class="mr-2 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    <a :href="`/items/${item.id}/edit`">Edit Barang</a>
                                  </PrimaryButton>
                                  <PrimaryButton class="mr-2 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    <a  :href="`/items/${item.id}/edit-stock`">Edit Stok</a>
                                  </PrimaryButton>
                                  <PrimaryButton class="mr-2 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    <a :href="`/items/${item.id}/StockCard`">Riwayat Stok</a>
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
