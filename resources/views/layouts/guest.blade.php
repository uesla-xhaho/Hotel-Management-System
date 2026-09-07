<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotel Booking') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis&family=Outfit:wght@700&family=Roboto&family=Urbanist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/vendor/flatpickr/flatpickr.min.css">

    <style>
        :root {
            --hm-navy: #080155;
            --hm-navy-deep: #130d5c;
            --hm-navy-soft: rgba(19, 13, 92, 0.88);
            --hm-ink: #1c1a22;
            --hm-ink-muted: #5d5f70;
            --hm-sand: #f6f1ea;
            --hm-brass: #caa56a;
            --hm-card: #ffffff;
            --hm-border: #e7e0d4;
            --hm-shadow: 0 18px 40px rgba(28, 26, 34, 0.12);
        }

        body {
            font-family: 'Urbanist', sans-serif;
            background:
                linear-gradient(180deg, rgba(6, 4, 26, 0.55) 0%, rgba(6, 4, 26, 0.32) 45%, rgba(6, 4, 26, 0.65) 100%),
                url('/7861522850.jpg') center/cover no-repeat fixed;
            color: var(--hm-ink);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .hm-shell {
            max-width: 1100px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hm-hero {
            position: relative;
            background: transparent;
            color: #fff;
            border-radius: 26px;
            padding: 8px 6px;
            font-weight: 600;
        }

        .hm-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.1rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #fff;
            font-weight: 800;
        }

        .hm-hero-subtitle {
            color: rgba(255, 255, 255, 0.85);
            max-width: 620px;
            font-size: 1.15rem;
            font-weight: 600;
        }

        .hm-topbar {
            padding: 24px 0;
        }

        .hm-main {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .hm-topbar .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .hm-brand {
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #fff;
            font-family: 'Outfit', sans-serif;
        }

        .hm-card {
            background: var(--hm-card);
            border: 1px solid var(--hm-border);
            border-radius: 20px;
            box-shadow: var(--hm-shadow);
            color: var(--hm-ink);
        }

        .hm-pill {
            border-radius: 999px;
            padding: 10px 22px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .hm-pill-primary {
            background: var(--hm-navy);
            color: #fff;
            border: none;
        }

        .hm-pill-ghost {
            border: 1px solid #fff;
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            transition: all 0.2s ease;
        }

        .hm-pill-ghost:hover {
            color: var(--hm-navy);
            background: #fff;
            border-color: #fff;
            box-shadow: 0 10px 20px rgba(8, 1, 85, 0.2);
            transform: translateY(-1px);
        }

        .hm-section-title {
            font-size: 1.4rem;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            color: var(--hm-ink);
        }

        .hm-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 999px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .hm-table thead {
            background: #f2ece4;
        }

        .hm-table thead th {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--hm-ink-muted);
        }

        .hm-table tbody tr {
            background: #fff;
        }

        .hm-table tbody tr:hover {
            background: #f7f1e8;
        }

        .hm-input {
            border-radius: 14px;
            border: 1px solid var(--hm-border);
            padding: 12px 14px;
            background: #fffaf2;
        }

        .hm-form-label {
            font-weight: 600;
            color: var(--hm-ink);
        }

        .hm-form-error {
            color: #b3261e;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .hm-check {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: var(--hm-ink);
        }

        .hm-check input {
            width: 18px;
            height: 18px;
            accent-color: var(--hm-navy);
        }

        .hm-auth-link {
            color: var(--hm-navy);
            font-weight: 600;
            text-decoration: none;
        }

        .hm-auth-link:hover {
            text-decoration: underline;
        }

        .hm-date {
            padding-right: 44px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23080155' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 18px 18px;
            cursor: pointer;
        }

        .hm-input:focus {
            outline: none;
            border-color: var(--hm-brass);
            box-shadow: 0 0 0 3px rgba(202, 165, 106, 0.2);
        }

        .hm-room-card {
            border: 1px solid var(--hm-border);
            border-radius: 18px;
            background: #fff;
            padding: 18px;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .hm-room-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--hm-shadow);
        }

        .hm-room-media {
            border-radius: 16px;
            overflow: hidden;
            height: 180px;
            background: #f2ece4;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hm-room-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hm-room-media-placeholder {
            color: var(--hm-ink-muted);
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .hm-room-body {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .hm-room-desc {
            margin: 6px 0 0;
            color: var(--hm-ink-muted);
            font-size: 0.95rem;
        }

        .hm-room-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 14px;
            font-size: 0.9rem;
        }

        .hm-room-details div {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .hm-room-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--hm-ink-muted);
            font-weight: 700;
        }

        .hm-room-hotel {
            font-size: 0.9rem;
            color: var(--hm-ink-muted);
            margin-top: 2px;
        }

        .hm-room-price {
            font-weight: 700;
            color: var(--hm-navy);
        }

        .hm-room-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .hm-room-select {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid var(--hm-border);
            background: #f9f4ec;
            color: var(--hm-ink);
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .hm-room-radio-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--hm-navy);
            display: inline-block;
            box-shadow: inset 0 0 0 0 var(--hm-navy);
            transition: all 0.2s ease;
        }

        .hm-room-radio:checked + .hm-room-select {
            background: var(--hm-navy);
            border-color: var(--hm-navy);
            color: #fff;
        }

        .hm-room-radio:checked + .hm-room-select .hm-room-radio-dot {
            border-color: #fff;
            box-shadow: inset 0 0 0 4px #fff;
        }

        .hm-room-radio:focus-visible + .hm-room-select {
            outline: none;
            box-shadow: 0 0 0 3px rgba(202, 165, 106, 0.25);
        }

        .hm-muted {
            color: var(--hm-ink-muted);
        }

        .hm-divider {
            height: 1px;
            background: var(--hm-border);
            margin: 12px 0;
        }

        .hm-feature {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .hm-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            gap: 24px;
        }

        @media (max-width: 992px) {
            .hm-hero-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .hm-room-media {
                height: 160px;
            }

            .hm-room-details {
                grid-template-columns: 1fr;
            }
        }

        @keyframes hm-fade-slide {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hm-fade-in {
            animation: hm-fade-slide 0.6s ease-out both;
        }

        .hm-fade-in[data-delay="1"] {
            animation-delay: 0.12s;
        }

        .hm-fade-in[data-delay="2"] {
            animation-delay: 0.22s;
        }
    </style>
</head>
<body>
    <div class="container hm-shell">
        <div class="hm-topbar d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <div class="hm-brand">Hotel Booking</div>
                <div class="text-muted">Find a room and book instantly.</div>
            </div>
            @hasSection('topbar-action')
                @yield('topbar-action')
            @else
                <a href="{{ route('login') }}" class="hm-pill hm-pill-ghost text-decoration-none">Login</a>
            @endif
        </div>

        <div class="hm-main">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="/vendor/flatpickr/flatpickr.min.js"></script>
</body>
</html>
