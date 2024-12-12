<template>
  <div class="flex items-center">
    <img
      :src="logoImage"
      alt="La Rifa Montañera"
      class="w-16 h-18"
    />
    <span :class="['ml-3 text-2xl font-bold font-montserrat', textColorClass]">
      La Rifa Mas Montañera
    </span>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import logoImage from '@/assets/images/logo.png';

defineProps({
  colorClass: {
    type: String,
    default: 'text-[#31572C]'
  },
  textColorClass: {
    type: String,
    default: 'text-[#132A13]'
  }
});
</script>
