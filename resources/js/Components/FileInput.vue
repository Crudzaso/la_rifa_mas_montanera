<script setup>
import { ref } from 'vue';

defineProps({
    modelValue: File | null, // El archivo que se selecciona
});

defineEmits(['update:modelValue']); // Emitir el evento con el archivo seleccionado

const input = ref(null);

// Exponer el método 'focus' para que el componente padre pueda acceder a él si es necesario
defineExpose({ focus: () => input.value.focus() });

// Manejar el cambio de archivo
const handleFileChange = (event) => {
    const file = event.target.files[0]; // Tomamos solo el primer archivo seleccionado
    if (file) {
        $emit('update:modelValue', file); // Emitimos el archivo al componente padre
    }
};
</script>

<template>
    <input
        ref="input"
        type="file"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @change="handleFileChange"
    />
</template>
