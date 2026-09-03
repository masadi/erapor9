<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

defineOptions({
  name: 'HorizontalNavItem'
});

const props = defineProps({
  item: { type: Object, default: () => ({}) },
  depth: { type: Number, default: 1 }
});

const page = usePage();
const isOpen = ref(false);
let closeTimer;

const getPath = (url) => {
  if (!url || url === '#') return '';

  try {
    return new URL(url, window.location.origin).pathname;
  } catch {
    return url.split('?')[0];
  }
};

const isActive = (href) => {
  const targetPath = getPath(href);
  const currentPath = page.url.split('?')[0];

  return Boolean(targetPath) && (currentPath === targetPath
    || (targetPath !== '/' && currentPath.startsWith(`${targetPath}/`)));
};

const hasActiveChild = (menuItem) => {
  if (!menuItem) return false;
  if (isActive(menuItem.href)) return true;
  return Array.isArray(menuItem.children) && menuItem.children.some(hasActiveChild);
};

const isParentActive = computed(() => hasActiveChild(props.item));
const hasChildren = computed(() => Array.isArray(props.item.children) && props.item.children.length > 0);

const handleToggle = (event) => {
  const { openedId, depth } = event.detail;
  if (depth === props.depth && openedId !== props.item.id && !isParentActive.value) {
    isOpen.value = false;
  }
};

const handleClickOutside = (event) => {
  if (!event.target.closest(`[data-horizontal-menu="${props.item.id}"]`)) {
    isOpen.value = false;
  }
};

const openDropdown = () => {
  clearTimeout(closeTimer);
  isOpen.value = true;
  window.dispatchEvent(new CustomEvent('horizontal-menu-toggle', {
    detail: { openedId: props.item.id, depth: props.depth }
  }));
};

const closeDropdown = () => {
  clearTimeout(closeTimer);
  closeTimer = window.setTimeout(() => {
    isOpen.value = false;
  }, 180);
};

watch(() => page.url, () => {
  isOpen.value = false;
});

onMounted(() => {
  if (isParentActive.value && props.depth === 1) isOpen.value = true;
  window.addEventListener('horizontal-menu-toggle', handleToggle);
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  clearTimeout(closeTimer);
  window.removeEventListener('horizontal-menu-toggle', handleToggle);
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div v-if="item && item.name" class="relative shrink-0" :data-horizontal-menu="item.id"
    @mouseenter="hasChildren && openDropdown()" @mouseleave="hasChildren && closeDropdown()">
    <!-- Single Link (Tanpa Child) -->
    <Link v-if="!hasChildren" :href="item.href || '#'" :class="[
      'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors cursor-pointer',
      isActive(item.href)
        ? 'bg-indigo-600 text-white shadow-sm dark:bg-indigo-600 dark:text-white'
        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-700 dark:hover:text-indigo-400'
    ]">
      <component :is="$getIcon(item.icon || 'Circle')" class="h-4 w-4 shrink-0" />
      <span class="whitespace-nowrap">{{ item.name }}</span>
    </Link>

    <!-- Parent Button (Dengan Child/Dropdown) -->
    <button v-else type="button" :aria-expanded="isOpen" :aria-haspopup="true" :class="[
      'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors cursor-pointer',
      isParentActive
        ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50'
        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-700 dark:hover:text-indigo-400'
    ]">
      <component :is="$getIcon(item.icon || 'Folder')" class="h-4 w-4 shrink-0" />
      <span class="whitespace-nowrap">{{ item.name }}</span>
      <component :is="$getIcon(isOpen ? 'ChevronRight' : 'ChevronDown')" class="h-3.5 w-3.5 opacity-70" />
    </button>

    <!-- Dropdown Container -->
    <div v-show="isOpen" :class="[
      'absolute z-50 min-w-56 rounded-xl border bg-white dark:bg-slate-900 p-1.5 shadow-xl border-slate-200 dark:border-slate-800 transition-colors',
      depth === 1 ? 'left-0 top-full mt-2' : 'left-full top-0 ml-1'
    ]" role="menu">
      <HorizontalNavItem v-for="child in item.children" :key="child.id" :item="child" :depth="depth + 1" />
    </div>
  </div>
</template>