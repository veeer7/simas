<x-app-layout title="Detail Pengajuan">
    @php
        $isAdmin = \App\Support\Peran::isAdmin();
        $nomor   = $pengajuan->nomor_pengajuan ?? $pengajuan->nomor ?? '#'.$pengajuan->id;
        $statusRaw = $pengajuan->status;
        if (is_object($statusRaw)) { $statusRaw = $statusRaw->value ?? $statusRaw->name ?? ''; }
        $status  = strtolower((string) $statusRaw);
        $final   = in_array($status, ['disetujui', 'approved', 'ditolak', 'rejected', 'draft']);
        $alasanTolak = $pengajuan->alasan_penolakan ?? $pengajuan->alasan ?? $pengajuan->catatan ?? null;
        $tgl = $pengajuan->tanggal_pelaksanaan ?? null;
        if ($tgl) { $tgl = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('l, d F Y'); }
        $waktu = trim(($pengajuan->waktu_mulai ?? '') . ($pengajuan->waktu_selesai ? ' - ' . $pengajuan->waktu_selesai : ''));
    @endphp

    <style>
        dialog.modal{border:0;border-radius:16px;padding:26px;width:min(460px,92vw);box-shadow:0 20px 60px rgba(0,0,0,.2)}
        dialog.modal::backdrop{background:rgba(17,24,39,.45)}
        dialog.modal h3{margin:0 0 14px;font-size:18px}
        dialog.modal textarea{min-height:110px}
        .row-btn{display:flex;gap:10px;justify-content:flex-end;margin-top:16px}
        .btn-orange{background:#ea580c;color:#fff}.btn-orange:hover{background:#c2410c}
        .btn-blue{background:#2563eb;color:#fff}.btn-blue:hover{background:#1d4ed8}
    </style>

    <div class="top">
        <div>
            <h1>Detail Pengajuan</h1>
            <p class="sub">Nomor: <b>{{ $nomor }}</b> &nbsp; <x-pengajuan.status-badge :status="$pengajuan->status" /></p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('pengajuan.index') }}" class="btn btn-ghost">← Kembali</a>
            <a href="{{ route('pengajuan.pdf', $pengajuan) }}" target="_blank" class="btn btn-ghost">Buka PDF / Print</a>
        </div>
    </div>

    @if($alasanTolak && in_array($status, ['ditolak','rejected','revisi']))
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:14px 18px;border-radius:12px;margin-bottom:20px;font-size:14px">
            <b>Alasan {{ $status === 'revisi' ? 'revisi' : 'penolakan' }}:</b> {{ $alasanTolak }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:14px">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card" style="margin-bottom:22px">
        <b style="font-size:16px;display:block;margin-bottom:16px">Informasi Pengajuan</b>
        <dl class="detail">
            <dt>Pengaju</dt><dd>{{ $pengajuan->user->name ?? '-' }}</dd>
            <dt>Nama kegiatan</dt><dd>{{ $pengajuan->nama_kegiatan ?? '-' }}</dd>
            <dt>Tanggal pelaksanaan</dt><dd>{{ $tgl ?? '-' }}</dd>
            <dt>Waktu</dt><dd>{{ $waktu ?: '-' }}</dd>
            <dt>Tempat</dt><dd>{{ $pengajuan->tempat ?? '-' }}</dd>
            <dt>Keterangan</dt><dd>{{ $pengajuan->keterangan ?? '-' }}</dd>
        </dl>
    </div>

    {{-- ===== AKSI ADMIN ===== --}}
    @if($isAdmin && !$final)
        <div class="card" style="margin-bottom:22px">
            <b style="font-size:16px;display:block;margin-bottom:14px">Tindakan Admin</b>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <form method="POST" action="{{ route('approval.verify', $pengajuan) }}">
                    @csrf <button class="btn btn-blue">Verifikasi</button>
                </form>
                <form method="POST" action="{{ route('approval.approve', $pengajuan) }}"
                      onsubmit="return confirm('Setujui pengajuan ini?')">
                    @csrf <button class="btn btn-green">Setujui</button>
                </form>
                <button type="button" class="btn btn-orange" onclick="document.getElementById('dlgRevisi').showModal()">Minta Revisi</button>
                <button type="button" class="btn btn-red" onclick="document.getElementById('dlgTolak').showModal()">Tolak</button>
            </div>
        </div>
    @endif

    {{-- Guru: kirim pengajuan yang masih draft --}}
    @if(!$isAdmin && $status === 'draft')
        <div class="card" style="margin-bottom:22px">
            <form method="POST" action="{{ route('pengajuan.submit', $pengajuan) }}">
                @csrf <button class="btn btn-primary">Kirim Pengajuan</button>
            </form>
        </div>
    @endif

    {{-- ===== POPUP TOLAK ===== --}}
    <dialog id="dlgTolak" class="modal">
        <form method="POST" action="{{ route('approval.reject', $pengajuan) }}">
            @csrf
            <h3>Tolak Pengajuan</h3>
            <label>Alasan penolakan</label>
            <textarea name="alasan" required placeholder="Tulis alasan penolakan..."></textarea>
            <div class="row-btn">
                <button type="button" class="btn btn-ghost" onclick="this.closest('dialog').close()">Batal</button>
                <button type="submit" class="btn btn-red">Tolak</button>
            </div>
        </form>
    </dialog>

    {{-- ===== POPUP REVISI ===== --}}
    <dialog id="dlgRevisi" class="modal">
        <form method="POST" action="{{ route('approval.revisi', $pengajuan) }}">
            @csrf
            <h3>Minta Revisi</h3>
            <label>Catatan revisi</label>
            <textarea name="alasan" required placeholder="Apa yang perlu diperbaiki..."></textarea>
            <div class="row-btn">
                <button type="button" class="btn btn-ghost" onclick="this.closest('dialog').close()">Batal</button>
                <button type="submit" class="btn btn-orange">Kirim Revisi</button>
            </div>
        </form>
    </dialog>
</x-app-layout>