<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TeamCard extends Component
{
    public array $team;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->team = !empty($team) ? $team : [
            [
                'name' => 'Rajesh Khadka',
                'position' => 'President',
                'image' => '/images/team/rajesh-khadka.jpg',
                'bio' => '20+ years of experience in industrial development and policy advocacy.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/rajeshkhadka',
                    'twitter' => 'https://twitter.com/rajeshkhadka',
                ]
            ],
            [
                'name' => 'Sunita Sharma',
                'position' => 'Vice President',
                'image' => '/images/team/sunita-sharma.jpg',
                'bio' => 'Expert in strategic planning and organizational development.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/sunitasharma',
                    'facebook' => 'https://facebook.com/sunitasharma',
                ]
            ],
            [
                'name' => 'Mohan Thapa',
                'position' => 'Secretary General',
                'image' => '/images/team/mohan-thapa.jpg',
                'bio' => 'Specializes in industrial relations and membership development.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/mohanthapa',
                    'twitter' => 'https://twitter.com/mohanthapa',
                ]
            ],
            [
                'name' => 'Anita Rai',
                'position' => 'Treasurer',
                'image' => '/images/team/anita-rai.jpg',
                'bio' => 'Financial expert with focus on industrial funding and investments.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/anitarai',
                    'instagram' => 'https://instagram.com/anitarai',
                ]
            ],
            [
                'name' => 'Deepak Gurung',
                'position' => 'Executive Member',
                'image' => '/images/team/deepak-gurung.jpg',
                'bio' => 'Technology enthusiast driving digital transformation in industries.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/deepakgurung',
                    'twitter' => 'https://twitter.com/deepakgurung',
                ]
            ],
            [
                'name' => 'Laxmi Poudel',
                'position' => 'Executive Member',
                'image' => '/images/team/laxmi-poudel.jpg',
                'bio' => 'Advocate for women entrepreneurship and industrial diversity.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/laxmipoudel',
                    'facebook' => 'https://facebook.com/laxmipoudel',
                ]
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.team-card');
    }
}
