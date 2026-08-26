<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import { IconEye as Eye, IconEyeOff as EyeOff, IconLock as Lock, IconMail as Mail, IconArrowRight as ArrowRight, IconLoader2 as Loader2, IconShieldCheck as ShieldCheck } from '@tabler/icons-vue';

const showPassword = ref(false);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

// Fitur Interaktif: Indikator Kekuatan Password Sederhana
/*const passwordStrength = computed(() => {
  const len = form.password.length;
  if (len === 0) return { score: 0, text: '', color: 'bg-slate-200' };
  if (len < 6) return { score: 1, text: 'Lemah', color: 'bg-red-500' };
  if (len < 10) return { score: 2, text: 'Sedang', color: 'bg-amber-500' };
  return { score: 3, text: 'Kuat', color: 'bg-emerald-500' };
});*/

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
    onError: (errors) => {
      // Jika terdeteksi session/CSRF kadaluarsa (Error 419)
      if (form.errors && Object.keys(form.errors).length === 0) {
        window.location.reload();
      }
    },
  });
};
</script>

<template>

  <Head title="Masuk ke Akun" />

  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

      <!-- SISI KIRI: FORM LOGIN -->
      <div class="p-8 sm:p-10 flex flex-col justify-between">
        <div>
          <!-- Logo & Header -->
          <div class="flex items-center gap-3 mb-8">
            <div
              class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
              S
            </div>
            <span class="font-bold text-xl text-slate-800 tracking-tight">{{ $page.props.appName || 'Laravel' }}</span>
          </div>

          <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali!</h1>
            <p class="text-sm text-slate-500 mt-1">Masukkan kredensial Anda untuk mengakses dashboard.</p>
          </div>

          <!-- Form Element -->
          <form @submit.prevent="submit" class="space-y-4">

            <!-- Email Input -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Mail class="w-5 h-5" />
                </div>
                <input v-model="form.email" required placeholder="Email/Username" :class="[
                  'w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all outline-none',
                  form.errors.email
                    ? 'border-red-500 focus:ring-2 focus:ring-red-200'
                    : 'border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100'
                ]" />
              </div>
              <p v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</p>
            </div>

            <!-- Password Input -->
            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Password</label>
                <Link :href="route('password.request')" class="text-xs text-indigo-600 hover:underline font-medium">
                  Lupa password?
                </Link>
              </div>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                  <Lock class="w-5 h-5" />
                </div>
                <input v-model="form.password" :type="showPassword ? 'text' : 'password'" required
                  placeholder="••••••••" :class="[
                    'w-full pl-10 pr-10 py-2.5 rounded-xl border text-sm transition-all outline-none',
                    form.errors.password
                      ? 'border-red-500 focus:ring-2 focus:ring-red-200'
                      : 'border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100'
                  ]" />
                <!-- Interactive Toggle Password Button -->
                <button type="button" @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                  <EyeOff v-if="showPassword" class="w-5 h-5" />
                  <Eye v-else class="w-5 h-5" />
                </button>
              </div>
              <p v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</p>

              <!-- Dynamic Password Strength Bar >
              <div v-if="form.password" class="mt-2 space-y-1">
                <div class="flex h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                  <div :class="[passwordStrength.color, 'transition-all duration-300']"
                    :style="{ width: (passwordStrength.score / 3) * 100 + '%' }"></div>
                </div>
                <p class="text-[10px] text-slate-400 text-right font-medium">
                  Kekuatan: <span class="font-semibold text-slate-700">{{ passwordStrength.text }}</span>
                </p>
              </div-->
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center justify-between pt-1">
              <label class="flex items-center gap-2 cursor-pointer">
                <input v-model="form.remember" type="checkbox"
                  class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                <span class="text-sm text-slate-600">Ingat Saya</span>
              </label>
            </div>

            <!-- Submit Button dengan Loading Animation -->
            <button type="submit" :disabled="form.processing"
              class="w-full mt-2 py-3 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 transition-all duration-200 disabled:opacity-70">
              <Loader2 v-if="form.processing" class="w-5 h-5 animate-spin" />
              <template v-else>
                <span>Masuk Sekarang</span>
                <ArrowRight class="w-4 h-4" />
              </template>
            </button>

          </form>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
          <p class="text-xs text-slate-400">
            &copy; {{ new Date().getFullYear() }} {{ $page.props.appName || 'Laravel' }}. Sistem Terintegrasi.
          </p>
        </div>
      </div>

      <!-- SISI KANAN: BANNER DEKORATIF (Desktop Only) -->
      <div
        class="hidden md:flex bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 p-10 flex-col justify-between text-white relative overflow-hidden">

        <!-- Background Pattern Ornaments -->
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-indigo-400/20 rounded-full blur-2xl"></div>

        <div
          class="relative z-10 flex items-center gap-2 text-indigo-200 text-xs font-semibold uppercase tracking-widest">
          <ShieldCheck class="w-4 h-4 text-emerald-400" /> Secure Environment
        </div>

        <div class="relative z-10 space-y-4 my-auto">
          <h2 class="text-3xl font-extrabold leading-tight">
            {{ $page.props.loginBanner?.title }}
          </h2>
          <p class="text-indigo-100 text-sm leading-relaxed">
            {{ $page.props.loginBanner?.description }}
          </p>
        </div>

        <div
          class="relative z-10 bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10 text-xs text-indigo-100">
          <p class="font-medium">Butuh bantuan akses?</p>
          <p class="text-indigo-300 mt-0.5">Hubungi tim administrator IT jika Anda lupa akun atau butuh otorisasi peran.
          </p>
        </div>

      </div>

    </div>
  </div>
</template>