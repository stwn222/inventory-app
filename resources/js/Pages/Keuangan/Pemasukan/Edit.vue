<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    pemasukan: Object, // Data ini dikirim dari controller
});

const form = useForm({
    tanggal: props.pemasukan.tanggal,
    catatan: props.pemasukan.catatan,
    jumlah: props.pemasukan.jumlah,
    status: props.pemasukan.status,
});

const submit = () => {
    form.put(route('pemasukan.update', props.pemasukan.id));
};
</script>

<template>
    <Head title="Edit Pemasukan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Data Pemasukan</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="tanggal" value="Tanggal" />
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

                            <div class="mt-4">
                                <InputLabel for="catatan" value="Catatan" />
                                <TextInput
                                    id="catatan"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.catatan"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.catatan" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="jumlah" value="Jumlah (Rp)" />
                                <TextInput
                                    id="jumlah"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.jumlah"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.jumlah" />
                            </div>
                            
                            <div class="mt-4">
                                <InputLabel for="status" value="Status" />
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

                            <div class="flex items-center justify-end mt-6">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>