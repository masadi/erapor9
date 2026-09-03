<script setup>
import UpdateNotifier from '@/Components/Dialogs/UpdateNotifier.vue'; // Adjust path
import SidebarNavItem from '@/Components/SidebarNavItem.vue';
import HorizontalNavItem from '@/Components/HorizontalNavItem.vue';

defineProps({
  title: { type: String, default: 'Dashboard' },
  breadcrumbs: { type: Array, default: () => [] }
});

const page = usePage();

// Ambil data menu langsung dari Shared Props Inertia
const navigation = computed(() => page.props.navigations || []);
const navigationMode = ref('vertical');
const navigationStorageKey = 'erapor-navigation-mode';

// State Sidebar
const isSidebarOpen = ref(false); // Mode mobile
const isCollapsed = ref(false);   // Mode desktop

const toggleMobileSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleCollapse = () => {
  isCollapsed.value = !isCollapsed.value;
};

const toggleNavigationMode = () => {
  navigationMode.value = navigationMode.value === 'vertical' ? 'horizontal' : 'vertical';
  isSidebarOpen.value = false;
  window.localStorage.setItem(navigationStorageKey, navigationMode.value);
};

// State Dropdown Profil
const isProfileDropdownOpen = ref(false);
const dropdownRef = ref(null);

const toggleProfileDropdown = () => {
  isProfileDropdownOpen.value = !isProfileDropdownOpen.value;
};

// ==========================================
// STATE & LOGIKA THEME SWITCHER (Light/Dark/System)
// ==========================================
const themeMode = ref('system'); // 'light' | 'dark' | 'system'
const isThemeDropdownOpen = ref(false);
const themeDropdownRef = ref(null);

const applyTheme = (mode) => {
  themeMode.value = mode;
  window.localStorage.setItem('erapor-theme-mode', mode);

  const root = document.documentElement;
  
  if (mode === 'dark') {
    root.classList.add('dark');
  } else if (mode === 'light') {
    root.classList.remove('dark');
  } else {
    // Mode System: Cek preferensi OS
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
  }
};

const setThemeMode = (mode) => {
  applyTheme(mode);
  isThemeDropdownOpen.value = false;
};

// Ikon dinamis untuk tombol header sesuai tema aktif
const currentThemeIcon = computed(() => {
  if (themeMode.value === 'dark') return 'Moon';
  if (themeMode.value === 'light') return 'Sun';
  return 'DeviceDesktop'; // Ikon untuk System
});

// Update event listener click outside
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isProfileDropdownOpen.value = false;
  }
  if (themeDropdownRef.value && !themeDropdownRef.value.contains(event.target)) {
    isThemeDropdownOpen.value = false;
  }
};

onMounted(() => {
  // Restore Theme Setting
  const savedTheme = window.localStorage.getItem('erapor-theme-mode') || 'system';
  applyTheme(savedTheme);

  // Listener untuk perubahan preferensi OS secara real-time (saat mode System aktif)
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (themeMode.value === 'system') {
      if (e.matches) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    }
  });

  const savedNavigationMode = window.localStorage.getItem(navigationStorageKey);
  if (savedNavigationMode === 'horizontal' || savedNavigationMode === 'vertical') {
    navigationMode.value = savedNavigationMode;
  }

  document.addEventListener('click', handleClickOutside);
});
onMounted(() => {
  const savedNavigationMode = window.localStorage.getItem(navigationStorageKey);
  if (savedNavigationMode === 'horizontal' || savedNavigationMode === 'vertical') {
    navigationMode.value = savedNavigationMode;
  }

  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
const buildVersion = ref(window.build_version || '9.0.000');
</script>

<template>
  <div class="h-screen w-screen bg-slate-100 dark:bg-slate-950 flex overflow-hidden text-slate-800 dark:text-slate-100">

    <!-- Mobile Sidebar Overlay -->
    <div v-if="isSidebarOpen" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden" @click="toggleMobileSidebar"></div>

    <!-- SIDEBAR AREA -->
    <aside v-if="navigationMode === 'vertical'" :class="[
      'fixed lg:static inset-y-0 left-0 z-50 bg-slate-900 text-white flex flex-col justify-between transition-all duration-300 ease-in-out shrink-0 h-full',
      isCollapsed ? 'lg:w-20' : 'lg:w-64',
      'w-64',
      isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]">
      <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- App Title Header -->
        <div class="h-16 flex items-center justify-between px-4 bg-slate-950 border-b border-slate-800 shrink-0">
          <div class="flex items-center gap-3 overflow-hidden">
            <!-- FOTO LOGO APLIKASI -->
            <div class="w-8 flex items-center justify-center font-bold text-white shrink-0 overflow-hidden">
              <img v-if="$page.props.appLogo" :src="$page.props.appLogo"
                :alt="($page.props.appName || 'S').charAt(0).toUpperCase() || 'Logo'"
                class="w-full h-full object-cover" />

              <span v-else>
                {{ ($page.props.appName || 'S').charAt(0).toUpperCase() }}
              </span>
            </div>
            <span v-show="!isCollapsed"
              class="font-bold text-lg tracking-wide text-white whitespace-nowrap transition-opacity">
              {{ $page.props.appName || 'Laravel' }}
            </span>
          </div>

          <button @click="toggleCollapse"
            class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <component :is="$getIcon('ChevronRight')" v-if="isCollapsed" class="w-5 h-5" />
            <component :is="$getIcon('ChevronLeft')" v-else class="w-5 h-5" />
          </button>

          <button @click="toggleMobileSidebar" class="lg:hidden text-slate-400 hover:text-white">
            <component :is="$getIcon('X')" class="w-6 h-6" />
          </button>
        </div>

        <!-- Navigation List Dinamis dari Database -->
        <nav class="flex-1 px-3 py-6 space-y-1">
          <SidebarNavItem v-for="item in navigation" :key="item.id" :item="item" :is-collapsed="isCollapsed" />
        </nav>
      </div>

      <!-- Logout Button -->
      <div class="p-3 border-t border-slate-800/80 bg-slate-950/40 shrink-0">
        <Link :href="route('logout')" method="post" as="button" :title="isCollapsed ? 'Keluar Aplikasi' : ''"
          class="w-full flex items-center justify-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-400 bg-red-500/10 border border-red-500/20 hover:text-white hover:bg-red-600 hover:border-red-600 active:bg-red-700 transition-all duration-200 group shadow-sm">
        <component :is="$getIcon('logout-2')" class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" />
        <span v-show="!isCollapsed" class="truncate">Keluar Aplikasi</span>
        </Link>
      </div>
    </aside>

    <!-- RIGHT MAIN AREA -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden relative">
      <header
        class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 lg:px-8 shrink-0 z-50 transition-colors">

        <!-- HEADER LEFT AREA (Logo Horizontal, Mobile Toggle, & Periode Badges) -->
        <div class="flex items-center gap-3">

          <!-- 🟢 LOGO & NAMA APLIKASI (Hanya tampil di Mode Horizontal Desktop) -->
          <div v-if="navigationMode === 'horizontal'"
            class="hidden lg:flex items-center gap-3 mr-2 border-r border-slate-200 dark:border-slate-800 pr-4">
            <div class="w-8 flex items-center justify-center font-bold text-white shrink-0 overflow-hidden shadow-xs">
              <img v-if="$page.props.appLogo" :src="$page.props.appLogo"
                :alt="($page.props.appName || 'S').charAt(0).toUpperCase() || 'Logo'"
                class="w-full h-full object-cover" />
              <span v-else class="text-sm">
                {{ ($page.props.appName || 'S').charAt(0).toUpperCase() }}
              </span>
            </div>
            <span class="font-bold text-base tracking-wide text-slate-900 dark:text-white whitespace-nowrap">
              {{ $page.props.appName || 'Laravel' }}
            </span>
          </div>

          <!-- Mobile Menu Button Toggle -->
          <button v-if="navigationMode === 'vertical'" @click="toggleMobileSidebar"
            class="lg:hidden p-2 rounded-md text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
            <component :is="$getIcon('Menu')" class="w-6 h-6" />
          </button>

          <!-- BADGE NAMA SEKOLAH -->
          <div
            class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50/80 dark:bg-indigo-950/40 border border-indigo-100/80 dark:border-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-semibold shadow-2xs">
            <component :is="$getIcon('building-bank')" class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0" />
            <span class="whitespace-nowrap"> {{ $page.props.sekolah?.nama }}</span>
          </div>

          <!-- BADGE PERIODE SEMESTER -->
          <div
            class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50/80 dark:bg-indigo-950/40 border border-indigo-100/80 dark:border-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-semibold shadow-2xs">
            <component :is="$getIcon('Calendar')" class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0" />
            <span class="whitespace-nowrap"> {{ $page.props.periodeAktif }}</span>
          </div>
        </div>
        <div class="flex items-center gap-3 ml-auto">
          <!-- 🟢 THEME SWITCHER DROPDOWN -->
          <div class="relative inline-block text-left" ref="themeDropdownRef">
            <button type="button" @click.stop="isThemeDropdownOpen = !isThemeDropdownOpen" title="Pilih Tema Tampilan"
              class="w-10 h-10 rounded-full flex items-center justify-center border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/80 dark:hover:bg-slate-800 focus:outline-none transition-all duration-200 shrink-0 shadow-xs cursor-pointer">
              <component :is="$getIcon(currentThemeIcon)" class="w-5 h-5 transition-transform hover:scale-110" />
            </button>

            <!-- Menu Option Theme -->
            <Transition enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0">
              <div v-if="isThemeDropdownOpen"
                class="absolute right-0 mt-2 w-40 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 p-1.5 z-50 origin-top-right">

                <button type="button" @click="setThemeMode('light')" :class="[
                  'w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium transition-colors cursor-pointer',
                  themeMode === 'light' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
                ]">
                  <component :is="$getIcon('Sun')" class="w-4 h-4" />
                  <span>Terang (Light)</span>
                </button>

                <button type="button" @click="setThemeMode('dark')" :class="[
                  'w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium transition-colors cursor-pointer',
                  themeMode === 'dark' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
                ]">
                  <component :is="$getIcon('Moon')" class="w-4 h-4" />
                  <span>Gelap (Dark)</span>
                </button>

                <button type="button" @click="setThemeMode('system')" :class="[
                  'w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium transition-colors cursor-pointer',
                  themeMode === 'system' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
                ]">
                  <component :is="$getIcon('DeviceDesktop')" class="w-4 h-4" />
                  <span>Sistem (Auto)</span>
                </button>
              </div>
            </Transition>
          </div>
          <!-- LAYOUT SWITCHER BUTTON -->
          <button type="button"
            :title="navigationMode === 'vertical' ? 'Ubah ke Navigasi Horizontal' : 'Ubah ke Navigasi Vertikal'"
            :aria-label="navigationMode === 'vertical' ? 'Ubah ke Navigasi Horizontal' : 'Ubah ke Navigasi Vertikal'"
            @click="toggleNavigationMode"
            class="w-10 h-10 rounded-full flex items-center justify-center border border-slate-200 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/80 hover:border-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 shrink-0 shadow-xs cursor-pointer">
            <component :is="$getIcon(navigationMode === 'vertical' ? 'LayoutNavbar' : 'LayoutSidebar')"
              class="w-5 h-5 transition-transform duration-300 hover:scale-110" />
          </button>
          <!-- HEADER RIGHT AREA (User Profile Dropdown) -->
          <div class="relative ml-auto" ref="dropdownRef">
            <button @click.stop="toggleProfileDropdown" type="button"
              class="w-10 h-10 rounded-full overflow-hidden border-2 border-indigo-100 dark:border-slate-700 hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0 transition-all shadow-sm cursor-pointer">
              <img v-if="$page.props.auth?.user?.photo" :src="`/storage/${$page.props.auth.user.photo}`" alt="Foto"
                class="w-full h-full object-cover" />
              <span v-else class="text-sm">{{ $page.props.auth?.user?.name?.charAt(0).toUpperCase() }}</span>
            </button>

            <!-- Dropdown Menu -->
            <Transition enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0">
              <div v-if="isProfileDropdownOpen"
                class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 py-2 z-50 transition-all origin-top-right">

                <!-- User Info Header -->
                <div
                  class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40">
                  <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                    {{ $page.props.auth?.user?.name || 'Administrator' }}
                  </p>
                  <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                    {{ $page.props.auth?.user?.email || 'admin@example.com' }}
                  </p>

                  <!-- Role Badges -->
                  <div class="mt-2.5 flex flex-wrap gap-1 items-center">
                    <span
                      class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50"
                      v-for="role in $page.props.auth?.user?.role" :key="role.id">
                      <component :is="$getIcon('ShieldCheck')" class="w-3 h-3" />
                      {{ role.display_name }}
                    </span>
                  </div>
                </div>

                <!-- Navigation Links -->
                <div class="p-1 space-y-0.5">
                  <Link :href="route('profile.edit')" @click="isProfileDropdownOpen = false"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                  <component :is="$getIcon('User')" class="w-4 h-4 text-slate-400 dark:text-slate-500" />
                  <span>Pengaturan Profil</span>
                  </Link>

                  <Link :href="route('logout')" method="post" as="button" @click="isProfileDropdownOpen = false"
                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors cursor-pointer">
                  <component :is="$getIcon('logout-2')" class="w-4 h-4 text-red-500 dark:text-red-400" />
                  <span>Keluar Aplikasi</span>
                  </Link>
                </div>

              </div>
            </Transition>
          </div>
        </div>
      </header>

      <!-- HORIZONTAL NAVIGATION BAR -->
      <nav v-if="navigationMode === 'horizontal'" aria-label="Navigasi utama"
        class="relative z-30 shrink-0 overflow-visible border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-2 shadow-xs lg:px-8 transition-colors">
        <div class="flex flex-wrap items-center gap-1 pb-0.5">
          <HorizontalNavItem v-for="item in navigation" :key="item.id" :item="item" />
        </div>
      </nav>

      <!-- MAIN CONTENT -->
      <main class="flex-1 overflow-y-auto p-4 lg:p-8">
        <div class="w-full space-y-6">
          <!-- Header Title & Breadcrumbs -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ title }}</h1>
              <!-- 🟢 Tambahkan dark:text-slate-400 di sini -->
              <nav v-if="breadcrumbs.length > 0"
                class="flex items-center gap-1.5 mt-1 text-xs text-slate-500 dark:text-slate-400">
                <template v-for="(item, index) in breadcrumbs" :key="index">
                  <Link v-if="item.href" :href="item.href"
                    class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                  {{ item.label }}
                  </Link>
                  <!-- 🟢 Tambahkan dark:text-slate-200 di sini -->
                  <span v-else class="text-slate-800 dark:text-slate-200 font-medium">{{ item.label }}</span>
                  <span v-if="index < breadcrumbs.length - 1" class="text-slate-300 dark:text-slate-600">/</span>
                </template>
              </nav>
            </div>

            <div v-if="$slots.actions" class="flex items-center gap-3">
              <slot name="actions" />
            </div>
          </div>

          <!-- Area Konten Utama -->
          <slot />
        </div>
      </main>

      <footer
        class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-3.5 px-4 lg:px-8 text-xs text-slate-500 dark:text-slate-400 shrink-0 z-30">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
          <p>&copy; {{ new Date().getFullYear() }} <strong class="text-slate-700 dark:text-slate-200 font-semibold">{{ $page.props.appName
            ||
            'Laravel' }}</strong>. Hak Cipta Dilindungi.</p>
          <div class="flex items-center gap-4 text-slate-400 dark:text-slate-200">
            <span class="hover:text-slate-600 cursor-pointer">Bantuan</span>
            <span>&bull;</span>
            <span class="hover:text-slate-600 cursor-pointer">Kebijakan Privasi</span>
            <span>&bull;</span>
            <span class="text-slate-500 dark:text-slate-200 font-medium">{{ buildVersion }}</span>
          </div>
        </div>
      </footer>
      <UpdateNotifier />
    </div>
  </div>
</template>