@php
    $code        = 408;
    $title       = 'Request Timeout';
    $icon        = '⏱️';
    $glowColor   = 'rgba(168,85,247,0.08)';
    $codeGlow    = 'rgba(168,85,247,0.30)';
    $description = 'The server timed out waiting for the request to complete. This can happen during slow network conditions or heavy server load. Please try again.';
    $secondaryHref  = request()->url();
    $secondaryLabel = '🔄 Try Again';
@endphp
@include('errors.layout')
