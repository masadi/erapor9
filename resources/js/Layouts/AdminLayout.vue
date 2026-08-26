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
const navigationStorageKey = 'dwk-navigation-mode';

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

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isProfileDropdownOpen.value = false;
  }
};

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
  <div class="h-screen w-screen bg-slate-100 flex overflow-hidden text-slate-800">

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
            <div
              class="w-8 flex items-center justify-center font-bold text-white shrink-0 overflow-hidden">
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
        <component :is="$getIcon('LogOut')" class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" />
        <span v-show="!isCollapsed" class="truncate">Keluar Aplikasi</span>
        </Link>
      </div>
    </aside>

    <!-- RIGHT MAIN AREA -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden relative">
      <header
        class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 shrink-0 z-30">

        <!-- HEADER LEFT AREA (Mobile Toggle & Periode Aktif Badge) -->
        <div class="flex items-center gap-3">
          <button v-if="navigationMode === 'vertical'" @click="toggleMobileSidebar" class="lg:hidden p-2 rounded-md text-slate-600 hover:bg-slate-100">
            <component :is="$getIcon('Menu')" class="w-6 h-6" />
          </button>

          <!-- BADGE PERIODE SEMESTER AKTIF -->
          <div
            class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50/80 border border-indigo-100/80 text-indigo-700 text-xs font-semibold shadow-2xs">
            <component :is="$getIcon('building-bank')" class="w-4 h-4 text-indigo-600 shrink-0" />
            <span class="whitespace-nowrap"> {{ $page.props.sekolah?.nama }}</span>
          </div>
          <div
            class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50/80 border border-indigo-100/80 text-indigo-700 text-xs font-semibold shadow-2xs">
            <component :is="$getIcon('Calendar')" class="w-4 h-4 text-indigo-600 shrink-0" />
            <span class="whitespace-nowrap"> {{ $page.props.periodeAktif }}</span>
          </div>
        </div>
        <div class="flex items-center gap-3 ml-auto">

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
          <button @click="toggleProfileDropdown"
            class="w-10 h-10 rounded-full overflow-hidden border-2 border-indigo-100 hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0 transition-all shadow-sm">
            <img v-if="$page.props.auth?.user?.photo" :src="`/storage/${$page.props.auth.user.photo}`" alt="Foto"
              class="w-full h-full object-cover" />
            <span v-else class="text-sm">{{ $page.props.auth?.user?.name?.charAt(0).toUpperCase() }}</span>
          </button>

          <div v-show="isProfileDropdownOpen"
            class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transition-all transform origin-top-right">
            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
              <p class="text-sm font-bold text-slate-900 truncate">{{ $page.props.auth?.user?.name || 'Administrator' }}
              </p>
              <p class="text-xs text-slate-500 truncate mt-0.5">{{ $page.props.auth?.user?.email || 'admin@example.com'
              }}</p>
              <div class="mt-2.5 flex items-center">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100"
                  v-for="role in $page.props.auth?.user?.role" :key="role.id">
                  <component :is="$getIcon('ShieldCheck')" class="w-3 h-3" />
                  {{ role.display_name }}
                </span>
              </div>
            </div>

            <div class="p-1 space-y-0.5">
              <Link :href="route('profile.edit')" @click="isProfileDropdownOpen = false"
                class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 hover:bg-slate-100 transition-colors">
              <component :is="$getIcon('User')" class="w-4 h-4 text-slate-400" />
              <span>Pengaturan Profil</span>
              </Link>
              <Link :href="route('logout')" method="post" as="button" @click="isProfileDropdownOpen = false"
                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
              <component :is="$getIcon('LogOut')" class="w-4 h-4 text-red-500" />
              <span>Keluar Aplikasi</span>
              </Link>
            </div>
          </div>
        </div>
        </div>
      </header>

      <nav
        v-if="navigationMode === 'horizontal'"
        aria-label="Navigasi utama"
        class="relative z-40 shrink-0 overflow-visible border-b border-slate-200 bg-white px-4 py-2 shadow-sm lg:px-8"
      >
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
              <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ title }}</h1>
              <nav v-if="breadcrumbs.length > 0" class="flex items-center gap-1.5 mt-1 text-xs text-slate-500">
                <template v-for="(item, index) in breadcrumbs" :key="index">
                  <Link v-if="item.href" :href="item.href" class="hover:text-indigo-600 transition-colors">{{ item.label
                  }}</Link>
                  <span v-else class="text-slate-800 font-medium">{{ item.label }}</span>
                  <span v-if="index < breadcrumbs.length - 1" class="text-slate-300">/</span>
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

      <footer class="bg-white border-t border-slate-200 py-3.5 px-4 lg:px-8 text-xs text-slate-500 shrink-0 z-30">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
          <p>&copy; {{ new Date().getFullYear() }} <strong class="text-slate-700 font-semibold">{{ $page.props.appName
            ||
            'Laravel' }}</strong>. Hak Cipta Dilindungi.</p>
          <div class="flex items-center gap-4 text-slate-400">
            <span class="hover:text-slate-600 cursor-pointer">Bantuan</span>
            <span>&bull;</span>
            <span class="hover:text-slate-600 cursor-pointer">Kebijakan Privasi</span>
            <span>&bull;</span>
            <span class="text-slate-500 font-medium">{{buildVersion}}</span>
          </div>
        </div>
      </footer>
      <UpdateNotifier />
    </div>
  </div>
</template>