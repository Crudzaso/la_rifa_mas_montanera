<template>
  <footer class="relative mt-auto overflow-hidden">
    <!-- Fondo con efecto montaña/hierba -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#4F772D]/80 via-[#31572C]/90 to-[#1B2A1B]">
      <!-- Efecto montaña -->
      <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#1B2A1B]/80"
           style="clip-path: polygon(0 30%, 20% 0, 50% 30%, 80% 0, 100% 30%, 100% 100%, 0 100%);">
      </div>
    </div>

    <!-- Contenido más compacto -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex flex-col md:flex-row justify-center items-center gap-8 min-h-[200px]">
        <!-- Sección de ubicación -->
        <div class="backdrop-blur-sm bg-white/5 rounded-xl p-4 border border-[#ECF39E]/10 w-full md:w-1/3">
          <h3 class="text-[#ECF39E] text-sm font-semibold mb-2 flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            Ubicación
          </h3>
          <div class="h-24 rounded-lg overflow-hidden mb-2">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.338260464583!2d-75.5858035878229!3d6.219047665287035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e44293f0e114eef%3A0x99610cdd44c7c081!2sRiwi!5e0!3m2!1ses-419!2sco!4v1733186283093!5m2!1ses-419!2sco"
              class="w-full h-full"
              style="border:0;"
              :allowfullscreen="true"
              loading="lazy"
            ></iframe>
          </div>
          <p class="text-xs text-[#ECF39E]/80">Calle 51 #45-93, Medellín</p>
        </div>

        <!-- Enlaces rápidos -->
        <div class="backdrop-blur-sm bg-white/5 rounded-xl p-4 border border-[#ECF39E]/10 w-full md:w-1/3">
          <h3 class="text-[#ECF39E] text-sm font-semibold mb-2 flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Enlaces Útiles
          </h3>
          <div class="flex items-center gap-4">
            <a href="/" class="text-xs text-white/80 hover:text-[#ECF39E] transition-colors duration-300">Legal</a>
            <span class="text-white/20">|</span>
            <a href="/" class="text-xs text-white/80 hover:text-[#ECF39E] transition-colors duration-300">Ayuda</a>
          </div>
        </div>

        <!-- Social -->
        <div class="backdrop-blur-sm bg-white/5 rounded-xl p-4 border border-[#ECF39E]/10 w-full md:w-1/3">
          <h3 class="text-[#ECF39E] text-sm font-semibold mb-2 flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            Síguenos
          </h3>
          <div class="flex items-center gap-4">
            <a href="https://github.com/Crudzaso/la_rifa_mas_montanera"
               target="_blank"
               class="text-white/80 hover:text-[#ECF39E] transition-all duration-300 transform hover:scale-110">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
/* Añadir efecto de movimiento suave a la hierba */
@keyframes grassWave {
  0% { transform: skewX(0deg); }
  50% { transform: skewX(-2deg); }
  100% { transform: skewX(0deg); }
}

.grass-pattern {
  animation: grassWave 8s ease-in-out infinite;
}
</style>
