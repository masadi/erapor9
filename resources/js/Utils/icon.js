import { defineAsyncComponent } from 'vue';

/**
 * Mengubah string format kebab-case, snake-case, atau spasi menjadi PascalCase
 * Contoh: "layout-dashboard" -> "LayoutDashboard", "user_profile" -> "UserProfile"
 */
const toPascalCase = (str) => {
  if (!str) return '';
  return str
    .replace(/[-_ ]+(.)?/g, (_, c) => (c ? c.toUpperCase() : ''))
    .replace(/^./, (c) => c.toUpperCase());
};

/**
 * Format nama komponen agar sesuai dengan konvensi Tabler Icons ("IconXxx")
 */
const formatIconName = (name) => {
  const pascalName = toPascalCase(name);
  return pascalName.startsWith('Icon') ? pascalName : `Icon${pascalName}`;
};

/**
 * Helper Global untuk load ikon Tabler secara dinamis berdasarkan nama string dari DB
 * @param {string} iconName - Nama ikon (contoh: "layout-dashboard", "Users", "settings")
 * @param {string} fallbackIcon - Nama ikon cadangan jika tidak ditemukan
 */
export const getTablerIcon = (iconName, fallbackIcon = 'Circle') => {
  const targetName = formatIconName(iconName || fallbackIcon);
  const fallbackName = formatIconName(fallbackIcon);

  return defineAsyncComponent(() =>
    import('@tabler/icons-vue')
      .then((module) => module[targetName] || module[fallbackName] || module.IconCircle)
      .catch(() =>
        import('@tabler/icons-vue').then((module) => module[fallbackName] || module.IconCircle)
      )
  );
};