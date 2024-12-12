<script setup>
import { ref, onMounted, computed } from 'vue';

const currentSlide = ref(0);
const slideInterval = ref(null);

const team = [
  {
    name: 'Davi',
    role: 'Desarrollador Full Stack',
    image: '/path/to/creator1.jpg'
  },
  {
    name: 'Sebastian',
    role: 'Diseñador UX/UI',
    image: '/path/to/creator2.jpg'
  },
  {
    name: 'Samuel',
    role: 'Desarrollador Full Stack',
    image: '/path/to/creator1.jpg'
  },
  {
    name: 'Nicolas',
    role: 'Desarrollador Backend',
    image: '/path/to/creator3.jpg'
  }
];

// Calcular el número total de slides
const totalSlides = computed(() => Math.ceil(team.length / 1.6));

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % totalSlides.value;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + totalSlides.value) % totalSlides.value;
};

// Modificar los indicadores
const indicators = computed(() => Array.from({ length: totalSlides.value }));

onMounted(() => {
  slideInterval.value = setInterval(nextSlide, 4000);
});
</script>

<template>
  <div class="relative overflow-hidden rounded-2xl">
    <!-- Carrusel -->
    <div class="relative h-[500px]">
      <div class="absolute inset-0 flex transition-transform duration-500 ease-in-out"
           :style="{ transform: `translateX(-${currentSlide * 50}%)` }">
        <div v-for="(member, index) in team" 
             :key="index"
             class="w-1/2 h-full flex-shrink-0 px-2">
          <div class="relative h-full group transform transition-all duration-500 hover:scale-105">
            <img :src="member.image"
                 :alt="member.name"
                 class="w-full h-full object-cover rounded-xl"/>
            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-[#31572C] p-8 rounded-b-xl">
              <h3 class="text-2xl font-serif text-white mb-2">{{ member.name }}</h3>
              <p class="text-[#ECF39E]">{{ member.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Controles -->
    <button @click="prevSlide" 
            class="absolute left-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 transition-all duration-200">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
    </button>
    <button @click="nextSlide"
            class="absolute right-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 transition-all duration-200">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </button>

    <!-- Indicadores actualizados -->
    <div class="absolute bottom-20 left-1/2 -translate-x-1/2 flex space-x-2">
      <button v-for="(_, index) in indicators"
              :key="index"
              @click="currentSlide = index"
              :class="[
                'w-2 h-2 rounded-full transition-all duration-300',
                currentSlide === index ? 'bg-white w-4' : 'bg-white/50'
              ]">
      </button>
    </div>
  </div>
</template>