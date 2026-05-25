@php
    $code        = 422;
    $title       = 'Unprocessable Request';
    $icon        = '📋';
    $glowColor   = 'rgba(249,115,22,0.08)';
    $codeGlow    = 'rgba(249,115,22,0.30)';
    $description = 'The data you submitted could not be processed. This usually means a form was submitted with invalid or missing information.';
    $secondaryHref  = url()->previous('/');
    $secondaryLabel = '← Go Back';
    $extra = '<p class="text-gray-500 text-xs font-heading uppercase tracking-wider mb-2">What to do</p>
              <ul class="text-gray-400 text-xs space-y-1 list-disc ml-4">
                  <li>Go back and check all required fields are filled in</li>
                  <li>Make sure scores are valid numbers (0–20)</li>
                  <li>Ensure the prediction deadline has not passed</li>
              </ul>';
@endphp
@include('errors.layout')
