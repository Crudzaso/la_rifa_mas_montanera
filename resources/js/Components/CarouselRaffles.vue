<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  raffles: {
    type: Array,
    required: true
  }
});

const activeRaffles = computed(() => {
  return props.raffles.filter(raffle => raffle.status === 'ongoing');
});

const formatDate = (date) => {
    if (!date) return 'Sin fecha';
    const fecha = new Date(date);
    fecha.setMinutes(fecha.getMinutes() + fecha.getTimezoneOffset());

    return fecha.toLocaleDateString();
};
</script>

<template>
  <div v-if="activeRaffles.length > 0" class="relative w-full py-8">
    <div class="overflow-x-auto no-scrollbar">
      <div class="flex space-x-6 px-4 pb-4 animate-scroll">
        <div v-for="raffle in activeRaffles"
             :key="raffle.id"
             class="relative w-96 h-[400px] flex-none rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
          <!-- Imagen y gradiente -->
          <div class="absolute inset-0">
            <template v-if="raffle.url_image">
              <img :src="raffle.url_image"
                   :alt="raffle.title"
                   class="w-full h-full object-cover"/>
            </template>
            <template v-else>
              <div class="w-full h-full flex items-center justify-center bg-[#4F772D]/10">
                <svg class="w-24 h-24 text-[#4F772D]/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            </template>
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/20"></div>
          </div>

          <!-- Título -->
          <div class="absolute top-0 left-0 right-0 p-4 z-10">
            <h3 class="text-xl font-bold text-white drop-shadow-lg">
              {{ raffle.title }}
            </h3>
          </div>

          <!-- Footer con premio, botón y fecha -->
          <div class="absolute bottom-0 left-0 right-0 p-4 space-y-3 z-10">
            <!-- Premio y Botón -->
            <div class="flex items-center space-x-4">
              <div class="flex-grow bg-[#4F772D]/90 backdrop-blur-sm rounded-lg p-3 shadow-lg">
                <span class="text-lg font-bold text-[#ECF39E]">
                  Premio: {{ raffle.prize }}
                </span>
              </div>
              <a :href="route('register')"
                 class="inline-flex items-center px-4 py-3 bg-[#ECF39E] text-[#132A13] rounded-lg text-bm font-bold
                        hover:bg-white transform hover:scale-105 transition-all duration-200 shadow-lg whitespace-nowrap">
                Participar
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
              </a>
            </div>
            
            <!-- Fecha -->
            <div class="flex justify-between items-center bg-black/50 backdrop-blur-sm rounded-lg px-4 py-2">
              <span class="text-sm text-white font-medium">
                Juega el {{ formatDate(raffle.end_date) }}
              </span>
              <div class="h-2 w-2 rounded-full bg-[#4F772D] animate-pulse"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="text-center py-12">
    <p class="text-[#31572C] text-xl">No hay rifas disponibles en este momento</p>
  </div>
</template>

<style scoped>
@keyframes scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(calc(-384px * 5)); }
}

.animate-scroll {
  animation: scroll 60s linear infinite;
  animation-delay: 2s;
}

.animate-scroll:hover {
  animation-play-state: paused;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
</style>
