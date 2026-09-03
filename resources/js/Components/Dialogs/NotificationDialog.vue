<script setup>
import { watch, onUnmounted } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'success', // 'success' atau 'error'
    validator: (val) => ['success', 'error'].includes(val),
  },
  title: {
    type: String,
    default: '',
  },
  subtitle: {
    type: String,
    default: '',
  },
  buttonText: {
    type: String,
    default: 'Tutup',
  },
});

const emit = defineEmits(['close']);

// Konfigurasi Warna & Icon berdasarkan Tipe ('success' / 'error')
const themeConfig = {
  success: {
    defaultTitle: 'Berhasil!',
    defaultSubtitle: 'Tindakan Anda telah berhasil diproses.',
    bgColor: 'bg-emerald-100 dark:bg-emerald-900/30',
    iconColor: 'text-emerald-600 dark:text-emerald-400',
    btnColor: 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500',
  },
  error: {
    defaultTitle: 'Gagal!',
    defaultSubtitle: 'Terjadi kesalahan saat memproses permintaan Anda.',
    bgColor: 'bg-rose-100 dark:bg-rose-900/30',
    iconColor: 'text-rose-600 dark:text-rose-400',
    btnColor: 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500',
  },
};

// Lock body scroll saat dialog aktif
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
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="emit('close')" />

                <!-- Dialog Container -->
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <Transition enter-active-class="transition duration-200 ease-out transform"
                        enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in transform"
                        leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
                        <div v-if="show"
                            class="relative w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800">
                            <!-- Icon Sesuai Tipe -->
                            <div :class="[
                                'mx-auto flex h-14 w-14 items-center justify-center rounded-full',
                                themeConfig[type].bgColor,
                                themeConfig[type].iconColor
                            ]">
                                <!-- Icon Success -->
                                <svg v-if="type === 'success'" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                <!-- Icon Error -->
                                <svg v-else class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>

                            <!-- Content: Custom Title & Subtitle -->
                            <div class="mt-4 text-center">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                                    {{ title || themeConfig[type].defaultTitle }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ subtitle || themeConfig[type].defaultSubtitle }}
                                </p>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-6">
                                <button type="button" @click="emit('close')" :class="[
                                    'w-full rounded-lg px-4 py-2.5 text-sm font-medium text-white shadow focus:outline-none focus:ring-2 focus:ring-offset-2',
                                    themeConfig[type].btnColor
                                ]">
                                    {{ buttonText }}
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>