@php
    $analyticsEnabled = (bool) config('analytics.enabled');
    $gaMeasurementId = (string) config('analytics.measurement_id');

    $isPrivateAnalyticsPage = request()->is(
        'admin*',
        'dashboard*',
        'login*',
        'register*',
        'forgot-password*',
        'reset-password*',
        'email/verify*',
        'user/confirm-password*',
        'two-factor-challenge*',
        'settings*',
        'reservation-confirmed/*',
        'nl/reservation-confirmed/*',
        'en/reservation-confirmed/*',
        'de/reservierung-bestaetigt/*'
    );

    $privacyUrl = data_get(
        $page,
        'props.localizedRoutes.privacy',
        route('privacy')
    );
@endphp

@if (
    $analyticsEnabled
    && $gaMeasurementId !== ''
    && ! $isPrivateAnalyticsPage
)
    <style>
        #vvs-analytics-consent[hidden],
        #vvs-cookie-settings[hidden] {
            display: none !important;
        }

        #vvs-analytics-consent {
            position: fixed;
            z-index: 2147483000;
            right: 16px;
            bottom: 16px;
            left: 16px;
            max-width: 620px;
            margin-left: auto;
            padding: 20px;
            border: 1px solid rgba(252, 211, 77, 0.28);
            border-radius: 20px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(251, 191, 36, 0.10),
                    transparent 35%
                ),
                rgba(9, 9, 11, 0.98);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.50);
            color: #fff;
            backdrop-filter: blur(18px);
        }

        #vvs-analytics-consent-title {
            margin: 0;
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        #vvs-analytics-consent-text {
            margin: 10px 0 0;
            color: #a1a1aa;
            font-size: 13px;
            line-height: 1.65;
        }

        .vvs-consent-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 17px;
        }

        .vvs-consent-button {
            min-height: 42px;
            padding: 10px 17px;
            border-radius: 11px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
        }

        .vvs-consent-button--accept {
            border-color: rgba(252, 211, 77, 0.45);
            background: #fcd34d;
            color: #18181b;
        }

        .vvs-consent-link {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            padding: 10px 4px;
            color: #fcd34d;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        #vvs-cookie-settings {
            position: fixed;
            z-index: 2147482999;
            bottom: 14px;
            left: 14px;
            padding: 8px 12px;
            border: 1px solid rgba(252, 211, 77, 0.24);
            border-radius: 999px;
            background: rgba(9, 9, 11, 0.92);
            color: #d4d4d8;
            cursor: pointer;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            backdrop-filter: blur(12px);
        }

        @media (min-width: 700px) {
            #vvs-analytics-consent {
                left: auto;
                width: min(620px, calc(100vw - 32px));
            }
        }
    </style>

    <aside
        id="vvs-analytics-consent"
        hidden
        role="dialog"
        aria-modal="false"
        aria-labelledby="vvs-analytics-consent-title"
        aria-describedby="vvs-analytics-consent-text"
    >
        <h2 id="vvs-analytics-consent-title">
            {{ trans('analytics.banner.title') }}
        </h2>

        <p id="vvs-analytics-consent-text">
            {{ trans('analytics.banner.text') }}
        </p>

        <div class="vvs-consent-actions">
            <button
                id="vvs-analytics-accept"
                class="vvs-consent-button vvs-consent-button--accept"
                type="button"
            >
                {{ trans('analytics.banner.accept') }}
            </button>

            <button
                id="vvs-analytics-reject"
                class="vvs-consent-button"
                type="button"
            >
                {{ trans('analytics.banner.reject') }}
            </button>

            <a
                class="vvs-consent-link"
                href="{{ $privacyUrl }}"
            >
                {{ trans('analytics.banner.privacy') }}
            </a>
        </div>
    </aside>

    <button
        id="vvs-cookie-settings"
        hidden
        type="button"
        aria-label="{{ trans('analytics.banner.settings') }}"
    >
        {{ trans('analytics.banner.settings') }}
    </button>

    <script
        @if ($cspNonce)
            nonce="{{ $cspNonce }}"
        @endif
    >
        (() => {
            const measurementId = @json($gaMeasurementId);
            const storageKey = 'vvs_analytics_consent_v1';

            const banner = document.getElementById(
                'vvs-analytics-consent',
            );

            const acceptButton = document.getElementById(
                'vvs-analytics-accept',
            );

            const rejectButton = document.getElementById(
                'vvs-analytics-reject',
            );

            const settingsButton = document.getElementById(
                'vvs-cookie-settings',
            );

            let analyticsLoaded = false;

            const readConsent = () => {
                try {
                    return window.localStorage.getItem(storageKey);
                } catch {
                    return null;
                }
            };

            const saveConsent = (value) => {
                try {
                    window.localStorage.setItem(storageKey, value);
                } catch {
                    // The preference remains valid for the current page.
                }
            };

            const showBanner = () => {
                if (banner) {
                    banner.hidden = false;
                }

                if (settingsButton) {
                    settingsButton.hidden = true;
                }
            };

            const hideBanner = () => {
                if (banner) {
                    banner.hidden = true;
                }

                if (settingsButton) {
                    settingsButton.hidden = false;
                }
            };

            const deleteAnalyticsCookies = () => {
                const hostname = window.location.hostname;

                document.cookie
                    .split(';')
                    .map((cookie) => cookie.split('=')[0].trim())
                    .filter(
                        (name) =>
                            name === '_ga'
                            || name.startsWith('_ga_'),
                    )
                    .forEach((name) => {
                        document.cookie =
                            `${name}=; Max-Age=0; path=/; SameSite=Lax`;

                        document.cookie =
                            `${name}=; Max-Age=0; path=/; domain=${hostname}; SameSite=Lax`;

                        if (
                            hostname !== 'localhost'
                            && hostname.includes('.')
                        ) {
                            document.cookie =
                                `${name}=; Max-Age=0; path=/; domain=.${hostname}; SameSite=Lax`;
                        }
                    });
            };

            const setGoogleConsent = (analyticsState) => {
                if (typeof window.gtag !== 'function') {
                    return;
                }

                window.gtag('consent', 'update', {
                    analytics_storage: analyticsState,
                    ad_storage: 'denied',
                    ad_user_data: 'denied',
                    ad_personalization: 'denied',
                });
            };

            const sendPageView = () => {
                if (
                    !analyticsLoaded
                    || readConsent() !== 'granted'
                    || typeof window.gtag !== 'function'
                ) {
                    return;
                }

                window.gtag('event', 'page_view', {
                    page_title: document.title,
                    page_location: window.location.href,
                });
            };

            const loadAnalytics = () => {
                if (analyticsLoaded) {
                    setGoogleConsent('granted');
                    sendPageView();

                    return;
                }

                analyticsLoaded = true;

                window.dataLayer = window.dataLayer || [];

                window.gtag = window.gtag || function () {
                    window.dataLayer.push(arguments);
                };

                window.gtag('consent', 'default', {
                    analytics_storage: 'denied',
                    ad_storage: 'denied',
                    ad_user_data: 'denied',
                    ad_personalization: 'denied',
                });

                window.gtag('consent', 'update', {
                    analytics_storage: 'granted',
                    ad_storage: 'denied',
                    ad_user_data: 'denied',
                    ad_personalization: 'denied',
                });

                window.gtag('js', new Date());

                window.gtag('config', measurementId, {
                    send_page_view: false,
                    allow_google_signals: false,
                    allow_ad_personalization_signals: false,
                });

                const script = document.createElement('script');

                script.async = true;
                script.src =
                    'https://www.googletagmanager.com/gtag/js?id='
                    + encodeURIComponent(measurementId);

                document.head.appendChild(script);

                sendPageView();
            };

            const acceptAnalytics = () => {
                saveConsent('granted');
                hideBanner();
                loadAnalytics();
            };

            const rejectAnalytics = () => {
                const wasLoaded = analyticsLoaded;

                saveConsent('denied');

                if (wasLoaded) {
                    setGoogleConsent('denied');
                }

                deleteAnalyticsCookies();
                hideBanner();

                if (wasLoaded) {
                    window.location.reload();
                }
            };

            acceptButton?.addEventListener(
                'click',
                acceptAnalytics,
            );

            rejectButton?.addEventListener(
                'click',
                rejectAnalytics,
            );

            settingsButton?.addEventListener(
                'click',
                showBanner,
            );

            window.addEventListener(
                'vvs:analytics-consent-open',
                showBanner,
            );

            document.addEventListener(
                'inertia:finish',
                () => {
                    window.setTimeout(sendPageView, 0);
                },
            );

            const existingConsent = readConsent();

            if (existingConsent === 'granted') {
                hideBanner();
                loadAnalytics();
            } else if (existingConsent === 'denied') {
                hideBanner();
            } else {
                showBanner();
            }
        })();
    </script>
@endif