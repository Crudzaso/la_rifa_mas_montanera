<script setup>
import { ref, provide, computed } from 'vue'; 
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import { usePermissions } from '@/Composables/usePermissions';

defineProps({
    title: String,
});

const { hasRole, hasPermission } = usePermissions();
const page = usePage();

// Agregar computed para ver permisos
const userPermissions = computed(() => {
    const user = page.props.auth?.user;
    if (!user || !user.roles) return [];
    
    // Obtener todos los permisos de todos los roles
    return user.roles.flatMap(role => role.permissions || []);
});

//console.log('Roles del usuario:', page.props.auth?.user?.roles);
//console.log('Permisos del usuario:', userPermissions.value);

// Proveer permisos a componentes hijos
provide('userPermissions', userPermissions);
provide('hasRole', hasRole);
provide('hasPermission', hasPermission);

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-[#ECF39E]/60 via-white to-[#90A955]">
        <Head :title="title" />
        <Banner />
        <div class="flex-grow">
            <NavBar 
                :auth="$page.props.auth" 
                :user-permissions="userPermissions"
            />

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-gradient-to-r from-[#90A955]/40 to-[#edf593]/80 backdrop-blur-sm shadow-md border-b border-[#4F772D]/20">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="min-h-screen px-2 sm:px-4 md:px-0">
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
