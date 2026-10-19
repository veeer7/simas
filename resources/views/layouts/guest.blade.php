<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMAS • Masuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--bg:#f5f6fa;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e5e7ef}
        @media(prefers-color-scheme:dark){:root{--bg:#0b1020;--card:#121a2e;--text:#e6e9f2;--muted:#8b97b1;--border:#1f2a44}}
        body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text)}
        .g-wrap{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;
            background:radial-gradient(900px 400px at 50% -10%,rgba(99,102,241,.25),transparent)}
        .g-brand{display:flex;align-items:center;gap:12px;margin-bottom:24px;text-decoration:none;color:inherit}
        .g-brand i{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#111827,#111827);display:grid;place-items:center;color:#fff;font-style:normal;font-size:22px;font-weight:700}
        .g-brand b{font-size:22px;letter-spacing:-.02em;display:block;line-height:1.1}
        .g-brand span{font-size:12px;color:var(--muted)}
        .g-card{width:100%;max-width:420px;background:var(--card);border:1px solid var(--border);border-radius:20px;padding:32px;box-shadow:0 10px 40px rgba(15,23,42,.08)}
        .g-card input:not([type=checkbox]){border-radius:10px!important}
    </style>
</head>
<body>
    <div class="g-wrap">
        <a href="/" class="g-brand">
            <i>S</i>
            <div><b>SIMAS</b><span>Sistem Manajemen Surat • SMK Informatika Pesat</span></div>
        </a>
        <div class="g-card">
            {{ $slot }}
        </div>
    </div>
</body>
</html>