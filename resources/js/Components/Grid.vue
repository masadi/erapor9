<script setup>
import { computed } from 'vue';

const props = defineProps({
  // Jumlah kolom pada Breakpoint Responsive
  cols: { type: [Number, String], default: 1 },       // Mobile (< 640px)
  colsSm: { type: [Number, String], default: null },  // Small (≥ 640px)
  colsMd: { type: [Number, String], default: null },  // Medium (≥ 768px)
  colsLg: { type: [Number, String], default: null },  // Large (≥ 1024px)
  colsXl: { type: [Number, String], default: null },  // Extra Large (≥ 1280px)
  
  // Ukuran Jarak / Gap (1 sampai 12)
  gap: { type: [Number, String], default: 5 },
});

// Map class Tailwind untuk grid-cols & gap agar terhindar dari dynamic class purging
const gridColsMap = {
  1: 'grid-cols-1', 2: 'grid-cols-2', 3: 'grid-cols-3', 4: 'grid-cols-4',
  5: 'grid-cols-5', 6: 'grid-cols-6', 12: 'grid-cols-12'
};

const smColsMap = {
  1: 'sm:grid-cols-1', 2: 'sm:grid-cols-2', 3: 'sm:grid-cols-3', 4: 'sm:grid-cols-4',
  6: 'sm:grid-cols-6', 12: 'sm:grid-cols-12'
};

const lgColsMap = {
  1: 'lg:grid-cols-1', 2: 'lg:grid-cols-2', 3: 'lg:grid-cols-3', 4: 'lg:grid-cols-4',
  6: 'lg:grid-cols-6', 12: 'lg:grid-cols-12'
};

const gapMap = {
  1: 'gap-1', 2: 'gap-2', 3: 'gap-3', 4: 'gap-4', 5: 'gap-5', 6: 'gap-6', 8: 'gap-8'
};

const gridClasses = computed(() => {
  return [
    'grid',
    gridColsMap[props.cols] || 'grid-cols-1',
    props.colsSm ? smColsMap[props.colsSm] : '',
    props.colsLg ? lgColsMap[props.colsLg] : '',
    gapMap[props.gap] || 'gap-5'
  ];
});
</script>

<template>
  <div :class="gridClasses">
    <slot />
  </div>
</template>