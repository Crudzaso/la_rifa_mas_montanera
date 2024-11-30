<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import DateInput from "@/Components/DateInput.vue";

const page = usePage();
const raffle = page.props.raffle;
const showMessage = ref(false);

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
      showMessage.value = true;
      setTimeout(() => {
        window.location.href = route('raffles.index');
      }, 2000);
    }
  });
};
</script>

<template>
  <AppLayout title="Editar Rifa">
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Editar Rifa
        </h2>
        <Link :href="route('raffles.index')"
              class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
          Volver
        </Link>
      </div>
    </template>

    <!-- Mensaje de éxito -->
    <div v-if="showMessage"
         class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md z-50">
      <span class="block sm:inline">Rifa actualizada correctamente</span>
    </div>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h2 class="text-2xl font-semibold text-gray-800 leading-tight py-6 text-center">
            Escoja los campos que desea editar
          </h2>
          <form @submit.prevent="submit" class="space-y-6 flex flex-col items-center">
            <!-- Título -->
            <div class="w-1/3">
              <InputLabel for="title" value="Título" />
              <TextInput
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.title" class="mt-2" />
            </div>

            <!-- Descripción -->
            <div class="w-1/3">
              <InputLabel for="description" value="Descripción" />
              <textarea
                id="description"
                v-model="form.description"
                rows="1"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm resize-none"
              ></textarea>
              <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <!-- Fecha de inicio -->
            <div class="w-1/3">
              <InputLabel for="start_date" value="Fecha de inicio" />
              <DateInput
                id="start_date"
                v-model="form.start_date"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.start_date" class="mt-2" />
            </div>

            <!-- Fecha de fin -->
            <div class="w-1/3">
              <InputLabel for="end_date" value="Fecha de fin" />
              <DateInput
                id="end_date"
                v-model="form.end_date"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.end_date" class="mt-2" />
            </div>

            <!-- Estado -->
            <div class="w-1/3">
              <InputLabel for="status" value="Estado" />
              <select
                id="status"
                v-model="form.status"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              >
                <option v-for="option in statusOptions"
                        :key="option.value"
                        :value="option.value">
                  {{ option.label }}
                </option>
              </select>
              <InputError :message="form.errors.status" class="mt-2" />
            </div>

            <!-- URL de la imagen -->
            <div class="w-1/3">
              <InputLabel for="url_image" value="URL de la imagen" />
              <TextInput
                id="url_image"
                v-model="form.url_image"
                type="url"
                class="mt-1 block w-full"
                placeholder="https://ejemplo.com/imagen.jpg"
              />
              <InputError :message="form.errors.url_image" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
              <PrimaryButton type="submit" :disabled="form.processing">
                Actualizar Rifa
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
