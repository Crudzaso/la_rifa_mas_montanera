<template>
  <div class="fixed top-4 right-4 z-50">
    <div :class="[
      'text-center py-4 px-4 rounded-lg shadow-lg transform transition-all duration-300',
      typeClasses[type].bg
    ]">
      <div class="p-2 items-center leading-none rounded-full flex lg:inline-flex" role="alert">
        <span :class="[
          'flex rounded-full px-2 py-1 text-xs font-bold mr-3',
          typeClasses[type].badge
        ]">{{ type }}</span>
        <span class="font-semibold mr-2 text-left flex-auto text-white">{{ message }}</span>
        <svg class="fill-current text-white opacity-75 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"/>
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  message: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'info', 'warning'].includes(value)
  }
});

const typeClasses = {
  success: {
    bg: 'bg-green-600',
    badge: 'bg-green-800 text-white'
  },
  error: {
    bg: 'bg-red-600',
    badge: 'bg-red-800 text-white'
  },
  info: {
    bg: 'bg-blue-600',
    badge: 'bg-blue-800 text-white'
  },
  warning: {
    bg: 'bg-yellow-600',
    badge: 'bg-yellow-800 text-white'
  }
};
</script>
