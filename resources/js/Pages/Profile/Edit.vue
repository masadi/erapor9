<script setup>
const page = usePage();
const user = page.props.auth.user;

const avatarInput = ref(null);
const avatarPreview = ref(null);

// Form Profil
const profileForm = useForm({
  _method: 'PATCH', // Diperlukan Inertia saat mengirim File / FormData
  name: user.name,
  email: user.email,
  photo: null,
});

// Form Password
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showPasswordConfirmation = ref(false);

const passwordRequirements = computed(() => [
  {
    label: 'Minimal 8 karakter',
    valid: passwordForm.password.length >= 8,
  },
  {
    label: 'Memiliki huruf besar dan kecil',
    valid: /[A-Z]/.test(passwordForm.password) && /[a-z]/.test(passwordForm.password),
  },
  {
    label: 'Memiliki angka atau simbol',
    valid: /[\d\W_]/.test(passwordForm.password),
  },
]);

const passwordConfirmationStatus = computed(() => {
  if (!passwordForm.password_confirmation) return 'empty';
  return passwordForm.password === passwordForm.password_confirmation ? 'match' : 'mismatch';
});

const canUpdatePassword = computed(() =>
  passwordRequirements.value.every((requirement) => requirement.valid)
  && passwordConfirmationStatus.value === 'match'
);

const selectNewAvatar = () => {
  avatarInput.value.click();
};

const updateAvatarPreview = (e) => {
  const file = e.target.files[0];
  if (!file) return;

  profileForm.photo = file;

  const reader = new FileReader();
  reader.onload = (e) => {
    avatarPreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const removeAvatar = () => {
  if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
    router.delete(route('profile.photo.destroy'), {
      preserveScroll: true,
      onSuccess: () => {
        avatarPreview.value = null;
        profileForm.photo = null;
      }
    });
  }
};

const updateProfile = () => {
  // Gunakan method post dengan _method: 'PATCH' agar file upload terbaca sempurna oleh Laravel
  profileForm.post(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      profileForm.photo = null;
    }
  });
};

const updatePassword = () => {
  if (!canUpdatePassword.value) return;

  passwordForm.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => passwordForm.reset(),
  });
};

const breadcrumbs = [
  { label: 'Admin', href: route('dashboard') },
  { label: 'Profil Saya' }
];
</script>

<template>

  <Head title="Pengaturan Profil" />

  <AdminLayout title="Profil Saya" :breadcrumbs="breadcrumbs">
    <div class="w-full space-y-6">

      <!-- User Summary Card Header with Interactive Avatar -->
      <div
        class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row items-center gap-6">

        <!-- Avatar Wrapper -->
        <div class="relative group shrink-0">
          <div
            class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200 bg-indigo-600 text-white font-extrabold text-3xl flex items-center justify-center shadow-md">
            <img v-if="avatarPreview || user.photo" :src="avatarPreview || `/storage/${user.photo}`" alt="Avatar"
              class="w-full h-full object-cover" />
            <span v-else>{{ user.name.charAt(0).toUpperCase() }}</span>
          </div>

          <!-- Quick Overlay Upload Button -->
          <button type="button" @click="selectNewAvatar"
            class="absolute inset-0 bg-slate-900/60 rounded-full flex flex-col items-center justify-center text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
            <component :is="$getIcon('Camera')" class="w-5 h-5 mb-0.5" />
            <span>Ganti</span>
          </button>
        </div>

        <div class="text-center sm:text-left flex-1">
          <h2 class="text-xl font-bold text-slate-900">{{ user.name }}</h2>
          <p class="text-xs text-slate-500 mt-0.5">{{ user.email }}</p>

          <div class="flex items-center justify-center sm:justify-start gap-2 mt-3">
            <span
              class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200"
              v-for="role in user.role" :key="role.id">
              <component :is="$getIcon('ShieldCheck')" class="w-3.5 h-3.5" />
              {{ role.display_name }}
            </span>
            <span v-if="user.email_verified_at"
              class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
              <component :is="$getIcon('UserCheck')" class="w-3.5 h-3.5" />
              Terverifikasi
            </span>
          </div>
        </div>

        <!-- Remove Avatar Button if exists -->
        <button v-if="user.photo || avatarPreview" type="button" @click="removeAvatar"
          class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
          title="Hapus Foto Profil">
          <component :is="$getIcon('Trash')" class="w-5 h-5" />
        </button>
      </div>

      <!-- FORM 1: Update Profil Information -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-6">
          <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
            <component :is="$getIcon('User')" class="w-5 h-5" />
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-900">Informasi Profil</h3>
            <p class="text-xs text-slate-500">Perbarui informasi akun dan foto profil Anda.</p>
          </div>
        </div>

        <form @submit.prevent="updateProfile" class="space-y-4 max-w-xl">

          <!-- Hidden File Input -->
          <input type="file" ref="avatarInput" class="hidden" accept="image/*" @change="updateAvatarPreview" />

          <!-- Upload Avatar Input Area -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Foto Profil</label>
            <div class="flex items-center gap-3">
              <button type="button" @click="selectNewAvatar"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold border border-slate-200 transition-colors flex items-center gap-2">
                <component :is="$getIcon('Camera')" class="w-4 h-4" />
                <span>Pilih Foto Baru</span>
              </button>
              <span class="text-xs text-slate-400">JPG, PNG, WEBP (Maks. 2MB)</span>
            </div>
            <p v-if="profileForm.errors.photo" class="text-xs text-red-500 mt-1">{{ profileForm.errors.photo }}</p>
          </div>

          <!-- Nama -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
            <div class="relative">
              <component :is="$getIcon('User')" class="w-4 h-4 absolute left-3 top-3 text-slate-400" />
              <input v-model="profileForm.name" type="text" required
                class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-xl outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all" />
            </div>
            <p v-if="profileForm.errors.name" class="text-xs text-red-500 mt-1">{{ profileForm.errors.name }}</p>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email</label>
            <div class="relative">
              <component :is="$getIcon('Mail')" class="w-4 h-4 absolute left-3 top-3 text-slate-400" />
              <input v-model="profileForm.email" type="email" required
                class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-xl outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all" />
            </div>
            <p v-if="profileForm.errors.email" class="text-xs text-red-500 mt-1">{{ profileForm.errors.email }}</p>
          </div>

          <div class="flex items-center gap-4 pt-2">
            <button type="submit" :disabled="profileForm.processing"
              class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl flex items-center gap-2 shadow-md shadow-indigo-600/20 transition-all disabled:opacity-70">
              <component :is="$getIcon('Loader2')" v-if="profileForm.processing" class="w-4 h-4 animate-spin" />
              <span>Simpan Perubahan</span>
            </button>

            <span v-if="profileForm.recentlySuccessful"
              class="text-xs text-emerald-600 font-medium flex items-center gap-1">
              <component :is="$getIcon('CheckCircle2')" class="w-4 h-4" /> Tersimpan.
            </span>
          </div>
        </form>
      </div>

      <!-- FORM 2: Update Password -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-6">
          <div class="p-2 bg-amber-50 text-amber-600 rounded-xl">
            <component :is="$getIcon('Key')" class="w-5 h-5" />
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-900">Perbarui Password</h3>
            <p class="text-xs text-slate-500">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap
              aman.</p>
          </div>
        </div>

        <form @submit.prevent="updatePassword" class="space-y-4 max-w-xl">
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password Saat
              Ini</label>
            <div class="relative">
              <component :is="$getIcon('Lock')" class="w-4 h-4 absolute left-3 top-3 text-slate-400" />
              <input v-model="passwordForm.current_password" :type="showCurrentPassword ? 'text' : 'password'" required
                autocomplete="current-password"
                class="w-full pl-9 pr-10 py-2 text-sm border border-slate-200 rounded-xl outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all" />
              <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                :aria-label="showCurrentPassword ? 'Sembunyikan password saat ini' : 'Tampilkan password saat ini'"
                class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 transition-colors">
                <component :is="$getIcon(showCurrentPassword ? 'EyeOff' : 'Eye')" class="w-4 h-4" />
              </button>
            </div>
            <p v-if="passwordForm.errors.current_password" class="text-xs text-red-500 mt-1">{{
              passwordForm.errors.current_password }}</p>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password
              Baru</label>
            <div class="relative">
              <component :is="$getIcon('Lock')" class="w-4 h-4 absolute left-3 top-3 text-slate-400" />
              <input v-model="passwordForm.password" :type="showNewPassword ? 'text' : 'password'" required
                autocomplete="new-password"
                class="w-full pl-9 pr-10 py-2 text-sm border border-slate-200 rounded-xl outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all" />
              <button type="button" @click="showNewPassword = !showNewPassword"
                :aria-label="showNewPassword ? 'Sembunyikan password baru' : 'Tampilkan password baru'"
                class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 transition-colors">
                <component :is="$getIcon(showNewPassword ? 'EyeOff' : 'Eye')" class="w-4 h-4" />
              </button>
            </div>
            <p v-if="passwordForm.errors.password" class="text-xs text-red-500 mt-1">{{ passwordForm.errors.password }}
            </p>
            <div class="mt-2 space-y-1">
              <div v-for="requirement in passwordRequirements" :key="requirement.label"
                class="flex items-center gap-1.5 text-xs transition-colors"
                :class="requirement.valid ? 'text-emerald-600' : 'text-slate-400'">
                <component :is="$getIcon(requirement.valid ? 'CircleCheck' : 'Circle')" class="w-3.5 h-3.5" />
                <span>{{ requirement.label }}</span>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Password
              Baru</label>
            <div class="relative">
              <component :is="$getIcon('Lock')" class="w-4 h-4 absolute left-3 top-3 text-slate-400" />
              <input v-model="passwordForm.password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'" required
                autocomplete="new-password"
                class="w-full pl-9 pr-10 py-2 text-sm border border-slate-200 rounded-xl outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all" />
              <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
                :aria-label="showPasswordConfirmation ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password'"
                class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 transition-colors">
                <component :is="$getIcon(showPasswordConfirmation ? 'EyeOff' : 'Eye')" class="w-4 h-4" />
              </button>
            </div>
            <p v-if="passwordForm.errors.password_confirmation" class="text-xs text-red-500 mt-1">{{
              passwordForm.errors.password_confirmation }}</p>
            <p v-else-if="passwordConfirmationStatus === 'mismatch'"
              class="mt-1 flex items-center gap-1.5 text-xs text-red-500">
              <component :is="$getIcon('CircleX')" class="h-3.5 w-3.5" />
              Password belum sesuai.
            </p>
            <p v-else-if="passwordConfirmationStatus === 'match'"
              class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600">
              <component :is="$getIcon('CircleCheck')" class="h-3.5 w-3.5" />
              Password sudah sesuai.
            </p>
          </div>

          <div class="flex items-center gap-4 pt-2">
            <button type="submit" :disabled="passwordForm.processing || !canUpdatePassword"
              class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl flex items-center gap-2 shadow-md shadow-amber-600/20 transition-all disabled:opacity-70">
              <component :is="$getIcon('Loader2')" v-if="passwordForm.processing" class="w-4 h-4 animate-spin" />
              <span>Ubah Password</span>
            </button>

            <span v-if="passwordForm.recentlySuccessful"
              class="text-xs text-emerald-600 font-medium flex items-center gap-1">
              <component :is="$getIcon('CheckCircle2')" class="w-4 h-4" /> Password berhasil diubah.
            </span>
          </div>
        </form>
      </div>

    </div>
  </AdminLayout>
</template>