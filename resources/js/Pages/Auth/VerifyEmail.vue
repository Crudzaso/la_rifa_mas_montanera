<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Logo from '@/Components/LogoText.vue';
import backgroundImage from '@/assets/images/background.png';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Verificar Email" />

    <div class="min-h-screen flex bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955]">
        <!-- Columna Izquierda - Contenido -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="text-center mb-8 lg:hidden">
                    <Logo colorClass="text-[#4F772D]" textColorClass="text-[#31572C]" class="w-48 mx-auto" />
                </div>

                <div class="space-y-6 bg-white/60 backdrop-blur-sm p-8 rounded-xl shadow-xl">
                    <h3 class="text-2xl font-bold text-[#31572C] mb-6">Verificar Email</h3>

                    <div class="text-gray-600">
                        Antes de continuar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.
                    </div>

                    <div v-if="verificationLinkSent" class="text-green-600 font-medium">
                        Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico proporcionada.
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <button type="submit"
                            class="w-full bg-[#4F772D] text-white px-6 py-3 rounded-lg hover:bg-[#31572C] transition-colors"
                            :disabled="form.processing">
                            Reenviar Email de Verificación
                        </button>

                        <div class="flex justify-between text-sm">
                            <Link :href="route('profile.show')"
                                class="text-[#4F772D] hover:text-[#31572C]">
                                Editar Perfil
                            </Link>

                            <Link :href="route('logout')" method="post" as="button"
                                class="text-[#4F772D] hover:text-[#31572C]">
                                Cerrar Sesión
                            </Link>
                        </div>
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
                <h2 class="text-3xl font-bold text-white mb-4">Verifica tu Email</h2>
                <p class="text-emerald-100 text-center max-w-md">
                    Para garantizar la seguridad de tu cuenta, necesitamos verificar tu dirección de correo electrónico.
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
