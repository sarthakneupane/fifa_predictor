@php
    /*
    |--------------------------------------------------------------------------
    | Generic / catch-all error page
    |--------------------------------------------------------------------------
    | Laravel renders this when no specific error blade exists for a given
    | HTTP status code (e.g. 410, 431, 451…).
    | $exception is always available in Laravel error views.
    */
    $code   = $exception->getStatusCode()   ?? 'Error';
    $reason = $exception->getMessage()       ?? '';

    // Map common status codes to friendly titles
    $titles = [
        400 => 'Bad Request',
        406 => 'Not Acceptable',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        421 => 'Misdirected Request',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        501 => 'Not Implemented',
        505 => 'HTTP Version Not Supported',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    $title = $titles[$code] ?? 'Unexpected Error';

    // Pick glow / icon based on status class
    if ($code >= 500) {
        $icon      = '💥';
        $glowColor = 'rgba(239,68,68,0.10)';
        $codeGlow  = 'rgba(239,68,68,0.40)';
        $description = 'Something went wrong on our end. Our team has been notified. Please try again shortly.';
    } elseif ($code === 410) {
        $icon      = '🗑️';
        $glowColor = 'rgba(107,114,128,0.10)';
        $codeGlow  = 'rgba(107,114,128,0.35)';
        $description = 'This resource has been permanently removed and is no longer available.';
    } elseif ($code === 451) {
        $icon      = '⚖️';
        $glowColor = 'rgba(239,68,68,0.08)';
        $codeGlow  = 'rgba(239,68,68,0.30)';
        $description = 'This content is not available in your region due to legal restrictions.';
    } else {
        $icon      = '🚩';
        $glowColor = 'rgba(200,16,46,0.08)';
        $codeGlow  = 'rgba(200,16,46,0.35)';
        $description = $reason ?: 'An unexpected error occurred. Please try again or navigate back to a working page.';
    }

    $extra = app()->environment('local') && $reason
        ? '<p class="text-gray-500 text-xs font-heading uppercase tracking-wider mb-2">Debug (local only)</p>
           <p class="text-gray-400 text-xs font-mono break-all">' . e($reason) . '</p>'
        : null;
@endphp
@include('errors.layout')
