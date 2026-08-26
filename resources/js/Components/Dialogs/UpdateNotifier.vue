<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const showUpdateDialog = ref(false)

// Ambil versi dari konstanta global Vite (__APP_VERSION__) atau window
const initialVersion = ref(
  typeof __APP_VERSION__ !== 'undefined'
    ? __APP_VERSION__
    : window.build_version || null
)

const newVersion = ref('')
const buildAuthor = ref('Direktorat SMP')
const buildTime = ref('')

// Countdown auto-update (5 menit = 300 detik)
const autoUpdateSeconds = ref(300)
let checkInterval = null
let countdownTimer = null

// Format detik ke format mm:ss (contoh: 4:44)
const formattedCountdown = computed(() => {
  const m = Math.floor(autoUpdateSeconds.value / 60)
  const s = autoUpdateSeconds.value % 60
  return `${m}:${s < 10 ? '0' : ''}${s}`
})

const startCountdown = () => {
  autoUpdateSeconds.value = 300
  if (countdownTimer) clearInterval(countdownTimer)

  countdownTimer = setInterval(() => {
    if (autoUpdateSeconds.value > 0) {
      autoUpdateSeconds.value--
    } else {
      clearInterval(countdownTimer)
      forceReload()
    }
  }, 1000)
}

const checkForUpdates = async () => {
  try {
    // Tambahkan timestamp query string agar terhindar dari cache browser
    const response = await fetch('/build-version.json?t=' + new Date().getTime())
    const data = await response.json()

    if (data && data.version) {
      const latestVersion = data.version

      if (initialVersion.value && latestVersion !== initialVersion.value) {
        newVersion.value = latestVersion

        // 🟢 Set nilai author & time dari file JSON server
        if (data.author) buildAuthor.value = data.author
        if (data.commitTime) buildTime.value = data.commitTime
        
        showUpdateDialog.value = true
        startCountdown()
      }
    }
  } catch (error) {
    // Silent error jika koneksi terputus/gagal fetch
  }
}

const snoozeUpdate = () => {
  showUpdateDialog.value = false
  if (countdownTimer) clearInterval(countdownTimer)

  // Cek ulang setelah ditunda 10 menit
  setTimeout(() => {
    checkForUpdates()
  }, 10 * 60 * 1000)
}

const forceReload = () => {
  window.location.reload()
}

onMounted(() => {
  // Cek update pertama kali saat di-mount
  checkForUpdates()
  // Cek update berkala setiap 5 menit
  checkInterval = setInterval(checkForUpdates, 5 * 60 * 1000)
})

onUnmounted(() => {
  if (checkInterval) clearInterval(checkInterval)
  if (countdownTimer) clearInterval(countdownTimer)
})
</script>

<template>
  <Teleport to="body">
    <!-- Modal Backdrop -->
    <div
      v-if="showUpdateDialog"
      class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
    >
      <!-- Modal Box -->
      <div
        class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 text-center transform transition-all border border-slate-100"
      >
        <!-- Icon Download Bulat -->
        <div class="flex justify-center mb-4">
          <div class="w-16 h-16 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
            <component :is="$getIcon('Download')" class="w-8 h-8" />
          </div>
        </div>

        <!-- Judul -->
        <h3 class="text-xl font-bold text-slate-900 mb-3">
          Pembaruan Aplikasi Tersedia
        </h3>

        <!-- Detail Versi -->
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 mb-4 text-xs">
          <div class="text-slate-600">
            Versi baru: <span class="font-bold text-slate-900">{{ newVersion || 'Terbaru' }}</span>
          </div>
          <div class="text-slate-500 mt-1">
            Dirilis oleh <span class="font-medium text-slate-700">{{ buildAuthor }}</span>
            <template v-if="buildTime"> pada {{ buildTime }} WIB</template>
          </div>
        </div>

        <!-- Auto Update Countdown -->
        <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 mb-5">
          <component :is="$getIcon('Clock')" class="w-4 h-4 text-slate-400 shrink-0" />
          <span>Auto-update dalam <strong class="text-slate-900 font-semibold">{{ formattedCountdown }}</strong> jika tidak ada aksi</span>
        </div>

        <!-- Tombol Aksi -->
        <div class="space-y-2 mb-5">
          <button
            type="button"
            @click="forceReload"
            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-sm rounded-xl shadow-md transition-all cursor-pointer"
          >
            <component :is="$getIcon('Download')" class="w-4 h-4" />
            <span>Update Sekarang</span>
          </button>

          <button
            type="button"
            @click="snoozeUpdate"
            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 font-semibold text-sm rounded-xl transition-all cursor-pointer"
          >
            <component :is="$getIcon('ClockPause')" class="w-4 h-4 text-slate-500" />
            <span>Tunda 10 Menit</span>
          </button>
        </div>

        <!-- Panel Manual Update Info -->
        <div class="bg-slate-50 border border-dashed border-slate-300 rounded-xl p-3 text-left">
          <div class="flex items-start gap-2 text-xs font-semibold text-slate-800 mb-2">
            <component :is="$getIcon('Keyboard')" class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" />
            <span>Cara update manual jika tombol tidak berfungsi:</span>
          </div>
          <ul class="pl-6 text-[11px] text-slate-600 space-y-1 list-disc">
            <li>Tekan <kbd class="px-1.5 py-0.5 bg-white border border-slate-300 rounded text-slate-800 font-mono">Ctrl + F5</kbd> (Windows)</li>
            <li>Atau <kbd class="px-1.5 py-0.5 bg-white border border-slate-300 rounded text-slate-800 font-mono">Ctrl + Shift + R</kbd></li>
            <li>Atau <kbd class="px-1.5 py-0.5 bg-white border border-slate-300 rounded text-slate-800 font-mono">Cmd + Shift + R</kbd> (Mac)</li>
            <li>Atau buka <strong class="text-slate-800">Profil &rarr; Tab Cache &rarr; Clear Cache</strong></li>
          </ul>
        </div>
      </div>
    </div>
  </Teleport>
</template>