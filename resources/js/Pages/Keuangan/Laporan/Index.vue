<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    tipe: 'pemasukan',
    dari_tanggal: new Date().toISOString().slice(0, 10),
    sampai_tanggal: new Date().toISOString().slice(0, 10),
});

// Menggunakan form.post akan membuat browser mengunduh file PDF
const submit = () => {
    form.post(route('laporan.cetak'), {
        // Tidak perlu 'onFinish' karena browser akan menangani unduhan
    });
};
</script>

<template>
    <Head title="Cetak Laporan Keuangan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cetak Laporan Keuangan</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="tipe" value="Jenis Laporan" />
                                <select 
                                    id="tipe" 
                                    v-model="form.tipe"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option value="pemasukan">Pemasukan</option>
                                    <option value="pengeluaran">Pengeluaran</option>
                                    <option value="utang">Utang</option>
                                    <option value="piutang">Piutang</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.tipe" />
                            </div>
                        
                            <div class="mt-4">
                                <InputLabel for="dari_tanggal" value="Dari Tanggal" />
                                <TextInput
                                    id="dari_tanggal"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.dari_tanggal"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.dari_tanggal" />
                            </div>
                            
                            <div class="mt-4">
                                <InputLabel for="sampai_tanggal" value="Sampai Tanggal" />
                                <TextInput
                                    id="sampai_tanggal"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.sampai_tanggal"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.sampai_tanggal" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Cetak Laporan (PDF)
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>