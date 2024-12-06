<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import { router, usePage } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted } from "vue";
import Alert from "@/Components/Alert.vue";
import { usePermissions } from "@/Composables/usePermissions";
import StepsToBuy from "@/Components/StepsToBuy.vue";
import SearchFilters from "@/Components/SearchFilters.vue";
import RafflesList from "@/Components/RafflesList.vue";

const { hasRole } = usePermissions();

const page = usePage();
const raffles = page.props.raffles;
const activeTab = ref("ongoing");
const showMessage = ref(false);
const message = ref("");

// Estados disponibles
const tabStates = ["ongoing", "pending", "finished"];

// Formatear fechas
const formatDate = (date) => {
  if (!date) return "Sin fecha";
  const fecha = new Date(date);
  fecha.setMinutes(fecha.getMinutes() + fecha.getTimezoneOffset());

  return fecha.toLocaleDateString("es-ES", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  });
};

// Calcular estado
const calculateStatus = (raffle) => {
  const now = new Date();
  const startDate = new Date(raffle.start_date);
  const endDate = new Date(raffle.end_date);

  if (now < startDate) {
    return "pending";
  } else if (now > endDate) {
    return "finished";
  } else {
    return "ongoing";
  }
};

const searchQuery = ref("");
const searchType = ref("title");

// Modificar filteredRaffles para incluir búsqueda y ordenamiento
const filteredRaffles = computed(() => {
  if (!raffles) return [];

  return raffles
    .filter((raffle) => {
      const matchesStatus = raffle.status === activeTab.value;
      const searchText = searchQuery.value.toLowerCase();

      const matchesSearch =
        searchType.value === "title"
          ? raffle.title.toLowerCase().includes(searchText)
          : raffle.prize.toLowerCase().includes(searchText);

      return matchesStatus && matchesSearch;
    })
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Actualizar vista al cambiar estado
watch(
  () => page.props.raffles,
  (newRaffles) => {
    if (newRaffles) {
      router.get(
        route("raffles.index"),
        {},
        {
          preserveState: true,
          preserveScroll: true,
          only: ["raffles"],
        }
      );
    }
  }
);

// Observar cambios en las fechas
watch(
  raffles,
  () => {
    raffles?.forEach((raffle) => {
      const newStatus = calculateStatus(raffle);
      if (raffle.status !== newStatus) {
        router.put(route("raffles.update", raffle.id), {
          ...raffle,
          status: newStatus,
        });
      }
    });
  },
  { deep: true }
);

// Traducción de estados
const getStatusTranslation = (status) => {
  const translations = {
    ongoing: "En Juego",
    pending: "Pendiente",
    finished: "Finalizado",
  };
  return translations[status] || status;
};

// Colores según estado
const getStatusColor = (status) => {
  const colors = {
    ongoing: "bg-green-100 text-green-800",
    pending: "bg-yellow-100 text-yellow-800",
    finished: "bg-red-100 text-red-800",
  };
  return colors[status] || "bg-gray-100 text-gray-800";
};

// Función para cambiar pestaña
const changeTab = (tab) => {
  activeTab.value = tab;
};

// Añadir ref para controlar animaciones
const isParticipateVisible = ref(false);
const isRafflesVisible = ref(false);

onMounted(() => {
  // Configurar Intersection Observer
  const observerOptions = {
    threshold: 0.1,
  };

  const participateObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        isParticipateVisible.value = true;
      }
    });
  }, observerOptions);

  const rafflesObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        isRafflesVisible.value = true;
      }
    });
  }, observerOptions);

  // Observar elementos
  const participateSection = document.querySelector(".participate-section");
  const rafflesSection = document.querySelector(".raffles-section");

  if (participateSection) participateObserver.observe(participateSection);
  if (rafflesSection) rafflesObserver.observe(rafflesSection);
});
</script>

<template>
  <AppLayout title="Rifas">
    <Alert v-if="showMessage" :message="message" :type="'Éxito'" />

    <template #header>
      <div class="flex justify-between items-center">
        <h2
          class="font-semibold text-2xl text-[#31572C] leading-tight flex items-center gap-3"
        >
          <img
            src="@/assets/images/background.png"
            alt="Logo"
            class="w-8 h-8 object-contain"
          />
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
            />
          </svg>
          Rifas más Montañeras
        </h2>
        <Link
          v-if="hasRole(['admin', 'organizador'])"
          :href="route('raffles.create')"
          class="inline-flex items-center px-6 py-3 bg-white/90 text-[#31572C] rounded-xl font-bold text-sm hover:bg-[#ECF39E] transform hover:scale-105 transition-all duration-200 shadow-xl"
        >
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            />
          </svg>
          Crear Rifa
        </Link>
      </div>
    </template>

    <StepsToBuy />

    <SearchFilters
      :active-tab="activeTab"
      :tab-states="tabStates"
      :get-status-translation="getStatusTranslation"
      @update:search-query="searchQuery = $event"
      @update:search-type="searchType = $event"
      @tab-change="changeTab"
    />

    <RafflesList
      :raffles="filteredRaffles"
      :format-date="formatDate"
      :get-status-translation="getStatusTranslation"
      :get-status-color="getStatusColor"
      :active-tab="activeTab"
    />
  </AppLayout>
</template>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.is-visible > * {
  animation: fadeIn 0.5s ease forwards;
  animation-delay: calc(var(--index) * 0.1s);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.raffle-list-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.raffle-list-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}

.raffle-list-move {
  transition: all 0.5s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

