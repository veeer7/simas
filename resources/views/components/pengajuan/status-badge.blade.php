@props(['status' => null, 'pengajuan' => null])
@php
    $raw = $status ?? ($pengajuan->status ?? '');
    if (is_object($raw)) { $raw = $raw->value ?? $raw->name ?? ''; }
    $key = strtolower((string) $raw);
    $map = [
        'draft'        => ['Draft',        '#f3f4f6', '#4b5563'],
        'diajukan'     => ['Diajukan',     '#fef3c7', '#b45309'],
        'pending'      => ['Pending',      '#fef3c7', '#b45309'],
        'verifikasi'   => ['Verifikasi',   '#e0f2fe', '#0369a1'],
        'diverifikasi' => ['Diverifikasi', '#e0f2fe', '#0369a1'],
        'revisi'       => ['Revisi',       '#ffedd5', '#c2410c'],
        'disetujui'    => ['Disetujui',    '#d1fae5', '#047857'],
        'approved'     => ['Disetujui',    '#d1fae5', '#047857'],
        'ditolak'      => ['Ditolak',      '#fee2e2', '#b91c1c'],
        'rejected'     => ['Ditolak',      '#fee2e2', '#b91c1c'],
    ];
    [$label, $bg, $fg] = $map[$key] ?? [ucfirst($key ?: '-'), '#f3f4f6', '#4b5563'];
@endphp
<span {{ $attributes->only('class') }} style="display:inline-block;padding:4px 11px;border-radius:999px;font-size:12px;font-weight:600;background:{{ $bg }};color:{{ $fg }}">{{ $label }}</span>
