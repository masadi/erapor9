<script setup>
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
      <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2">
        <component :is="$getIcon('Plus')" class="w-4 h-4" />
        <span>Buat Laporan Baru</span>
      </button>
    </template>

    <!-- Stat Cards Grid (4 Kolom) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      
      <!-- Card 1: Jumlah Sekolah -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Sekolah</span>
          <p class="text-2xl font-black text-slate-900 mt-1">
            {{ formatCompactNumber(stats.jumlahSekolah) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
          <component :is="$getIcon('Building2')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 2: Jumlah Guru Aktif -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Guru Aktif</span>
          <p class="text-2xl font-black text-slate-900 mt-1">
            {{ formatCompactNumber(stats.jumlahGuruAktif) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
          <component :is="$getIcon('UserCheck')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 3: Jumlah Kelas (Rombel) -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Kelas</span>
          <p class="text-2xl font-black text-slate-900 mt-1">
            {{ formatCompactNumber(stats.jumlahKelas) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
          <component :is="$getIcon('Chalkboard')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 4: Jumlah Siswa Aktif -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Aktif</span>
          <p class="text-2xl font-black text-slate-900 mt-1" :title="(stats.jumlahSiswaAktif || 0).toLocaleString('id-ID')">
            {{ formatCompactNumber(stats.jumlahSiswaAktif) }}
          </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
          <component :is="$getIcon('Users')" class="w-6 h-6" />
        </div>
      </div>

    </div>

    <!-- Rekapitulasi Sekolah Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mt-6">
      <!-- Card Header -->
      <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <div>
          <h3 class="text-base font-bold text-slate-900">Rekapitulasi Sekolah</h3>
          <p class="text-xs text-slate-500 mt-0.5">Rincian data PTK, Siswa Aktif, dan Jumlah Kelas per Sekolah</p>
        </div>
      </div>

      <!-- Table Container (Responsive Scroll) -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
          <!-- Table Head -->
          <thead>
            <!-- Row 1 -->
            <tr class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200 text-xs">
              <th rowspan="2" class="px-3 py-3 text-center align-middle border-r border-slate-200 w-12">
                No.
              </th>
              <th rowspan="2" class="px-4 py-3 align-middle border-r border-slate-200 min-w-[220px]">
                Nama Sekolah
              </th>
              <th colspan="3" class="px-3 py-2 text-center border-r border-slate-200">
                PTK / Guru
              </th>
              <th colspan="3" class="px-3 py-2 text-center border-r border-slate-200">
                Siswa Aktif
              </th>
              <th rowspan="2" class="px-3 py-3 text-center align-middle border-slate-200 min-w-[100px]">
                Jumlah Kelas
              </th>
            </tr>

            <!-- Row 2 -->
            <tr class="bg-slate-50 font-bold text-[11px] text-slate-600 uppercase border-b border-slate-200">
              <th class="px-3 py-1.5 text-center border-t border-r border-slate-200">L</th>
              <th class="px-3 py-1.5 text-center border-t border-r border-slate-200">P</th>
              <th class="px-3 py-1.5 text-center border-t border-r border-slate-200 bg-slate-100/60">Total</th>
              <th class="px-3 py-1.5 text-center border-t border-r border-slate-200">L</th>
              <th class="px-3 py-1.5 text-center border-t border-r border-slate-200">P</th>
              <th class="px-3 py-1.5 text-center border-t border-r border-slate-200 bg-slate-100/60">Total</th>
            </tr>
          </thead>

          <!-- Table Body -->
          <tbody class="divide-y divide-slate-200 text-slate-700">
            <tr 
              v-for="(lembaga, index) in dataLembaga" 
              :key="lembaga.lembaga_id || index"
              class="hover:bg-slate-50/80 transition-colors"
            >
              <td class="px-3 py-3 text-center border-r border-slate-200 font-medium text-slate-500">
                {{ index + 1 }}
              </td>
              <td class="px-4 py-3 font-semibold text-slate-900 border-r border-slate-200 whitespace-nowrap">
                {{ lembaga.nama }}
              </td>
              <td class="px-3 py-3 text-center border-r border-slate-200">
                {{ lembaga.guru_l?.toLocaleString('id-ID') || 0 }}
              </td>
              <td class="px-3 py-3 text-center border-r border-slate-200">
                {{ lembaga.guru_p?.toLocaleString('id-ID') || 0 }}
              </td>
              <td class="px-3 py-3 text-center border-r border-slate-200 font-bold bg-slate-50/50">
                {{ (lembaga.guru_total ?? ((lembaga.guru_l || 0) + (lembaga.guru_p || 0))).toLocaleString('id-ID') }}
              </td>
              <td class="px-3 py-3 text-center border-r border-slate-200">
                {{ lembaga.pd_l?.toLocaleString('id-ID') || 0 }}
              </td>
              <td class="px-3 py-3 text-center border-r border-slate-200">
                {{ lembaga.pd_p?.toLocaleString('id-ID') || 0 }}
              </td>
              <td class="px-3 py-3 text-center border-r border-slate-200 font-bold bg-slate-50/50">
                {{ (lembaga.pd_total ?? ((lembaga.pd_l || 0) + (lembaga.pd_p || 0))).toLocaleString('id-ID') }}
              </td>
              <td class="px-3 py-3 text-center font-bold text-indigo-600">
                {{ lembaga.rombel_count?.toLocaleString('id-ID') || 0 }}
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="!dataLembaga || dataLembaga.length === 0">
              <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                Belum ada data rekapitulasi sekolah.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>