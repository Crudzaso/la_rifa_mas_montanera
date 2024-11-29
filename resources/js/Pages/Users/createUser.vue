<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Link from "@/Components/NavLink.vue";

import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const initialValues = {
  name: "",
  email: "",
  password: "",
};

const form = useForm(initialValues);

const submit = (e) => {
    e.preventDefault();
  form.post(route("users.store"));
};
</script>

<template>
  <AppLayout title="Crear usuario">
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Crear usuarios
        </h2>
        <Link :href="route('users.index')"> Listar Usuarios </Link>
      </div>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="text-center py-4">
            <h1 class="text-2xl font-semibold text-gray-800 leading-tight">
              Crear Usuario
            </h1>
          </div>
          <form class="py-12 px-12" @submit="submit">
            <div>
              <InputLabel for="name" value="" />
              <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                autfocus
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
                autfocus
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
                Crear Usuario
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

