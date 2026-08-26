<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { IconTool as Wrench, IconRefresh as RefreshCw, IconClock as Clock, IconShieldExclamation as ShieldAlert } from '@tabler/icons-vue';

const isRefreshing = ref(false);

const checkSystemStatus = () => {
  isRefreshing.value = true;
  setTimeout(() => {
    window.location.reload();
  }, 800);
};
</script>

<template>
  <Head title="503 - Sistem Dalam Pemeliharaan" />

  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden text-slate-100">
    
    <!-- Ambient Glow Decorators -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Main Card Container -->
    <div class="max-w-xl w-full bg-slate-800/60 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-8 sm:p-12 text-center shadow-2xl relative z-10">
      
      <!-- Live Status Badge -->
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-400 font-semibold text-xs tracking-wide mb-6">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
        </span>
        <span>Pemeliharaan Sistem Sedang Berlangsung</span>
      </div>

      <!-- Icon Graphic -->
      <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center">
        <div class="absolute inset-0 bg-amber-500/10 rounded-3xl rotate-6"></div>
        <div class="relative w-20 h-20 bg-slate-800 rounded-2xl flex items-center justify-center border border-slate-700 shadow-xl">
          <Wrench class="w-10 h-10 text-amber-400 animate-bounce" />
        </div>
      </div>

      <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-3">
        Sistem Sedang Diperbarui
      </h1>

      <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-md mx-auto">
        Aplikasi {{ $page.props.appName || 'Laravel' }} saat ini sedang dalam perawatan rutin untuk peningkatan performa dan keamanan. Kami akan segera kembali online.
      </p>

      <!-- Estimation Info Card -->
      <div class="bg-slate-900/50 border border-slate-700/40 rounded-2xl p-4 mb-8 flex items-center justify-center gap-3 text-xs text-slate-300">
        <Clock class="w-4 h-4 text-amber-400 shrink-0" />
        <span>Perkiraan selesai: <strong class="text-white font-semibold">15 - 30 Menit</strong></span>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button
          @click="checkSystemStatus"
          :disabled="isRefreshing"
          class="w-full sm:w-auto px-6 py-3 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-slate-950 font-bold rounded-xl shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2 transition-all text-sm disabled:opacity-70"
        >
          <RefreshCw :class="['w-4 h-4', isRefreshing ? 'animate-spin' : '']" />
          <span>Cek Status Sekarang</span>
        </button>
      </div>

      <!-- Footer Info -->
      <div class="mt-8 pt-6 border-t border-slate-700/50 flex items-center justify-center gap-2 text-xs text-slate-500">
        <ShieldAlert class="w-4 h-4 text-slate-400" />
        <span>{{ $page.props.appName || 'Laravel' }} Maintenance Service - HTTP 503</span>
      </div>

    </div>
  </div>
</template>