<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    transaction: Object,
});

const form = useForm({
    tanggal: props.transaction.tanggal,
    catatan: props.transaction.catatan,
    jumlah: props.transaction.jumlah,
    status: props.transaction.status,
    tipe: props.transaction.tipe,
});

const submit = () => {
    form.put(route('transaksi.update', props.transaction.id));
};
</script>

<template>
    <Head title="Edit Transaksi" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Transaksi</h2>
        </template>
        <div class="py-12 bg-gray-50">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-8">
                        <form @submit.prevent="submit">
                             <div class="mb-6">
                                <InputLabel value="Tipe Transaksi" class="font-bold mb-2"/>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.tipe" value="pemasukan" name="tipe" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2">Pemasukan</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.tipe" value="pengeluaran" name="tipe" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2">Pengeluaran</span>
                                    </label>
                                </div>
                                <InputError class="mt-2" :message="form.errors.tipe" />
                            </div>

                             <div>
                                <InputLabel for="tanggal" value="Tanggal Transaksi" class="font-bold" />
                                <TextInput id="tanggal" type="date" class="mt-1 block w-full" v-model="form.tanggal" required/>
                                <InputError class="mt-2" :message="form.errors.tanggal" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="catatan" value="Catatan" class="font-bold" />
                                <textarea id="catatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="form.catatan" rows="3" required></textarea>
                                <InputError class="mt-2" :message="form.errors.catatan" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="jumlah" value="Jumlah (Rp)" class="font-bold" />
                                <TextInput id="jumlah" type="number" class="mt-1 block w-full" v-model="form.jumlah" required />
                                <InputError class="mt-2" :message="form.errors.jumlah" />
                            </div>
                            <div class="mt-6">
                                <InputLabel for="status" value="Status" class="font-bold" />
                                <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="selesai">Selesai</option>
                                    <option value="pending">Pending</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div class="flex items-center justify-end mt-8 pt-6 border-t">
                                <Link :href="route('transaksi.index')" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                    Batal
                                </Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update Transaksi
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>