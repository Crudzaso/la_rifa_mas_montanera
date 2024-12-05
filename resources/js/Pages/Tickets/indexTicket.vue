<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import confetti from "canvas-confetti";

dayjs.extend(utc);

const page = usePage();
const tickets = computed(() => page.props.tickets || []);
const searchQuery = ref("");
const confettiShown = ref(new Set());

const getStatusTranslation = (status) => {
  const translations = {
    'ongoing': 'En juego',
    'pending': 'Pendiente',
    'finished': 'Finalizado',
    'winner': 'Ganador'
  };
  return translations[status] || status;
};

const getStatusText = (group) => {
  const status = getStatus(group.raffle, group.tickets);
  if (status.type === 'winner') return '¡Ganador!';
  return getStatusTranslation(status.type);
};

const groupedTickets = computed(() => {
  return Object.values(
    tickets.value
      .filter((ticket) =>
        ticket.raffle?.title
          .toLowerCase()
          .includes(searchQuery.value.toLowerCase())
      )
      .reduce((groups, ticket) => {
        const raffleId = ticket.raffle?.id;
        if (!groups[raffleId]) {
          groups[raffleId] = { raffle: ticket.raffle, tickets: [] };
        }
        groups[raffleId].tickets.push(ticket);
        return groups;
      }, {})
  ).sort(
    (a, b) =>
      dayjs(b.raffle.end_date).valueOf() - dayjs(a.raffle.end_date).valueOf()
  );
});

const getStatus = (raffle, tickets) => {
  const today = dayjs().utc().startOf("day");
  const endDate = dayjs(raffle.end_date).utc().startOf("day");

  if (tickets.some((ticket) => ticket.is_winner)) {
    return {
      text: "¡Ganador!",
      class: "bg-yellow-400 text-yellow-900 animate-bounce shadow-lg",
      type: "winner",
    };
  }

  if (today.isSame(endDate))
    return {
      text: "En Juego",
      class: "bg-green-100 text-green-800",
      type: "ongoing",
    };

  if (today.isAfter(endDate))
    return {
      text: "Terminada",
      class: "bg-red-100 text-red-800",
      type: "finished",
    };

  return {
    text: "Pendiente",
    class: "bg-yellow-100 text-yellow-800",
    type: "pending",
  };
};

const shootConfetti = () => {
  confetti({
    particleCount: 150,
    spread: 80,
    origin: { y: 0.6 },
    colors: ['#FFD700', '#FFA500', '#FF6347'],
    gravity: 1.2,
    scalar: 1.2,
    ticks: 100
  });
};

const formatDate = (date) => dayjs(date).utc().format("DD/MM/YYYY");

const activeFilter = ref('ongoing');
const statusOptions = ['pending', 'ongoing', 'winner', 'finished'];

const filteredTickets = computed(() => {
  return groupedTickets.value.filter(group => {
    const status = getStatus(group.raffle, group.tickets);
    if (activeFilter.value === 'winner') {
      return group.tickets.some(ticket => ticket.is_winner);
    }
    return status.type === activeFilter.value;
  });
});

const getStatusClass = (group) => {
  const status = getStatus(group.raffle, group.tickets);
  return {
    'px-4 py-1.5 rounded-full text-sm font-semibold shadow-md': true,
    'bg-emerald-500 text-white': status.type === 'ongoing',
    'bg-amber-400 text-white': status.type === 'winner',
    'bg-slate-600 text-white': status.type === 'finished',
    'bg-[#90A955] text-white': status.type === 'pending'
  };
};

// Observador para cuando cambia el filtro
watch(activeFilter, (newFilter) => {
  if (newFilter === 'winner' && filteredTickets.value.length > 0) {
    setTimeout(() => {
      shootConfetti();
    }, 500);
  }
});
</script>

<template>
  <AppLayout title="Mis Boletos">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-[#31572C] leading-tight flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
          </svg>
          Mis Boletos
        </h2>
        <div class="flex items-center space-x-4">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar rifa..."
              class="w-64 px-4 py-2 rounded-xl border border-[#4F772D]/20 focus:ring-2 focus:ring-[#4F772D] focus:border-[#4F772D] bg-white/80 backdrop-blur-sm transition-all duration-300"
            />
          </div>
          <Link
            :href="route('raffles.index')"
            class="inline-flex items-center px-6 py-3 bg-white/90 text-[#31572C] rounded-xl font-bold text-sm hover:bg-[#ECF39E] transform hover:scale-105 transition-all duration-200 shadow-xl"
          >
            Volver a Rifas
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12 bg-gradient-to-br from-[#ECF39E]/20 to-[#90A955]/10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="mb-8 flex justify-center">
          <nav class="flex flex-wrap gap-2 bg-white/50 backdrop-blur-sm p-2 rounded-xl shadow-md border border-[#4F772D]/20">
            <button
              v-for="status in statusOptions"
              :key="status"
              @click="activeFilter = status"
              class="px-6 py-2.5 rounded-lg font-medium text-sm transition-all duration-300"
              :class="[
                activeFilter === status
                  ? 'bg-[#4F772D] text-white shadow-lg transform scale-105'
                  : 'text-[#31572C] hover:bg-[#4F772D]/10'
              ]"
            >
              {{ getStatusTranslation(status) }}
            </button>
          </nav>
        </div>

        <div v-if="!groupedTickets.length"
             class="bg-white/80 backdrop-blur-sm p-12 rounded-xl shadow-xl text-center border border-[#4F772D]/20 animate-fade-in-up">
          <h3 class="mt-4 text-2xl font-bold text-[#31572C]">
            No tienes boletos comprados
          </h3>
          <p class="mt-2 text-[#4F772D]">
            Participa en nuestras rifas y gana increíbles premios
          </p>
        </div>

        <div class="relative min-h-[200px]">
          <TransitionGroup
            name="ticket-list"
            tag="div"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <div
              v-for="(group, index) in filteredTickets"
              :key="`${group.raffle.id}-${activeFilter}`"
              class="bg-white/80 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-[#4F772D]/20 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
              :style="{ animationDelay: `${index * 100}ms` }"
            >
              <!-- Cabecera -->
              <div class="bg-gradient-to-r from-[#4F772D] to-[#90A955] p-4">
                <div class="flex flex-col space-y-2">
                  <div class="flex justify-between items-start">
                    <h3 class="font-bold text-white text-lg">{{ group.raffle.title }}</h3>
                    <span :class="getStatusClass(group)">
                      {{ getStatusText(group) }}
                    </span>
                  </div>
                  <!-- Premio -->
                  <div class="text-white/90 text-base">
                    <span class="font-medium">Premio:</span>
                    <span class="font-semibold">{{ group.raffle.prize }}</span>
                  </div>
                  <p class="text-sm text-white/80">{{ formatDate(group.raffle.end_date) }}</p>
                </div>
              </div>

              <!-- Contenido -->
              <div class="p-4">
                <div class="grid grid-cols-5 gap-2">
                  <div
                    v-for="ticket in group.tickets"
                    :key="ticket.id"
                    class="relative group"
                  >
                    <div
                      class="aspect-square rounded-lg flex items-center justify-center text-sm font-bold transition-all duration-300 transform hover:scale-110"
                      :class="[
                        ticket.is_winner
                          ? 'bg-gradient-to-br from-yellow-300 to-yellow-500 text-white shadow-lg animate-pulse'
                          : 'bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/20 text-[#31572C] group-hover:from-[#4F772D]/20 group-hover:to-[#90A955]/30'
                      ]"
                    >
                      {{ ticket.ticket_number }}
                    </div>
                  </div>
                </div>

                <!-- Premio -->
                <div class="mt-4 text-sm text-[#31572C]">
                  <span class="font-semibold">Premio:</span> {{ group.raffle.prize }}
                </div>
              </div>
            </div>
          </TransitionGroup>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
  animation-delay: calc(var(--index, 0) * 150ms);
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(60px) scale(0.8);
  }
  60% {
    transform: translateY(-10px) scale(1.05);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.ticket-list-enter-active,
.ticket-list-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.ticket-list-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.ticket-list-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}

.ticket-list-move {
  transition: transform 0.5s ease;
}

.grid-enter-active,
.grid-leave-active,
.ticket-item {
  display: none;
}

@keyframes pulse {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4);
  }
  50% {
    transform: scale(1.05);
    box-shadow: 0 0 20px 10px rgba(255, 215, 0, 0);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
  }
}

.animate-pulse {
  animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

