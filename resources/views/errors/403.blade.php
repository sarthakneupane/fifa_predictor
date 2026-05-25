@php
    $code        = 403;
    $title       = 'Access Forbidden';
    $icon        = '🚫';
    $glowColor   = 'rgba(239,68,68,0.08)';
    $codeGlow    = 'rgba(239,68,68,0.35)';
    $description = $exception->getMessage() ?: 'You don\'t have permission to view this page. This area may be restricted to admins or verified users only.';
    $secondaryHref  = route('home');
    $secondaryLabel = '⚽ Back to Matches';
@endphp

@php
    // Show a helpful hint depending on auth state
    if (!auth()->check()) {
        $extra = '<p class="text-gray-400">💡 <span class="text-gray-300 font-medium">Tip:</span> Some pages require you to be logged in. Try <a href="' . route('login') . '" class="text-fifa-red hover:underline">signing in</a> first.</p>';
    } elseif (!auth()->user()->hasVerifiedEmail()) {
        $extra = '<p class="text-gray-400">📧 <span class="text-gray-300 font-medium">Tip:</span> Your email address is not yet verified. Check your inbox for the verification link.</p>';
    } else {
        $extra = null;
    }
@endphp

@include('errors.layout')
