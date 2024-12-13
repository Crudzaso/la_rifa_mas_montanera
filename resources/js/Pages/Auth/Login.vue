<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Logo from '@/Components/LogoText.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import backgroundImage from '@/assets/images/background.png';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div class="min-h-screen flex bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955]">
        <!-- Columna Izquierda - Logo y Mensaje -->
        <div class="hidden lg:flex lg:w-1/2 relative">
            <div class="absolute inset-0 bg-gradient-to-br from-[#101d0ebe] to-[#132a13d8]">
                <div
                    class="absolute inset-0 mix-blend-overlay opacity-10"
                    :style="{
                        backgroundImage: `url(${backgroundImage})`,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center'
                    }"
                ></div>
            </div>
            <div class="relative z-10 flex flex-col justify-center items-center w-full p-12">
                <Logo colorClass="text-white" textColorClass="text-white" class="w-64 mb-8" />
                <h2 class="text-3xl font-bold text-white mb-4">¡Bienvenido de vuelta!</h2>
                <p class="text-emerald-100 text-center max-w-md">
                    Accede a tu cuenta para participar en las mejores rifas y ganar increíbles premios.
                </p>
            </div>
        </div>

        <!-- Columna Derecha - Formulario -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="text-center mb-8 lg:hidden">
                    <Logo colorClass="text-[#4F772D]" textColorClass="text-[#31572C]" class="w-48 mx-auto" />
                </div>

                <form @submit.prevent="submit" class="space-y-6 bg-white/50 backdrop-blur-md p-8 rounded-xl shadow-xl">
                    <h3 class="text-2xl font-bold text-[#31572C] mb-6">Iniciar Sesión</h3>

                    <div>
                        <InputLabel for="email" value="Correo electrónico" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Contraseña" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Botones de login social -->
                    <div class="space-y-3">
                        <a :href="(route('auth.google'))"
                            class="w-full flex items-center justify-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-lg border hover:bg-gray-50 transition">
                            <img src="https://www.google.com/favicon.ico" class="w-5 h-5" alt="Google" />
                            Continuar con Google
                        </a>
                        <a :href="(route('auth.github'))"
                            class="w-full flex items-center justify-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                            Continuar con GitHub
                        </a>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="text-sm text-[#4F772D] hover:text-[#31572C]">
                            ¿Olvidaste tu contraseña?
                        </Link>
                        <button type="submit"
                            class="bg-[#4F772D] text-white px-6 py-2 rounded-lg hover:bg-[#31572C] transition-colors"
                            :disabled="form.processing">
                            Ingresar
                        </button>
                    </div>
                </form>

                <p class="mt-6 text-center text-gray-600">
                    ¿No tienes cuenta?
                    <Link :href="route('register')" class="text-[#4F772D] hover:text-[#31572C]">
                        Regístrate aquí
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}
</style>
