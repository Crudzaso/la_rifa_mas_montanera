<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Link from '@/Components/NavLink.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const page = usePage();
const users = page.props.users;

const showMessage = ref(false);
const message = ref('');


const deleteUser = async (id) => {
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4F772D',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: '#fff',
        borderRadius: '1rem',
    });

    if (result.isConfirmed) {
        router.delete(route('users.destroy', id), {
            preserveScroll: true,
            preserveState: false,
            onSuccess: () => {
                message.value = 'Usuario eliminado correctamente';
                showMessage.value = true;
                setTimeout(() => {
                    showMessage.value = false;
                }, 3000);
            }
        });
    }
};

const editUser = (id) => {
    router.get(route('users.edit', id));
};
</script>

<template>
    <AppLayout title="Usuarios">
        <!-- Alerta -->
        <div v-if="showMessage"
             class="fixed top-4 right-4 bg-[#4F772D]/10 border border-[#4F772D] text-[#31572C] px-6 py-4 rounded-xl shadow-lg z-50 backdrop-blur-sm">
            <span class="block sm:inline font-semibold">{{ message }}</span>
        </div>

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-2xl text-[#242724] leading-tight flex items-center gap-2">
                    <img src="@/assets/images/background.png" alt="Logo" class="w-8 h-8 object-contain rounded-full">
                    Gestión de Usuarios
                </h2>
                <Link :href="route('users.create')"
                      class="inline-flex items-center px-6 py-3 bg-white/90 text-[#31572C] rounded-xl font-bold text-sm hover:bg-[#ECF39E] transform hover:scale-105 transition-all duration-200 shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Crear Usuario
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Logo -->
                <div class="absolute right-10 top-40 opacity-20">
                    <div class="w-64 h-64 bg-gradient-to-br from-[#4F772D]/30 to-[#90A955]/100 rounded-full absolute"></div>
                    <img src="@/assets/images/background.png" alt="Background Logo" class="w-64 h-64 object-contain relative z-10">
                </div>

                <div class="bg-white/80 backdrop-blur-sm overflow-hidden rounded-xl shadow-xl border border-[#4F772D]/20 relative">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#4F772D]/10">
                            <thead class="bg-gradient-to-r from-[#4F772D]/20 to-[#90A955]/20">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-[#31572C] uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-[#31572C] uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-[#31572C] uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-[#31572C] uppercase tracking-wider">Creado</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-[#31572C] uppercase tracking-wider">Actualizado</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-[#31572C] uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 divide-y divide-[#4F772D]/10">
                                <tr v-for="user in users" :key="user.id"
                                    class="hover:bg-[#4F772D]/5 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#31572C]">{{ user.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#31572C]">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#31572C]">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#31572C]">
                                        {{ new Date(user.created_at).toLocaleDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#31572C]">
                                        {{ new Date(user.updated_at).toLocaleDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <button
                                            @click="editUser(user.id)"
                                            class="inline-flex items-center p-2 bg-[#4F772D]/10 text-[#31572C] rounded-lg hover:bg-[#4F772D]/20 transition-all duration-200 hover:scale-105"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button
                                            @click="deleteUser(user.id)"
                                            class="inline-flex items-center p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 hover:scale-105"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>



