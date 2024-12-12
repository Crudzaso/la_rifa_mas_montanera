<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Logo from '@/Components/LogoText.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import backgroundImage from '@/assets/images/background.png';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Recuperar Contraseña" />

    <div class="min-h-screen flex bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955]">
        <!-- Columna Izquierda - Formulario -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="text-center mb-8 lg:hidden">
                    <Logo colorClass="text-[#4F772D]" textColorClass="text-[#31572C]" class="w-48 mx-auto" />
                </div>

                <div class="space-y-6 bg-white/60 backdrop-blur-sm p-8 rounded-xl shadow-xl">
                    <h3 class="text-2xl font-bold text-[#31572C] mb-6">Recuperar Contraseña</h3>

                    <div class="text-gray-600 text-sm mb-6">
                        ¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                    </div>

                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
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

                        <button type="submit"
                            class="w-full bg-[#4F772D] text-white px-6 py-3 rounded-lg hover:bg-[#31572C] transition-colors"
                            :disabled="form.processing">
                            Enviar enlace de recuperación
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna Derecha - Logo -->
        <div class="hidden lg:flex lg:w-1/2 relative">
            <div class="absolute inset-0 bg-gradient-to-br from-[#101d0ebe] to-[#132a13d8]">
                <div class="absolute inset-0 mix-blend-overlay opacity-5 backdrop-blur-xl"
                    :style="{
                        backgroundImage: `url(${backgroundImage})`,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center'
                    }">
                </div>
            </div>
            <div class="relative z-10 flex flex-col justify-center items-center w-full p-12">
                <Logo colorClass="text-white" textColorClass="text-white" class="w-64 mb-8" />
                <h2 class="text-3xl font-bold text-white mb-4">¿Olvidaste tu contraseña?</h2>
                <p class="text-emerald-100 text-center max-w-md">
                    Te enviaremos un enlace a tu correo electrónico para que puedas restablecer tu contraseña de forma segura.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

.backdrop-blur-xl {
    backdrop-filter: blur(24px);
}
</style>
