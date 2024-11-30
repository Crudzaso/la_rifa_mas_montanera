<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Link from '@/Components/NavLink.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const tickets = page.props.tickets;

const search = ref('');

const getStatusClass = (status) => {
    return {
        'Disponible': 'bg-green-100 text-green-800 px-2 py-1 rounded',
        'Vendido': 'bg-blue-100 text-blue-800 px-2 py-1 rounded',
        'Reservado': 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded'
    }[status] || 'bg-gray-100 text-gray-800 px-2 py-1 rounded';
};

const formatDate = (date) => {
    if (!date) return '';
    const fecha = new Date(date);
    fecha.setMinutes(fecha.getMinutes() + fecha.getTimezoneOffset());
    return fecha.toLocaleDateString('es-ES');
};

const deleteTicket = (id) => {
    if (confirm('¿Está seguro de eliminar este ticket?')) {
        // Implement delete functionality
    }
};
</script>

<template>
    <AppLayout title="Boletos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Boletos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Search and Filter Section -->
                    <div class="mb-4 flex justify-between">
                        <input type="text"
                               v-model="search"
                               placeholder="Buscar tickets..."
                               class="border rounded p-2">

                    </div>

                    <!-- Tickets Table -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">Número</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Estado</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Cliente</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Rifa</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Fecha</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="ticket in tickets" :key="ticket.id">
                                <td class="px-6 py-4">{{ ticket.ticket_number }}</td>
                                <td class="px-6 py-4">
                                    <span :class="getStatusClass(ticket.is_winner)">
                                        {{ ticket.is_winner ? 'Ganador' : 'Pendiente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ ticket.user.name }}</td>
                                <td class="px-6 py-4">{{ ticket.raffle.title }}</td>
                                <td class="px-6 py-4">{{ formatDate(ticket.created_at) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <Link :href="route('tickets.edit', ticket.id)"
                                              class="text-blue-600 hover:text-blue-900">
                                            Editar
                                        </Link>
                                        <button @click="deleteTicket(ticket.id)"
                                                class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


