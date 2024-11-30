<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Link from '@/Components/NavLink.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const page = usePage();
const raffles = page.props.raffles;
const activeTab = ref('ongoing');
const showMessage = ref(false);
const message = ref('');

// Estados disponibles
const tabStates = ['ongoing', 'pending', 'finished'];

// Formatear fechas
const formatDate = (date) => {
    if (!date) return 'Sin fecha';
    const fecha = new Date(date);
    fecha.setMinutes(fecha.getMinutes() + fecha.getTimezoneOffset());

    return fecha.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
};

// Calcular estado
const calculateStatus = (raffle) => {
    const now = new Date();
    const startDate = new Date(raffle.start_date);
    const endDate = new Date(raffle.end_date);

    if (now < startDate) {
        return 'pending';
    } else if (now > endDate) {
        return 'finished';
    } else {
        return 'ongoing';
    }
};


const filteredRaffles = computed(() => {
    if (!raffles) return [];
    return raffles.filter(raffle => raffle.status === activeTab.value);
});

// Actualizar vista al cambiar estado
watch(() => page.props.raffles, (newRaffles) => {
    if (newRaffles) {
        router.get(route('raffles.index'), {}, {
            preserveState: true,
            preserveScroll: true,
            only: ['raffles']
        });
    }
});

// Observar cambios en las fechas
watch(raffles, () => {
    raffles?.forEach(raffle => {
        const newStatus = calculateStatus(raffle);
        if (raffle.status !== newStatus) {
            router.put(route('raffles.update', raffle.id), {
                ...raffle,
                status: newStatus
            });
        }
    });
}, { deep: true });

// Traducción de estados
const getStatusTranslation = (status) => {
    switch(status) {
        case 'ongoing': return 'En Juego';
        case 'pending': return 'Pendiente';
        case 'finished': return 'Finalizado';
        default: return status;
    }
};

// Colores según estado
const getStatusColor = (status) => {
    switch(status) {
        case 'ongoing': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'finished': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

// Función para cambiar pestaña
const changeTab = (tab) => {
    activeTab.value = tab;
};

// Función para debug
const debugDate = (date) => {
    console.log('Fecha original:', date);
    console.log('Fecha formateada:', formatDate(date));
};
</script>

<template>
  <AppLayout title="Rifa">
    <!-- Mensaje de alerta -->
    <div v-if="showMessage"
         class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md z-50"
         role="alert">
      <span class="block sm:inline font-bold">{{ message }}</span>
    </div>

    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Rifas
        </h2>
        <Link :href="route('raffles.create')"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          Crear Rifa
        </Link>
      </div>
    </template>

    <!-- Pestañas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            v-for="tab in tabStates"
            :key="tab"
            @click="changeTab(tab)"
            class="py-4 px-1 border-b-2 font-medium text-sm"
            :class="[
              activeTab === tab
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            {{ getStatusTranslation(tab) }}
          </button>
        </nav>
      </div>
    </div>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Mensaje cuando no hay rifas -->
        <div v-if="filteredRaffles.length === 0"
             class="bg-white p-8 rounded-lg shadow-md text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 14h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-gray-500 text-lg">
            {{
              activeTab === 'ongoing' ? 'No hay rifas en juego' :
              activeTab === 'pending' ? 'No hay rifas pendientes' :
              'No hay rifas finalizadas'
            }}
          </p>
        </div>

        <!-- Lista de rifas -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Tarjeta de Rifa -->
          <div v-for="raffle in filteredRaffles" :key="raffle.id"
               class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow duration-300">
            <!-- Imagen de la rifa -->
            <img v-if="raffle.url_image" :src="raffle.url_image" :alt="raffle.title"
                 class="w-full h-48 object-cover"/>
            <div v-else class="w-full h-48 bg-gray-200 flex items-center justify-center">
              <span class="text-gray-400">Sin imagen</span>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="p-6">
              <!-- Título y Estado -->
              <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-bold text-gray-800 hover:text-indigo-600 transition-colors duration-200">
                  {{ raffle.title }}
                </h3>
                <span :class="[getStatusColor(raffle.status), 'px-3 py-1 rounded-full text-sm font-semibold shadow-sm']">
                  {{ getStatusTranslation(raffle.status) }}
                </span>
              </div>

              <!-- Detalles -->
              <div class="space-y-4 mb-6">
                <p class="text-gray-600 italic">{{ raffle.description }}</p>

                <!-- Premio  -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-lg shadow-sm">
                  <h4 class="text-lg font-semibold text-indigo-800 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    Premio
                  </h4>
                  <p class="text-xl font-bold text-indigo-900">{{ raffle.prize }}</p>
                </div>

                <!-- Informacion rifa -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                  <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-gray-600">Boletos:</p>
                    <p class="font-semibold text-gray-800">{{ raffle.tickets_sold }}/{{ raffle.total_tickets }}</p>
                  </div>
                  <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-gray-600">Precio:</p>
                    <p class="font-semibold text-gray-800">${{ raffle.price_tickets }}</p>
                  </div>
                </div>

                <!-- Fechas -->
                <div class="text-sm text-gray-600 space-y-1">
                  <p class="flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Fecha de inicio: {{ formatDate(raffle.start_date) }}
                  </p>
                  <p class="flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Fecha de juego: {{ formatDate(raffle.end_date) }}
                  </p>
                </div>
              </div>

              <!-- Botones -->
              <div class="flex justify-end space-x-3 mt-6">
                <!-- Botón de editar (Recordar cambiar cuando se creen roles
                y permisos v-if="$page.props.auth.user.role === 'admin'")-->
                <Link

                  :href="route('raffles.edit', raffle.id)"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Editar
                </Link>

                <Link
                  v-if="raffle.status === 'ongoing'"
                  :href="route('raffles.create', raffle.id)"
                  class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                  </svg>
                  Comprar Boleto
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

