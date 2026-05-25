<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnimatedSection extends Component
{
    public string $animation;
    public int $delay;
    public string $className;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $animation = 'slide-up',
        int $delay = 0,
        string $className = ''
    ) {
        $this->animation = $animation;
        $this->delay = $delay;
        $this->className = $className;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.animated-section');
    }
}
