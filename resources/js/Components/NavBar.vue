<script setup>
import { ref, computed, inject } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import Logo from "@/Components/LogoNavBar.vue";
import NavLink from "@/Components/NavLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

// Inyectar función hasRole desde AppLayout
const hasRole = inject('hasRole');

const navLinks = computed(() => {
  const links = [
    {
      name: "Rifas",
      route: "raffles.index",
      // Submenu solo para admin y organizador
      submenu: hasRole(['admin', 'organizador']) ? [
        { name: "Ver Rifas", route: "raffles.index" },
        { name: "Crear Rifa", route: "raffles.create" },
      ] : null,
    },
    {
      name: "Resultado loterias",
      route: "lottery",
    },
    {
      name: "Boletos",
      route: "tickets.index",
    }
  ];

  // Solo agregar el menú de usuarios si es admin
  if (hasRole('admin')) {
    links.push({
      name: "Usuarios",
      route: "users.index",
      submenu: [
        { name: "Ver Usuarios", route: "users.index" },
        { name: "Crear Usuario", route: "users.create" },
      ],
    });
  }

  return links;
});

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

// Función para logout
const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <nav class="sticky top-0 z-50 shadow-lg">
    <!-- Fondo con montañas -->
    <div class="absolute inset-0 bg-gradient w-full">
      <div class="mountain-bg"></div>
    </div>

    <!-- Contenido del nav -->
    <div class="relative z-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex justify-between h-28">
          <!-- Logo y Links principales -->
          <div class="flex items-center space-x-6">
            <!-- Logo -->
            <Link :href="route('raffles.index')">
              <Logo
                colorClass="text-[#4F772D]"
                textColorClass="text-[#31572C]"
                class="py-4"
              />
            </Link>

            <!-- Links de navegación -->
            <div class="flex items-center space-x-4">
              <template v-for="link in navLinks" :key="link.route">
                <!-- Link con submenú -->
                <div v-if="link.submenu" class="relative group">
                  <Link :href="route(link.route)"
                        class="flex items-center space-x-1 px-4 py-2 rounded-lg text-[#31572C] hover:bg-[#4F772D]/10 transition-all duration-200">
                    <span>{{ link.name }}</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </Link>
                  <div class="absolute left-0 mt-2 w-48 rounded-xl bg-white/80 backdrop-blur-sm shadow-lg border border-[#4F772D]/20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <div class="py-1">
                      <Link v-for="sublink in link.submenu" :key="sublink.route"
                            :href="route(sublink.route)"
                            class="block px-4 py-2 text-sm text-[#31572C] hover:bg-[#4F772D]/10">
                        {{ sublink.name }}
                      </Link>
                    </div>
                  </div>
                </div>
                <!-- Link sin submenú -->
                <NavLink v-else
                        :href="route(link.route)"
                        :active="route().current(link.route)"
                        class="px-4 py-2 rounded-lg text-[#31572C] hover:bg-[#4F772D]/10">
                  {{ link.name }}
                </NavLink>
              </template>
            </div>
          </div>

          <!-- Dropdown de usuario con nombre e iniciales -->
          <div class="flex items-center">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button class="flex items-center space-x-2 text-sm font-medium text-[#31572C] hover:bg-[#4F772D]/10 transition-all duration-200 rounded-lg px-4 py-2">
                  <div class="w-8 h-8 rounded-full bg-[#4F772D] flex items-center justify-center text-white font-semibold">
                    {{ getInitials(auth.user.name) }}
                  </div>
                  <span class="ml-2">{{ auth.user.name }}</span>
                  <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </template>

              <template #content>
                <DropdownLink :href="route('profile.show')">
                  Perfil
                </DropdownLink>
                <form @submit.prevent="logout">
                  <DropdownLink as="button">
                    Cerrar Sesión
                  </DropdownLink>
                </form>
              </template>
            </Dropdown>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>
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
  .max-w-7xl {
    padding-left: 1rem;
    padding-right: 1rem;
  }

  header {
    padding: 0.75rem;
  }

  .grid {
    grid-template-columns: 1fr !important;
    gap: 1rem !important;
  }

  .flex-col {
    padding: 0.5rem;
  }


  .text-2xl {
    font-size: 1.25rem;
  }

  .text-xl {
    font-size: 1.125rem;
  }

  .p-6 {
    padding: 1rem;
  }

  .gap-8 {
    gap: 1rem;
  }
}

@media (max-width: 375px) {
  .max-w-7xl {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }

  .p-4 {
    padding: 0.75rem;
  }

  .px-6 {
    padding-left: 1rem;
    padding-right: 1rem;
  }
}

@media (max-width: 320px) {
  .max-w-7xl {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
  }

  .p-4 {
    padding: 0.5rem;
  }

  .text-sm {
    font-size: 0.75rem;
  }

  .h-4 {
    height: 0.875rem;
  }

  .w-4 {
    width: 0.875rem;
  }
}
</style>
