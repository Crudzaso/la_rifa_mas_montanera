<!-- Carousel.vue -->
<script setup>
import { ref, onMounted } from 'vue';

const currentSlide = ref(0);
const rifas = [
  {
    title: "iPhone 15 Pro Max",
    price: "$10,000",
    image: "https://th.bing.com/th/id/OIP.CC9n2P2JLje4xapsAGSExAHaEK?rs=1&pid=ImgDetMain",
    endDate: "30 Diciembre 2024"
  },
  {
    title: "PlayStation 5",
    price: "$20,000",
    image: "https://cdn.pocket-lint.com/r/s/1200x630/assets/images/143354-games-feature-sony-playstation-5-release-date-rumours-and-everything-you-need-to-know-about-ps5-image1-cvz3adase9.jpg",
    endDate: "15 Enero 2025"
  },
  {
    title: "MacBook Pro",
    price: "$50,000",
    image: "https://th.bing.com/th/id/R.5371ed482428103757f36f11202383ad?rik=BK5Bt43q3oZrNw&riu=http%3a%2f%2fksassets.timeincuk.net%2fwp%2fuploads%2fsites%2f54%2f2016%2f12%2fmacbook-pro-13-2022-1.jpg&ehk=2EGvNZ1VpKKHBW9QZ%2faoJfy3tTXSDvY8F4hEuSbxx2I%3d&risl=&pid=ImgRaw&r=0",
    endDate: "1 Enero 2025"
  }
];

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % rifas.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + rifas.length) % rifas.length;
};

onMounted(() => {
  setInterval(nextSlide, 5000);
});
</script>

<template>
  <div class="relative w-full h-[500px] rounded-2xl overflow-hidden shadow-2xl">
    <!-- Slides -->
    <div class="relative h-full">
      <transition-group name="fade">
        <div
          v-for="(rifa, index) in rifas"
          :key="index"
          v-show="currentSlide === index"
          class="absolute inset-0"
        >
          <div class="relative h-full">
            <!-- Imagen de fondo con overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#132A13] via-transparent">
              <img
                :src="rifa.image"
                :alt="rifa.title"
                class="w-full h-full object-cover opacity-75"
              />
            </div>

            <!-- Contenido -->
            <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
              <h3 class="text-3xl font-bold mb-2">{{ rifa.title }}</h3>
              <div class="flex justify-between items-center">
                <span class="text-2xl font-semibold text-[#ECF39E]">{{ rifa.price }}</span>
                <span class="text-sm bg-[#4F772D] px-4 py-2 rounded-full">
                  Termina: {{ rifa.endDate }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </transition-group>
    </div>

    <!-- Controles -->
    <button
      @click="prevSlide"
      class="absolute left-4 top-1/2 -translate-y-1/2 bg-[#31572C]/80 hover:bg-[#31572C] p-2 rounded-full"
    >
      <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>

    <button
      @click="nextSlide"
      class="absolute right-4 top-1/2 -translate-y-1/2 bg-[#31572C]/80 hover:bg-[#31572C] p-2 rounded-full"
    >
      <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>

    <!-- Indicadores -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
      <button
        v-for="(_, index) in rifas"
        :key="index"
        @click="currentSlide = index"
        :class="[
          'w-2 h-2 rounded-full transition-all',
          currentSlide === index ? 'bg-[#ECF39E] w-4' : 'bg-white/50'
        ]"
      />
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
