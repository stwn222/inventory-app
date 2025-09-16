<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue'; // Tombol merah untuk aksi berbahaya
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'; // Import router Inertia
import { computed } from 'vue';

defineProps({
    pemasukan: Object,
});

// Mengambil flash message dari page props
const flash = computed(() => usePage().props.flash);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// Fungsi untuk menghapus data
const destroy = (id) => {
    // Tampilkan dialog konfirmasi dari browser
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        // Kirim request DELETE menggunakan router Inertia
        router.delete(route('pemasukan.destroy', id), {
            preserveScroll: true // Agar halaman tidak scroll ke atas setelah aksi
        });
    }
};
</script>

<template>
    <Head title="Data Pemasukan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pemasukan</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <Link :href="route('pemasukan.create')">
                            <PrimaryButton>Tambah Pemasukan</PrimaryButton>
                        </Link>

                        <div v-if="flash.message" class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ flash.message }}</span>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 mt-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in pemasukan.data" :key="item.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(item.tanggal) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ item.catatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatCurrency(item.jumlah) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ item.user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="item.status === 'selesai' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                            {{ item.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <Link :href="route('pemasukan.edit', item.id)">
                                            <PrimaryButton>Edit</PrimaryButton>
                                        </Link>
                                        <DangerButton @click="destroy(item.id)">
                                            Hapus
                                        </DangerButton>
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