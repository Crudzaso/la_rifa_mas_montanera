<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from 'vue';

const page = usePage();
const user = ref(page.props.user);

const showMessage = ref(false);
const form = useForm({
  name: user.value.name,
  email: user.value.email,
  password: ''
});

const submit = () => {
  form.put(route('users.update', user.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showMessage.value = true;
      setTimeout(() => {
        window.location.href = route('users.index');
      }, 3000);
    },
    onError: (errors) => {
      console.error('Errores:', errors);
    }
  });
};

</script>

<template>
  <AppLayout title="Editar usuario">
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Editar usuario
        </h2>
        <Link :href="route('users.index')"
        class="inline-flex items-center px-6 py-4 bg-indigo-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Listar Usuarios </Link>
      </div>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="text-center py-4">
            <h1 class="text-2xl font-semibold text-gray-800 leading-tight">
              Editar Usuario
            </h1>
          </div>
          <!-- Agregar el mensaje después del título -->
          <div v-if="showMessage" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline"> Usuario actualizado correctamente.</span>
            <span class="block text-sm">Redirigiendo en 3 segundos...</span>
          </div>
          <form class="w-1/2 py-12 px-12 space-y-3" @submit.prevent="submit">
            <div>
              <InputLabel for="name" value="" />
              <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                placeholder="Nombre"
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4 py-8">
              <InputLabel for="email" value="" />
              <TextInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                placeholder="Correo"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 py-2">
              <InputLabel for="password" value="" />
              <TextInput
                id="password"
                v-model="form.password"
                type="password"
                class="mt-1 block w-full"
                autfocus
                placeholder="Contraseña"
              />
              <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="flex justify-center">
              <PrimaryButton class="mt-8" type="submit">
                Actualizar contacto
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

