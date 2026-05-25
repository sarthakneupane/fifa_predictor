@php
    $code        = 419;
    $title       = 'Page Expired';
    $icon        = '⌛';
    $glowColor   = 'rgba(234,179,8,0.08)';
    $codeGlow    = 'rgba(234,179,8,0.30)';
    $description = 'Your session has expired or the security token is invalid. This happens when a page is left open too long or you navigate using the browser\'s back button after logging out.';
    $secondaryHref  = url()->previous('/');
    $secondaryLabel = '🔄 Reload Page';
    $extra = '<div class="space-y-2">
                <p class="text-gray-400 text-xs">
                    <span class="text-yellow-400">⚠️</span>
                    <span class="text-gray-300 font-medium ml-1">What to do:</span>
                </p>
                <ul class="text-gray-500 text-xs space-y-1 ml-4 list-disc">
                    <li>Click <strong class="text-gray-300">Reload Page</strong> to refresh and try again</li>
                    <li>If the problem persists, try clearing your browser cookies</li>
                    <li>Make sure you are still logged in before submitting forms</li>
                </ul>
              </div>';
@endphp
@include('errors.layout')
