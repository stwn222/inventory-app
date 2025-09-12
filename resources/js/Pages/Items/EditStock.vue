<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    item: Object
});

const form = useForm({
    qty: 0,
    note: '',
    description: '',
    vendor: ''
});

const submitForm = () => {
    form.patch(`/items/${props.item.id}/update-stock`, {
        onSuccess: () => {
            form.reset();
        }
    });
}
</script>

<template>
    <Head title="Edit Stock Item" />
    <AuthenticatedLayout>  
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Stock Barang : {{ props.item.name }} ({{ props.item.qty }} {{ props.item.unit }})
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label for="qty" class="block text-gray-700 text-sm font-bold mb-2">Quantity :</label>
                            <input 
                                type="number" 
                                id="qty" 
                                v-model="form.qty" 
                                required 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                min="1"
                                max="9999"
                            >
                            <div class="text-red-500 text-xs italic mt-1" v-if="form.errors.qty">
                                {{ form.errors.qty }}
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Note :</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        value="in" 
                                        v-model="form.note" 
                                        class="form-radio h-4 w-4 text-blue-600"
                                    >
                                    <span class="ml-2">IN</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        value="out" 
                                        v-model="form.note" 
                                        class="form-radio h-4 w-4 text-blue-600"
                                    >
                                    <span class="ml-2">OUT</span>
                                </label>
                            </div>
                            <div class="text-red-500 text-xs italic mt-1" v-if="form.errors.note">
                                {{ form.errors.note }}
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description :</label>
                            <textarea 
                                id="description" 
                                v-model="form.description" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                rows="3"
                            ></textarea>
                            <div class="text-red-500 text-xs italic mt-1" v-if="form.errors.description">
                                {{ form.errors.description }}
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="vendor" class="block text-gray-700 text-sm font-bold mb-2">Vendor :</label>
                            <input 
                                type="text" 
                                id="vendor" 
                                v-model="form.vendor" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Nama vendor/supplier (opsional)"
                            >
                            <div class="text-red-500 text-xs italic mt-1" v-if="form.errors.vendor">
                                {{ form.errors.vendor }}
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <PrimaryButton 
                                type="submit" 
                                :disabled="form.processing"
                                class="hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                <span v-if="form.processing">Processing...</span>
                                <span v-else>Update Stock Barang</span>
                            </PrimaryButton>
                            
                            <a 
                                :href="route('items.index')" 
                                class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                            >
                                Kembali ke Daftar Barang
                            </a>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.form-radio:checked {
    background-color: currentColor;
    border-color: currentColor;
}
</style>