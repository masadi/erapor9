<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  featureName: {
    type: String,
    default: 'Halaman Ini'
  }
});

const breadcrumbs = [
  { label: 'Admin', href: route('dashboard') },
  { label: 'Dalam Pemeliharaan' }
];
</script>

<template>
  <Head :title="`${featureName} - Dalam Pengembangan`" />

  <AdminLayout title="Halaman Dalam Pemeliharaan" :breadcrumbs="breadcrumbs">
    
    <!-- MAIN CONTAINER -->
    <div class="w-full bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 flex flex-col items-center justify-center text-center space-y-6">
      
      <!-- GAMBAR ORANG BEKERJA (ILUSTRASI VECTOR MODERN) -->
      <div class="w-full max-w-md mx-auto aspect-4/3 flex items-center justify-center">
        <img 
          src="https://illustrations.popsy.co/amber/work-from-home.svg" 
          alt="Orang Sedang Bekerja" 
          class="w-full h-auto object-contain max-h-72 drop-shadow-sm select-none"
        />
      </div>

      <!-- TEXT DESCRIPTION -->
      <div class="space-y-2 max-w-lg">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
          {{ featureName }} Sedang Dikembangkan
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
          Kami sedang mempersiapkan dan memperbarui halaman ini agar dapat digunakan segera. Silakan kembali lagi nanti.
        </p>
      </div>

      <!-- ACTION BUTTON -->
      <div class="pt-2">
        <Link 
          :href="route('dashboard')" 
          class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2"
        >
          <component :is="$getIcon('ArrowLeft')" class="w-4 h-4" />
          <span>Kembali ke Dashboard</span>
        </Link>
      </div>

    </div>

  </AdminLayout>
</template>