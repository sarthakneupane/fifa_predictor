@php
    $code        = 504;
    $title       = 'Gateway Timeout';
    $icon        = '🕐';
    $glowColor   = 'rgba(168,85,247,0.08)';
    $codeGlow    = 'rgba(168,85,247,0.30)';
    $description = 'The server is taking too long to respond. This is usually caused by heavy load or a temporary network issue between our servers. Please wait a moment and try again.';
    $secondaryHref  = url()->current();
    $secondaryLabel = '🔄 Try Again';
    $extra = '<div class="flex items-start gap-3">
                <span class="text-xl">💡</span>
                <p class="text-gray-400 text-xs leading-relaxed">If you were submitting a prediction, it may not have been saved. After retrying, please check your <a href="/my-predictions" class="text-fifa-red hover:underline">predictions page</a> to confirm.</p>
              </div>';
@endphp
@include('errors.layout')
