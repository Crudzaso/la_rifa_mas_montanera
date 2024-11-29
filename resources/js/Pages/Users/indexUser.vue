<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Link from '@/Components/NavLink.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const users = page.props.users;

const showMessage = ref(false);
const message = ref('');


const deleteUser = (id) => {
    if (confirm('¿Estás seguro de eliminar este usuario?')) {
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
    <AppLayout title="usuarios">
        <!-- Mensaje de alerta -->
        <div v-if="showMessage"
             class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-md z-50"
             role="alert">
            <span class="block sm:inline font-bold">{{ message }}</span>
        </div>

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
                </h2>
                <Link  :href="route('users.create')"
                class="inline-flex items-center px-6 py-4 bg-indigo-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Crear Usuario
                </Link>
            </div>

        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actualizado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ user.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ new Date(user.updated_at).toLocaleDateString() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <!-- Editar -->
                                        <button
                                            @click="editUser(user.id)"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200 px-2"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <!-- Eliminar -->
                                        <button
                                            @click="deleteUser(user.id)"
                                            class="text-red-600 hover:text-red-900 transition-colors duration-200 px-2"
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

