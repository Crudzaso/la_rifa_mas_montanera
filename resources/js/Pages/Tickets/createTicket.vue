<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Alert from '@/Components/Alert.vue';
import Link from '@/Components/NavLink.vue';

const page = usePage();
const raffle = page.props.raffle;
const selectedNumbers = ref([]);
const showMessage = ref(false);
const message = ref('');
const type = ref('');

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

const buyTickets = () => {
    // Asignar los números seleccionados al formulario antes de enviar
    form.ticket_numbers = selectedNumbers.value;

    console.log('Enviando números:', form.ticket_numbers); // Debug

    form.post(route('tickets.store'), {
        preserveScroll: true,
        onSuccess: () => {
            message.value = 'Boletos comprados exitosamente';
            type.value = 'success';
            showMessage.value = true;
            setTimeout(() => {
                window.location.href = route('raffles.index');
            }, 2000);
        },
        onError: (errors) => {
            message.value = errors.ticket_numbers || 'Error al comprar los boletos';
            type.value = 'error';
            showMessage.value = true;
        }
    });
};
</script>

<template>
  <AppLayout title="Comprar Boletos">
    <Alert v-if="showMessage" :message="message" :type="type" />

    <div class="max-w-7xl mx-auto p-6">
      <!-- Header  -->
      <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-md">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">{{ raffle.title }}</h1>
          <p class="text-gray-600 mt-2">Precio por boleto: ${{ raffle.price_tickets }}</p>
        </div>
        <Link
          :href="route('raffles.index')"
          class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all transform hover:scale-105 shadow-md"
        >
          ← Volver a Rifas
        </Link>
      </div>

      <!-- Mensaje sin boletos  -->
      <div v-if="availableNumbers.length === 0"
           class="text-center py-16 bg-white rounded-lg shadow-md">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-2xl font-medium text-gray-900 mb-3">No hay boletos disponibles</h3>
        <p class="text-gray-500 text-lg">Todos los boletos de esta rifa han sido vendidos.</p>
      </div>

      <!-- Grid de números  -->
      <template v-else>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <h2 class="text-xl font-semibold mb-4 text-gray-800">Selecciona tus números</h2>
          <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-3">
            <button
              v-for="item in availableNumbers"
              :key="item.number"
              @click="!item.isSold && toggleNumber(item.number)"
              :class="[
                'relative h-12 rounded-lg font-bold text-lg transition-all duration-200',
                {
                  'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg scale-105':
                    selectedNumbers.includes(item.number),
                  'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md hover:scale-105':
                    !item.isSold && !selectedNumbers.includes(item.number),
                  'bg-red-100 text-red-400 cursor-not-allowed opacity-60':
                    item.isSold
                },
                'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
              ]"
              :disabled="item.isSold"
            >
              {{ item.number }}
              <span v-if="selectedNumbers.includes(item.number)"
                    class="absolute -top-1 -right-1 h-4 w-4 bg-green-500 rounded-full border-2 border-white">
              </span>
              <span v-if="item.isSold"
                    class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full border-2 border-white">
              </span>
            </button>
          </div>
        </div>

        <!-- Resumen  -->
        <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
          <div class="flex justify-between items-center border-b pb-4">
            <div>
              <h3 class="text-lg font-medium text-gray-900">Boletos seleccionados:</h3>
              <p class="text-gray-600">{{ selectedNumbers.join(', ') || 'Ninguno seleccionado' }}</p>
            </div>
            <p class="text-2xl font-bold text-indigo-600">
              ${{ totalAmount }}
            </p>
          </div>

          <button
            @click="buyTickets"
            class="w-full py-4 bg-gradient-to-r from-green-500 to-green-600 text-white text-lg font-semibold rounded-lg
                   hover:from-green-600 hover:to-green-700 transform transition-all duration-200 hover:scale-[1.02]
                   disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
            :disabled="!selectedNumbers.length"
          >
            {{ selectedNumbers.length ? `Comprar ${selectedNumbers.length} Boleto(s)` : 'Selecciona al menos un boleto' }}
          </button>
        </div>
      </template>
    </div>
  </AppLayout>
</template>

