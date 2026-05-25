@php
    $code        = 429;
    $title       = 'Too Many Requests';
    $icon        = '🚦';
    $glowColor   = 'rgba(239,68,68,0.08)';
    $codeGlow    = 'rgba(239,68,68,0.30)';
    $retryAfter  = request()->header('Retry-After') ?? 60;
    $description = 'You\'ve made too many requests in a short period. Please slow down and try again in a moment — the server needs a breather just like a player at half-time.';
    $secondaryHref  = url()->previous('/');
    $secondaryLabel = '🔄 Try Again';
    $extra = '<div class="flex items-center gap-3">
                <span class="text-3xl">⏱️</span>
                <div>
                    <p class="text-gray-300 text-sm font-medium">Rate limit reached</p>
                    <p class="text-gray-500 text-xs mt-0.5">Please wait <span id="countdown" class="text-red-400 font-mono font-bold">' . $retryAfter . 's</span> before trying again.</p>
                </div>
              </div>';
@endphp
@include('errors.layout')

<script>
    // Countdown timer before auto-reload
    (function () {
        let secs = parseInt('{{ $retryAfter }}') || 60;
        const el = document.getElementById('countdown');
        if (!el) return;

        const tick = setInterval(() => {
            secs--;
            el.textContent = secs + 's';
            if (secs <= 0) {
                clearInterval(tick);
                window.location.reload();
            }
        }, 1000);
    })();
</script>
