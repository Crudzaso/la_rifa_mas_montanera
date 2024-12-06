<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String, // El valor de la fecha, debe ser una cadena (formato 'YYYY-MM-DD')
});

defineEmits(['update:modelValue']); // Emitir evento para actualizar el valor de la fecha

const input = ref(null);

// Foco automático en el input si tiene el atributo 'autofocus'
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

// Exponer el método 'focus' para acceder a él desde el componente padre
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        type="date"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
