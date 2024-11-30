<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const raffle = page.props.raffle;
const selectedNumbers = ref([]);

const form = useForm({
    raffle_id: raffle.id,
    ticket_numbers: []
});

// Números disponibles
const availableNumbers = computed(() => {
    const numbers = [];
    for (let i = 0; i < 100; i++) {
        const num = String(i).padStart(2, '0');
        numbers.push(num);
    }
    return numbers;
});

// Total a pagar
const totalAmount = computed(() => {
    return selectedNumbers.value.length * raffle.price_tickets;
});

// Función para seleccionar/deseleccionar números
const toggleNumber = (number) => {
    const index = selectedNumbers.value.indexOf(number);
    if (index === -1) {
        selectedNumbers.value.push(number);
    } else {
        selectedNumbers.value.splice(index, 1);
    }
};

// Función para comprar boletos
const buyTickets = () => {
    form.ticket_numbers = selectedNumbers.value;
    form.post(route('tickets.store'), {
        preserveScroll: true,
        onSuccess: () => {
            selectedNumbers.value = [];
        }
    });
};
</script>

<template>
  <AppLayout title="Comprar Boletos">
    <div class="max-w-7xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">Comprar Boletos - {{ raffle.title }}</h1>

      <!-- Grid de números -->
      <div class="grid grid-cols-10 gap-2 mb-6">
        <button
          v-for="number in availableNumbers"
          :key="number"
          @click="toggleNumber(number)"
          :class="[
            'p-2 rounded-lg transition-colors',
            selectedNumbers.includes(number)
              ? 'bg-indigo-600 text-white'
              : 'bg-gray-100 hover:bg-gray-200'
          ]"
        >
          {{ number }}
        </button>
      </div>

      <!-- Resumen -->
      <div class="mt-4">
        <p class="text-lg font-semibold">Total a pagar: ${{ totalAmount }}</p>
        <button
          @click="buyTickets"
          class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors"
          :disabled="!selectedNumbers.length"
        >
          Comprar Boletos
        </button>
      </div>
    </div>
  </AppLayout>
</template>

