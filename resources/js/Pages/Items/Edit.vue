<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    item: Object
});

const form = useForm({
    name: props.item.name,
    unit: props.item.unit,
    price: props.item.price // simpan angka murni
});

// fungsi untuk format rupiah
const formatRupiah = (value) => {
    if (!value) return '';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

const handlePriceInput = (e) => {
    // ambil angka saja (hapus selain digit)
    let raw = e.target.value.replace(/[^0-9]/g, '');
    form.price = raw ? parseInt(raw) : 0;

    // update tampilan input dengan format Rp.
    e.target.value = formatRupiah(form.price);
};

const submitForm = () => {
    form.patch(`/items/${props.item.id}`);
};
</script>

<template>
    <Head title="Edit Item" />
    <AuthenticatedLayout>  
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Barang
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submitForm">
                            <!-- Nama -->
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2"> Nama Barang :</label>
                                <input type="text" id="name" v-model="form.name" required class="shadow appearance-none border rounded w-full">
                                <div class="bg-red-100 border border-red-400 px-4 py-3 rounded mt-2" v-if="form.errors.name">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <!-- Satuan -->
                            <div class="mb-4">
                                <label for="unit" class="block text-gray-700 text-sm font-bold mb-2"> Satuan :</label>
                                <input type="text" id="unit" v-model="form.unit" required class="shadow appearance-none border rounded w-full">
                                <div class="bg-red-100 border border-red-400 px-4 py-3 rounded mt-2" v-if="form.errors.unit">
                                    {{ form.errors.unit }}
                                </div>
                            </div>

                            <!-- Harga -->
                            <div class="mb-4">
                                <label for="price" class="block text-gray-700 text-sm font-bold mb-2"> Harga :</label>
                                <input 
                                    type="text" 
                                    id="price" 
                                    :value="formatRupiah(form.price)" 
                                    @input="handlePriceInput"
                                    required 
                                    class="shadow appearance-none border rounded w-full">
                                <div class="bg-red-100 border border-red-400 px-4 py-3 rounded mt-2" v-if="form.errors.price">
                                    {{ form.errors.price }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <PrimaryButton 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                >
                                    <span v-if="form.processing">Processing...</span>
                                    <span v-else>Update Barang</span>
                                </PrimaryButton>
                                
                                <a 
                                    :href="route('items.index')" 
                                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                                >
                                    Kembali ke Daftar Item
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
