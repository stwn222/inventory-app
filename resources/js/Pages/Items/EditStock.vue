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
    description: ''
});

const submitForm = () => {
    form.patch(`/items/${props.item.id}/update-stock`);
}
</script>

<template>
    <Head title="Create Item" />
    <AuthenticatedLayout>  
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Stock Item : {{ props.item.name }} ({{ props.item.qty }} {{ props.item.unit }})
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label for="qty" class="block text-gray-700 text-sm font-bold mb-2"> Quantity :</label>
                            <input type="number" id="qty" v-model="form.qty" required class="shadow appearance-none border rounded w-full">
                            <div class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative mt-2 p-2" v-if="form.errors.qty">
                                {{ form.errors.qty }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="note" class="block text-gray-700 text-sm font-bold mb-2"> Note :</label>
                            <input type="radio" id="note" value="in" name="note" v-model="form.note"> IN</input>
                            <input type="radio" id="note" value="out" name="note" v-model="form.note"> OUT</input>
                            <div class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative mt-2 p-2" v-if="form.errors.note">
                                {{ form.errors.note }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2"> Description :</label>
                            <input type="text" id="description" v-model="form.description" required class="shadow appearance-none border rounded w-full">
                            <div class="bg-red-100 border border-red-400 text-black-700 px-4 py-3 rounded m-2 relative mt-2 p-2" v-if="form.errors.description">
                                {{ form.errors.description }}
                            </div>
                        </div>
                        <PrimaryButton type="submit">Update Stock Item</PrimaryButton>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>