<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    items: Array
});

// helper format harga
const formatPrice = (value) => {
    if (!value) return ''; // kalau null, undefined, atau 0 kosongkan
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value).replace('IDR', 'Rp').trim();
};
</script>

<template>
    <Head title="Item List" />
    <AuthenticatedLayout>  
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                List Barang
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <PrimaryButton>
                            <a href="/items/create" class="text-white">
                                Tambah Barang
                            </a>
                        </PrimaryButton>

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
                              <th class="text-xl px-6 py-3">#</th>
                              <th class="text-xl px-6 py-3">Nama Barang</th>
                              <th class="text-xl px-6 py-3">Jumlah</th>
                              <th class="text-xl px-6 py-3">Harga</th>
                              <th class="text-xl px-6 py-3">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(item, index) in items" :key="index">
                              <td class="border text-xl px=6 py-3">{{ ++index }}</td>
                              <td class="border text-xl px=6 py-3">{{ item.name }}</td>
                              <td class="border text-xl px=6 py-3">{{ item.qty + ' ' + item.unit }}</td>
                              <td class="border text-xl px=6 py-3">
                                {{ formatPrice(item.price) }}
                              </td>
                              <td class="border px=4 py-2">
                                  <PrimaryButton class="mr-2">
                                    <a class="px-4" :href="`/items/${item.id}/edit`">Edit Barang</a>
                                  </PrimaryButton>
                                  <PrimaryButton class="mr-2">
                                    <a class="px-4" :href="`/items/${item.id}/edit-stock`">Edit Stok</a>
                                  </PrimaryButton>
                                  <PrimaryButton class="mr-2">
                                    <a class="px-4" href="">Riwayat Stok</a>
                                  </PrimaryButton>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
