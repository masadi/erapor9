<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/Card.vue'
import Grid from '@/Components/Grid.vue';
import GridCol from '@/Components/GridCol.vue';
import Toggle from '@/Components/Toggle.vue';
import ConfirmDialog from '@/Components/Dialogs/ConfirmDialog.vue';
import NotificationDialog from '@/Components/Dialogs/NotificationDialog.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      guru: 0,
      tendik: 0,
      pd: 0,
      rombel: 0,
    })
  },
  app: {
    type: Object,
    default: () => ({
      app_name: '',
      db_version: 0,
      status_penilaian: true,
    })
  },
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
const buildVersion = ref(window.build_version || '9.0.000');
// State Dialog Konfirmasi
const isConfirmOpen = ref(false);
const isDeleting = ref(false);

// State Dialog Notifikasi
const isNotifyOpen = ref(false);
const notifyType = ref('success'); // 'success' atau 'error'
const notifyTitle = ref('');
const notifySubtitle = ref('');

// Handler Tombol Hapus
const openDeleteModal = () => {
  isConfirmOpen.value = true;
};
// Function yang akan dipanggil saat nilai berubah
const handleStatusChange = (newValue) => {
  openDeleteModal()
  console.log('Status baru:', newValue);
  /**/
  // Autosave ke backend via Inertia
  /*router.patch(route('posts.update-status'), {
    status: newValue,
  }, {
    preserveScroll: true,
  });*/
};
const handleConfirm = () => {
  isDeleting.value = true;
  router.delete(route('users.destroy', 1), {
    onSuccess: () => {
      isConfirmOpen.value = false;
      isDeleting.value = false;
      
      // Tampilkan Notifikasi Sukses
      notifyType.value = 'success';
      notifyTitle.value = 'User Berhasil Dihapus';
      notifySubtitle.value = 'Data user telah dihapus secara permanen dari database.';
      isNotifyOpen.value = true;
    },
    onError: (errors) => {
      isConfirmOpen.value = false;
      isDeleting.value = false;

      // Tampilkan Notifikasi Gagal
      notifyType.value = 'error';
      notifyTitle.value = 'Gagal Menghapus Data';
      notifySubtitle.value = errors.message || 'Anda tidak memiliki akses untuk menghapus user ini.';
      isNotifyOpen.value = true;
    },
  });
}
</script>

<template>

  <Head title="Dashboard Area" />

  <AdminLayout title="Ringkasan Dashboard" :breadcrumbs="breadcrumbs">
    <!-- Slot tombol aksi -->
    <template #actionsa>
      <button
        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2 cursor-pointer">
        <component :is="$getIcon('Plus')" class="w-4 h-4" />
        <span>Buat Laporan Baru</span>
      </button>
    </template>

    <!-- Stat Cards Grid (4 Kolom) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

      <!-- Card 1: Jumlah Sekolah -->
      <div
        class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Guru</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">
            {{ formatCompactNumber(stats.guru) }}
          </p>
        </div>
        <div
          class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('school')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 2: Jumlah Guru Aktif -->
      <div
        class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tendik</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">
            {{ formatCompactNumber(stats.tendik) }}
          </p>
        </div>
        <div
          class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('user-check')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 3: Jumlah Kelas (Rombel) -->
      <div
        class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Murid Aktif</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">
            {{ formatCompactNumber(stats.pd) }}
          </p>
        </div>
        <div
          class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('users-group')" class="w-6 h-6" />
        </div>
      </div>

      <!-- Card 4: Jumlah Siswa Aktif -->
      <div
        class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
        <div>
          <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Rombel</span>
          <p class="text-2xl font-black text-slate-900 dark:text-white mt-1"
            :title="(stats.rombel || 0).toLocaleString('id-ID')">
            {{ formatCompactNumber(stats.rombel) }}
          </p>
        </div>
        <div
          class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
          <component :is="$getIcon('category-minus')" class="w-6 h-6" />
        </div>
      </div>

    </div>
    <div class="space-y-6">
      <Grid :cols="12" :gap="6">
        <!-- Kolom Kiri (Lebar 8 Kolom di Desktop) -->
        <GridCol :span="12" :span-lg="8">
          <Card title="Data Sekolah" :padding="false">
            <table class="w-full text-sm text-left">
              <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <tr>
                  <td class="p-3">Nama Sekolah</td>
                  <td class="p-3">{{ $page.props.sekolah?.nama }}</td>
                </tr>
                <tr>
                  <td class="p-3">NPSN</td>
                  <td class="p-3">{{ $page.props.sekolah?.npsn }}</td>
                </tr>
                <tr>
                  <td class="p-3">Alamat</td>
                  <td class="p-3">{{ $page.props.sekolah?.alamat }}</td>
                </tr>
                <tr>
                  <td class="p-3">Kodepos</td>
                  <td class="p-3">{{ $page.props.sekolah?.kode_pos }}</td>
                </tr>
                <tr>
                  <td class="p-3">Desa/Kelurahan</td>
                  <td class="p-3">{{ $page.props.sekolah?.desa_kelurahan }}</td>
                </tr>
                <tr>
                  <td class="p-3">Kecamatan</td>
                  <td class="p-3">{{ $page.props.sekolah?.kecamatan }}</td>
                </tr>
                <tr>
                  <td class="p-3">Kabupaten/Kota</td>
                  <td class="p-3">{{ $page.props.sekolah?.kabupaten }}</td>
                </tr>
                <tr>
                  <td class="p-3">Provinsi</td>
                  <td class="p-3">{{ $page.props.sekolah?.provinsi }}</td>
                </tr>
                <tr>
                  <td class="p-3">Email</td>
                  <td class="p-3">{{ $page.props.sekolah?.email }}</td>
                </tr>
                <tr>
                  <td class="p-3">Website</td>
                  <td class="p-3">{{ $page.props.sekolah?.website }}</td>
                </tr>
                <tr>
                  <td class="p-3">Kepala Sekolah</td>
                  <td class="p-3">{{ $page.props.kepala_sekolah?.nama || '-' }}</td>
                </tr>
              </tbody>
            </table>
          </Card>
        </GridCol>
        <GridCol :span="12" :span-lg="4">
          <Card title="Informasi Aplikasi">
            <div class="detail-row">
              <span class="label">Nama Aplikasi</span>
              <span class="colon">:</span>
              <span class="value font-weight-medium">e-Rapor SMK</span>
            </div>
            <div class="detail-row">
              <span class="label">Versi Aplikasi</span>
              <span class="colon">:</span>
              <span class="value font-weight-medium">{{ buildVersion }}</span>
            </div>
            <div class="detail-row">
              <span class="label">Versi Database</span>
              <span class="colon">:</span>
              <span class="value font-weight-medium">6.0.6</span>
            </div>
            <div class="detail-row">
              <span class="label">Status Penilaian</span>
              <span class="colon">:</span>
              <span class="value font-weight-medium">
                <Toggle v-model="app.status_penilaian" size="sm" :show-status-label="true" on-label="Aktif"
                  off-label="Nonaktif" @change="handleStatusChange" />
              </span>
            </div>
            <div class="detail-row">
              <span class="label">Link Group Diskusi</span>
              <span class="colon">:</span>
              <span class="value font-weight-medium">e-Rapor SMK</span>
            </div>
            <template #footer>
              <div class="text-xs text-slate-400 text-center">Dikembangkan oleh SMK, dari SMK, untuk SMK</div>
            </template>
          </Card>
        </GridCol>
      </Grid>
    </div>
    <!-- 1. Dialog Konfirmasi -->
    <ConfirmDialog :show="isConfirmOpen" title="Hapus Akun User?"
      subtitle="Apakah Anda yakin ingin menghapus user ini? Data yang terhapus tidak dapat dikembalikan."
      confirm-text="Ya, Hapus" cancel-text="Batal" :loading="isDeleting" @close="isConfirmOpen = false"
      @confirm="handleConfirm" />

    <!-- 2. Dialog Notifikasi -->
    <NotificationDialog :show="isNotifyOpen" :type="notifyType" :title="notifyTitle" :subtitle="notifySubtitle"
      @close="isNotifyOpen = false" />
  </AdminLayout>
</template>
<style scoped>
/* Styling Align Titik Dua Identitas */
.detail-row {
  display: flex;
  align-items: flex-start;
  margin-bottom: 8px;
  font-size: 0.85rem;
}

.label {
  flex: 0.8;
}

.colon {
  width: 14px;
  text-align: center;
}

.value {
  flex: 1.5;
  word-break: break-word;
}
</style>