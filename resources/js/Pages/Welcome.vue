<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Logo from '@/Components/LogoText.vue';
import Carousel from '@/Components/Carousel.vue';
import backgroundImage from '@/assets/images/background.png';

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});

function handleImageError() {
  document.getElementById("screenshot-container")?.classList.add("!hidden");
  document.getElementById("docs-card")?.classList.add("!row-span-1");
  document.getElementById("docs-card-content")?.classList.add("!flex-row");
  document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
  <Head title="Welcome" />
  <div class="min-h-screen bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955] relative">
    <!-- Nav -->
    <nav class="sticky top-0 z-50 bg-white/30 backdrop-blur-md border-b border-[#4F772D]/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <a class="py-3" href="/"><Logo colorClass="text-[#4F772D]" textColorClass="text-[#31572C]" /></a>
          <div class="flex items-center space-x-4">
            <a href="/login" class="px-4 py-2 text-[#31572C] hover:text-[#132A13] font-montserrat transition-colors duration-200">
              Iniciar Sesión
            </a>
            <a href="/register" class="px-6 py-3 bg-[#4F772D] hover:bg-[#31572C] text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 font-montserrat">
              Registrarse
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!--gradiente verde -->
    <div class="relative overflow-hidden bg-gradient-to-br from-[#31572C] to-[#132A13] py-12 sm:py-16 lg:py-24">
      <!-- Imagen de fondo con overlay -->
      <div
        class="absolute inset-0 mix-blend-overlay opacity-10"
        :style="{
          backgroundImage: `url(${backgroundImage})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          backgroundRepeat: 'no-repeat'
        }"
      ></div>

      <!-- Contenido existente -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="text-center lg:text-left">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-8">
              Participa y Gana<br/>
              <span class="text-emerald-400">Increíbles Premios</span>
            </h1>
            <p class="text-lg text-emerald-100 mb-12 max-w-xl mx-auto lg:mx-0">
              Únete a nuestra comunidad de ganadores. Compra tus boletos y sé parte de las rifas más emocionantes.
            </p>
            <a href="/register" class="inline-flex items-center px-8 py-4 bg-white text-emerald-900 rounded-xl font-bold text-lg hover:bg-emerald-50 transform hover:scale-105 transition-all duration-200 shadow-xl">
              Comenzar Ahora
              <svg class="ml-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </a>
          </div>

          <!-- Carrusel de Rifas -->
          <div class="relative">
            <Carousel />
          </div>
        </div>
      </div>
    </div>

    <!-- Sección con fondo suave -->
    <div class="py-12 sm:py-16 lg:py-24 bg-[#ECF39E]/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-serif text-center mb-16 text-emerald-900">
          ¿Cómo Participar?
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
          <!-- Paso 1 -->
          <div class="group">
            <div
              class="flex flex-col items-center transform transition-all duration-500 hover:scale-105"
            >
              <div
                class="bg-gradient-to-br from-rose-100 to-rose-200 rounded-2xl p-6 mb-6 shadow-xl group-hover:shadow-2xl transition-all duration-500"
              >
                <svg
                  class="h-12 w-12 text-amber-700"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="{2}"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
              </div>
              <h3 class="text-2xl font-serif mb-4 text-emerald-900">
                1. Regístrate
              </h3>
              <p class="text-center text-emerald-700 px-6">
                Crea tu cuenta como organizador para crear rifas emocionantes o
                como participante para comprar boletos.
              </p>
            </div>
          </div>

          <!-- Paso 2 -->
          <div class="group">
            <div
              class="flex flex-col items-center transform transition-all duration-500 hover:scale-105"
            >
              <div
                class="bg-gradient-to-br from-rose-100 to-rose-200 rounded-2xl p-6 mb-6 shadow-xl group-hover:shadow-2xl transition-all duration-500"
              >
                <svg
                  class="h-12 w-12 text-amber-700"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="{2}"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                  />
                </svg>
              </div>
              <h3 class="text-2xl font-serif mb-4 text-emerald-900">
                2. Selecciona
              </h3>
              <p class="text-center text-emerald-700 px-6">
                Explora las rifas disponibles y elige tus números favoritos.
                ¡Cada número es una oportunidad de ganar!
              </p>
            </div>
          </div>

          <!-- Paso 3 -->
          <div class="group">
            <div
              class="flex flex-col items-center transform transition-all duration-500 hover:scale-105"
            >
              <div
                class="bg-gradient-to-br from-rose-100 to-rose-200 rounded-2xl p-6 mb-6 shadow-xl group-hover:shadow-2xl transition-all duration-500"
              >
                <svg
                  class="h-12 w-12 text-amber-700"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="{2}"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <h3 class="text-2xl font-serif mb-4 text-emerald-900">
                3. Espera
              </h3>
              <p class="text-center text-emerald-700 px-6">
                Aguarda al día del sorteo y sigue los resultados en tiempo real.
                ¡La suerte puede estar de tu lado!
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Cards con bordes verdes -->
    <div class="rounded-2xl border-2 border-[#4F772D]/20 bg-white">
      <div class="py-12 sm:py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="text-4xl font-serif text-center mb-16 text-emerald-900">
            Nuestro Equipo
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-12">
            <!-- Creador 1 -->
            <div class="group">
              <div
                class="relative overflow-hidden rounded-2xl shadow-xl transition-all duration-500 transform hover:scale-105"
              >
                <img
                  src="/path/to/creator1.jpg"
                  alt="Creador 1"
                  class="w-full h-96 object-cover"
                />
                <div
                  class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-emerald-900 p-6"
                >
                  <h3 class="text-2xl font-serif text-white mb-2">Davi</h3>
                  <p class="text-emerald-100">Desarrollador Full Stack</p>
                </div>
              </div>
            </div>

            <!-- Creador 2 -->
            <div class="group">
              <div
                class="relative overflow-hidden rounded-2xl shadow-xl transition-all duration-500 transform hover:scale-105"
              >
                <img
                  src="/path/to/creator2.jpg"
                  alt="Creador 2"
                  class="w-full h-96 object-cover"
                />
                <div
                  class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-emerald-900 p-6"
                >
                  <h3 class="text-2xl font-serif text-white mb-2">Sebastian</h3>
                  <p class="text-emerald-100">Diseñador UX/UI</p>
                </div>
              </div>
            </div>

            <!-- Creador 3 -->
            <div class="group">
              <div
                class="relative overflow-hidden rounded-2xl shadow-xl transition-all duration-500 transform hover:scale-105"
              >
                <img
                  src="/path/to/creator3.jpg"
                  alt="Creador 3"
                  class="w-full h-96 object-cover"
                />
                <div
                  class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-emerald-900 p-6"
                >
                  <h3 class="text-2xl font-serif text-white mb-2">Nicolas</h3>
                  <p class="text-emerald-100">Desarrollador Backend</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-b from-[#132A13] to-[#31572C] text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
        <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
          <a
            href="/"
            class="hover:text-amber-300 transition-colors duration-300 flex items-center"
          >
            <svg
              class="h-5 w-5 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="{2}"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Términos y Condiciones
          </a>
          <a
            href="/"
            class="hover:text-amber-300 transition-colors duration-300 flex items-center"
          >
            <svg
              class="h-5 w-5 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="{2}"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"
              />
            </svg>
            Contáctanos
          </a>
          <a
            href="/"
            class="hover:text-amber-300 transition-colors duration-300 flex items-center"
          >
            <svg
              class="h-5 w-5 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="{2}"
                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Preguntas Frecuentes
          </a>
          <a
            href="https://github.com/Crudzaso/la_rifa_mas_montanera"
            target="_blank"
            class="hover:text-amber-300 transition-colors duration-300 flex items-center"
          >
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-.1.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23"
              />
            </svg>
            GitHub
          </a>
        </div>
      </div>
      <div class="py-0 text-center text-sm text-black dark:text-white/70">
        Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
      </div>
    </footer>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

.font-montserrat {
  font-family: 'Montserrat', sans-serif;
}

@media (max-width: 425px) {
  .container {
    width: 100% !important;
    min-width: 100% !important;
    overflow-x: hidden;
  }
  .text-4xl {
    font-size: 2rem;
    line-height: 1.2;
  }
  .py-24 {
    padding-top: 3rem;
    padding-bottom: 3rem;
  }
  .grid {
    margin: 0;
  }
}

@media (max-width: 375px) {
  .text-4xl {
    font-size: 1.75rem;
  }
}

@media (max-width: 320px) {
  .px-4 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }
}
</style>
