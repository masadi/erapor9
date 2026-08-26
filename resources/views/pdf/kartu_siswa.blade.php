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
        }
        .page-break {
            page-break-after: always;
        }

        /* CONTAINER KARTU UTAMA */
        .card-table {
            width: 85.6mm;
            height: 53.98mm;
            border-collapse: collapse;
            table-layout: fixed;
            background: #ffffff;
        }

        /* HEADER KARTU */
        .header-td {
            background-color: #1e1b4b; /* Indigo gelap */
            color: #ffffff;
            text-align: center;
            padding: 4px 6px;
            height: 11mm;
            vertical-align: middle;
        }
        .header-td h1 {
            font-size: 7.5pt !important;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #ffffff;
        }
        .header-td p {
            font-size: 5pt;
            margin: 1px 0 0 0;
            color: #c7d2fe;
        }

        /* BODY DEPAN */
        .body-td {
            padding: 4mm 5mm;
            vertical-align: top;
        }

        /* FOTO SISWA */
        .photo-td {
            width: 20mm;
            vertical-align: top;
            padding-right: 4mm;
        }
        .photo-container {
            width: 19mm;
            height: 25mm;
            border: 1px solid #cbd5e1;
            border-radius: 2px;
            background-color: #f8fafc;
            text-align: center;
            overflow: hidden;
        }
        .photo-container img {
            width: 100%;
            height: 100%;
        }

        /* BIODATA TABLE */
        .bio-table {
            width: 100%;
            border-collapse: collapse;
        }
        .bio-table td {
            font-size: 5.5pt;
            padding: 1.2px 0;
            vertical-align: top;
            line-height: 1.2;
        }
        .bio-table td.label {
            width: 17mm;
            color: #475569;
            font-weight: bold;
        }
        .bio-table td.colon {
            width: 2mm;
            color: #475569;
        }
        .bio-table td.val {
            color: #0f172a;
        }

        /* BODY BELAKANG */
        .back-body-td {
            padding: 3mm 6mm;
            text-align: center;
            vertical-align: middle;
        }
        .qr-img {
            width: 21mm;
            height: 21mm;
            margin-bottom: 1mm;
        }
        .uuid-text {
            font-size: 4.5pt;
            font-family: monospace;
            font-weight: bold;
            color: #475569;
            margin-bottom: 2mm;
        }
        .address-box {
            font-size: 5pt;
            color: #334155;
            line-height: 1.3;
            border-top: 1px dashed #cbd5e1;
            padding-top: 2mm;
        }

        /* ACCENT FOOTER BAR */
        .accent-td {
            height: 2.5mm;
            background-color: #4f46e5;
        }
    </style>
</head>
<body>

    <!-- ================= HALAMAN 1: KARTU DEPAN ================= -->
    <table class="card-table">
        <!-- Header -->
        <tr>
            <td class="header-td">
                KARTU TANDA SISWA
            </td>
        </tr>

        <!-- Content Body -->
        <tr>
            <td class="body-td">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <!-- Kolom Foto -->
                        <td class="photo-td">
                            <div class="photo-container">
                                @if($student->foto && file_exists(public_path('storage/' . $student->foto)))
                                    <img src="{{ public_path('storage/' . $student->foto) }}" alt="Foto">
                                @else
                                    <table style="width: 100%; height: 100%;">
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle; font-size: 14pt; color: #94a3b8;">
                                                👤
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </td>

                        <!-- Kolom Detail Biodata -->
                        <td style="vertical-align: top;">
                            <table class="bio-table">
                                <tr>
                                    <td class="label">Nama</td>
                                    <td class="colon">:</td>
                                    <td class="val" style="font-weight: bold; text-transform: uppercase;">{{ $student->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="label">NISN</td>
                                    <td class="colon">:</td>
                                    <td class="val" style="font-family: monospace;">{{ $student->nisn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">NIK</td>
                                    <td class="colon">:</td>
                                    <td class="val" style="font-family: monospace;">{{ $student->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">TTL</td>
                                    <td class="colon">:</td>
                                    <td class="val">
                                        {{ $student->tempat_lahir }}, {{ $student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Jenis Kelamin</td>
                                    <td class="colon">:</td>
                                    <td class="val">{{ $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        &nbsp;
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Accent Footer Bar -->
        <tr>
            <td class="accent-td"></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 2: KARTU BELAKANG ================= -->
    <table class="card-table">
        <!-- Header -->
        <tr>
            <td class="header-td">
                PRESENSI SYSTEM DIGITAL <br>
                Scan QR Code untuk Absensi Harian
            </td>
        </tr>

        <!-- Content Body -->
        <tr>
            <td class="back-body-td">
                <!-- QR Code SVG Base64 -->
                <img class="qr-img" src="data:image/svg+xml;base64,{!! base64_encode(\QrCode::format('svg')->size(120)->margin(0)->generate($student->id)) !!}" alt="QR Code">

                <div class="address-box">
                    <strong>Alamat Sekolah:</strong><br>
                    {{ $student->registrasiPd->sekolah->alamat ?? 'Jl. Pendidikan No. 1, Kota' }}
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </td>
        </tr>

        <!-- Accent Footer Bar -->
        <tr>
            <td class="accent-td"></td>
        </tr>
    </table>

</body>
</html>