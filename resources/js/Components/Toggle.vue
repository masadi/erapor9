<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  label: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  size: {
    type: String,
    default: 'md',
    validator: (val) => ['sm', 'md', 'lg'].includes(val),
  },
  // Dynamic Label Text
  showStatusLabel: {
    type: Boolean,
    default: false,
  },
  onLabel: {
    type: String,
    default: 'ON',
  },
  offLabel: {
    type: String,
    default: 'OFF',
  },
  // Prop opsional jika ingin pass handler langsung via prop
  onChange: {
    type: Function,
    default: null,
  },
});

const emit = defineEmits(['update:modelValue', 'change']);

const toggle = () => {
  if (props.disabled) return;

  const newValue = !props.modelValue;
  
  // 1. Update state v-model
  emit('update:modelValue', newValue);
  
  // 2. Emit event @change
  emit('change', newValue, event);

  // 3. Jalankan callback onChange jika dikirim via prop
  if (typeof props.onChange === 'function') {
    props.onChange(newValue, event);
  }
};

const trackSizes = {
  sm: 'h-4 w-7',
  md: 'h-6 w-11',
  lg: 'h-8 w-14',
};

const thumbSizes = {
  sm: 'h-3 w-3',
  md: 'h-5 w-5',
  lg: 'h-7 w-7',
};

const translateSizes = {
  sm: 'translate-x-3',
  md: 'translate-x-5',
  lg: 'translate-x-6',
};

const labelSizes = {
  sm: 'text-xs',
  md: 'text-sm',
  lg: 'text-base',
};

</script>

<template>
  <label
    class="inline-flex items-center gap-3 select-none"
    :class="{ 'cursor-pointer': !disabled, 'cursor-not-allowed opacity-60': disabled }"
  >
    <!-- Switch Container -->
    <button
      type="button"
      role="switch"
      :aria-checked="modelValue"
      :disabled="disabled"
      @click="toggle"
      :class="[
        'relative inline-flex shrink-0 rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
        trackSizes[size],
        modelValue ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700'
      ]"
    >
      <!-- Sliding Circle -->
      <span
        :class="[
          'pointer-events-none inline-block transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
          thumbSizes[size],
          modelValue ? translateSizes[size] : 'translate-x-0'
        ]"
      />
    </button>

    <!-- Label Utama & Status Dinamis -->
    <span 
      v-if="label || showStatusLabel" 
      :class="[
        'font-medium text-gray-900 dark:text-gray-100 flex items-center gap-1.5',
        labelSizes[size]
      ]"
    >
      <span v-if="label">{{ label }}</span>
      
      <!-- Badges Status Dinamis -->
      <span 
        v-if="showStatusLabel"
        :class="[
          'font-semibold transition-colors duration-150',
          modelValue ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500'
        ]"
      >
        {{ modelValue ? onLabel : offLabel }}
      </span>
    </span>
  </label>
</template>