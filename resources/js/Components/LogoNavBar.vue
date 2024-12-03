<!-- Components/LogoNavBar.vue -->
<script setup>
import { Link } from '@inertiajs/vue3';
import logoImage from '@/assets/images/logo.png';

const props = defineProps({
  colorClass: {
    type: String,
    default: 'text-[#4F772D]'
  },
  textColorClass: {
    type: String,
    default: 'text-[#31572C]'
  }
});

</script>

<template>
  <Link
    :href="route('dashboard')"
    class="flex items-center group hover:opacity-90 transition-all duration-200"
  >
    <img
      :src="logoImage"
      alt="Logo La Rifa Montañera"
      class="w-20 h-18 object-contain transform group-hover:scale-105 transition-transform duration-200"
    />
  </Link>
</template>
