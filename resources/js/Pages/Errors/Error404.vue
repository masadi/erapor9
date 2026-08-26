<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { IconArrowLeft as ArrowLeft, IconLayoutDashboard as LayoutDashboard, IconSearchOff as SearchX, IconHelpCircle as HelpCircle } from '@tabler/icons-vue';

// State untuk efek paralaks / tracking kursor mouse
const mouseX = ref(0);
const mouseY = ref(0);

const handleMouseMove = (event) => {
  // Menghitung posisi kursor relatif terhadap tengah layar (-1 sampai 1)
  mouseX.value = (event.clientX / window.innerWidth - 0.5) * 20;
  mouseY.value = (event.clientY / window.innerHeight - 0.5) * 20;
};

const goBack = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    window.location.href = route('dashboard');
  }
};

onMounted(() => {
  window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
  window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<template>
  <Head title="404 - Halaman Tidak Ditemukan" />

  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden text-slate-100">
    
    <!-- Background Decorator Spheres (Parallax Effect) -->
    <div 
      class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl transition-transform duration-100 ease-out pointer-events-none"
      :style="{ transform: `translate(${mouseX * -1.5}px, ${mouseY * -1.5}px)` }"
    ></div>
    <div 
      class="absolute -bottom-24 -right-24 w-96 h-96 bg-violet-600/20 rounded-full blur-3xl transition-transform duration-100 ease-out pointer-events-none"
      :style="{ transform: `translate(${mouseX * 1.5}px, ${mouseY * 1.5}px)` }"
    ></div>

    <!-- Main Card Container -->
    <div class="max-w-xl w-full bg-slate-800/60 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-8 sm:p-12 text-center shadow-2xl relative z-10">
      
      <!-- Interactive Illustration Header -->
      <div class="relative w-32 h-32 mx-auto mb-6 flex items-center justify-center">
        <!-- Pulse Rings -->
        <div class="absolute inset-0 bg-indigo-500/10 rounded-full animate-ping"></div>
        <div class="absolute inset-2 bg-indigo-500/20 rounded-full"></div>
        
        <!-- Center Icon with Parallax Movement -->
        <div 
          class="relative w-20 h-20 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/40 border border-indigo-400/30 transition-transform duration-75 ease-out"
          :style="{ transform: `translate(${mouseX * 0.5}px, ${mouseY * 0.5}px)` }"
        >
          <SearchX class="w-10 h-10 text-white" />
        </div>
      </div>

      <!-- Error Code Display -->
      <div 
        class="inline-block px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 rounded-full text-indigo-400 font-semibold text-xs uppercase tracking-widest mb-3"
      >
        Error 404
      </div>

      <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-3">
        Halaman Tidak Ditemukan
      </h1>

      <p class="text-slate-400 text-sm leading-relaxed mb-8 max-w-md mx-auto">
        Maaf, halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau URL yang Anda masukkan salah.
      </p>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button
          @click="goBack"
          class="w-full sm:w-auto px-5 py-3 bg-slate-700 hover:bg-slate-600 active:bg-slate-800 text-slate-200 font-medium rounded-xl border border-slate-600/50 flex items-center justify-center gap-2 transition-all text-sm"
        >
          <ArrowLeft class="w-4 h-4" />
          <span>Kembali</span>
        </button>

        <Link
          :href="route('dashboard')"
          class="w-full sm:w-auto px-5 py-3 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 transition-all text-sm"
        >
          <LayoutDashboard class="w-4 h-4" />
          <span>Ke Dashboard</span>
        </Link>
      </div>

      <!-- Helpful Footer Note -->
      <div class="mt-8 pt-6 border-t border-slate-700/50 flex items-center justify-center gap-2 text-xs text-slate-500">
        <HelpCircle class="w-4 h-4 text-slate-400" />
        <span>{{ $page.props.appName || 'Laravel' }} System - Layanan Bantuan IT</span>
      </div>

    </div>
  </div>
</template>