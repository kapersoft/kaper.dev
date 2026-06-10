<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile['name'] }}</title>
    <meta name="description" content="{{ $profile['bio'] }}">
    <meta property="og:title" content="{{ $profile['name'] }}">
    <meta property="og:description" content="{{ $profile['bio'] }}">
    <meta property="og:type" content="profile">
    <meta property="og:image" content="{{ url($profile['headshot']) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #111318;
            --surface: #1a1f28;
            --surface-raised: #232a36;
            --ink: #ede8df;
            --ink-muted: #9aa3b2;
            --accent: #d4845a;
            --accent-glow: rgba(212, 132, 90, 0.35);
            --line: rgba(237, 232, 223, 0.08);
            --radius: 1.25rem;
        }

        html { font-size: 16px; }

        body {
            min-height: 100dvh;
            font-family: 'DM Sans', system-ui, sans-serif;
            color: var(--ink);
            background: var(--bg);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .backdrop {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse 80% 60% at 15% 10%, rgba(212, 132, 90, 0.12), transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 90%, rgba(90, 130, 180, 0.08), transparent 50%),
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 39px,
                    rgba(237, 232, 223, 0.025) 39px,
                    rgba(237, 232, 223, 0.025) 40px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 39px,
                    rgba(237, 232, 223, 0.025) 39px,
                    rgba(237, 232, 223, 0.025) 40px
                );
        }

        .page {
            position: relative;
            max-width: 52rem;
            margin: 0 auto;
            padding: clamp(2rem, 6vw, 4.5rem) clamp(1.25rem, 4vw, 2rem);
        }

        .card {
            display: grid;
            gap: clamp(2rem, 4vw, 3rem);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: calc(var(--radius) + 0.5rem);
            padding: clamp(1.75rem, 4vw, 2.75rem);
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.04) inset,
                0 24px 64px rgba(0, 0, 0, 0.45);
        }

        @media (min-width: 44rem) {
            .card {
                grid-template-columns: 13rem 1fr;
                align-items: start;
            }
        }

        .portrait-wrap {
            display: flex;
            justify-content: center;
        }

        @media (min-width: 44rem) {
            .portrait-wrap {
                justify-content: flex-start;
            }
        }

        .portrait {
            position: relative;
            width: min(13rem, 70vw);
            aspect-ratio: 1;
        }

        .portrait::before {
            content: '';
            position: absolute;
            inset: -0.6rem;
            border: 1px solid var(--accent);
            border-radius: var(--radius);
            transform: rotate(-4deg);
            opacity: 0.55;
        }

        .portrait img {
            position: relative;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: var(--radius);
            border: 1px solid var(--line);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            filter: saturate(0.92) contrast(1.04);
        }

        .intro { display: grid; gap: 1.25rem; }

        .eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--accent);
        }

        header {
            display: grid;
            gap: 0.75rem;
        }

        h1 {
            font-family: 'Instrument Serif', Georgia, serif;
            font-size: clamp(2.4rem, 6vw, 3.4rem);
            font-weight: 400;
            line-height: 1.05;
            letter-spacing: -0.02em;
        }

        .roles {
            display: grid;
            gap: 0.15rem;
            list-style: none;
        }

        .role {
            font-size: 1.125rem;
            color: var(--ink-muted);
        }

        .role strong {
            color: var(--ink);
            font-weight: 500;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1.25rem;
            font-size: 0.9rem;
            color: var(--ink-muted);
        }

        .meta a {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: inherit;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        .meta a:hover,
        .meta a:focus-visible {
            color: var(--ink);
            outline: none;
        }

        .meta svg {
            width: 0.95rem;
            height: 0.95rem;
            opacity: 0.7;
        }

        .bio {
            font-size: 1.05rem;
            color: var(--ink-muted);
            max-width: 38ch;
        }

        .links {
            display: grid;
            gap: 2rem;
            margin-top: 1.25rem;
        }

        .link-group {
            display: grid;
            gap: 0.75rem;
        }

        .links-heading {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 0.25rem;
        }

        .link {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.15rem;
            border-radius: var(--radius);
            border: 1px solid var(--line);
            background: var(--surface-raised);
            color: inherit;
            text-decoration: none;
            transition: border-color 0.25s ease, background-color 0.25s ease;
        }

        .link:hover,
        .link:focus-visible {
            border-color: rgba(237, 232, 223, 0.14);
            background: #2a3140;
            outline: none;
        }

        .link-icon {
            display: grid;
            place-items: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: rgba(212, 132, 90, 0.12);
            color: var(--accent);
        }

        .link-icon svg {
            width: 1.15rem;
            height: 1.15rem;
        }

        .link-text {
            display: grid;
            gap: 0.1rem;
        }

        .link-label {
            font-weight: 600;
            font-size: 1rem;
        }

        .link-desc {
            font-size: 0.85rem;
            color: var(--ink-muted);
        }

        .link-arrow {
            color: var(--ink-muted);
            font-size: 1.1rem;
            transition: color 0.25s ease;
        }

        .link:hover .link-arrow,
        .link:focus-visible .link-arrow {
            color: var(--ink);
        }

        .footer {
            margin-top: 2rem;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--ink-muted);
            opacity: 0.6;
        }

        .footer a {
            color: inherit;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        .footer a:hover,
        .footer a:focus-visible {
            color: var(--ink);
            outline: none;
        }
    </style>
</head>
<body>
    <div class="backdrop" aria-hidden="true"></div>

    <main class="page">
        <article class="card">
            <div class="portrait-wrap">
                <figure class="portrait">
                    <img
                        src="{{ $profile['headshot'] }}"
                        alt="Portrait of {{ $profile['name'] }}"
                        width="460"
                        height="460"
                        loading="eager"
                    >
                </figure>
            </div>

            <div class="intro">
                <p class="eyebrow">kaper.dev</p>

                <header>
                    <h1>{{ $profile['name'] }}</h1>
                    <ul class="roles">
                        @foreach ($profile['roles'] as $role)
                            <li class="role"><strong>{{ $role['title'] }}</strong> @ {{ $role['company'] }}</li>
                        @endforeach
                    </ul>
                </header>

                <div class="meta">
                    <a href="{{ $profile['location_url'] }}" target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                        {{ $profile['location'] }}
                    </a>
                </div>

                <p class="bio">{{ $profile['bio'] }}</p>

                <section class="links" aria-label="Links">
                    @foreach ($profile['link_groups'] as $group)
                        <div class="link-group">
                            <p class="links-heading">{{ $group['heading'] }}</p>

                            @foreach ($group['links'] as $link)
                                <a
                                    class="link"
                                    href="{{ $link['url'] }}"
                                    @if (! str_starts_with($link['url'], 'mailto:'))
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    @endif
                                >
                                    <span class="link-icon" aria-hidden="true">
                                        @include('partials.link-icon', ['icon' => $link['icon']])
                                    </span>
                                    <span class="link-text">
                                        <span class="link-label">{{ $link['label'] }}</span>
                                        @if (isset($link['description']))
                                            <span class="link-desc">{{ $link['description'] }}</span>
                                        @endif
                                    </span>
                                    <span class="link-arrow" aria-hidden="true">↗</span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </section>
            </div>
        </article>

        <p class="footer">Built with <a href="https://www.cursor.com/" target="_blank" rel="noopener noreferrer">Cursor</a>, hosted on <a href="https://laravel.com/cloud" target="_blank" rel="noopener noreferrer">Laravel Cloud</a> · &copy; {{ date('Y') }} Kapersoft bv</p>
    </main>
</body>
</html>
