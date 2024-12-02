<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Link from '@/Components/NavLink.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import Alert from '@/Components/Alert.vue';

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

const searchQuery = ref('');
const searchType = ref('title'); 

// Modificar filteredRaffles para incluir búsqueda y ordenamiento
const filteredRaffles = computed(() => {
    if (!raffles) return [];
    
    return raffles
        .filter(raffle => {
            const matchesStatus = raffle.status === activeTab.value;
            const searchText = searchQuery.value.toLowerCase();
            
            const matchesSearch = searchType.value === 'title' 
                ? raffle.title.toLowerCase().includes(searchText)
                : raffle.prize.toLowerCase().includes(searchText);
            
            return matchesStatus && matchesSearch;
        })
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
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
    <Alert
      v-if="showMessage"
      :message="message"
      :type="'Éxito'"
    />

    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
          Rifas
        </h2>
        <Link :href="route('raffles.create')"
              class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm uppercase tracking-wider hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
          <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          Crear Rifa
        </Link>
      </div>
    </template>

    <!-- Barra de búsqueda y filtros -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
      <div class="flex items-center space-x-4 mb-6">
        <div class="flex-1 relative">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="searchType === 'title' ? 'Buscar por nombre...' : 'Buscar por premio...'"
            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
          />
          <svg class="absolute right-3 top-3 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <select
          v-model="searchType"
          class="px-8 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
        >
          <option value="title">Buscar por nombre</option>
          <option value="prize">Buscar por premio</option>
        </select>
      </div>

      <!-- Pestañas mejoradas -->
      <div class="flex justify-center mb-8">
        <nav class="flex space-x-2 bg-gray-100 p-1 rounded-xl">
          <button
            v-for="tab in tabStates"
            :key="tab"
            @click="changeTab(tab)"
            class="px-6 py-2.5 rounded-lg font-medium text-sm transition-all duration-200"
            :class="[
              activeTab === tab
                ? 'bg-white text-indigo-600 shadow-sm'
                : 'text-gray-600 hover:text-gray-900'
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
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Tarjeta de Rifa (más compacta) -->
          <div v-for="raffle in filteredRaffles" :key="raffle.id"
               class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <!-- Imagen más pequeña -->
            <div class="relative">
              <img v-if="raffle.url_image" 
                   :src="raffle.url_image" 
                   :alt="raffle.title"
                   class="w-full h-44 object-cover"/> <!-- Reducida de h-56 a h-44 -->
              <div v-else class="w-full h-44 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <!-- Icono de imagen -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
              </div>
              <!-- Estado -->
              <span :class="[getStatusColor(raffle.status), 'absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold shadow-md']">
                {{ getStatusTranslation(raffle.status) }}
              </span>
            </div>

            <!-- Contenido más compacto -->
            <div class="p-4 space-y-3">
              <h3 class="text-xl font-bold text-gray-800">{{ raffle.title }}</h3>
              
              <!-- Premio con nuevo icono -->
              <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-3 rounded-lg">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                  </svg>
                  <div>
                    <p class="text-xs text-indigo-600 font-medium">Premio</p>
                    <p class="text-base font-bold text-indigo-900">{{ raffle.prize }}</p>
                  </div>
                </div>
              </div>

              <!-- Grid de información -->
              <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 p-3 rounded-lg">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <p class="text-xs text-gray-600">Boletos</p>
                  </div>
                  <p class="text-sm font-bold text-gray-900 mt-1">{{ raffle.tickets_sold }}/{{ raffle.total_tickets }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-gray-600">Precio</p>
                  </div>
                  <p class="text-sm font-bold text-gray-900 mt-1">${{ raffle.price_tickets }}</p>
                </div>
              </div>

              <!-- Fecha -->
              <div class="flex items-center text-sm text-gray-600 pt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <span class="text-xs">Juega: {{ formatDate(raffle.end_date) }}</span>
              </div>

              <!-- Botones -->
              <div class="flex justify-end space-x-2 pt-2">
                <Link :href="route('raffles.edit', raffle.id)" 
                      class="text-sm px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                  </svg>
                </Link>
                <Link v-if="raffle.status === 'ongoing'"
                      :href="route('tickets.create', { raffle_id: raffle.id })"
                      class="text-sm px-4 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700">
                  Comprar
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

