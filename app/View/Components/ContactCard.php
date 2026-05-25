<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ContactCard extends Component
{
    public bool $hasFaq;
    public array $contactData;

    public function __construct(bool $hasFaq = false, array $contactData = [])
    {
        $this->hasFaq = $hasFaq;
        $this->contactData = $contactData;
    }

    public function render()
    {
        return view('components.contact-card');
    }
}
