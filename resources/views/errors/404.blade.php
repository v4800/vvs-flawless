<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, follow">
        <title>{{ trans('site.errors.not_found_title') }}</title>
        <style>
            * { box-sizing: border-box; }
            body { margin: 0; background: #050505; color: #fff; font-family: Arial, sans-serif; }
            main { min-height: 100vh; display: grid; place-items: center; padding: 2rem; text-align: center; background: radial-gradient(circle at 50% 25%, rgba(251, 191, 36, .12), transparent 36%); }
            .code { color: #fcd34d; font-size: clamp(5rem, 20vw, 11rem); font-weight: 900; line-height: .8; letter-spacing: -.08em; }
            h1 { margin: 2rem 0 .75rem; font-size: clamp(1.7rem, 5vw, 3rem); text-transform: uppercase; }
            p { max-width: 38rem; margin: 0 auto; color: #a1a1aa; line-height: 1.7; }
            a { display: inline-block; margin-top: 2rem; border-radius: .75rem; background: #fcd34d; color: #09090b; padding: 1rem 1.5rem; font-size: .75rem; font-weight: 900; letter-spacing: .12em; text-decoration: none; text-transform: uppercase; }
        </style>
    </head>
    <body>
        <main>
            <div>
                <div class="code">404</div>
                <h1>{{ trans('site.errors.not_found_heading') }}</h1>
                <p>{{ trans('site.errors.not_found_text') }}</p>
                <a href="{{ app()->getLocale() === 'nl_BE' ? route('nl.watches.index') : route('watches.index') }}">
                    {{ trans('site.errors.back_collection') }}
                </a>
            </div>
        </main>
    </body>
</html>
