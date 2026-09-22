<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class MarketingAttribution
{
    public function capture(Request $request): void
    {
        $keys = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ];

        $attribution = [];

        foreach ($keys as $key) {
            $value = $request->query($key);

            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);

            if ($value === '') {
                continue;
            }

            $attribution[$key] = Str::limit($value, 100, '');
        }

        if ($attribution === []) {
            return;
        }

        $referrer = $request->headers->get('referer');

        if (is_string($referrer) && trim($referrer) !== '') {
            $safeReferrer = $this->trackingUrl($referrer);

            if ($safeReferrer !== null) {
                $attribution['referrer'] = $safeReferrer;
            }
        }

        $attribution['landing_page'] = Str::limit(
            $request->url(),
            2048,
            ''
        );

        $request->session()->put('marketing_attribution', $attribution);
    }

    private function trackingUrl(string $value): ?string
    {
        $parts = parse_url(trim($value));

        if (! is_array($parts)
            || ! in_array($parts['scheme'] ?? null, ['http', 'https'], true)
            || ! is_string($parts['host'] ?? null)) {
            return null;
        }

        $url = $parts['scheme'].'://'.$parts['host'];

        if (is_int($parts['port'] ?? null)) {
            $url .= ':'.$parts['port'];
        }

        if (is_string($parts['path'] ?? null)) {
            $url .= $parts['path'];
        }

        return Str::limit($url, 2048, '');
    }
}
