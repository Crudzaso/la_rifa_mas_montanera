<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  raffles: {
    type: Array,
    required: true
  }
});
</script>

<template>
  <div v-if="raffles && raffles.length > 0" class="relative w-full py-8">
    <div class="overflow-x-auto no-scrollbar">
      <div class="flex space-x-6 px-4 pb-4 animate-scroll">
        <div v-for="raffle in raffles"
             :key="raffle.id"
             class="relative w-80 h-[400px] flex-none rounded-xl overflow-hidden shadow-lg transform hover:scale-105 transition-all duration-300">
          <!-- Imagen -->
          <div class="absolute inset-0 bg-gradient-to-t from-[#132A13] via-transparent">
            <template v-if="raffle.url_image">
              <img :src="raffle.url_image"
                   :alt="raffle.title"
                   class="w-full h-full object-cover opacity-75"/>
            </template>
            <template v-else>
              <div class="w-full h-full flex items-center justify-center bg-[#4F772D]/10">
                <svg class="w-24 h-24 text-[#4F772D]/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            </template>
          </div>

          <!-- Contenido -->
          <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
            <h3 class="text-xl font-bold mb-2">{{ raffle.title }}</h3>
            <div class="flex flex-col space-y-2">
              <span class="text-lg font-semibold text-[#ECF39E]">Premio: {{ raffle.prize }}</span>
              <span class="bg-[#4F772D] px-3 py-1 rounded-full text-sm inline-block w-fit">
                Termina el {{ new Date(raffle.end_date).toLocaleDateString() }}
              </span>
            </div>
            <div class="mt-4">
              <a :href="route('register')"
                 class="inline-flex items-center px-4 py-2 bg-white text-[#132A13] rounded-lg text-sm font-bold hover:bg-[#ECF39E] transform hover:scale-105 transition-all duration-200">
                Participar
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
              </a>
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
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}

@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(calc(-280px * 5));
  }
}

.animate-scroll {
  animation: scroll 45s linear infinite;
}

.animate-scroll:hover {
  animation-play-state: paused;
}
</style>
