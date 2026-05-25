@php
    $code        = 500;
    $title       = 'Server Error';
    $icon        = '💥';
    $glowColor   = 'rgba(239,68,68,0.10)';
    $codeGlow    = 'rgba(239,68,68,0.45)';
    $description = 'Something went wrong on our end. Our team has been notified and is working on a fix. Please try again in a few minutes.';
    $secondaryHref  = url('/');
    $secondaryLabel = '🔄 Reload';
    $extra = '<div class="space-y-3">
                <div class="flex items-start gap-3">
                    <span class="text-xl mt-0.5">📧</span>
                    <div>
                        <p class="text-gray-300 text-sm font-medium">Need immediate help?</p>
                        <p class="text-gray-500 text-xs mt-0.5">If this keeps happening, contact support and mention error code <span class="font-mono text-red-400">500</span> and the time <span class="font-mono text-gray-400">' . now()->format('H:i d M Y') . ' UTC</span>.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-xl mt-0.5">⚽</span>
                    <div>
                        <p class="text-gray-500 text-xs">Your predictions and points are safe — this is a temporary issue with the server, not your account.</p>
                    </div>
                </div>
              </div>';
@endphp
@include('errors.layout')
