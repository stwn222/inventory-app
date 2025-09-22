<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const showingSidebar = ref(false);
</script>

<template>
    <div class="flex h-screen bg-gray-100 font-sans">
        <aside class="w-64 flex-shrink-0 bg-blue-800 text-white flex flex-col p-4">
            <div class="flex items-center justify-center mb-10">
                <Link :href="route('dashboard')" class="flex items-center gap-3">
                    <div class="bg-white p-1 rounded-full shadow">
                        <ApplicationLogo class="block h-10 w-10 text-blue-800" />
                    </div>
                    <span class="text-2xl font-bold">BIP</span>
                </Link>
            </div>

            <nav class="flex flex-col gap-2">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors"
                    :class="{ 'bg-blue-900': route().current('dashboard') }"
                >
                    <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Dashboard</span>
                </Link>
                <Link
                    :href="route('items.index')"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors"
                    :class="{ 'bg-blue-900': route().current('items.*') }"
                >
                    <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                    <span>Barang</span>
                </Link>
                 <Link
                    :href="route('transaksi.index')"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors"
                    :class="{ 'bg-blue-900': route().current('transaksi.*') }"
                >
                    <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                    <span>Transaksi</span>
                </Link>
            </nav>

            <div class="mt-auto">
                <div class="flex items-center gap-4 p-2 rounded-lg hover:bg-blue-700">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <div class="flex-1">
                        <p class="font-semibold">{{ $page.props.auth.user.name }}</p>
                        <Link :href="route('logout')" method="post" as="button" class="text-xs text-blue-200 hover:underline">
                           Log Out
                        </Link>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                     <slot name="header" />
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>