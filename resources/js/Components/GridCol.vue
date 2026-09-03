<script setup>
import { computed } from 'vue';

const props = defineProps({
  // Span kolom (1 sampai 12)
  span: { type: [Number, String], default: 1 },
  spanSm: { type: [Number, String], default: null },
  spanLg: { type: [Number, String], default: null },
});

const spanMap = {
  1: 'col-span-1', 2: 'col-span-2', 3: 'col-span-3', 4: 'col-span-4',
  5: 'col-span-5', 6: 'col-span-6', 8: 'col-span-8', 12: 'col-span-12'
};

const smSpanMap = {
  1: 'sm:col-span-1', 2: 'sm:col-span-2', 3: 'sm:col-span-3', 4: 'sm:col-span-4',
  6: 'sm:col-span-6', 8: 'sm:col-span-8', 12: 'sm:col-span-12'
};

const lgSpanMap = {
  1: 'lg:col-span-1', 2: 'lg:col-span-2', 3: 'lg:col-span-3', 4: 'lg:col-span-4',
  6: 'lg:col-span-6', 8: 'lg:col-span-8', 12: 'lg:col-span-12'
};

const colClasses = computed(() => {
  return [
    spanMap[props.span] || 'col-span-1',
    props.spanSm ? smSpanMap[props.spanSm] : '',
    props.spanLg ? lgSpanMap[props.spanLg] : ''
  ];
});
</script>

<template>
  <div :class="colClasses">
    <slot />
  </div>
</template>