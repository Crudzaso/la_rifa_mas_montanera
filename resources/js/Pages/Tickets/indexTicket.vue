<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";

dayjs.extend(utc);

const page = usePage();
const tickets = computed(() => page.props.tickets || []);

const searchQuery = ref('');

// Modificar groupedTickets para incluir búsqueda y ordenamiento
const groupedTickets = computed(() => {
  const filtered = tickets.value
    .filter(ticket => 
      ticket.raffle?.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
    .reduce((groups, ticket) => {
      const raffleId = ticket.raffle?.id;
      if (!groups[raffleId]) {
        groups[raffleId] = {
          raffle: ticket.raffle,
          tickets: [],
        };
      }
      groups[raffleId].tickets.push(ticket);
      return groups;
    }, {});

  // Convertir a array y ordenar por fecha más reciente
  return Object.values(filtered).sort((a, b) => 
    dayjs(b.raffle.end_date).valueOf() - dayjs(a.raffle.end_date).valueOf()
  );
});

// Función de estado
const getStatus = (raffle, tickets) => {
  const today = dayjs().utc().startOf("day");
  const endDate = dayjs(raffle.end_date).utc().startOf("day");

  if (tickets.some((ticket) => ticket.is_winner)) {
    return {
      text: "¡Ganador!",
      class: "bg-yellow-400 text-yellow-900 animate-bounce shadow-lg",
    };
  }

  if (today.isSame(endDate)) {
    return {
      text: "En Juego",
      class: "bg-green-100 text-green-800",
    };
  }

  if (today.isAfter(endDate)) {
    return {
      text: "Terminada",
      class: "bg-red-100 text-red-800",
    };
  }

  return {
    text: "Pendiente",
    class: "bg-yellow-100 text-yellow-800",
  };
};

// Formateo de fecha
const formatDate = (date) => {
  return date ? dayjs(date).utc().format("DD/MM/YYYY") : "";
};
</script>

<template>
  <AppLayout title="Mis Boletos">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Mis Boletos
        </h2>
        <div class="flex items-center space-x-4">
          <!-- Barra de búsqueda -->
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar rifa..."
              class="w-64 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
            <svg
              class="absolute right-3 top-2.5 h-5 w-5 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
          </div>
          
          <Link 
              :href="route('raffles.index')"
              class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 border-2 border-indigo-600 rounded-xl 
                     font-semibold text-sm uppercase tracking-wider 
                     hover:bg-indigo-600 hover:text-white 
                     transform hover:scale-105 transition-all duration-200 shadow-lg"
          >
              <svg 
                  xmlns="http://www.w3.org/2000/svg" 
                  class="w-5 h-5 mr-2" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke="currentColor"
              >
                  <path 
                      stroke-linecap="round" 
                      stroke-linejoin="round" 
                      stroke-width="2" 
                      d="M11 17l-5-5m0 0l5-5m-5 5h12"
                  />
              </svg>
              Volver a Rifas
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Sin boletos -->
        <div
          v-if="!groupedTickets.length"
          class="bg-white p-12 rounded-2xl shadow-xl text-center"
        >
          <svg
            class="mx-auto h-16 w-16 text-gray-400"
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
          <h3 class="mt-4 text-2xl font-bold text-gray-900">
            No tienes boletos comprados
          </h3>
          <p class="mt-2 text-gray-600">
            Participa en nuestras rifas y gana increíbles premios
          </p>
          <Link
            :href="route('raffles.index')"
            class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg"
          >
            <span class="text-lg font-semibold">Comprar Boletos</span>
            <svg
              class="ml-2 h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M17 8l4 4m0 0l-4 4m4-4H3"
              />
            </svg>
          </Link>
        </div>

        <!-- Mensaje cuando no hay resultados de búsqueda -->
        <div
          v-if="searchQuery && !groupedTickets.length"
          class="text-center text-gray-500 mt-4"
        >
          No se encontraron rifas que coincidan con tu búsqueda
        </div>

        <!-- Lista de rifas y sus boletos -->
        <div v-else class="space-y-8">
          <div
            v-for="group in groupedTickets"
            :key="group.raffle.id"
            class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl"
          >
            <!-- Cabecera con imagen -->
            <div
              class="relative bg-gradient-to-r from-indigo-600 to-purple-600 p-8"
            >
              <div class="flex justify-between items-start">
                <!-- Contenido izquierdo -->
                <div class="text-white flex-grow">
                  <h3 class="text-3xl font-extrabold mb-4 tracking-wide">
                    Rifa: {{ group.raffle.title }}
                  </h3>
                  <p class="text-2xl text-indigo-100 mt-6">
                    <span class="font-semibold mr-2">Premio:</span>
                    <span class="font-bold">{{ group.raffle.prize }}</span>
                  </p>
                </div>

                <!-- Contenido derecho (imagen y estado) -->
                <div class="flex flex-col items-end gap-4">
                  <!-- Estado -->
                  <span
                    :class="[
                      'px-6 py-2 rounded-full text-sm font-bold shadow-lg',
                      getStatus(group.raffle, group.tickets).class,
                    ]"
                  >
                    {{ getStatus(group.raffle, group.tickets).text }}
                  </span>

                  <!-- Imagen -->
                  <div
                    class="w-20 h-20 overflow-hidden rounded-lg shadow-lg mr-4"
                  >
                    <img
                      v-if="group.raffle.url_image"
                      :src="group.raffle.url_image"
                      :alt="group.raffle.title"
                      class="w-full h-full object-cover"
                    />
                    <div
                      v-else
                      class="w-full h-full bg-gray-200 flex items-center justify-center"
                    >
                      <svg
                        class="w-10 h-10 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Números -->
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
                    <div
                      v-if="ticket.is_winner"
                      class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center"
                    >
                      <svg
                        class="w-4 h-4 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"
                        />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="flex justify-between items-center text-sm font-medium"
              >
                <div class="flex items-center space-x-2 text-gray-600">
                  <svg
                    class="w-5 h-5"
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
                  <span
                    >Fecha de juego:
                    {{ formatDate(group.raffle.end_date) }}</span
                  >
                </div>
                <div class="px-4 py-2 bg-indigo-50 rounded-lg text-indigo-700">
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


