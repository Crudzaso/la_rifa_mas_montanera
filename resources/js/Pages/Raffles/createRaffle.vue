<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import DateInput from "@/Components/DateInput.vue";
import Alert from '@/Components/Alert.vue';

const showMessage = ref(false);
const message = ref('');
const type = ref('');

const form = useForm({
  title: "",
  description: "",
  url_image: "",
  start_date: "",
  end_date: "",
    price_tickets: "",
    prize: ""
});

// Submit form function
const submit = () => {
  form.post(route("raffles.store"), {
    preserveScroll: true,

    onSuccess: () => {
      showMessage.value = true;
      message.value = 'Rifa creada correctamente';
      type.value = 'success';
      setTimeout(() => {
        window.location.href = route("raffles.index");
      }, 2000);
    },
    onError: (errors) => {
      showMessage.value = true;
      message.value = 'Error al crear la rifa';
      type.value = 'error';
      console.error("Errores:", errors);
    },
  });
};

const currentStep = ref(0);
const steps = ['Información básica', 'Premio y Precio', 'Fechas'];
const prices = [2000, 5000, 10000, 20000, 50000];

const scrollToError = () => {
  const firstError = document.querySelector('.text-red-500');
  if (firstError) {
    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
};

const validateStep = () => {
  switch (currentStep.value) {
    case 0:
      return !form.errors.title && !form.errors.description;
    case 1:
      return !form.errors.prize && !form.errors.price_tickets;
    case 2:
      return !form.errors.start_date && !form.errors.end_date;
    default:
      return true;
  }
};

const nextStep = () => {
  if (currentStep.value < steps.length - 1) {
    currentStep.value++;
  }
};
</script>

<template>
  <AppLayout title="Crear Rifa">
    <!-- Header -->
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-[#31572C] leading-tight flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          Nueva Rifa
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

    <div class="py-12 bg-gradient-to-br from-[#ECF39E]/20 to-[#90A955]/10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Alert v-if="showMessage" :message="message" :type="type"/>

        <!-- Card principal con gradiente y animación -->
        <div class="bg-gradient-to-br from-white/90 to-[#ECF39E]/20 backdrop-blur-sm overflow-hidden rounded-xl shadow-xl border border-[#4F772D]/20 animate-fade-in-up">
          <!-- Pasos con fondo -->
          <div class="p-6 bg-gradient-to-r from-[#4F772D]/10 to-[#90A955]/10">
            <div class="flex items-center justify-center">
              <div v-for="(step, index) in steps" :key="index"
                   class="flex items-center">
                <div :class="[
                  'w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-lg',
                  currentStep > index ? 'bg-gradient-to-br from-[#4F772D] to-[#31572C] text-white' :
                  currentStep === index ? 'bg-gradient-to-br from-[#90A955] to-[#4F772D] text-white' :
                  'bg-gradient-to-br from-gray-200 to-gray-300'
                ]">
                  {{ index + 1 }}
                </div>
                <div v-if="index < steps.length - 1"
                     :class="[
                       'h-1 w-16 mx-2 transition-all duration-500',
                       currentStep > index ? 'bg-gradient-to-r from-[#4F772D] to-[#90A955]' : 'bg-gray-200'
                     ]">
                </div>
              </div>
            </div>
          </div>

          <!-- Formulario con pasos -->
          <form @submit.prevent="submit" class="p-8">
            <TransitionGroup
              name="slide-fade"
              mode="out-in">
              <!-- Paso 1: Información básica -->
              <div v-show="currentStep === 0"
                   :key="0"
                   class="space-y-6">
                <h3 class="text-2xl font-bold text-[#31572C] mb-6">Información básica para crear una rifa</h3>

                <div class="grid grid-cols-1 gap-6">
                  <!-- Título -->
                  <div class="relative group">
                    <InputLabel for="title" value="Título de la Rifa" class="text-[#4F772D]"/>
                    <TextInput
                      id="title"
                      v-model="form.title"
                      type="text"
                      class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                      placeholder="Ej: Gran Rifa Navideña"
                    />
                    <InputError class="mt-2" :message="form.errors.title"/>
                  </div>

                  <!-- URL de la imagen -->
                  <div class="relative group">
                    <InputLabel for="url_image" value="URL de la imagen" class="text-[#4F772D]"/>
                    <TextInput
                      id="url_image"
                      v-model="form.url_image"
                      type="url"
                      class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                      placeholder="https://ejemplo.com/imagen.jpg"
                    />
                    <InputError class="mt-2" :message="form.errors.url_image"/>
                  </div>

                  <!-- Descripción -->
                  <div class="relative group">
                    <InputLabel for="description" value="Descripción" class="text-[#4F772D]"/>
                    <textarea
                      id="description"
                      v-model="form.description"
                      rows="4"
                      class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                      placeholder="Describe los detalles de tu rifa..."
                    ></textarea>
                    <InputError class="mt-2" :message="form.errors.description"/>
                  </div>
                </div>
              </div>

              <!-- Paso 2: Premio y Precio -->
              <div v-show="currentStep === 1"
                   :key="1"
                   class="space-y-6">
                <h3 class="text-2xl font-bold text-[#31572C] mb-6">Premio y Precio</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Premio -->
                  <div class="relative group">
                    <InputLabel for="prize" value="Premio" class="text-[#4F772D]"/>
                    <TextInput
                      id="prize"
                      v-model="form.prize"
                      type="text"
                      class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                      placeholder="Ej: $1,000,000 en efectivo"
                    />
                    <InputError class="mt-2" :message="form.errors.prize"/>
                  </div>

                  <!-- Precio del boleto -->
                  <div class="relative group">
                    <InputLabel for="price_tickets" value="Precio del boleto" class="text-[#4F772D]"/>
                    <select
                      id="price_tickets"
                      v-model="form.price_tickets"
                      class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                    >
                      <option value="" disabled>Seleccione un precio</option>
                      <option v-for="price in prices" :key="price" :value="price">
                        ${{ price.toLocaleString() }}
                      </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.price_tickets"/>
                  </div>
                </div>
              </div>

              <!-- Paso 3: Fechas -->
              <div v-show="currentStep === 2"
                   :key="2"
                   class="space-y-6">
                <h3 class="text-2xl font-bold text-[#31572C] mb-6">Fechas</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Fecha de inicio -->
                  <div class="relative group">
                    <InputLabel for="start_date" value="Fecha de inicio" class="text-[#4F772D]"/>
                    <DateInput
                      id="start_date"
                      v-model="form.start_date"
                      type="date"
                      class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                    />
                    <InputError class="mt-2" :message="form.errors.start_date"/>
                  </div>

                  <!-- Fecha de fin -->
                  <div class="relative group">
                    <InputLabel for="end_date" value="Fecha de fin" class="text-[#4F772D]"/>
                    <DateInput
                      id="end_date"
                      v-model="form.end_date"
                      type="date"
                      class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                    />
                    <InputError class="mt-2" :message="form.errors.end_date"/>
                  </div>
                </div>
              </div>
            </TransitionGroup>

            <!-- Navegación entre pasos -->
            <div class="flex justify-between mt-8">
              <button
                v-if="currentStep > 0"
                type="button"
                @click="currentStep--"
                class="inline-flex items-center px-6 py-3 bg-white text-[#31572C] rounded-xl font-bold text-sm hover:bg-[#4F772D]/10 transition-all duration-200"
              >
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Anterior
              </button>

              <button
                v-if="currentStep < steps.length - 1"
                type="button"
                @click="nextStep"
                class="inline-flex items-center px-6 py-3 bg-[#4F772D] text-white rounded-xl font-bold text-sm hover:bg-[#31572C] transition-all duration-200"
              >
                Siguiente
                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </button>

              <button
                v-else
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center px-6 py-3 bg-[#4F772D] text-white rounded-xl font-bold text-sm hover:bg-[#31572C] transition-all duration-200"
              >
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Crear Rifa
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.slide-fade-enter-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-fade-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  position: absolute;
}

.slide-fade-enter-from {
  opacity: 0;
  transform: translateX(100px);
}

.slide-fade-leave-to {
  opacity: 0;
  transform: translateX(-100px);
}

.slide-fade-enter-to,
.slide-fade-leave-from {
  opacity: 1;
  transform: translateX(0);
}
</style>
