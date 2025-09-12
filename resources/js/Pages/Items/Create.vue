<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name:'',
    qty: 0,
    unit:'',
    price:0,
});

const submitForm = () => {
    form.post('/items');
}
</script>

<template>
    <Head title="Create Item" />
    <AuthenticatedLayout>  
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tambah Barang
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2"> Nama Barang :</label>
                            <input type="text" id="name" v-model="form.name" required class="shadow appearance-none border rounded w-full">
                            <div class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative mt-2 p-2" v-if="form.errors.name">
                                {{ form.errors.name }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="qty" class="block text-gray-700 text-sm font-bold mb-2"> Jumlah :</label>
                            <input type="number" id="qty" v-model="form.qty" required class="shadow appearance-none border rounded w-full">
                            <div class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative mt-2 p-2" v-if="form.errors.qty">
                                {{ form.errors.qty }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="unit" class="block text-gray-700 text-sm font-bold mb-2"> Satuan :</label>
                            <input type="text" id="unit" v-model="form.unit" required class="shadow appearance-none border rounded w-full">
                            <div class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative mt-2 p-2" v-if="form.errors.unit">
                                {{ form.errors.unit }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700 text-sm font-bold mb-2"> Harga :</label>
                            <input type="text" id="price" v-model="form.price" required class="shadow appearance-none border rounded w-full">
                            <div class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative mt-2 p-2" v-if="form.errors.price">
                                {{ form.errors.price }}
                            </div>
                        </div>
                        <PrimaryButton type="submit">Create Item</PrimaryButton>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>