<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    tanggal: new Date().toISOString().slice(0, 10), // Default ke hari ini
    catatan: '',
    jumlah: null,
    status: 'selesai',
});

const submit = () => {
    form.post(route('pengeluaran.store'), {
        onSuccess: () => form.reset('catatan', 'jumlah'),
    });
};
</script>

<template>
    <Head title="Tambah Pengeluaran" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-minus-circle mr-2"></i> Tambah Data Pengeluaran Baru
            </h2>
        </template>

        <div class="py-12 bg-gray-50">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-8 border-b border-gray-200">
                        <p class="text-gray-600 mb-6">
                            Isi formulir di bawah ini untuk menambahkan data transaksi pengeluaran baru ke dalam sistem.
                        </p>
                        
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="tanggal" value="Tanggal Transaksi" class="font-bold" />
                                <TextInput
                                    id="tanggal"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.tanggal"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.tanggal" />
                            </div>

                            <div class="mt-6">
                                <InputLabel for="catatan" value="Catatan Pengeluaran" class="font-bold" />
                                <textarea
                                    id="catatan"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.catatan"
                                    rows="3"
                                    required
                                    placeholder="Contoh: Pembelian ATK untuk kantor"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.catatan" />
                            </div>

                            <div class="mt-6">
                                <InputLabel for="jumlah" value="Jumlah Pengeluaran" class="font-bold" />
                                <div class="relative mt-1">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <TextInput
                                        id="jumlah"
                                        type="number"
                                        class="block w-full pl-10"
                                        v-model="form.jumlah"
                                        required
                                        placeholder="150000"
                                    />
                                </div>
                                <InputError class="mt-2" :message="form.errors.jumlah" />
                            </div>
                            
                            <div class="mt-6">
                                <InputLabel for="status" value="Status Transaksi" class="font-bold" />
                                <select 
                                    id="status" 
                                    v-model="form.status"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option value="selesai">Selesai</option>
                                    <option value="pending">Pending</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                                <Link :href="route('pengeluaran.index')" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                    Batal
                                </Link>

                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Pengeluaran
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>