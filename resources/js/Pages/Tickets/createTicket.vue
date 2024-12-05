<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Alert from '@/Components/Alert.vue';
import Link from '@/Components/NavLink.vue';
import Modal from '@/Components/Modal.vue';

const page = usePage();
const raffle = page.props.raffle;
const selectedNumbers = ref([]);
const showMessage = ref(false);
const message = ref('');
const type = ref('');
const showConfirmModal = ref(false);

const form = useForm({
    raffle_id: raffle.id,
    ticket_numbers: []
});

// Modificar computed para manejar números vendidos
const availableNumbers = computed(() => {
    const numbers = [];
    const soldNumbers = new Set(raffle.tickets.map(t => t.ticket_number));

    // Generar todos los números del 00 al 99
    for (let i = 0; i < 100; i++) {
        const num = String(i).padStart(2, '0');
        numbers.push({
            number: num,
            isSold: soldNumbers.has(num)
        });
    }
    return numbers;
});

// Total a pagar
const totalAmount = computed(() => {
    return selectedNumbers.value.length * raffle.price_tickets;
});

const toggleNumber = (number) => {
    const index = selectedNumbers.value.indexOf(number);
    if (index === -1) {
        selectedNumbers.value.push(number);
    } else {
        selectedNumbers.value.splice(index, 1);
    }
};

// Limpiar selección
const clearSelection = () => {
    selectedNumbers.value = [];
};

// Mostrar modal de confirmación
const confirmPurchase = () => {
    showConfirmModal.value = true;
};

// Proceder con la compra
const buyTickets = () => {
    form.ticket_numbers = selectedNumbers.value;
    form.post(route('tickets.store'), {
        preserveScroll: true,
        onSuccess: () => {
            message.value = 'Boletos comprados exitosamente';
            type.value = 'success';
            showMessage.value = true;
            showConfirmModal.value = false;
            setTimeout(() => {
                window.location.href = route('tickets.index');
            }, 2000);
        },
        onError: () => {
            message.value = 'Error al comprar los boletos';
            type.value = 'error';
            showMessage.value = true;
        }
    });
};

const getStatus = (raffle) => {
    const now = new Date();
    const endDate = new Date(raffle.end_date);
    const startDate = new Date(raffle.start_date);

    // Ajustar las fechas al inicio del día
    now.setHours(0, 0, 0, 0);
    endDate.setHours(0, 0, 0, 0);
    startDate.setHours(0, 0, 0, 0);

    if (now < startDate) {
        return { text: 'Pendiente', class: 'bg-yellow-100 text-yellow-800' };
    }
    if (now.getTime() === endDate.getTime()) {
        return { text: 'En Juego', class: 'bg-green-100 text-green-800' };
    }
    if (now > endDate) {
        return { text: 'Terminada', class: 'bg-red-100 text-red-800' };
    }
    return { text: 'En Juego', class: 'bg-green-100 text-green-800' };
};

const hasWinningTicket = (tickets) => {
    return tickets.some(ticket => ticket.is_winner);
};
</script>

<template>
  <AppLayout title="Comprar Boletos">
    <Alert v-if="showMessage" :message="message" :type="type" />

    <div class="max-w-7xl mx-auto p-6">
      <!-- Header  -->
      <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl overflow-hidden mb-8">
        <div class="relative p-8">
          <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full opacity-10"></div>

          <div class="flex justify-between items-start relative z-10">
            <div class="text-white">
              <h1 class="text-4xl font-extrabold tracking-tight mb-2">{{ raffle.title }}</h1>
              <div class="flex items-center space-x-4 mt-4">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-xl font-bold">${{ raffle.price_tickets }}/boleto</span>
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                  </svg>
                  <span class="text-xl">{{ availableNumbers.filter(n => !n.isSold).length }} disponibles</span>
                </div>
              </div>
            </div>
            <Link :href="route('raffles.index')"
                  class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-xl hover:bg-white/20 transition-all duration-200 font-medium">
              ← Volver a Rifas
            </Link>
          </div>
        </div>
      </div>

      <!-- Grid de números -->
      <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Escoge tus números</h2>
          <button
              v-if="selectedNumbers.length"
              @click="clearSelection"
              class="group flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 shadow-sm hover:shadow"
          >
              <svg xmlns="http://www.w3.org/2000/svg"
                   class="h-4 w-4 mr-2 group-hover:rotate-12 transition-transform duration-200"
                   fill="none"
                   viewBox="0 0 24 24"
                   stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Limpiar selección
          </button>
        </div>
        <!-- Grid de números centrado y más grande -->
        <div class="flex justify-center w-full px-4">
            <div class="grid grid-cols-10 gap-2 w-[90%]">
                <button
                    v-for="item in availableNumbers"
                    :key="item.number"
                    @click="!item.isSold && toggleNumber(item.number)"
                    :class="[
                        'relative h-14 w-20 rounded-lg font-bold text-lg transition-all duration-200',
                        {
                            'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md scale-105':
                                selectedNumbers.includes(item.number),
                            'bg-white border border-gray-200 text-gray-700 hover:border-indigo-500 hover:text-indigo-500':
                                !item.isSold && !selectedNumbers.includes(item.number),
                            'bg-red-50 border border-red-200 text-red-300 cursor-not-allowed':
                                item.isSold
                        }
                    ]"
                    :disabled="item.isSold"
                >
                    {{ item.number }}
                    <span v-if="selectedNumbers.includes(item.number)"
                          class="absolute -top-1 -right-1 h-3 w-3 bg-green-500 rounded-full border border-white animate-pulse">
                    </span>
                    <span v-if="item.isSold"
                          class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border border-white">
                    </span>
                </button>
            </div>
        </div>
      </div>

      <!-- Resumen  -->
      <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-xl p-8">
        <div class="flex justify-between items-start mb-6">
          <div>
            <h3 class="text-xl font-bold text-gray-900">Tus números seleccionados</h3>
            <div class="flex flex-wrap gap-2 mt-3">
              <span v-for="number in selectedNumbers"
                    :key="number"
                    class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-lg font-medium">
                {{ number }}
              </span>
              <span v-if="!selectedNumbers.length" class="text-gray-500">
                Ninguno seleccionado
              </span>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600 mb-1">Total a pagar</p>
            <p class="text-3xl font-bold text-indigo-600">${{ totalAmount }}</p>
          </div>
        </div>

        <!-- Botón comprar con icono -->
        <button
            @click="confirmPurchase"
            :disabled="!selectedNumbers.length"
            class="mt-6 w-full py-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-lg font-bold rounded-xl
                   hover:from-green-600 hover:to-emerald-600 transition-all duration-200 transform hover:scale-[1.01]
                   disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg
                   flex items-center justify-center space-x-2"
        >
            <span v-if="selectedNumbers.length">
                Comprar {{ selectedNumbers.length }} Boleto(s) - ${{ totalAmount }}
            </span>
            <span v-else>
                Selecciona al menos un boleto
            </span>
            <svg
                v-if="selectedNumbers.length"
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 ml-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                />
            </svg>
        </button>
      </div>
    </div>

    <!-- Modal de confirmación -->
    <Modal :show="showConfirmModal" @close="showConfirmModal = false">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                Confirmar compra
            </h3>
            <div class="space-y-4">
                <p class="text-gray-600">
                    ¿Estás seguro de comprar los siguientes números?
                </p>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="number in selectedNumbers"
                        :key="number"
                        class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-lg font-medium"
                    >
                        {{ number }}
                    </span>
                </div>
                <p class="font-semibold text-lg">
                    Total a pagar: ${{ totalAmount }}
                </p>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button
                    @click="showConfirmModal = false"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
                >
                    Cancelar
                </button>
                <button
                    @click="buyTickets"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                >
                    Confirmar compra
                </button>
            </div>
        </div>
    </Modal>
  </AppLayout>
</template>

