<script setup>
defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
  padding: { type: Boolean, default: true }
})
</script>

<template>
    <div
        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-colors">
        <!-- Card Header (Hanya muncul jika title atau slot header diisi) -->
        <div v-if="title || $slots.header || $slots.actions"
            class="p-5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-4">
            <div>
                <slot name="header">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ title }}</h3>
                    <p v-if="subtitle" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ subtitle }}</p>
                </slot>
            </div>

            <!-- Slot untuk tombol/aksi di kanan header -->
            <div v-if="$slots.actions" class="flex items-center gap-2">
                <slot name="actions" />
            </div>
        </div>

        <!-- Card Body -->
        <div :class="[padding ? 'p-5' : '']">
            <slot />
        </div>

        <!-- Card Footer (Opsional) -->
        <div v-if="$slots.footer"
            class="px-5 py-3 bg-slate-50/50 dark:bg-slate-950/40 border-t border-slate-100 dark:border-slate-800/80">
            <slot name="footer" />
        </div>
    </div>
</template>