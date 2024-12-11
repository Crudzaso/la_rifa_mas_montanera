<script setup>
import { ref, reactive, watch, computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Alert from '@/Components/Alert.vue';
import Link from '@/Components/NavLink.vue';
import Modal from '@/Components/Modal.vue';
import gsap from 'gsap';

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

const tweenedTotal = reactive({
  number: 0
});

watch(totalAmount, (newTotal) => {
  gsap.to(tweenedTotal, {
    duration: 0.5,
    number: newTotal,
    ease: "power2.out"
  });
});
</script>


<template>
  <AppLayout title="Comprar Boletos">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-[#31572C] leading-tight flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
          </svg>
          Comprar Boletos
        </h2>
        <Link :href="route('raffles.index')"
              class="inline-flex items-center px-6 py-3 bg-white/90 text-[#31572C] rounded-xl font-bold text-sm hover:bg-[#ECF39E] transform hover:scale-105 transition-all duration-200 shadow-xl">
          <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Volver a Rifas
        </Link>
      </div>
    </template>

    <Alert v-if="showMessage" :message="message" :type="type" />

    <div class="py-12 bg-gradient-to-br from-[#ECF39E]/20 to-[#90A955]/10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Información de la rifa -->
        <div class="bg-gradient-to-br from-white/90 to-[#ECF39E]/20 backdrop-blur-sm rounded-xl shadow-xl border border-[#4F772D]/20 overflow-hidden mb-8">
          <div class="p-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6">
              <div>
                <h1 class="text-3xl font-bold text-[#31572C] mb-4">{{ raffle.title }}</h1>
                <div class="space-y-2">
                  <div class="flex items-center text-[#4F772D]">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-lg font-semibold">${{ raffle.price_tickets }} por boleto</span>
                  </div>
                  <div class="flex items-center text-[#4F772D]">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-lg">{{ availableNumbers.filter(n => !n.isSold).length }} números disponibles</span>
                  </div>
                </div>
              </div>

              <button
                v-if="selectedNumbers.length"
                @click="clearSelection"
                class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all duration-200 shadow-sm hover:shadow flex items-center group"
              >
                <svg class="h-4 w-4 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Limpiar selección
              </button>
            </div>
          </div>
        </div>

        <!-- Grid de números con nuevo estilo -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-xl border border-[#4F772D]/20 p-6">
          <h2 class="text-2xl font-bold text-[#31572C] mb-6 text-center">Selecciona tus números</h2>

          <div class="flex justify-center">
            <div class="grid grid-cols-8 sm:grid-cols-10 gap-2 w-full max-w-3xl">
              <button
                v-for="item in availableNumbers"
                :key="item.number"
                @click="!item.isSold && toggleNumber(item.number)"
                :class="[
                  'relative w-10 h-10 rounded-lg font-bold text-base transition-all duration-300 transform',
                  {
                    'bg-gradient-to-br from-[#4F772D] to-[#90A955] text-white shadow-lg scale-110 hover:shadow-xl rotate-3':
                      selectedNumbers.includes(item.number),
                    'bg-white/80 border border-[#4F772D]/20 text-[#31572C] hover:border-[#4F772D] hover:bg-[#ECF39E]/20 hover:-translate-y-1 hover:rotate-2':
                      !item.isSold && !selectedNumbers.includes(item.number),
                    'bg-red-50 border border-red-200 text-red-300 cursor-not-allowed hover:shake':
                      item.isSold
                  }
                ]"
                :disabled="item.isSold"
              >
                {{ item.number }}
                <span v-if="selectedNumbers.includes(item.number)"
                      class="absolute -top-1 -right-1 h-3 w-3 bg-[#90A955] rounded-full border border-white">
                </span>
                <span v-if="item.isSold"
                      class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border border-white">
                </span>
              </button>
            </div>
          </div>
        </div>

        <!-- Resumen de compra -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-xl border border-[#4F772D]/20 p-8">
          <div class="flex flex-col md:flex-row justify-between items-start gap-6">
            <div>
              <h3 class="text-xl font-bold text-[#31572C] mb-4">Números seleccionados</h3>
              <div class="flex flex-wrap gap-2">
                <span v-for="number in selectedNumbers"
                      :key="number"
                      class="px-3 py-1 bg-[#4F772D]/10 text-[#31572C] rounded-lg font-medium">
                  {{ number }}
                </span>
                <span v-if="!selectedNumbers.length" class="text-[#4F772D]">
                  Ningún número seleccionado
                </span>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm text-[#4F772D] mb-1">Total a pagar</p>
              <p class="text-3xl font-bold text-[#31572C]">
                ${{ tweenedTotal.number.toFixed(0) }}
              </p>
            </div>
          </div>

          <button
            @click="confirmPurchase"
            :disabled="!selectedNumbers.length"
            class="mt-8 w-full py-4 bg-gradient-to-r from-[#4F772D] to-[#90A955] text-white text-lg font-bold rounded-xl
                   hover:from-[#31572C] hover:to-[#4F772D] transition-all duration-300 transform hover:scale-[1.01]
                   disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg
                   flex items-center justify-center gap-2"
          >
            <span v-if="selectedNumbers.length">
              Comprar {{ selectedNumbers.length }} Boleto(s)
            </span>
            <span v-else>
              Selecciona al menos un número
            </span>
            <svg v-if="selectedNumbers.length" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Botón flotante de compra -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-md border-t border-[#4F772D]/20 shadow-lg transform transition-all duration-300"
         :class="{'translate-y-full': !selectedNumbers.length}">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="flex flex-wrap gap-2">
            <span v-for="number in selectedNumbers.slice(0, 3)"
                  :key="number"
                  class="px-3 py-1 bg-[#4F772D]/10 text-[#31572C] rounded-lg font-medium">
              {{ number }}
            </span>
            <span v-if="selectedNumbers.length > 3"
                  class="px-3 py-1 bg-[#4F772D]/10 text-[#31572C] rounded-lg font-medium">
              +{{ selectedNumbers.length - 3 }} más
            </span>
          </div>
          <div class="text-right">
            <p class="text-sm text-[#4F772D]">Total</p>
            <p class="text-xl font-bold text-[#31572C]">
              ${{ tweenedTotal.number.toFixed(0) }}
            </p>
          </div>
        </div>

        <button
            @click="confirmPurchase"
            :disabled="!selectedNumbers.length"
            class="px-6 py-3 bg-gradient-to-r from-[#4F772D] to-[#90A955] text-white font-bold rounded-xl
                    hover:from-[#31572C] hover:to-[#4F772D] transition-all duration-300 transform hover:scale-[1.01]
                    disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg
                    flex items-center gap-2"
            >
            <span>Comprar {{ selectedNumbers.length }} Boleto(s)</span>
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            </button>
      </div>
    </div>

    <!-- Modal de confirmación -->
    <Modal :show="showConfirmModal" @close="showConfirmModal = false">
      <div class="p-6">
        <h3 class="text-xl font-bold text-[#31572C] mb-4">
          Confirmar compra
        </h3>
        <div class="space-y-4">
          <p class="text-[#4F772D]">
            ¿Estás seguro de comprar los siguientes números?
          </p>
          <div class="flex flex-wrap gap-2">
            <span v-for="number in selectedNumbers"
                  :key="number"
                  class="px-3 py-1 bg-[#4F772D]/10 text-[#31572C] rounded-lg font-medium">
              {{ number }}
            </span>
          </div>
          <p class="font-semibold text-lg text-[#31572C]">
            Total a pagar: ${{ totalAmount }}
          </p>
        </div>
        <div class="mt-6 flex justify-end gap-3">
          <button
            @click="showConfirmModal = false"
            class="px-4 py-2 text-[#31572C] hover:bg-[#4F772D]/10 rounded-lg transition-colors duration-200"
          >
            Cancelar
          </button>
          <button
            @click="buyTickets"
            class="px-4 py-2 bg-[#4F772D] text-white rounded-lg hover:bg-[#31572C] transition-colors duration-200"
          >
            Confirmar compra
          </button>
        </div>
      </div>
    </Modal>
  </AppLayout>
</template>

<style scoped>
.hover\:shake:hover {
  animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
}

@keyframes shake {
  10%, 90% { transform: translate3d(-1px, 0, 0); }
  20%, 80% { transform: translate3d(2px, 0, 0); }
  30%, 50%, 70% { transform: translate3d(-2px, 0, 0); }
  40%, 60% { transform: translate3d(2px, 0, 0); }
}

.animate-ping {
  animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes ping {
  75%, 100% {
    transform: scale(1.5);
    opacity: 0;
  }
}
</style>

