<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Sistem Dalam Pemeliharaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-2xl p-8 text-center shadow-2xl">
        <div class="w-16 h-16 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-amber-400 font-bold text-2xl">
            🛠️
        </div>
        <h1 class="text-2xl font-bold text-white mb-2">Sistem Sedang Diperbarui</h1>
        <p class="text-slate-400 text-sm mb-6">
            Aplikasi {{config('app.name')}} saat ini sedang dalam perawatan rutin. Kami akan segera kembali online.
        </p>
        <button onclick="window.location.reload()" class="w-full py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm transition-colors">
            Cek Status Sekarang
        </button>
    </div>
</body>
</html>