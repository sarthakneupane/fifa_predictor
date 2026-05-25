@php
    $code        = 401;
    $title       = 'Unauthorized';
    $icon        = '🔐';
    $glowColor   = 'rgba(234,179,8,0.08)';
    $codeGlow    = 'rgba(234,179,8,0.35)';
    $description = 'You need to be logged in to access this page. Please sign in to continue.';
    $secondaryHref  = route('login');
    $secondaryLabel = '🔑 Log In';
@endphp
@include('errors.layout')
