@php
    $code        = 502;
    $title       = 'Bad Gateway';
    $icon        = '🔌';
    $glowColor   = 'rgba(239,68,68,0.08)';
    $codeGlow    = 'rgba(239,68,68,0.35)';
    $description = 'The server received an invalid response from an upstream service. This is usually a temporary issue — like a bad pass that goes out of play. Please try again shortly.';
    $secondaryHref  = url()->current();
    $secondaryLabel = '🔄 Try Again';
    $extra = '<div class="flex items-center gap-3">
                <span class="text-2xl">🔧</span>
                <p class="text-gray-400 text-xs leading-relaxed">If this error persists for more than a few minutes, the server may be undergoing maintenance. Check back soon.</p>
              </div>';
@endphp
@include('errors.layout')
