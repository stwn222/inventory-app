<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    item: Object, // berisi item beserta stockCards
    filters: Object, // berisi filter yang aktif
});

// State untuk setiap filter, diisi dengan nilai dari controller jika ada
const search = ref(props.filters.search || '');
const noteFilter = ref(props.filters.note || 'semua');
const dariTanggal = ref(props.filters.dari_tanggal || '');
const sampaiTanggal = ref(props.filters.sampai_tanggal || '');

// Fungsi watcher yang akan berjalan setiap kali salah satu filter berubah
// debounce digunakan untuk menunda request selama 300ms setelah user selesai mengetik
watch([search, noteFilter, dariTanggal, sampaiTanggal], debounce(() => {
    router.get(route('items.StockCard', props.item.id), {
        search: search.value,
        note: noteFilter.value,
        dari_tanggal: dariTanggal.value,
        sampai_tanggal: sampaiTanggal.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

// format angka qty
function formatNumber(value) {
    return new Intl.NumberFormat('id-ID').format(value);
}

// format tanggal
function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString("id-ID", {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
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
                        <Link 
                            :href="route('items.index')" 
                            class="inline-block mb-4 font-bold text-sm text-blue-500 hover:text-blue-800"
                        >
                            &larr; Kembali ke Daftar Barang
                        </Link>

                        <div class="bg-gray-50 p-4 rounded-lg border grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <InputLabel for="search" value="Cari Keterangan/Vendor" />
                                <TextInput 
                                    id="search"
                                    type="text" 
                                    v-model="search" 
                                    placeholder="Ketik untuk mencari..." 
                                    class="mt-1 block w-full"
                                />
                            </div>
                            <div>
                                <InputLabel for="noteFilter" value="Tipe Transaksi" />
                                <select id="noteFilter" v-model="noteFilter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="semua">Semua</option>
                                    <option value="in">Masuk</option>
                                    <option value="out">Keluar</option>
                                </select>
                            </div>
                            <div>
                                <InputLabel for="dariTanggal" value="Dari Tanggal" />
                                <TextInput id="dariTanggal" type="date" class="mt-1 block w-full" v-model="dariTanggal" />
                            </div>
                            <div>
                                <InputLabel for="sampaiTanggal" value="Sampai Tanggal" />
                                <TextInput id="sampaiTanggal" type="date" class="mt-1 block w-full" v-model="sampaiTanggal" />
                            </div>
                        </div>

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
                            <tr v-for="(card, index) in item.stock_cards" :key="card.id">
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
                              <td class="border px-4 py-2 text-left">{{ card.description ?? '-' }}</td>
                              <td class="border px-4 py-2 text-left">{{ card.vendor ?? '-' }}</td>
                            </tr>
                            
                            <tr v-if="item.stock_cards.length === 0">
                              <td colspan="6" class="text-gray-500 py-4">Tidak ada riwayat ditemukan dengan filter ini.</td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>