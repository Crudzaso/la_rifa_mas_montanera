<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

const loading = ref(true);
const error = ref(null);
const results = ref([]);

// Obtener los resultados de la API
const fetchResults = async () => {

    const response = await axios.get('/api/loteria/resultados');
    console.log('Estado de la respuesta HTTP:', response.status);
    console.log('Respuesta completa:', response);
    if (response.status === 200 && response.data && response.data.data && response.data.data.length > 0) {
        results.value = response.data.data;
    } else {

        results.value = [];
        error.value = response.data.message || "No hay resultados disponibles en este momento.";
    }

    loading.value = false;
};

onMounted(() => {
    fetchResults();
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Resultados de Lotería
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Cargando -->
                    <div v-if="loading" class="text-center py-4">
                        <div class="animate-spin h-8 w-8 mx-auto border-4 border-[#4F772D] border-t-transparent rounded-full"></div>
                    </div>

                    <!-- Resultados -->
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="result in results" :key="result.slug" class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                            <h3 class="text-xl font-bold text-[#31572C] mb-3">
                                {{ result.lottery }}
                            </h3>
                            <div class="space-y-2">
                                <p class="text-3xl font-bold text-[#4F772D]">
                                    {{ result.result }}
                                </p>
                                <p v-if="result.series" class="text-sm text-gray-600">
                                    Serie: {{ result.series }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ formatDate(result.date) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje en caso de no haber resultados -->
                    <div v-if="!loading && results.length === 0" class="text-center py-4 text-gray-600">
                        No hay resultados disponibles en este momento.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
