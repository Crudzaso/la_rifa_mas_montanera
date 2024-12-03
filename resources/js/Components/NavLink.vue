<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'relative inline-flex items-center px-4 py-2 text-base font-montserrat font-semibold text-[#132A13] group overflow-hidden transform hover:scale-102 transition-all duration-300'
        : 'relative inline-flex items-center px-4 py-2 text-base font-montserrat font-medium text-[#31572C]/80 group overflow-hidden transform hover:scale-102 transition-all duration-300';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <span class="relative z-10 flex items-center">
            <slot />

            <div
                :class="[
                    'ml-2 w-1.5 h-1.5 rounded-full transition-all duration-300 transform',
                    active
                        ? 'bg-[#4F772D] scale-100'
                        : 'bg-[#4F772D]/30 scale-0 group-hover:scale-100'
                ]"
            />
        </span>
        <div
            :class="[
                'absolute inset-0 transition-all duration-300',
                active
                    ? 'bg-gradient-to-r from-[#ECF39E]/20 to-transparent'
                    : 'bg-gradient-to-r from-[#ECF39E]/0 to-transparent group-hover:from-[#ECF39E]/10'
            ]"
        />
    </Link>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

.font-montserrat {
    font-family: 'Montserrat', sans-serif;
}

:focus {
    outline: none;
}

.hover\:scale-102:hover {
    transform: scale(1.280);
}

.group:hover .group-hover\:w-2\/3 {
    width: 66.666667%;
}

.group:hover {
    filter: drop-shadow(0 4px 6px rgba(79, 119, 45, 0.1));
}

.group:hover .group-hover\:scale-100 {
    transform: scale(1.2);
}

.group:hover .group-hover\:from-\[\#ECF39E\]\/10 {
    --tw-gradient-from: rgba(236, 243, 158, 0.2);
}
</style>
