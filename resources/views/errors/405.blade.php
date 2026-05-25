@php
    $code        = 405;
    $title       = 'Method Not Allowed';
    $icon        = '⛔';
    $glowColor   = 'rgba(249,115,22,0.08)';
    $codeGlow    = 'rgba(249,115,22,0.35)';
    $description = 'The HTTP method used for this request is not supported by this endpoint. This is usually caused by submitting a form incorrectly or a bad API call.';
    $secondaryHref  = route('home');
    $secondaryLabel = '← Go Home';
    $extra = '<p class="text-gray-500 text-xs font-heading uppercase tracking-wider mb-2">Request info</p>
              <p class="text-gray-400 text-xs">Method: <span class="text-orange-400 font-mono">' . request()->method() . '</span></p>
              <p class="text-gray-400 text-xs mt-1">URL: <span class="text-gray-300 font-mono truncate block">' . request()->url() . '</span></p>';
@endphp
@include('errors.layout')
