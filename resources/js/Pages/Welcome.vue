<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import Logo from "@/Components/LogoText.vue";
import Carousel from "@/Components/Carousel.vue";
import backgroundImage from "@/assets/images/background.png";
import Footer from "@/Components/Footer.vue";
import CarouselRaffles from "@/Components/CarouselRaffles.vue";
import CarouselTeam from "@/Components/CarouselTeam.vue";

const page = usePage();

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
  raffles: {
    type: Array,
    default: () => [],
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
  <Head title="Bienvenido" />
  <div class="min-h-screen bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955] relative">
    <!-- Nav con nuevo estilo -->
    <nav class="sticky top-0 z-50">
      <!-- Fondo con montañas -->
      <div class="absolute inset-0 bg-gradient w-full">
        <div class="mountain-bg"></div>
      </div>

      <!-- Contenido del nav -->
      <div class="relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
          <div class="flex justify-between h-28">
            <!-- Logo -->
            <div class="flex items-center">
              <a class="py-3" href="/">
                <Logo colorClass="text-[#4F772D]" textColorClass="text-[#31572C]" />
              </a>
            </div>

            <!-- Botones de autenticación -->
            <div class="flex items-center space-x-4">
              <template v-if="!$page.props.auth.user">
                <a href="/login"
                   class="px-4 py-2 rounded-lg text-[#31572C] hover:bg-[#4F772D]/10 transition-all duration-200">
                  Iniciar Sesión
                </a>
                <a href="/register"
                   class="px-6 py-3 bg-[#4F772D] hover:bg-[#31572C] text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                  Registrarse
                </a>
              </template>
              <template v-else>
                <a href="/rifas"
                   class="px-6 py-3 bg-[#4F772D] hover:bg-[#31572C] text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                  Ir a la página principal
                </a>
              </template>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!--gradiente verde -->
    <div
      class="relative overflow-hidden bg-gradient-to-br from-[#101d0ebe] to-[#132a13d8] py-12 sm:py-16 lg:py-24"
    >
      <!-- Imagen de fondo con overlay -->
      <div
        class="absolute inset-0 mix-blend-overlay opacity-10"
        :style="{
          backgroundImage: `url(${backgroundImage})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          backgroundRepeat: 'no-repeat',
        }"
      ></div>

      <!-- Contenido existente -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="text-center lg:text-left">
            <h1
              class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-8"
            >
              Participa y Gana<br />
              <span class="text-emerald-400">Increíbles Premios</span>
            </h1>
            <p class="text-lg text-emerald-100 mb-12 max-w-xl mx-auto lg:mx-0">
              Únete a nuestra comunidad de ganadores. Compra tus boletos y sé
              parte de las rifas más emocionantes.
            </p>
            <a
              href="/register"
              class="inline-flex items-center px-8 py-4 bg-white text-emerald-900 rounded-xl font-bold text-lg hover:bg-emerald-50 transform hover:scale-105 transition-all duration-200 shadow-xl"
            >
              Comenzar Ahora
              <svg
                class="ml-2 h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7l5 5m0 0l-5 5m5-5H6"
                />
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

    <div class="py-12 sm:py-16 lg:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-serif text-center mb-16 text-[#31572C]">
          Rifas Disponibles
        </h2>
        <CarouselRaffles :raffles="raffles" />
      </div>
    </div>

    <!-- Sección de Equipo -->
    <div class="py-12 sm:py-16 lg:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-serif text-center mb-16 text-[#31572C]">
          Nuestro Equipo
        </h2>
        <CarouselTeam />
      </div>
    </div>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<style scoped>
/* Nuevos estilos del navbar */
.bg-gradient {
  background: linear-gradient(to bottom right, rgba(237, 245, 147, 0.8), rgba(144, 169, 85, 0.6));
  backdrop-filter: blur(8px);
  box-shadow: 0 4px 16px 10px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.mountain-bg::before,
.mountain-bg::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 10;
}

.mountain-bg::before {
  height: 60%;
  background: linear-gradient(165deg,
    rgba(79, 119, 45, 0.2) 0%,
    rgba(49, 87, 44, 0.1) 50%,
    rgba(144, 169, 85, 0.15) 100%
  );
  clip-path: polygon(
    0 100%,
    15% 65%,
    30% 85%,
    50% 55%,
    70% 80%,
    85% 60%,
    100% 85%,
    100% 100%
  );
}

.mountain-bg::after {
  height: 65%;
  background: linear-gradient(165deg,
    rgba(79, 119, 45, 0.15) 0%,
    rgba(49, 87, 44, 0.08) 50%,
    rgba(144, 169, 85, 0.1) 100%
  );
  clip-path: polygon(
    0 100%,
    20% 10%,
    40% 90%,
    60% 20%,
    80% 55%,
    100% 30%,
    100% 100%
  );
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
