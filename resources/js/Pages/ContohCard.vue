<script setup>
import Card from '@/Components/Card.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      jumlahSekolah: 0,
      jumlahGuruAktif: 0,
      jumlahKelas: 0,
      jumlahSiswaAktif: 0,
    })
  },
  dataLembaga: {
    type: Array,
    default: () => []
  },
  rekap: {
    type: Array,
    default: () => []
  }
});

// Helper pemformat angka ribuan ke format K (misal: 2450 -> 2.4K)
const formatCompactNumber = (number) => {
  if (number === null || number === undefined) return '0';
  if (number >= 1000) {
    return (number / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
  }
  return number.toLocaleString('id-ID');
};

const breadcrumbs = [
  { label: 'Admin', href: '#' },
  { label: 'Dashboard' }
];
</script>

<template>
  <Head title="Dashboard Area" />

  <AdminLayout title="Ringkasan Dashboard" :breadcrumbs="breadcrumbs">
    <!-- Slot tombol aksi -->
    <template #actions>
      <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2 cursor-pointer">
        <component :is="$getIcon('Plus')" class="w-4 h-4" />
        <span>Buat Laporan Baru</span>
      </button>
    </template>

    <!-- Stat Cards Grid (4 Kolom) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      
      <!-- Card 1: Jumlah Sekolah -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Guru</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">
            {{ formatCompactNumber(stats.guru) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('school')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 2: Jumlah Guru Aktif -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tendik</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">
            {{ formatCompactNumber(stats.tendik) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('user-check')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 3: Jumlah Kelas (Rombel) -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Murid Aktif</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">
            {{ formatCompactNumber(stats.pd) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('users-group')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 4: Jumlah Siswa Aktif -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Rombel</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1" :title="(stats.rombel || 0).toLocaleString('id-ID')">
            {{ formatCompactNumber(stats.rombel) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('category-minus')" class="w-6 h-6" />
        </div>
      </div>

    </div>
    <div class="space-y-6 max-w-4xl">
      
      <!-- Contoh 1: Card Standar -->
      <Card title="Data Sekolah">
        <!-- Isi Konten Utama Card -->
        <p class="text-sm text-slate-600 dark:text-slate-300">
          Ini adalah isi konten di dalam card.
        </p>

        <!-- Footer Card -->
        <template #footer>
          <span class="text-xs text-slate-400">Terakhir diperbarui: Hari ini</span>
        </template>
      </Card>

      <!-- Contoh 2: Card untuk Tabel (Tanpa Padding Body) -->
      <Card title="Rekapitulasi" subtitle="Tabel tanpa padding body" :padding="false">
        <table class="w-full text-sm text-left">
          <thead class="bg-slate-50 dark:bg-slate-950/50 text-xs">
            <tr>
              <th class="p-3">No</th>
              <th class="p-3">Nama</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr>
              <td class="p-3">1</td>
              <td class="p-3">Ahmad</td>
            </tr>
          </tbody>
        </table>
      </Card>
      <!-- Contoh 1: Responsive Equal Grid (Card Statistik 4 Kolom) -->
      <Grid :cols="1" :cols-sm="2" :cols-lg="4" :gap="5">
        <Card title="Card 1">Isi Stat 1</Card>
        <Card title="Card 2">Isi Stat 2</Card>
        <Card title="Card 3">Isi Stat 3</Card>
        <Card title="Card 4">Isi Stat 4</Card>
      </Grid>

      <!-- Contoh 2: 12-Column Grid System (Layout Kiri-Kanan 8:4) -->
      <Grid :cols="12" :gap="6">
        <!-- Kolom Kiri (Lebar 8 Kolom di Desktop) -->
        <GridCol :span="12" :span-lg="8">
          <Card title="Konten Utama (8 Col)" subtitle="Tabel atau Grafik">
            <p class="text-slate-600 dark:text-slate-300">
              Area ini mengambil 8 kolom dari 12 kolom.
            </p>
          </Card>
        </GridCol>

        <!-- Kolom Kanan (Lebar 4 Kolom di Desktop) -->
        <GridCol :span="12" :span-lg="4">
          <Card title="Sidebar (4 Col)" subtitle="Aktivitas Terbaru">
            <p class="text-slate-600 dark:text-slate-300">
              Area ini mengambil 4 kolom sisanya.
            </p>
          </Card>
        </GridCol>
      </Grid>
    </div>
  </AdminLayout>
</template>