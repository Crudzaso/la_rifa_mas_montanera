<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

const loading = ref(true);
const error = ref(null);
const results = ref([]);
const selectedDate = ref(new Date().toISOString().split('T')[0]);

const antioquenita = computed(() => {
    return results.value.find(r => r.lottery === 'ANTIOQUEÑITA TARDE') || {
        lottery: 'ANTIOQUEÑITA TARDE',
        date: '',
        result: 'Sin resultados',
        slug: 'antioquenita-tarde'
    };
});

const otherResults = computed(() => {
    return results.value.filter(r => r.lottery !== 'ANTIOQUEÑITA TARDE');
});

const fetchResults = async (date = null) => {
    loading.value = true;
    try {
        const url = date
            ? `https://api-resultadosloterias.com/api/results/${date}`
            : '/api/loteria/resultados';

        const response = await axios.get(url, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (response.status === 200 && response.data?.data) {
            results.value = response.data.data;
        } else {
            error.value = "No hay resultados disponibles para esta fecha.";
            results.value = [];
        }
    } catch (e) {
        console.error('Error:', e);
        error.value = "Error al cargar los resultados.";
        results.value = [];
    } finally {
        loading.value = false;
    }
};

const handleDateChange = async (event) => {
    const date = event.target.value;
    selectedDate.value = date;
    await fetchResults(date);
};

onMounted(() => {
    fetchResults(selectedDate.value);
});

const formatDate = (date) => {
    if (!date) return 'Sin fecha';
    const fecha = new Date(date);
    fecha.setMinutes(fecha.getMinutes() + fecha.getTimezoneOffset());

    return fecha.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'UTC'
    });
};
</script>

<template>
    <AppLayout title="Loterias">
        <template #header>
            <h2 class="font-semibold text-2xl text-[#31572C] leading-tight text-center flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Resultados del <span class="text-[#4F772D] font-bold ml-1">{{ formatDate(selectedDate) }}</span>
            </h2>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955]">
            <div class="py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <!-- Contenedor flex para Antioqueñita y selector de fecha -->
                    <div class="flex flex-col md:flex-row justify-center items-start mb-8">
                        <!-- Tarjeta Antioqueñita -->
                        <div class="flex justify-center md:w-2/3 lg:w-1/2 mx-auto">
                            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 w-full border border-[#4F772D]/20">
                                <h3 class="text-2xl font-bold text-[#31572C] mb-6 text-center flex items-center justify-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                    {{ antioquenita.lottery }}
                                </h3>
                                <div class="text-center bg-[#4F772D]/10 backdrop-blur-sm rounded-lg p-6">
                                    <p class="text-6xl font-bold text-[#4F772D] mb-3">
                                        {{ antioquenita.result }}
                                    </p>
                                    <p class="text-sm font-medium text-[#31572C]">Resultado Oficial</p>
                                </div>
                                <div class="text-center mt-4">
                                    <p class="text-[#4F772D] font-bold hover:text-[#31572C] transition-all duration-300">
                                        de lunes a sabado a las 4:05 pm
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Selector de fecha -->
                        <div class="bg-white/80 backdrop-blur-sm rounded-lg p-6 shadow-xl border border-[#4F772D]/20 w-full md:w-1/3 lg:w-2/12">
                            <h3 class="text-lg font-bold text-[#31572C] mb-2 text-center">
                                Consultar resultados pasados
                            </h3>
                            <div class="flex flex-col gap-2">
                                <input
                                    type="date"
                                    :value="selectedDate"
                                    @change="handleDateChange"
                                    class="px-2 py-2 rounded-lg bg-white/90 border border-[#4F772D]/20 text-[#31572C] focus:outline-none focus:ring-2 focus:ring-[#4F772D]/50"
                                />
                                <p class="text-sm text-[#31572C]/70 text-center">
                                    Selecciona una fecha para ver resultados anteriores
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de Resultados -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-xl p-8 border border-[#4F772D]/20">
                        <div v-if="loading" class="text-center py-8">
                            <div class="animate-spin h-10 w-10 mx-auto border-4 border-[#4F772D] border-t-transparent rounded-full"></div>
                        </div>

                        <div v-else-if="otherResults.length > 0"
                             class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <div v-for="result in otherResults"
                                 :key="result.slug"
                                 class="bg-[#4F772D]/5 backdrop-blur-sm rounded-lg p-6 text-center hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:bg-[#4F772D]/10 border border-[#4F772D]/10">
                                <h3 class="text-sm font-bold text-[#31572C] mb-3">
                                    {{ result.lottery }}
                                </h3>
                                <p class="text-3xl font-bold text-[#4F772D]">
                                    {{ result.result }}
                                </p>
                            </div>
                        </div>

                        <div v-else class="text-center py-8 text-[#31572C]">
                            {{ error || "No hay resultados disponibles en este momento." }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

.font-montserrat {
  font-family: 'Montserrat', sans-serif;
}
</style>
