<script setup>
import { ref, onMounted } from 'vue';
import { usePermissions } from '@/Composables/usePermissions';
import Link from '@/Components/NavLink.vue';

const { hasRole } = usePermissions();

const props = defineProps({
  raffles: {
    type: Array,
    required: true
  },
  formatDate: {
    type: Function,
    required: true
  },
  getStatusTranslation: {
    type: Function,
    required: true
  },
  getStatusColor: {
    type: Function,
    required: true
  },
  activeTab: {
    type: String,
    required: true
  }
});

const isRafflesVisible = ref(false);

onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          isRafflesVisible.value = true;
        }
      });
    },
    { threshold: 0.1 }
  );

  const section = document.querySelector('.raffles-section');
  if (section) observer.observe(section);
});
</script>

<template>
  <!-- Lista de rifas -->
  <div class="py-12 raffles-section">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Mensaje cuando no hay rifas -->
        <div v-if="props.raffles.length === 0"
             class="bg-white/80 backdrop-blur-sm p-8 rounded-xl shadow-xl border border-[#4F772D]/20 text-center">
          <svg class="mx-auto h-12 w-12 text-[#4F772D] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 14h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-[#31572C] text-lg">
            {{
              activeTab === 'ongoing' ? 'No hay rifas en juego' :
              activeTab === 'pending' ? 'No hay rifas pendientes' :
              'No hay rifas finalizadas'
            }}
          </p>
        </div>

        <!-- Grid de rifas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 raffles-section"
             :class="{ 'is-visible': isRafflesVisible }">
          <div v-for="(raffle, index) in props.raffles"
               :key="raffle.id"
               class="bg-white/80 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden border border-[#4F772D]/20 transition-transform duration-300 ease-out hover:-translate-y-4 hover:shadow-2xl group"
               :style="{ '--index': index }"
          >
            <!-- Imagen con overlay -->
            <div class="relative overflow-hidden">
              <img v-if="raffle.url_image"
                   :src="raffle.url_image"
                   :alt="raffle.title"
                   class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"/>
              <div v-else class="w-full h-48 bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/30 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#4F772D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
              </div>
              <!-- Estado-->
              <span :class="[
                getStatusColor(raffle.status),
                'absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold shadow-md backdrop-blur-sm'
              ]">
                {{ getStatusTranslation(raffle.status) }}
              </span>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="p-6 space-y-4">
              <h3 class="text-xl font-bold text-[#31572C] group-hover/card:text-[#4F772D] transition-colors duration-300">
                {{ raffle.title }}
              </h3>

              <!-- Premio-->
              <div class="bg-gradient-to-br from-[#4F772D]/10 to-[#90A955]/30 p-4 rounded-xl transition-all duration-300 group-hover/card:from-[#4F772D]/20 group-hover/card:to-[#90A955]/40">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#4F772D] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <div>
                    <p class="text-sm text-[#4F772D] font-medium">Premio</p>
                    <p class="text-lg font-bold text-[#31572C]">{{ raffle.prize }}</p>
                  </div>
                </div>
              </div>

              <!-- Grid de información -->
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-[#4F772D]/5 to-[#90A955]/10 p-4 rounded-xl transition-all duration-300 group-hover/card:from-[#4F772D]/10 group-hover/card:to-[#90A955]/20">
                  <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#4F772D] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <p class="text-sm text-[#4F772D]">Boletos</p>
                  </div>
                  <p class="text-lg font-bold text-[#31572C]">{{ raffle.tickets_sold }}/{{ raffle.total_tickets }}</p>
                </div>

                <div class="bg-gradient-to-br from-[#4F772D]/5 to-[#90A955]/10 p-4 rounded-xl transition-all duration-300 group-hover/card:from-[#4F772D]/10 group-hover/card:to-[#90A955]/20">
                  <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#4F772D] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-[#4F772D]">Precio</p>
                  </div>
                  <p class="text-lg font-bold text-[#31572C]">${{ raffle.price_tickets }}</p>
                </div>
              </div>

              <!-- Fechas -->
              <div class="space-y-2 pt-2">
                <div class="flex items-center text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#4F772D] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span class="text-[#4F772D]">Juega: {{ formatDate(raffle.end_date) }}</span>
                </div>
              </div>

              <!-- Botones -->
              <div class="flex justify-end items-center gap-4 pt-4">
                <Link v-if="hasRole(['admin', 'organizador'])"
                      :href="route('raffles.edit', raffle.id)"
                      class="flex items-center px-4 py-2 bg-[#4F772D]/10 text-[#31572C] rounded-lg hover:bg-[#4F772D]/20 transition-all duration-300">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                  </svg>
                  Editar
                </Link>
                <Link v-if="raffle.status === 'ongoing'"
                      :href="route('tickets.create', { raffle_id: raffle.id })"
                      class="flex items-center px-6 py-2 bg-[#4F772D] text-white rounded-lg hover:bg-[#31572C] transition-all duration-300">
                  Comprar
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>