@php
    $code        = 404;
    $title       = 'Page Not Found';
    $icon        = '🔍';
    $glowColor   = 'rgba(200,16,46,0.08)';
    $codeGlow    = 'rgba(200,16,46,0.4)';
    $description = 'The page you\'re looking for has gone off-side. It may have been moved, deleted, or never existed.';
    $secondaryHref  = route('games.index');
    $secondaryLabel = '⚽ Browse Matches';

    // Suggest related links based on the requested URL
    $requestedUrl = request()->path();
    $suggestions  = [];

    if (str_contains($requestedUrl, 'game'))       $suggestions[] = ['href' => route('games.index'),     'label' => 'All Matches'];
    if (str_contains($requestedUrl, 'leaderboard')) $suggestions[] = ['href' => route('leaderboard.index'), 'label' => 'Leaderboard'];
    if (str_contains($requestedUrl, 'dashboard'))   $suggestions[] = ['href' => route('dashboard'),         'label' => 'Dashboard'];
    if (str_contains($requestedUrl, 'admin'))       $suggestions[] = ['href' => route('admin.dashboard'),   'label' => 'Admin Panel'];

    if (!empty($suggestions)) {
        $links = collect($suggestions)->map(fn($s) =>
            '<a href="' . $s['href'] . '" class="text-fifa-red hover:underline">' . $s['label'] . '</a>'
        )->implode(' &middot; ');
        $extra = '<p class="text-gray-500 text-xs mb-1 font-heading uppercase tracking-wider">Did you mean?</p><p class="text-sm">' . $links . '</p>';
    } else {
        $extra = null;
    }
@endphp
@include('errors.layout')
