<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955]">
        <Head :title="title" />
        <Banner />
        <div class="flex-grow">
            <NavBar :auth="$page.props.auth" />

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white/80 backdrop-blur-sm shadow-md border-b border-[#4F772D]/20">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="min-h-screen">
                <slot />
            </main>
        </div>

        <!-- Footer Slot -->
        <slot name="footer">
            <Footer /> 
        </slot>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

.font-montserrat {
    font-family: 'Montserrat', sans-serif;
}

:deep(.bg-gray-100) {
    background: transparent;
}
</style>
