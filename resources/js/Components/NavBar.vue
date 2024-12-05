<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import Logo from "@/Components/LogoNavBar.vue";
import NavLink from "@/Components/NavLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

const navLinks = [
  {
    name: "Rifas",
    route: "raffles.index",
    submenu: [
      { name: "Ver Rifas", route: "raffles.index" },
      { name: "Crear Rifa", route: "raffles.create" },
    ],
  },

  {
    name: "Resultado loterias",
    route: "dashboard",
  },
  {
    name: "Boletos",
    route: "tickets.index",
  },
  {
    name: "Usuarios",
    route: "users.index",
    submenu: [
      { name: "Ver Usuarios", route: "users.index" },
      { name: "Crear Usuario", route: "users.create" },
    ],
  },    
  
];

defineProps({
  auth: {
    type: Object,
    required: true,
  },
});

const getInitials = (fullName) => {
  const names = fullName.split(" ");
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase();
  }
  return names[0][0].toUpperCase();
};
</script>

<template>
  <nav
    class="sticky top-0 z-50 bg-gradient-to-r from-[#4F772D]/5 to-[#ECF39E]/20 backdrop-blur-md border-b border-[#4F772D]/20 shadow-lg"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
      <div class="flex justify-between h-28">
        <!-- Logo con animación -->

        <div class="flex">
          <Link :href="route('dashboard')">
            <Logo
              colorClass="text-[#4F772D]"
              textColorClass="text-[#31572C]"
              class="py-4"
            />
          </Link>

          <!-- Links con submenús -->
          <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
            <div
              v-for="(link, index) in navLinks"
              :key="index"
              class="relative group"
            >
              <NavLink
                :href="route(link.route)"
                :active="route().current(link.route)"
                class="relative font-montserrat px-3 py-2"
              >
                <span class="relative z-10 flex items-center">
                  {{ link.name }}
                  <svg
                    v-if="link.submenu"
                    class="ml-1 h-4 w-4 transform group-hover:rotate-180 transition-transform duration-300"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </span>
              </NavLink>

              <!-- Submenú -->
              <div
                v-if="link.submenu"
                class="absolute left-0 mt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left"
              >
                <div
                  class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl border border-[#4F772D]/20 overflow-hidden py-1"
                >
                  <Link
                    v-for="(subItem, subIndex) in link.submenu"
                    :key="subIndex"
                    :href="route(subItem.route)"
                    class="block px-4 py-2 text-sm text-[#31572C] hover:bg-[#ECF39E]/20 transition-colors duration-200"
                  >
                    {{ subItem.name }}
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones Auth mejorados -->
        <div class="flex items-center space-x-4">
          <template v-if="!auth.user">
            <Link
              :href="route('login')"
              class="relative overflow-hidden px-4 py-2 text-[#31572C] font-montserrat group"
            >
              <span class="relative z-10">Iniciar Sesión</span>
              <div
                class="absolute inset-0 bg-[#4F772D]/10 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"
              />
            </Link>
            <Link
              :href="route('register')"
              class="relative overflow-hidden px-6 py-3 bg-gradient-to-r from-[#4F772D] to-[#31572C] text-white rounded-xl shadow-lg group transform hover:scale-105 transition-all duration-300 font-montserrat"
            >
              <span class="relative z-10">Registrarse</span>
              <div
                class="absolute inset-0 bg-white/20 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"
              />
            </Link>
          </template>

          <template v-else>
            <Dropdown align="right" width="48">
              <template #trigger>
                <button
                  type="button"
                  class="group inline-flex items-center gap-3 px-4 py-2 font-montserrat text-[#31572C] rounded-lg hover:bg-[#4F772D]/10 transition-all duration-300"
                >
                  <!-- Círculo con inicial -->
                  <div
                    class="relative w-10 h-10 rounded-full bg-gradient-to-br from-[#4F772D] to-[#90A955] flex items-center justify-center transform group-hover:scale-105 transition-all duration-300 shadow-lg"
                  >
                    <span
                      class="text-white font-montserrat font-semibold text-lg"
                    >
                      {{ getInitials(auth.user.name) }}
                    </span>
                    <!-- Overlay hover -->
                    <div
                      class="absolute inset-0 rounded-full bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    ></div>
                  </div>

                  <div class="flex items-center">
                    <span class="font-medium">{{ auth.user.name }}</span>
                    <svg
                      class="ms-2 h-5 w-5 transform group-hover:rotate-180 transition-transform duration-300"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                      />
                    </svg>
                  </div>
                </button>
              </template>

              <template #content>
                <div
                  class="bg-white/90 backdrop-blur-md rounded-xl shadow-xl border border-[#4F772D]/20 overflow-hidden"
                >
                  <DropdownLink
                    :href="route('profile.show')"
                    class="block px-4 py-3 text-[#31572C] hover:bg-[#ECF39E]/20 transition-colors duration-200"
                  >
                    Perfil
                  </DropdownLink>
                  <form @submit.prevent="$inertia.post(route('logout'))">
                    <DropdownLink
                      as="button"
                      class="w-full text-left px-4 py-3 text-[#31572C] hover:bg-[#ECF39E]/20 transition-colors duration-200"
                    >
                      Cerrar Sesion
                    </DropdownLink>
                  </form>
                </div>
              </template>
            </Dropdown>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.group:hover .group-hover\:rotate-180 {
  transform: rotate(180deg);
}

.group:hover .group-hover\:opacity-100 {
  opacity: 1;
  visibility: visible;
}
</style>
