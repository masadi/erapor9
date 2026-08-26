import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import Icons from 'unplugin-icons/vite';
import IconsResolver from 'unplugin-icons/resolver';

const maxJavaScriptChunkSize = 100 * 1024;
import fs from 'node:fs'
import path from 'node:path'
import { execSync } from 'child_process';
// Hitung versi dan tulis ke file JSON di folder public
const getAppVersion = () => {
  try {
    const gitCommits = parseInt(execSync('git rev-list --count HEAD', { encoding: 'utf8' }).trim(), 10) || 0
    const totalCommits = gitCommits + 1
    const major = 1
    const dynamicMinor = Math.floor(totalCommits / 1000)
    const lastThreeDigits = totalCommits % 1000
    const buildPadded = String(lastThreeDigits).padStart(3, '0')
    const version = `${major}.${dynamicMinor}.${buildPadded}`

    // 🟢 Ambil nama author komit terakhir dari Git
    const author = execSync('git log -1 --pretty=format:"%an"', { encoding: 'utf8' }).trim() || 'Direktorat SMP'

    // 🟢 Ambil tanggal/waktu komit terakhir (Format ISO / Opsional)
    const commitTime = execSync('git log -1 --pretty=format:"%cd" --date=format:"%d-%m-%Y %H:%M"', { encoding: 'utf8' }).trim()

    // Simpan file version ke folder public Laravel
    const versionFilePath = path.resolve(__dirname, 'public/build-version.json')
    fs.writeFileSync(
      versionFilePath, 
      JSON.stringify({ 
        version, 
        author, 
        commitTime 
      })
    )
    return version
  } catch (e) {
    return '1.0.000'
  }
}
export default defineConfig({
    // 2. Pasang 'define' di sini agar getAppVersion() dipanggil saat build/dev
    define: {
        'process.env': {},
        '__APP_VERSION__': JSON.stringify(getAppVersion()),
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        // 1. AUTO IMPORT FUNGSI & COMPOSABLES (PERBAIKAN DI SINI)
        AutoImport({
            imports: [
                'vue', // Preset resmi Vue (ref, computed, watch, onMounted, dll.)
                {
                    // Pemetaan manual fungsi/composables dari @inertiajs/vue3
                    '@inertiajs/vue3': [
                        'usePage',
                        'useForm',
                        'router',
                        'useRemember',
                        'Head',
                    ],
                },
            ],
            dts: 'resources/js/auto-imports.d.ts',
        }),
        // 2. Auto Import Komponen & Ikon
        Components({
            dirs: ['resources/js/Components', 'resources/js/Layouts'],
            resolvers: [
                // Auto Import Ikon Tabler (<IconTablerCheck />, <IconTablerUser />, dll.)
                IconsResolver({
                    prefix: 'icon',
                    enabledCollections: ['tabler'],
                }),

                // Auto Import Komponen Inertia (<Link />, <Head />)
                (componentName) => {
                    if (['Link', 'Head'].includes(componentName)) {
                        return { name: componentName, from: '@inertiajs/vue3' };
                    }
                },
            ],
            dts: 'resources/js/components.d.ts',
        }),

        // 3. Plugin Icons Engine
        Icons({
            autoInstall: true,
        }),
        {
            name: 'warn-large-javascript-chunks',
            generateBundle(_, bundle) {
                Object.values(bundle).forEach((output) => {
                    if (output.type !== 'chunk' || output.name === 'icons') return;

                    const sizeInBytes = Buffer.byteLength(output.code, 'utf8');
                    if (sizeInBytes > maxJavaScriptChunkSize) {
                        this.warn(
                            `${output.fileName} is ${(sizeInBytes / 1024).toFixed(1)} kB; limit is 100 kB.`,
                        );
                    }
                });
            },
        },
    ],
    build: {
        // Vite's default warning cannot exclude a specific chunk, so the
        // custom check below skips the dedicated Tabler icons chunk.
        target: 'esnext',
        chunkSizeWarningLimit: 1000000,
        rollupOptions: {
            output: {
                manualChunks: {
                    icons: ['@tabler/icons-vue'],
                },
            },
        },
    },
    optimizeDeps: {
        include: [
            '@fullcalendar/vue3',
            '@fullcalendar/daygrid',
            '@fullcalendar/interaction',
        ],
    },
});