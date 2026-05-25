<?php

return [
    'main' => [
        [
            'label' => 'Home',
            'route' => 'home',
            'icon'  => 'mdi:home-outline',
        ],
        [
            'label' => 'About Us',
            'route' => 'about-us',
            'icon'  => 'mdi:information-outline',
        ],
        [
            'label' => 'Team',
            'route' => 'teams',
            'icon'  => 'mdi:account-group-outline',
        ],
        [
            'label' => 'FAQs',
            'route' => 'faqs',
            'icon'  => 'mdi:account-group-outline',
        ],
        [
            'label' => 'Contact',
            'route' => 'contact',
            'icon'  => 'mdi:phone-outline',
        ],
    ],

    'admin' => [
        [
            'label' => 'Media',
            'route' => 'admin.media.index',
            'icon'  => 'heroicons:home-solid',
            'active' => 'admin.media.*',
        ],
        [
            'label' => 'Missions',
            'route' => 'admin.missions.index',
            'icon'  => 'material-symbols:other-admission',
            'active' => 'admin.missions.*', // Note: This might need updating if missions has its own route pattern
        ],
        [
            'label' => 'Teams',
            'route' => 'admin.teams.index',
            'icon'  => 'heroicons:user-group-solid',
            'active' => 'admin.teams.*',
        ],
        [
            'label' => 'FAQs',
            'route' => 'admin.faqs.index',
            'icon'  => 'heroicons:question-mark-circle-solid',
            'active' => 'admin.faqs.*',
        ],
        [
            'label' => 'Services',
            'route' => 'admin.services.index',
            'icon'  => 'heroicons:wrench-screwdriver-solid',
            'active' => 'admin.services.*',
        ],
        [
            'label' => 'Carousel',
            'route' => 'admin.carousel.index',
            'icon'  => 'heroicons:photo-solid',
            'active' => 'admin.carousel.*',
        ],
        [
            'label' => 'Configuration',
            'route' => 'admin.configuration.index',
            'icon'  => 'heroicons:cog-6-tooth-solid',
            'active' => 'admin.configuration.*',
        ],
        [
            'label' => 'Messages',
            'route' => 'admin.messages.index',
            'icon'  => 'tabler:message',
            'active' => 'admin.messages.*',
        ],
        [
            'label' => 'Team Messages',
            'route' => 'admin.team-messages.index',
            'icon'  => 'tabler:message',
            'active' => 'admin.team-messages.*',
        ],
    ],
];
