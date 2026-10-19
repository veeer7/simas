<x-app-layout title="Pengajuan Surat">
    @php
        $isAdmin = \App\Support\Peran::isAdmin();
        $items   = $pengajuans ?? $pengajuanSurats ?? $pengajuan ?? $data ?? null;
        \Carbon\Carbon::setLocale('id');
    @endphp

    <div class="top">
        <div>
            <h1>Pengajuan Surat</h1>
            <p class="sub">Daftar surat tugas dan pengajuan kegiatan</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            @if($isAdmin)
                <form method="POST" action="{{ route('pengajuan.hapus-draft') }}"
                      onsubmit="return confirm('Hapus SEMUA pengajuan berstatus Draft? Tidak bisa dikembalikan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost" style="color:#dc2626;border-color:#fecaca">Hapus semua draft</button>
                </form>
            @endif
            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">+ Buat Pengajuan</a>
        </div>
    </div>

    @if(session('error'))
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:14px">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" class="filters">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor / kegiatan...">
        <select name="status">
            <option value="">Semua status</option>
            @foreach(['draft' => 'Draft', 'diajukan' => 'Diajukan', 'diproses' => 'Diproses', 'revisi' => 'Revisi', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $v => $l)
                <option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>
            @endforeach
        </select>
        <input type="date" name="dari" value="{{ request('dari') }}">
        <input type="date" name="sampai" value="{{ request('sampai') }}">
        <button class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search','status','dari','sampai']))
            <a href="{{ route('pengajuan.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>

    <div class="card" style="padding:8px 18px">
        @if($items && $items->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Pengaju</th>
                            <th>Kegiatan</th>
                            <th>Tgl Pelaksanaan</th>
                            <th>Status</th>
                            <th style="text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $p)
                        @php
                            $nomor = $p->nomor_pengajuan ?? $p->nomor ?? ('#'.$p->id);
                            $jenis = $p->blanko->nama ?? $p->blanko->nama_blanko ?? $p->jenis_surat ?? null;
                            $tgl   = $p->tanggal_pelaksanaan ?? null;
                        @endphp
                        <tr>
                            <td style="white-space:nowrap">
                                <b>{{ $nomor }}</b>
                                @if(!empty($p->nomor_surat))
                                    <div style="font-size:12px;color:#6b7280">Surat: {{ $p->nomor_surat }}</div>
                                @endif
                            </td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>
                                {{ $p->nama_kegiatan ?? '-' }}
                                @if($jenis)
                                    <div style="font-size:12px;color:#6b7280">{{ $jenis }}</div>
                                @endif
                            </td>
                            <td style="white-space:nowrap">
                                {{ $tgl ? \Carbon\Carbon::parse($tgl)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td><x-pengajuan.status-badge :status="$p->status" /></td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('pengajuan.show', $p) }}" class="btn btn-ghost" style="padding:6px 14px">Detail</a>
                                @if($isAdmin)
                                    <form method="POST" action="{{ route('pengajuan.hapus', $p) }}" style="display:inline"
                                          onsubmit="return confirm('Hapus pengajuan ini? Tidak bisa dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost" style="padding:6px 14px;color:#dc2626;border-color:#fecaca">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($items, 'links'))
                <div style="padding:14px 0">{{ $items->withQueryString()->links() }}</div>
            @endif
        @else
            <div class="empty">Belum ada pengajuan.</div>
        @endif
    </div>
</x-app-layout>
