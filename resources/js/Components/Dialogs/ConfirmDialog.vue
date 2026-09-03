<script setup>
import { watch, onUnmounted } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Konfirmasi Perubahan',
  },
  subtitle: {
    type: String,
    default: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
  },
  cancelText: {
    type: String,
    default: 'Batal',
  },
  confirmText: {
    type: String,
    default: 'Yakin',
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'confirm']);

// Logic default tombol Batal: Menutup dialog
const handleCancel = () => {
  if (!props.loading) {
    emit('close');
  }
};

// Logic tombol Yakin: Menjalankan event custom @confirm
const handleConfirm = () => {
  emit('confirm');
};

// Lock body scroll saat dialog muncul
watch(
  () => props.show,
  (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
  }
);

onUnmounted(() => {
  document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <Transition enter-active-class="transition opacity-100 duration-200 ease-out" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="transition opacity-100 duration-150 ease-in"
            leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <!-- Backdrop Overlay -->
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="handleCancel" />

                <!-- Dialog Container -->
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <Transition enter-active-class="transition duration-200 ease-out transform"
                        enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in transform"
                        leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
                        <div v-if="show"
                            class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800">
                            <!-- Icon Perhatian -->
                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>

                            <!-- Content: Title & Subtitle -->
                            <div class="mt-4 text-center">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                                    {{ title }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ subtitle }}
                                </p>
                            </div>

                            <!-- Actions Buttons -->
                            <div class="mt-6 flex items-center justify-end gap-3">
                                <!-- Tombol Batal (Default: Tutup Dialog) -->
                                <button type="button" :disabled="loading" @click="handleCancel"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                    {{ cancelText }}
                                </button>

                                <!-- Tombol Yakin (Menjalankan Event Confirm) -->
                                <button type="button" :disabled="loading" @click="handleConfirm"
                                    class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50">
                                    <svg v-if="loading" class="-ml-1 mr-2 h-4 w-4 animate-spin text-white" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ confirmText }}
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>