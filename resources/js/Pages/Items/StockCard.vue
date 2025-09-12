<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    item: Object, // berisi item beserta stockCards
});

const search = ref("");

// filter data stock card
const filteredStockCards = computed(() => {
    if (!search.value) return props.item.stock_cards ?? [];
    return (props.item.stock_cards ?? []).filter(card =>
        (card.note?.toLowerCase() || '').includes(search.value.toLowerCase()) ||
        (card.description ?? '').toLowerCase().includes(search.value.toLowerCase()) ||
        (card.vendor ?? '').toLowerCase().includes(search.value.toLowerCase())
    );
});

// format angka qty
function formatNumber(value) {
    return new Intl.NumberFormat('id-ID').format(value);
}

// format tanggal
function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        return new Date(dateString).toLocaleString("id-ID");
    } catch (error) {
        return dateString;
    }
}
</script>

<template>
    <Head title="Riwayat Stok" />
    <AuthenticatedLayout>  
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Riwayat Stok - {{ item.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Search bar -->
                        <div class="flex justify-between mb-4">
                            <input 
                                type="text" 
                                v-model="search" 
                                placeholder="Cari catatan/keterangan/vendor..." 
                                class="border rounded px-3 py-2 w-1/3"
                            >
                        </div>
                                                    <a 
                                :href="route('items.index')" 
                                class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                            >
                                Kembali ke Daftar Item
                            </a>

                        <table class="table-auto w-full mt-4 text-center border">
                          <thead class="bg-gray-100">
                            <tr>
                              <th class="px-4 py-2 border">#</th>
                              <th class="px-4 py-2 border">Tanggal</th>
                              <th class="px-4 py-2 border">Jumlah</th>
                              <th class="px-4 py-2 border">Catatan</th>
                              <th class="px-4 py-2 border">Keterangan</th>
                              <th class="px-4 py-2 border">Vendor</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(card, index) in filteredStockCards" :key="card.id">
                              <td class="border px-4 py-2">{{ index + 1 }}</td>
                              <td class="border px-4 py-2">{{ formatDate(card.created_at) }}</td>
                              <td class="border px-4 py-2">{{ formatNumber(card.qty) }}</td>
                              <td class="border px-4 py-2">
                                <span 
                                  :class="card.note === 'in' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'"
                                >
                                  {{ card.note === 'in' ? 'Masuk' : 'Keluar' }}
                                </span>
                              </td>
                              <td class="border px-4 py-2">{{ card.description ?? '-' }}</td>
                              <td class="border px-4 py-2">{{ card.vendor ?? '-' }}</td>
                            </tr>
                            
                            <tr v-if="filteredStockCards.length === 0">
                              <td colspan="6" class="text-gray-500 py-4">Tidak ada riwayat ditemukan</td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>