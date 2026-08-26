<script setup>
const showUpdateDialog = ref(false)
//const initialVersion = ref(window.build_version || null)
// Mengambil nilai awal dari konstanta Vite atau global window
const initialVersion = ref(typeof __APP_VERSION__ !== 'undefined' ? __APP_VERSION__ : app_version)
const newVersion = ref('')
const buildAuthor = ref('Direktorat SMP') // Sesuaikan atau ambil dari meta tag
const buildTime = ref('')

// Countdown auto-update (dalam detik, misal 5 menit = 300 detik)
const autoUpdateSeconds = ref(300)
let checkInterval = null
let countdownTimer = null

// Format detik ke format mm:ss (misal: 4:44)
const formattedCountdown = computed(() => {
  const m = Math.floor(autoUpdateSeconds.value / 60)
  const s = autoUpdateSeconds.value % 60
  return `${m}:${s < 10 ? '0' : ''}${s}`
})

const startCountdown = () => {
  autoUpdateSeconds.value = 300 // Reset ke 5 menit
  if (countdownTimer) clearInterval(countdownTimer)
  
  countdownTimer = setInterval(() => {
    if (autoUpdateSeconds.value > 0) {
      autoUpdateSeconds.value--
    } else {
      clearInterval(countdownTimer)
      forceReload() // Auto reload ketika waktu habis
    }
  }, 1000)
}

const checkForUpdates = async () => {
  try {
    // Ambil file json versi dari server dengan query string timestamp agar TIDAK KENA CACHE browser
    const response = await fetch('/build-version.json?t=' + new Date().getTime())
    const data = await response.json()

    if (data && data.version) {
      const latestVersion = data.version

      // Jika versi di server berbeda dari versi saat user pertama kali buka aplikasi
      if (initialVersion.value && latestVersion !== initialVersion.value) {
        newVersion.value = latestVersion
        showUpdateDialog.value = true
        startCountdown()
      }
    }
  } catch (error) {
    // Silent error
  }
}

const snoozeUpdate = () => {
  showUpdateDialog.value = false
  if (countdownTimer) clearInterval(countdownTimer)
  
  // Cek lagi setelah ditunda 10 menit (600.000 ms)
  setTimeout(() => {
    checkForUpdates()
  }, 10 * 60 * 1000)
}

const forceReload = () => {
  window.location.reload(true)
}

onMounted(() => {
  // Cek update berkala setiap 5 menit
  checkInterval = setInterval(checkForUpdates, 5 * 60 * 1000)
})

onUnmounted(() => {
  if (checkInterval) clearInterval(checkInterval)
  if (countdownTimer) clearInterval(countdownTimer)
})
</script>

<template>
  <VDialog v-model="showUpdateDialog" persistent max-width="480" overlay-opacity="0.6">
    <VCard class="pa-6 text-center rounded-xl elevation-10">
      <!-- Icon Download Bulat -->
      <div class="d-flex justify-center mb-4">
        <VAvatar color="blue-lighten-5" size="64">
          <VIcon icon="tabler-download" color="primary" size="32" />
        </VAvatar>
      </div>

      <!-- Judul -->
      <h3 class="text-h5 font-weight-bold mb-4 text-high-emphasis">
        Pembaruan Aplikasi Tersedia
      </h3>

      <!-- Detail Versi -->
      <div class="bg-grey-lighten-4 rounded-lg pa-3 mb-3">
        <div class="text-body-2 text-medium-emphasis">
          Versi baru: <span class="font-weight-bold text-high-emphasis">{{ newVersion || 'Terbaru' }}</span>
        </div>
        <div class="text-caption text-medium-emphasis">
          Dirilis oleh <span class="font-weight-medium">{{ buildAuthor }}</span>
          <template v-if="buildTime"> pada {{ buildTime }} WIB</template>
        </div>
      </div>

      <!-- Auto Update Countdown -->
      <div class="d-flex align-center justify-center text-caption text-medium-emphasis mb-5">
        <VIcon icon="tabler-clock" size="16" class="mr-1" />
        <span>Auto-update dalam <strong class="text-high-emphasis">{{ formattedCountdown }}</strong> jika tidak ada
          aktivitas</span>
      </div>

      <!-- Tombol Aksi -->
      <VBtn block color="primary" size="large" class="mb-2 text-capitalize font-weight-bold rounded-lg" elevation="0"
        @click="forceReload">
        <VIcon icon="tabler-download" class="mr-2" />
        Update Sekarang
      </VBtn>

      <VBtn block variant="tonal" color="secondary" size="large" class="mb-5 text-capitalize rounded-lg"
        @click="snoozeUpdate">
        <VIcon icon="tabler-clock-pause" class="mr-2" />
        Tunda 10 menit
      </VBtn>

      <!-- Panel Manual Update Info -->
      <div class="manual-update-box text-left pa-3 rounded-lg">
        <div class="d-flex align-top mb-2 text-caption text-high-emphasis font-weight-medium">
          <VIcon icon="tabler-keyboard" size="16" class="mr-2 mt-1" />
          <span>Cara update manual jika tombol di atas tidak berfungsi:</span>
        </div>
        <ul class="pl-6 text-caption text-medium-emphasis custom-list">
          <li>Tekan <kbd>Ctrl + F5</kbd> (Windows)</li>
          <li>Atau <kbd>Ctrl + Shift + R</kbd></li>
          <li>Atau <kbd>Cmd + Shift + R</kbd> (Mac)</li>
          <li>Atau buka <strong class="text-high-emphasis">Profil &rarr; Tab Cache &rarr; Clear Cache</strong></li>
        </ul>
      </div>
    </VCard>
  </VDialog>
</template>

<style scoped>
.manual-update-box {
  background-color: #f8fafc;
  border: 1px dashed #cbd5e1;
}

kbd {
  background-color: #f1f5f9;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  padding: 1px 5px;
  font-family: monospace;
  font-size: 11px;
  color: #334155;
}

.custom-list {
  list-style-type: disc;
  line-height: 1.6;
}
</style>
