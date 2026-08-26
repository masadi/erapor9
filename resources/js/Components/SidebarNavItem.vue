<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

defineOptions({
  name: 'SidebarNavItem'
});

const props = defineProps({
  item: { type: Object, default: () => ({}) },
  isCollapsed: { type: Boolean, default: false },
  depth: { type: Number, default: 1 }
});

const page = usePage();

// Helper URL Path Matching
const getPath = (url) => {
  if (!url || url === '#') return '';
  try {
    if (url.startsWith('http://') || url.startsWith('https://')) {
      return new URL(url).pathname;
    }
    return url.split('?')[0];
  } catch (e) {
    return url;
  }
};

const isChildActive = (href) => {
  if (!href || href === '#') return false;
  const currentPath = page.url.split('?')[0];
  const targetPath = getPath(href);
  if (!targetPath) return false;
  if (currentPath === targetPath) return true;
  if (targetPath !== '/' && currentPath.startsWith(targetPath + '/')) return true;
  return false;
};

// Pengecekan status aktif secara rekursif
const hasActiveChild = (menuItem) => {
  if (!menuItem || !menuItem.name) return false;

  if (menuItem.href && isChildActive(menuItem.href)) {
    return true;
  }

  if (Array.isArray(menuItem.children) && menuItem.children.length > 0) {
    return menuItem.children.some(child => child && hasActiveChild(child));
  }

  return false;
};

const isParentActive = computed(() => {
  return hasActiveChild(props.item);
});

// State untuk toggle buka/tutup dropdown
const isOpen = ref(false);

// Handler Event Accordion saat item lain di-klik
const handleAccordion = (event) => {
  const { openedId, depth: openedDepth } = event.detail;

  // Tutup menu ini JIKA:
  // 1. Menu lain di level/depth yang sama dibuka
  // 2. Menu ini BUKAN menu yang baru saja dibuka
  // 3. Menu ini TIDAK sedang memiliki anak/route yang sedang aktif di halaman saat ini
  if (openedDepth === props.depth && openedId !== props.item.id && !isParentActive.value) {
    isOpen.value = false;
  }
};

onMounted(() => {
  if (isParentActive.value) {
    isOpen.value = true;
  }
  // Dengarkan sinyal buka menu dari komponen selevel
  window.addEventListener('sidebar-menu-toggle', handleAccordion);
});

onUnmounted(() => {
  window.removeEventListener('sidebar-menu-toggle', handleAccordion);
});

// Watch perubahan route/URL
watch(
  () => page.url,
  () => {
    if (isParentActive.value) {
      isOpen.value = true;
    } else {
      isOpen.value = false;
    }
  }
);

const toggleDropdown = () => {
  if (!props.isCollapsed) {
    isOpen.value = !isOpen.value;

    // Jika menu baru saja DIBUKA, kirim sinyal ke item selevel untuk MENUTUP diri
    if (isOpen.value) {
      window.dispatchEvent(
        new CustomEvent('sidebar-menu-toggle', {
          detail: {
            openedId: props.item.id,
            depth: props.depth
          }
        })
      );
    }
  }
};
</script>

<template>
  <div v-if="item && item.name" class="w-full">
    <!-- MENU LEAF (TANPA SUB-MENU) -->
    <Link
      v-if="!item.children || item.children.length === 0"
      :href="item.href || '#'"
      :class="[
        'flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-all duration-200',
        isParentActive 
          ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/30' 
          : 'text-slate-400 hover:text-white hover:bg-slate-800/80',
        depth > 1 ? 'py-2 text-[13px]' : 'py-2.5 text-sm'
      ]"
      :title="isCollapsed ? item.name : ''"
    >
      <component 
        :is="$getIcon(item.icon || (depth > 1 ? 'Circle' : 'Folder'))" 
        :class="[
          'shrink-0',
          depth === 1 ? 'w-5 h-5' : 'w-3.5 h-3.5'
        ]" 
      />
      <span v-show="!isCollapsed" class="truncate">{{ item.name }}</span>
    </Link>

    <!-- PARENT MENU (MEMILIKI SUB-MENU LEVEL 2 ATAU 3) -->
    <div v-else>
      <button
        type="button"
        @click="toggleDropdown"
        :class="[
          'w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium transition-all duration-200',
          isParentActive 
            ? 'bg-slate-800/90 text-white font-bold border-l-4 border-indigo-500 pl-2.5' 
            : 'text-slate-400 hover:text-white hover:bg-slate-800/80',
          depth > 1 ? 'py-2 text-[13px]' : 'py-2.5 text-sm'
        ]"
        :title="isCollapsed ? item.name : ''"
      >
        <div class="flex items-center gap-3 overflow-hidden">
          <component 
            :is="$getIcon(item.icon || (depth > 1 ? 'FolderOpen' : 'Folder'))" 
            :class="[
              'shrink-0',
              depth === 1 ? 'w-5 h-5' : 'w-4 h-4',
              isParentActive ? 'text-indigo-400' : ''
            ]" 
          />
          <span v-show="!isCollapsed" class="truncate">{{ item.name }}</span>
        </div>

        <div v-show="!isCollapsed" class="shrink-0 ml-2">
          <component 
            :is="$getIcon(isOpen ? 'ChevronDown' : 'ChevronRight')" 
            :class="['w-3.5 h-3.5 transition-transform duration-200', isParentActive ? 'text-indigo-400' : 'text-slate-400']" 
          />
        </div>
      </button>

      <!-- DAFTAR SUB-MENU REKURSIF (LEVEL 2 & LEVEL 3) -->
      <div 
        v-show="isOpen && !isCollapsed" 
        :class="[
          'mt-1 space-y-1 border-l border-slate-800',
          depth === 1 ? 'ml-3.5 pl-2.5' : 'ml-3 pl-2'
        ]"
      >
        <template v-for="(child, idx) in item.children" :key="child?.id || child?.name || idx">
          <SidebarNavItem
            v-if="child"
            :item="child"
            :is-collapsed="isCollapsed"
            :depth="depth + 1"
          />
        </template>
      </div>
    </div>
  </div>
</template>