<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Link from '@/Components/NavLink.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
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

// Añadir ref para controlar animaciones
const isParticipateVisible = ref(false);
const isRafflesVisible = ref(false);

onMounted(() => {
  // Configurar Intersection Observer
  const observerOptions = {
    threshold: 0.1
  };

  const participateObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        isParticipateVisible.value = true;
      }
    });
  }, observerOptions);

  const rafflesObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        isRafflesVisible.value = true;
      }
    });
  }, observerOptions);

  // Observar elementos
  const participateSection = document.querySelector('.participate-section');
  const rafflesSection = document.querySelector('.raffles-section');

  if (participateSection) participateObserver.observe(participateSection);
  if (rafflesSection) rafflesObserver.observe(rafflesSection);
});
</script>

<template>
  <AppLayout title="Rifas">
    <Alert v-if="showMessage" :message="message" :type="'Éxito'" />

    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-[#31572C] leading-tight flex items-center gap-3">
          <img src="@/assets/images/background.png" alt="Logo" class="w-8 h-8 object-contain">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
          </svg>
          Rifas más Montañeras
        </h2>
        <Link :href="route('raffles.create')"
              class="inline-flex items-center px-6 py-3 bg-white/90 text-[#31572C] rounded-xl font-bold text-sm hover:bg-[#ECF39E] transform hover:scale-105 transition-all duration-200 shadow-xl">
          <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          Crear Rifa
        </Link>
      </div>
    </template>

    <!-- Pasos para comprar-->
    <div class="py-12 relative overflow-hidden bg-gradient-to-br from-white/80 to-[#ECF39E]/20 group hover:from-gray-800/5 hover:to-[#31572C]/10 transition-all duration-700 backdrop-blur-md participate-section"
         :class="{ 'animate-fade-in-up': isParticipateVisible }">
      <!-- Logo Background -->
      <div class="absolute right-0 top-0 opacity-5 transform rotate-12 translate-x-0
              group-hover:opacity-20
              group-hover:rotate-6
              group-hover:scale-110
              group-hover:-translate-x-10
              transition-all duration-700 ease-in-out">
        <img src="@/assets/images/background.png" alt="Background Logo" class="w-96 h-96 object-contain"/>
      </div>

      <!-- Overlay -->
      <div class="absolute inset-0 bg-gradient-to-br from-transparent to-transparent
              group-hover:from-gray-900/10
              group-hover:to-transparent
              transition-all duration-700"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <h2 class="text-4xl font-serif text-center mb-16 text-[#31572C] transform transition-all duration-500 group-hover:scale-105 group-hover:text-[#4F772D]">
          ¿Cómo Participar?
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
          <!-- Paso 1: Buscar -->
          <div class="group/card transform transition-all duration-500 hover:-translate-y-3 hover:rotate-1">
            <div class="flex flex-col items-center">
              <div class="bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/30 rounded-2xl p-6 mb-6 shadow-xl">
                <svg class="h-12 w-12 text-[#31572C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <h3 class="text-2xl font-serif mb-4 text-[#31572C]">1. Busca</h3>
              <p class="text-center text-[#4F772D] px-6">
                Explora las rifas disponibles y encuentra los mejores premios.
              </p>
            </div>
          </div>

          <!-- Paso 2: Selecciona -->
          <div class="group/card transform transition-all duration-500 hover:-translate-y-3 hover:rotate-1">
            <div class="flex flex-col items-center">
              <div class="bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/30 rounded-2xl p-6 mb-6 shadow-xl group-hover/card:shadow-2xl group-hover/card:from-[#4F772D]/20 group-hover/card:to-[#90A955]/40 transition-all duration-700">
                <svg class="h-12 w-12 text-[#31572C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
              </div>
              <h3 class="text-2xl font-serif mb-4 text-[#31572C] group-hover/card:text-[#4F772D] transition-colors duration-300">
                2. Selecciona
              </h3>
              <p class="text-center text-[#4F772D] px-6 opacity-90 group-hover/card:opacity-100">
                Elige tus números favoritos. ¡Cada número es una oportunidad!
              </p>
            </div>
          </div>

          <!-- Paso 3: Espera -->
          <div class="group/card transform transition-all duration-500 hover:-translate-y-3 hover:rotate-1">
            <div class="flex flex-col items-center">
              <div class="bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/30 rounded-2xl p-6 mb-6 shadow-xl group-hover/card:shadow-2xl group-hover/card:from-[#4F772D]/20 group-hover/card:to-[#90A955]/40 transition-all duration-700">
                <svg class="h-12 w-12 text-[#31572C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-2xl font-serif mb-4 text-[#31572C] group-hover/card:text-[#4F772D] transition-colors duration-300">
                3. Espera
              </h3>
              <p class="text-center text-[#4F772D] px-6 opacity-90 group-hover/card:opacity-100">
                Aguarda al día del sorteo. ¡La suerte puede estar de tu lado!
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Barra de búsqueda y filtros -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
      <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1 relative">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="searchType === 'title' ? 'Buscar por nombre...' : 'Buscar por premio...'"
            class="w-full px-4 py-3 rounded-xl border border-[#4F772D]/20 focus:ring-2 focus:ring-[#4F772D]/50 focus:border-[#4F772D] shadow-sm bg-white/80 backdrop-blur-sm"
          />
        </div>
        <select
          v-model="searchType"
          class="px-8 py-3 rounded-xl border border-[#4F772D]/20 focus:ring-2 focus:ring-[#4F772D]/50 focus:border-[#4F772D] shadow-sm bg-white/80 backdrop-blur-sm text-[#31572C]"
        >
          <option value="title">Buscar por nombre</option>
          <option value="prize">Buscar por premio</option>
        </select>
      </div>

      <!-- Pestañas-->
      <div class="flex justify-center mb-8">
        <nav class="flex space-x-2 bg-white/50 backdrop-blur-sm p-1 rounded-xl shadow-md border border-[#4F772D]/20">
          <button
            v-for="tab in tabStates"
            :key="tab"
            @click="changeTab(tab)"
            class="px-6 py-2.5 rounded-lg font-medium text-sm transition-all duration-200"
            :class="[
              activeTab === tab
                ? 'bg-[#4F772D] text-white shadow-sm'
                : 'text-[#31572C] hover:bg-[#4F772D]/10'
            ]"
          >
            {{ getStatusTranslation(tab) }}
          </button>
        </nav>
      </div>
    </div>

    <!-- Lista de rifas -->
    <div class="py-12 raffles-section">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Mensaje cuando no hay rifas -->
        <div v-if="filteredRaffles.length === 0"
             class="bg-white/80 backdrop-blur-sm p-8 rounded-xl shadow-xl border border-[#4F772D]/20 text-center">
          <svg class="mx-auto h-12 w-12 text-[#4F772D] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 14h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-[#31572C] text-lg">
            {{
              activeTab === 'ongoing' ? 'No hay rifas en juego' :
              activeTab === 'pending' ? 'No hay rifas pendientes' :
              'No hay rifas finalizadas'
            }}
          </p>
        </div>

        <!-- Grid de rifas -->
        <TransitionGroup
          name="raffle-list"
          tag="div"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
          :class="{ 'is-visible': isRafflesVisible }">
          <div v-for="(raffle, index) in filteredRaffles"
               :key="raffle.id"
               class="bg-white/80 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-[#4F772D]/20 group/card"
               :style="{ animationDelay: `${index * 0.1}s` }">
            <!-- Imagen con overlay -->
            <div class="relative overflow-hidden">
              <img v-if="raffle.url_image"
                   :src="raffle.url_image"
                   :alt="raffle.title"
                   class="w-full h-48 object-cover transform transition-transform duration-500 group-hover/card:scale-110"/>
              <div v-else class="w-full h-48 bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/30 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#4F772D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
              </div>
              <!-- Estado con nuevo estilo -->
              <span :class="[
                getStatusColor(raffle.status),
                'absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold shadow-md backdrop-blur-sm'
              ]">
                {{ getStatusTranslation(raffle.status) }}
              </span>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="p-6 space-y-4">
              <h3 class="text-xl font-bold text-[#31572C] group-hover/card:text-[#4F772D] transition-colors duration-300">
                {{ raffle.title }}
              </h3>

              <!-- Premio con nuevo estilo -->
              <div class="bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/30 p-4 rounded-xl transition-all duration-300 group-hover/card:from-[#4F772D]/20 group-hover/card:to-[#90A955]/40">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#4F772D] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <div>
                    <p class="text-sm text-[#4F772D] font-medium">Premio</p>
                    <p class="text-lg font-bold text-[#31572C]">{{ raffle.prize }}</p>
                  </div>
                </div>
              </div>

              <!-- Grid de información -->
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-[#4F772D]/5 to-[#90A955]/10 p-4 rounded-xl transition-all duration-300 group-hover/card:from-[#4F772D]/10 group-hover/card:to-[#90A955]/20">
                  <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#4F772D] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <p class="text-sm text-[#4F772D]">Boletos</p>
                  </div>
                  <p class="text-lg font-bold text-[#31572C]">{{ raffle.tickets_sold }}/{{ raffle.total_tickets }}</p>
                </div>

                <div class="bg-gradient-to-br from-[#4F772D]/5 to-[#90A955]/10 p-4 rounded-xl transition-all duration-300 group-hover/card:from-[#4F772D]/10 group-hover/card:to-[#90A955]/20">
                  <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#4F772D] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-[#4F772D]">Precio</p>
                  </div>
                  <p class="text-lg font-bold text-[#31572C]">${{ raffle.price_tickets }}</p>
                </div>
              </div>

              <!-- Fechas -->
              <div class="space-y-2 pt-2">
                <div class="flex items-center text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#4F772D] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span class="text-[#4F772D]">Juega: {{ formatDate(raffle.end_date) }}</span>
                </div>
              </div>

              <!-- Botones -->
              <div class="flex justify-end items-center gap-4 pt-4">
                <Link :href="route('raffles.edit', raffle.id)"
                      class="flex items-center px-4 py-2 bg-[#4F772D]/10 text-[#31572C] rounded-lg hover:bg-[#4F772D]/20 transition-all duration-300">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                  </svg>
                  Editar
                </Link>
                <Link v-if="raffle.status === 'ongoing'"
                      :href="route('tickets.create', { raffle_id: raffle.id })"
                      class="flex items-center px-6 py-2 bg-[#4F772D] text-white rounded-lg hover:bg-[#31572C] transition-all duration-300">
                  Comprar
                </Link>
              </div>
            </div>
          </div>
        </TransitionGroup>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 1s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.raffle-list-enter-active,
.raffle-list-leave-active {
  transition: all 0.5s ease;
}

.raffle-list-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.raffle-list-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}

.is-visible > * {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInStagger 0.5s ease-out forwards;
}

@keyframes fadeInStagger {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

