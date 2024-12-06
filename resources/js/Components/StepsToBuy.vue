<script setup>
import { ref, onMounted } from 'vue';

const isParticipateVisible = ref(false);

onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          isParticipateVisible.value = true;
        }
      });
    },
    { threshold: 0.1 }
  );

  const section = document.querySelector('.participate-section');
  if (section) observer.observe(section);
});
</script>

<template>
    <!-- Pasos para comprar -->
  <div class="w-full py-8 relative overflow-hidden bg-gradient-to-br from-white/80 to-[#ECF39E]/20 group hover:from-gray-800/5 hover:to-[#31572C]/10 transition-all duration-700 backdrop-blur-md participate-section"
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
      <div class="absolute inset-0 w-full bg-gradient-to-br from-transparent to-transparent
              group-hover:from-gray-900/10
              group-hover:to-transparent
              transition-all duration-700"></div>

      <!-- Contenido -->
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
</template>