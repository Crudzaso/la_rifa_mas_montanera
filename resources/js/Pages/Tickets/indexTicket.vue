<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import confetti from "canvas-confetti";

dayjs.extend(utc);

const page = usePage();
const tickets = computed(() => page.props.tickets || []);
const searchQuery = ref("");
const confettiShown = ref(new Set());

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
    };
  }

  if (today.isSame(endDate))
    return {
      text: "En Juego",
      class: "bg-green-100 text-green-800",
    };

  if (today.isAfter(endDate))
    return {
      text: "Terminada",
      class: "bg-red-100 text-red-800",
    };

  return {
    text: "Pendiente",
    class: "bg-yellow-100 text-yellow-800",
  };
};

const shootConfetti = (raffleId) => {
  if (!confettiShown.value.has(raffleId)) {
    confetti({
      particleCount: 100,
      spread: 70,
      origin: { y: 0.6 },
      colors: ["#FFD700", "#FFA500", "#FF6347"],
    });
    confettiShown.value.add(raffleId);
  }
};

const formatDate = (date) => dayjs(date).utc().format("DD/MM/YYYY");
</script>

<template>
  <AppLayout title="Mis Boletos">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Mis Boletos
        </h2>
        <div class="flex items-center space-x-4">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar rifa..."
              class="w-64 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <Link
            :href="route('raffles.index')"
            class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 border-2 border-indigo-600 rounded-xl font-semibold text-sm uppercase tracking-wider hover:bg-indigo-600 hover:text-white transform hover:scale-105 transition-all duration-200 shadow-lg"
          >
            Volver a Rifas
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          v-if="!groupedTickets.length"
          class="bg-white p-12 rounded-2xl shadow-xl text-center"
        >
          <h3 class="mt-4 text-2xl font-bold text-gray-900">
            No tienes boletos comprados
          </h3>
          <p class="mt-2 text-gray-600">
            Participa en nuestras rifas y gana increíbles premios
          </p>
        </div>

        <div v-else class="space-y-8">
          <div
            v-for="group in groupedTickets"
            :key="group.raffle.id"
            class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl"
            @mouseenter="
              group.tickets.some((t) => t.is_winner) &&
                shootConfetti(group.raffle.id)
            "
          >
            <div
              class="relative bg-gradient-to-r from-indigo-600 to-purple-600 p-8"
            >
              <div class="flex justify-between items-start">
                <div class="text-white flex-grow">
                  <h3 class="text-3xl font-extrabold mb-4 tracking-wide">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="inline h-8 w-8 mr-2"
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
                    Rifa: {{ group.raffle.title }}
                  </h3>
                  <p class="text-2xl text-indigo-100 mt-6">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="inline h-6 w-6 mr-2"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"
                      />
                    </svg>
                    Premio: {{ group.raffle.prize }}
                  </p>
                </div>
                <div class="flex flex-col">
                  <!-- Status -->
                  <span :class="['px-6 py-2 rounded-full text-sm font-bold shadow-lg flex items-center', getStatus(group.raffle, group.tickets).class]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ getStatus(group.raffle, group.tickets).text }}
                  </span>

                  <!-- Imagen -->
                  <div v-if="group.raffle.url_image" class="mt-6 mx-4 relative overflow-hidden rounded-lg shadow-md h-20">
                    <img
                      :src="group.raffle.url_image"
                      :alt="group.raffle.title"
                      class="w-full h-20 object-cover transform transition-transform duration-300 hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300">
                    </div>
                  </div>

                  <!-- Mensaje sin imagen -->
                  <div v-else class="mt-4 mx-4 text-center text-sm text-gray-333 italic">
                    <h4>Sin imagen disponible</h4>
                </div>
                </div>
              </div>
            </div>

            <div class="p-8 bg-gradient-to-b from-gray-50 to-white">
              <div class="mb-6">
                <h4 class="text-xl font-bold text-gray-800 mb-4">
                  Tus números de la suerte
                </h4>
                <div class="flex flex-wrap gap-4">
                  <div
                    v-for="ticket in group.tickets"
                    :key="ticket.id"
                    :class="[
                      'relative flex items-center justify-center w-16 h-16 rounded-xl font-bold text-2xl transform transition-all duration-300 hover:scale-110 shadow-md',
                      ticket.is_winner
                        ? 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900 ring-4 ring-yellow-300 animate-pulse'
                        : 'bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800',
                    ]"
                  >
                    {{ ticket.ticket_number }}
                  </div>
                </div>
              </div>

              <div
                class="flex justify-between items-center text-sm font-medium"
              >
                <div class="text-gray-600 flex items-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                  </svg>
                  Fecha de juego: {{ formatDate(group.raffle.end_date) }}
                </div>
                <div
                  class="px-4 py-2 bg-indigo-50 rounded-lg text-indigo-700 flex items-center"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                    />
                  </svg>
                  Total de boletos: {{ group.tickets.length }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

