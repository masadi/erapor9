<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            size: 85.6mm 53.98mm landscape;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
        }

        .page-break {
            page-break-after: always;
        }

        /* CONTAINER KARTU UTAMA (CR-80 EXACT SIZE) */
        .card {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            background-color: #ffffff;
        }

        /* BACKGROUND IMAGE CUSTOM */
        .card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 85.6mm;
            height: 53.98mm;
            z-index: 1;
        }

        /* Pastikan tinggi card-content persis sama dengan ukuran kartu */
        .card-content {
            position: relative;
            z-index: 2;
            width: 85.6mm;
            height: 53.98mm;
            /* UBAH DARI 100% KE 53.98mm PASTI */
            box-sizing: border-box;
            padding: 3mm 4mm;
        }

        /* TABLE LAYOUT HELPER */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            padding: 0;
            vertical-align: top;
        }

        /* HEADER DEPAN */
        .header-table {
            border-bottom: 1.5px solid {{ $settings['accent_color'] ?? '#4f46e5' }};
            padding-bottom: 1.5mm;
            margin-bottom: 2mm;
        }

        .logo-school {
            width: 7mm;
            height: 7mm;
        }

        .school-title {
            font-size: 6.5pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1;
            margin: 0;
        }

        .school-subtitle {
            font-size: 4.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 0.8px;
        }
        .badge-kts {
            width: 10mm; text-align: right; padding:1mm;
            font-weight: bold;
            font-size: 4.5pt;
            margin-top: 2mm;
            vertical-align: middle;
        }
        div.badge-kts-text {
            background-color: {{ $settings['accent_color'] ?? '#4f46e5' }};
            border: 1px solid {{ $settings['accent_color'] ?? '#4f46e5' }};
            color: #ffffff;
            border-radius: 5px;
            /*font-size: 4.5pt;
            font-weight: bold;
            padding: 2mm;
            
            text-align: center;
            float: left;
            margin-right: 10px;
            text-transform: uppercase;*/
        }

        /* BODY DEPAN (FOTO + BIODATA) */
        .photo-box {
            width: 17mm !important;
            height: 22.5mm !important;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            overflow: hidden;
            background-color: #f1f5f9;
            text-align: center;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bio-table {
            margin-left: 2mm;
        }

        .bio-table td {
            font-size: 4.8pt;
            padding: 0.8px 0;
            line-height: 1.15;
            color: #334155;
        }

        .bio-table td.label {
            width: 13mm;
            font-weight: bold;
            color: #475569;
        }

        .bio-table td.colon {
            width: 1.5mm;
        }

        .bio-table td.val {
            font-weight: 600;
            color: #0f172a;
        }

        /* Terapkan position absolute pada DIV footer (bukan pada table) */
        div.footer-front {
            position: absolute;
            bottom: 0px;
            border-top: 1px solid #e2e8f0;
            padding: 1mm 4mm;
            font-size: 4pt;
            color: #000000;
            background: #f1f2f3;
            /* #64748b;*/
        }

        /* ================= BELAKANG KARTU ================= */
        .header-back {
            border-bottom: 1.5px solid {{ $settings['accent_color'] ?? '#4f46e5' }};
            padding-bottom: 1.5mm;
            margin-bottom: 2mm;
        }

        .back-title {
            font-size: 6pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
        }

        .back-subtitle {
            font-size: 4pt;
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* CONTENT BELAKANG (QR & RULES) */
        .qr-container {
            width: 17mm;
            text-align: center;
        }

        .qr-code {
            border: 1px solid #e2e8f0;
            width: 15mm;
            height: 15mm;
            padding: 2px;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            margin-bottom: 1mm;
            margin-top: 5mm;
        }

        .nisn-text {
            font-size: 4pt;
            font-family: monospace;
            font-weight: bold;
            color: #475569;
        }

        .rules-title {
            font-size: 4.8pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 1mm;
        }

        .rules-list {
            margin: 0;
            padding-left: 3mm;
            font-size: 5.8pt;
            color: #334155;
            line-height: 2em;
        }

        /* STEMPEL & TTD SECTION */
        .sign-section {
            position: absolute;
            bottom: 10mm;
            right: 4mm;
            width: 32mm;
            text-align: center;
            font-size: 4pt;
            color: #334155;
        }

        .sign-area {
            position: relative;
            height: 7mm;
            margin: 0.5mm 0;
        }

        .img-stamp {
            position: absolute;
            right: 8mm;
            top: -2mm;
            height: 9mm;
            width: auto;
            opacity: 0.75;
            z-index: 1;
        }

        .img-signature {
            position: relative;
            height: 7mm;
            width: auto;
            z-index: 2;
        }

        .principal-name {
            font-size: 4.5pt;
            font-weight: bold;
            color: #0f172a;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    @foreach ($students as $student)
        <!-- ================= KARTU DEPAN ================= -->
        <div class="card">
            <!-- Background Depan (jika diupload di settings) -->
            @if (isset($settings['bg_front_path']) && file_exists(storage_path('app/public/' . $settings['bg_front_path'])))
                <img src="{{ storage_path('app/public/' . $settings['bg_front_path']) }}" class="card-bg">
            @endif

            <div class="card-content">
                <!-- Header Card -->
                <table class="header-table">
                    <tr>
                        <td style="width: 8mm;">
                            <div
                                style="width: 6.5mm; height: 6.5mm; background: #1e1b4b; border-radius: 2px; text-align: center; color: #fff; font-size: 4.5pt; line-height: 6.5mm; font-weight: bold;">
                                🏫
                            </div>
                        </td>
                        <td>
                            <h1 class="school-title">{{ $student->registrasiPd->sekolah->nama ?? 'SMA NEGERI 1 CONTOH' }}
                            </h1>
                            <div class="school-subtitle">
                                {{ $settings['subtitle_header'] ?? 'KARTU TANDA SISWA RESMI (KTS)' }}</div>
                        </td>
                        <td class="badge-kts">
                            <div class="badge-kts-text">KTS</div>
                        </td>
                    </tr>
                </table>

                <!-- Body Card (Foto & Biodata) -->
                <table style="margin-top: 10px;">
                    <tr>
                        <td style="width: 18mm;">
                            <div class="photo-box">
                                @if ($student->foto && file_exists(public_path('storage/' . $student->foto)))
                                    <img src="{{ public_path('storage/' . $student->foto) }}" alt="Foto">
                                @else
                                    <div style="padding-top: 6mm; font-size: 14pt; color: #cbd5e1;">
                                        Foto
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <table class="bio-table">
                                <tr>
                                    <td class="label">Nama</td>
                                    <td class="colon">:</td>
                                    <td class="val" style="text-transform: uppercase; font-size: 5.2pt;">
                                        {{ $student->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="label">NISN</td>
                                    <td class="colon">:</td>
                                    <td class="val" style="font-family: monospace;">{{ $student->nisn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">TTL</td>
                                    <td class="colon">:</td>
                                    <td class="val" style="font-family: monospace;">
                                        {{ $student->tempat_lahir ?? '-' }}, {{ $student->tanggal_lahir }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Jenis Kelamin</td>
                                    <td class="colon">:</td>
                                    <td class="val">{{ $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Footer Front (Bungkus tabel dengan DIV) -->

            </div>
        </div>
        <div class="footer-front">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="font-size: 4pt; color: #64748b;">Berlaku Selama Menjadi Siswa</td>
                    <td style="text-align: right; font-size: 4pt; color: #64748b; font-family: monospace;">
                        ID: {{ $student->nisn ?? $student->id }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="page-break"></div>

        <!-- ================= KARTU BELAKANG ================= -->
        <div class="card">
            <!-- Background Belakang (jika diupload di settings) -->
            @if (isset($settings['bg_back_path']) && file_exists(storage_path('app/public/' . $settings['bg_back_path'])))
                <img src="{{ storage_path('app/public/' . $settings['bg_back_path']) }}" class="card-bg">
            @endif

            <div class="card-content">
                <!-- Header Back -->
                <table class="header-back">
                    <tr>
                        <td>
                            <h1 class="back-title">QR ABSENSI DIGITAL</h1>
                            <div class="back-subtitle">TATA TERTIB & KETENTUAN KARTU</div>
                        </td>
                        <td class="badge-kts">
                            <div class="badge-kts-text">SCAN</div>
                        </td>
                    </tr>
                </table>

                <!-- Body Back (QR Code + Rules) -->
                <table>
                    <tr>
                        <td class="qr-container">
                            <img class="qr-code" src="data:image/svg+xml;base64,{!! base64_encode(\QrCode::format('svg')->size(100)->margin(0)->generate($student->id)) !!}" alt="QR">
                            <div class="nisn-text">NISN: {{ $student->nisn ?? '-' }}</div>
                        </td>
                        <td style="padding-left: 2mm;">
                            <div class="rules-title">Ketentuan Penggunaan:</div>
                            <ol class="rules-list">
                                @if (isset($settings['rules']))
                                    @foreach (explode("\n", $settings['rules']) as $rule)
                                        @if (trim($rule) != '')
                                            <li>{{ preg_replace('/^[0-9]+\.\s*/', '', trim($rule)) }}</li>
                                        @endif
                                    @endforeach
                                @else
                                    <li>Kartu ini adalah identitas resmi siswa sekolah.</li>
                                    <li>Wajib dibawa & ditunjukkan saat presensi QR.</li>
                                    <li>Kartu tidak dapat dipindahtangankan.</li>
                                @endif
                            </ol>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="sign-section">
            <div>Pamekasan, {{ date('Y') }}</div>
            <div>Kepala Sekolah,</div>

            <div class="sign-area">
                <!-- Stempel Sekolah -->
                @if (isset($settings['stamp_path']) && file_exists(storage_path('app/public/' . $settings['stamp_path'])))
                    <img src="{{ storage_path('app/public/' . $settings['stamp_path']) }}" class="img-stamp">
                @endif

                <!-- Tanda Tangan Kepsek -->
                @if (isset($settings['signature_path']) && file_exists(storage_path('app/public/' . $settings['signature_path'])))
                    <img src="{{ storage_path('app/public/' . $settings['signature_path']) }}" class="img-signature">
                @endif
            </div>

            <div class="principal-name">{{ $settings['principal_name'] ?? 'Dr. H. Ahmad Wijaya, M.Pd.' }}</div>
        </div>
        <div class="footer-front">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="font-size: 4pt; color: #64748b;">Yayasan Darul Karomah</td>
                    <td style="text-align: right; font-size: 4pt; color: #64748b;">
                        Jika menemukan kartu ini, harap kembalikan ke Sekolah
                    </td>
                </tr>
            </table>
        </div>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
