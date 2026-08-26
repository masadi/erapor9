<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        .header-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
        }

        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 10px;
        }

        .info-table td.label {
            font-weight: bold;
            color: #475569;
            width: 15%;
            text-transform: uppercase;
        }

        .info-table td.value {
            font-weight: bold;
            color: #0f172a;
            width: 35%;
        }

        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .summary-box td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            text-align: center;
            background-color: #ffffff;
        }

        .summary-box td .num {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }

        .summary-box td .lbl {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #94a3b8;
            padding: 4px 5px;
            font-size: 9px;
        }

        table.data-table th {
            background-color: #e2e8f0;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        table.data-table td.text-center {
            text-align: center;
        }

        table.data-table td.font-bold {
            font-weight: bold;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8px;
            text-align: center;
        }

        .badge-hadir { background-color: #d1fae5; color: #065f46; }
        .badge-terlambat { background-color: #fef3c7; color: #92400e; }
        .badge-izin { background-color: #e0f2fe; color: #075985; }
        .badge-sakit { background-color: #e0e7ff; color: #3730a3; }
        .badge-alpa { background-color: #ffe4e6; color: #9f1239; }

        .footer-sign {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .footer-sign td {
            width: 50%;
            vertical-align: top;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- JUDUL LAPORAN -->
    <div class="header-title">
        REKAPITULASI PRESENSI SISWA
    </div>

    <!-- HEADER INFORMATION BOX -->
    <table class="info-table">
        <tr>
            <td class="label">NAMA SEKOLAH</td>
            <td class="value">: {{ $info['nama_sekolah'] }}</td>
            <td class="label">NAMA KELAS</td>
            <td class="value">: {{ $info['nama_kelas'] }}</td>
        </tr>
        <tr>
            <td class="label">NPSN</td>
            <td class="value">: {{ $info['npsn'] }}</td>
            <td class="label">TAHUN AJARAN</td>
            <td class="value">: {{ $info['tahun_ajaran'] }}</td>
        </tr>
        <tr>
            <td class="label">JENIS REKAP</td>
            <td class="value">: {{ strtoupper($info['jenis_rekap']) }}</td>
            <td class="label">PERIODE</td>
            <td class="value">: {{ $info['periode'] }}</td>
        </tr>
    </table>

    <!-- RINGKASAN REKAPITULASI STATISTIK -->
    <table class="summary-box">
        <tr>
            <td>
                <div class="num">{{ $summary['total_siswa'] ?? 0 }}</div>
                <div class="lbl">TOTAL SISWA</div>
            </td>
            <td>
                <div class="num" style="color: #059669;">{{ $summary['total_hadir'] ?? 0 }}</div>
                <div class="lbl">TOTAL HADIR</div>
            </td>
            <td>
                <div class="num" style="color: #d97706;">{{ $summary['total_terlambat'] ?? 0 }}</div>
                <div class="lbl">TERLAMBAT</div>
            </td>
            <td>
                <div class="num" style="color: #0284c7;">{{ ($summary['total_izin'] ?? 0) + ($summary['total_sakit'] ?? 0) }}</div>
                <div class="lbl">IZIN / SAKIT</div>
            </td>
            <td>
                <div class="num" style="color: #dc2626;">{{ $summary['total_alpa'] ?? 0 }}</div>
                <div class="lbl">ALPA</div>
            </td>
            <td>
                <div class="num" style="color: #4f46e5;">{{ $summary['persentase'] ?? 0 }}%</div>
                <div class="lbl">RATA-RATA KEHADIRAN</div>
            </td>
        </tr>
    </table>

    <!-- TABLE REKAP DATA PER TYPE -->

    <!-- MODE 1: HARIAN -->
    @if($info['type'] === 'harian')
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">NO.</th>
                <th style="width: 100px;">NISN</th>
                <th>NAMA SISWA</th>
                <th style="width: 40px;">L/P</th>
                <th style="width: 70px;">JAM MASUK</th>
                <th style="width: 70px;">JAM PULANG</th>
                <th style="width: 90px;">STATUS PRESENSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapData['list'] as $student)
            <tr>
                <td class="text-center">{{ $student['no'] }}</td>
                <td class="text-center">{{ $student['nisn'] ?: '-' }}</td>
                <td class="font-bold">{{ $student['nama'] }}</td>
                <td class="text-center">{{ $student['jenis_kelamin'] }}</td>
                <td class="text-center">{{ $student['check_in'] }}</td>
                <td class="text-center">{{ $student['check_out'] }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $student['status'] }}">
                        {{ strtoupper($student['status']) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data presensi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- MODE 2: MINGGUAN -->
    @elseif($info['type'] === 'mingguan')
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">NO</th>
                <th rowspan="2">NAMA SISWA</th>
                <th rowspan="2" style="width: 25px;">L/P</th>
                <th colspan="{{ count($rekapData['days']) }}">HARI EFEKTIF MINGGU INI</th>
                <th colspan="5">TOTAL</th>
                <th rowspan="2" style="width: 45px;">% HADIR</th>
            </tr>
            <tr>
                @foreach($rekapData['days'] as $d)
                <th style="width: 28px;">{{ strtoupper($d['short_day']) }}<br><span style="font-weight:normal; font-size: 7px;">{{ $d['day_num'] }}</span></th>
                @endforeach
                <th style="width: 22px; background-color: #d1fae5; color: #065f46;">H</th>
                <th style="width: 22px; background-color: #fef3c7; color: #92400e;">T</th>
                <th style="width: 22px; background-color: #e0f2fe; color: #075985;">I</th>
                <th style="width: 22px; background-color: #e0e7ff; color: #3730a3;">S</th>
                <th style="width: 22px; background-color: #ffe4e6; color: #9f1239;">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData['list'] as $student)
            <tr>
                <td class="text-center">{{ $student['no'] }}</td>
                <td class="font-bold">{{ $student['nama'] }}</td>
                <td class="text-center">{{ $student['jenis_kelamin'] }}</td>
                @foreach($rekapData['days'] as $d)
                @php $st = $student['daily_status'][$d['date']] ?? 'alpa'; @endphp
                <td class="text-center font-bold">
                    @if($st === 'hadir') <span style="color:#059669;">H</span>
                    @elseif($st === 'terlambat') <span style="color:#d97706;">T</span>
                    @elseif($st === 'izin') <span style="color:#0284c7;">I</span>
                    @elseif($st === 'sakit') <span style="color:#4338ca;">S</span>
                    @else <span style="color:#dc2626;">A</span>
                    @endif
                </td>
                @endforeach
                <td class="text-center font-bold" style="background-color: #f0fdf4;">{{ $student['hadir'] }}</td>
                <td class="text-center font-bold" style="background-color: #fffbeb;">{{ $student['terlambat'] }}</td>
                <td class="text-center font-bold" style="background-color: #f0f9ff;">{{ $student['izin'] }}</td>
                <td class="text-center font-bold" style="background-color: #eef2ff;">{{ $student['sakit'] }}</td>
                <td class="text-center font-bold" style="background-color: #fff1f2;">{{ $student['alpa'] }}</td>
                <td class="text-center font-bold" style="color: #4338ca;">{{ $student['persentase'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- MODE 3: BULANAN -->
    @elseif($info['type'] === 'bulanan')
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 20px;">NO</th>
                <th rowspan="2">NAMA SISWA</th>
                <th rowspan="2" style="width: 20px;">L/P</th>
                <th colspan="{{ $rekapData['days_in_month'] }}">TANGGAL ({{ strtoupper($rekapData['month_name']) }})</th>
                <th colspan="5">JUMLAH</th>
                <th rowspan="2" style="width: 35px;">% HADIR</th>
            </tr>
            <tr>
                @for($dayNum = 1; $dayNum <= $rekapData['days_in_month']; $dayNum++)
                <th style="width: 16px; font-size: 7px; padding: 2px 0;">{{ $dayNum }}</th>
                @endfor
                <th style="width: 18px; background-color: #d1fae5; color: #065f46;">H</th>
                <th style="width: 18px; background-color: #fef3c7; color: #92400e;">T</th>
                <th style="width: 18px; background-color: #e0f2fe; color: #075985;">I</th>
                <th style="width: 18px; background-color: #e0e7ff; color: #3730a3;">S</th>
                <th style="width: 18px; background-color: #ffe4e6; color: #9f1239;">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData['list'] as $student)
            <tr>
                <td class="text-center">{{ $student['no'] }}</td>
                <td class="font-bold" style="white-space: nowrap;">{{ $student['nama'] }}</td>
                <td class="text-center">{{ $student['jenis_kelamin'] }}</td>
                @for($dayNum = 1; $dayNum <= $rekapData['days_in_month']; $dayNum++)
                @php $st = $student['daily_map'][$dayNum] ?? null; @endphp
                <td class="text-center font-bold" style="font-size: 7px; padding: 2px 0;">
                    @if($st === 'hadir') <span style="color:#059669;">H</span>
                    @elseif($st === 'terlambat') <span style="color:#d97706;">T</span>
                    @elseif($st === 'izin') <span style="color:#0284c7;">I</span>
                    @elseif($st === 'sakit') <span style="color:#4338ca;">S</span>
                    @elseif($st === 'alpa') <span style="color:#dc2626;">A</span>
                    @else -
                    @endif
                </td>
                @endfor
                <td class="text-center font-bold" style="background-color: #f0fdf4;">{{ $student['hadir'] }}</td>
                <td class="text-center font-bold" style="background-color: #fffbeb;">{{ $student['terlambat'] }}</td>
                <td class="text-center font-bold" style="background-color: #f0f9ff;">{{ $student['izin'] }}</td>
                <td class="text-center font-bold" style="background-color: #eef2ff;">{{ $student['sakit'] }}</td>
                <td class="text-center font-bold" style="background-color: #fff1f2;">{{ $student['alpa'] }}</td>
                <td class="text-center font-bold" style="color: #4338ca;">{{ $student['persentase'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- MODE 4: SEMESTER -->
    @elseif($info['type'] === 'semester')
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">NO.</th>
                <th style="width: 90px;">NISN</th>
                <th>NAMA SISWA</th>
                <th style="width: 35px;">L/P</th>
                <th style="width: 50px; background-color: #d1fae5;">HADIR</th>
                <th style="width: 60px; background-color: #fef3c7;">TERLAMBAT</th>
                <th style="width: 45px; background-color: #e0f2fe;">IZIN</th>
                <th style="width: 45px; background-color: #e0e7ff;">SAKIT</th>
                <th style="width: 45px; background-color: #ffe4e6;">ALPA</th>
                <th style="width: 80px;">TOTAL HARI</th>
                <th style="width: 70px;">% KEHADIRAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData['list'] as $student)
            <tr>
                <td class="text-center">{{ $student['no'] }}</td>
                <td class="text-center">{{ $student['nisn'] ?: '-' }}</td>
                <td class="font-bold">{{ $student['nama'] }}</td>
                <td class="text-center">{{ $student['jenis_kelamin'] }}</td>
                <td class="text-center font-bold" style="background-color: #f0fdf4;">{{ $student['hadir'] }}</td>
                <td class="text-center font-bold" style="background-color: #fffbeb;">{{ $student['terlambat'] }}</td>
                <td class="text-center font-bold" style="background-color: #f0f9ff;">{{ $student['izin'] }}</td>
                <td class="text-center font-bold" style="background-color: #eef2ff;">{{ $student['sakit'] }}</td>
                <td class="text-center font-bold" style="background-color: #fff1f2;">{{ $student['alpa'] }}</td>
                <td class="text-center font-bold">{{ $student['total_recorded'] }} Hari</td>
                <td class="text-center font-bold" style="color: {{ $student['persentase'] >= 85 ? '#059669' : '#dc2626' }};">
                    {{ $student['persentase'] }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- SIGNATURE BLOCK -->
    <table class="footer-sign">
        <tr>
            <td></td>
            <td style="text-align: right;">
                <div>
                    Dicetak Pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                    <strong>Wali Kelas {{ $info['nama_kelas'] }}</strong>
                </div>
                
                <!-- SPACE UNTUK TANDA TANGAN -->
                <div style="height: 60px;"></div>

                <div>
                    @if(!empty($info['nama_wali_kelas']))
                        <strong><u>{{ $info['nama_wali_kelas'] }}</u></strong><br>
                        @if(!empty($info['nip_wali_kelas']))
                            <span>NIP/NUPTK. {{ $info['nip_wali_kelas'] }}</span>
                        @endif
                    @else
                        <strong><u>( ..................................................... )</u></strong>
                    @endif
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
