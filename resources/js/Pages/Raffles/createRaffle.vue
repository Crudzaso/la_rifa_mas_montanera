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
</script>

<template>
  <AppLayout title="Crear Rifa">
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Crear Rifa
        </h2>
        <Link
          :href="route('raffles.index')"
          class="inline-flex items-center px-6 py-4 bg-indigo-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
          Ver rifas
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="text-center py-4">
            <h1 class="text-2xl font-semibold text-gray-800 leading-tight">
              Crear Rifa
            </h1>
          </div>

          <Alert
            v-if="showMessage"
            :message="message"
            :type="type"
          />

          <form class="w-1/2 py-12 px-12 space-y-3" @submit.prevent="submit">
            <!-- Title input -->
            <div>
              <InputLabel for="title" value="Título" />
              <TextInput
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full"
                placeholder="Titulo de la rifa"
              />
              <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <!-- Description input -->
            <div>
              <InputLabel for="description" value="Descripción" />
              <TextInput
                id="description"
                v-model="form.description"
                type="text"
                class="mt-1 block w-full"
                placeholder="Descripción de la rifa"
              />
              <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <!-- Prize input -->
            <div>
              <InputLabel for="prize" value="Premio" />
              <TextInput
                id="prize"
                v-model="form.prize"
                type="text"
                class="mt-1 block w-full"
                placeholder="Descripción del premio de la rifa ya sea en efectivo o en especie"
              />
              <InputError class="mt-2" :message="form.errors.prize" />
            </div>

            <!-- Start date -->
            <div>
              <InputLabel for="start_date" value="Fecha de inicio" />
              <DateInput
                id="start_date"
                v-model="form.start_date"
                type="date"
                class="mt-1 block w-full"
              />
              <InputError class="mt-2" :message="form.errors.start_date" />
            </div>

            <!-- End date -->
            <div>
              <InputLabel for="end_date" value="Fecha de finalización" />
              <DateInput
                id="end_date"
                v-model="form.end_date"
                type="date"
                class="mt-1 block w-full"
              />
              <InputError class="mt-2" :message="form.errors.end_date" />
            </div>

            <!-- Ticket price (select) -->
            <div>
              <InputLabel
                for="price_tickets"
                value="Precio del boleto en pesos"
              />
              <select
                id="price_tickets"
                v-model="form.price_tickets"
                class="mt-1 block w-full"
              >
                <option value="" disabled selected>
                  Seleccione un precio para la boleta en pesos
                </option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
                <option value="20000">20000</option>
                <option value="50000">50000</option>
              </select>
              <InputError class="mt-2" :message="form.errors.price_tickets" />
            </div>
            <!-- Image input -->
            <div>
              <InputLabel for="url_image" value="Imagen de la rifa (URL)" />
              <TextInput
                id="url_image"
                v-model="form.url_image"
                type="text"
                class="mt-1 block w-full"
                placeholder="Ingrese la URL de la imagen"
              />
              <InputError class="mt-2" :message="form.errors.url_image" />
            </div>

            <!-- Submit button -->
            <div class="flex justify-center">
              <PrimaryButton class="mt-8" type="submit">
                Crear Rifa
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
