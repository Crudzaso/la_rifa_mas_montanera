<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import Alert from "@/Components/Alert.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import DateInput from "@/Components/DateInput.vue";

const page = usePage();
const raffle = page.props.raffle;
const showMessage = ref(false);
const message = ref('');
const type = ref('');

// Formatear fechas al YYYY-MM-DD
const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toISOString().split('T')[0];
};

const form = useForm({
  title: raffle.title,
  description: raffle.description,
  start_date: formatDate(raffle.start_date),
  end_date: formatDate(raffle.end_date),
  status: raffle.status,
  url_image: raffle.url_image
});

const statusOptions = [
  { value: 'pending', label: 'Pendiente' },
  { value: 'ongoing', label: 'En Juego' },
  { value: 'finished', label: 'Finalizado' }
];

const submit = () => {
  form.put(route('raffles.update', raffle.id), {
    preserveScroll: true,
    onSuccess: () => {
      message.value = 'Rifa actualizada correctamente';
      type.value = 'success';
      showMessage.value = true;
      setTimeout(() => {
        window.location.href = route('raffles.index');
      }, 2000);
    },
    onError: () => {
      message.value = 'Error al actualizar la rifa';
      type.value = 'error';
      showMessage.value = true;
    }
  });
};
</script>

<template>
  <AppLayout title="Editar Rifa">
    <!-- Header -->
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-2xl text-[#31572C] leading-tight flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
          </svg>
          Editar Rifa
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

        <!-- Card principal -->
        <div class="bg-gradient-to-br from-white/90 to-[#ECF39E]/20 backdrop-blur-sm overflow-hidden rounded-xl shadow-xl border border-[#4F772D]/20 animate-fade-in-up">
          <div class="p-8">
            <h3 class="text-2xl font-bold text-[#31572C] mb-6">Información de la Rifa</h3>

            <form @submit.prevent="submit" class="space-y-6">
              <!-- Título -->
              <div class="relative group">
                <InputLabel for="title" value="Título de la Rifa" class="text-[#4F772D]"/>
                <TextInput
                  id="title"
                  v-model="form.title"
                  type="text"
                  class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
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
                ></textarea>
                <InputError class="mt-2" :message="form.errors.description"/>
              </div>

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

              <!-- Estado -->
              <div class="relative group">
                <InputLabel for="status" value="Estado" class="text-[#4F772D]"/>
                <select
                  id="status"
                  v-model="form.status"
                  class="mt-1 block w-full border-[#4F772D]/20 focus:border-[#4F772D] focus:ring-[#4F772D] rounded-xl transition-all duration-300"
                >
                  <option v-for="option in statusOptions"
                          :key="option.value"
                          :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
                <InputError class="mt-2" :message="form.errors.status"/>
              </div>

              <!-- Botón de actualizar -->
              <div class="flex justify-end mt-8">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="inline-flex items-center px-6 py-3 bg-[#4F772D] text-white rounded-xl font-bold text-sm hover:bg-[#31572C] transition-all duration-200"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  Actualizar Rifa
                </button>
              </div>
            </form>
          </div>
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
</style>
