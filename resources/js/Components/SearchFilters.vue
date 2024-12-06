<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  activeTab: String,
  tabStates: Array,
  getStatusTranslation: Function
});

const emit = defineEmits(['update:searchQuery', 'update:searchType', 'tab-change']);

const searchQuery = ref('');
const searchType = ref('title');

watch(searchQuery, (value) => {
  emit('update:searchQuery', value);
});

watch(searchType, (value) => {
  emit('update:searchType', value);
});

const changeTab = (tab) => {
  emit('tab-change', tab);
};
</script>

<template>
  <!-- Barra de búsqueda y filtros -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="flex flex-col md:flex-row gap-4 mb-6">
      <div class="flex-1 relative">
        <input
          v-model="searchQuery"
          type="text"
          :placeholder="searchType === 'title' ? 'Buscar por nombre...' : 'Buscar por premio...'"
          class="w-full px-4 py-3 rounded-xl border border-[#4F772D]/20 focus:ring-2 focus:ring-[#4F772D]/50 focus:border-[#4F772D] shadow-sm bg-white/80 backdrop-blur-sm"
        />
      </div>
      <select
        v-model="searchType"
        class="px-8 py-3 rounded-xl border border-[#4F772D]/20 focus:ring-2 focus:ring-[#4F772D]/50 focus:border-[#4F772D] shadow-sm bg-white/80 backdrop-blur-sm text-[#31572C]"
      >
        <option value="title">Buscar por nombre</option>
        <option value="prize">Buscar por premio</option>
      </select>
    </div>

    <!-- Pestañas -->
    <div class="flex justify-center mb-8">
      <nav class="flex space-x-2 bg-white/50 backdrop-blur-sm p-1 rounded-xl shadow-md border border-[#4F772D]/20">
        <button
          v-for="tab in tabStates"
          :key="tab"
          @click="changeTab(tab)"
          class="px-6 py-2.5 rounded-lg font-medium text-sm transition-all duration-200"
          :class="[
            activeTab === tab
              ? 'bg-[#4F772D] text-white shadow-sm'
              : 'text-[#31572C] hover:bg-[#4F772D]/10'
          ]"
        >
          {{ props.getStatusTranslation(tab) }}
        </button>
      </nav>
    </div>
  </div>
</template>