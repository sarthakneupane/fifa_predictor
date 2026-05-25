<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [

            // Group A
            'A' => [
                ['name' => 'Mexico', 'short_name' => 'MEX', 'flag_emoji' => '🇲🇽', 'confederation' => 'CONCACAF'],
                ['name' => 'South Africa', 'short_name' => 'RSA', 'flag_emoji' => '🇿🇦', 'confederation' => 'CAF'],
                ['name' => 'Czechia', 'short_name' => 'CZE', 'flag_emoji' => '🇨🇿', 'confederation' => 'UEFA'],
                ['name' => 'South Korea', 'short_name' => 'KOR', 'flag_emoji' => '🇰🇷', 'confederation' => 'AFC'],
            ],

            // Group B
            'B' => [
                ['name' => 'Canada', 'short_name' => 'CAN', 'flag_emoji' => '🇨🇦', 'confederation' => 'CONCACAF'],
                ['name' => 'Bosnia and Herzegovina', 'short_name' => 'BIH', 'flag_emoji' => '🇧🇦', 'confederation' => 'UEFA'],
                ['name' => 'Qatar', 'short_name' => 'QAT', 'flag_emoji' => '🇶🇦', 'confederation' => 'AFC'],
                ['name' => 'Switzerland', 'short_name' => 'SUI', 'flag_emoji' => '🇨🇭', 'confederation' => 'UEFA'],
            ],

            // Group C
            'C' => [
                ['name' => 'Brazil', 'short_name' => 'BRA', 'flag_emoji' => '🇧🇷', 'confederation' => 'CONMEBOL'],
                ['name' => 'Morocco', 'short_name' => 'MAR', 'flag_emoji' => '🇲🇦', 'confederation' => 'CAF'],
                ['name' => 'Scotland', 'short_name' => 'SCO', 'flag_emoji' => '🏴', 'confederation' => 'UEFA'],
                ['name' => 'Haiti', 'short_name' => 'HAI', 'flag_emoji' => '🇭🇹', 'confederation' => 'CONCACAF'],
            ],

            // Group D
            'D' => [
                ['name' => 'United States', 'short_name' => 'USA', 'flag_emoji' => '🇺🇸', 'confederation' => 'CONCACAF'],
                ['name' => 'Australia', 'short_name' => 'AUS', 'flag_emoji' => '🇦🇺', 'confederation' => 'AFC'],
                ['name' => 'Paraguay', 'short_name' => 'PAR', 'flag_emoji' => '🇵🇾', 'confederation' => 'CONMEBOL'],
                ['name' => 'Türkiye', 'short_name' => 'TUR', 'flag_emoji' => '🇹🇷', 'confederation' => 'UEFA'],
            ],

            // Group E
            'E' => [
                ['name' => 'Germany', 'short_name' => 'GER', 'flag_emoji' => '🇩🇪', 'confederation' => 'UEFA'],
                ['name' => 'Ecuador', 'short_name' => 'ECU', 'flag_emoji' => '🇪🇨', 'confederation' => 'CONMEBOL'],
                ['name' => 'Côte d\'Ivoire', 'short_name' => 'CIV', 'flag_emoji' => '🇨🇮', 'confederation' => 'CAF'],
                ['name' => 'Curaçao', 'short_name' => 'CUW', 'flag_emoji' => '🇨🇼', 'confederation' => 'CONCACAF'],
            ],

            // Group F
            'F' => [
                ['name' => 'Netherlands', 'short_name' => 'NED', 'flag_emoji' => '🇳🇱', 'confederation' => 'UEFA'],
                ['name' => 'Sweden', 'short_name' => 'SWE', 'flag_emoji' => '🇸🇪', 'confederation' => 'UEFA'],
                ['name' => 'Japan', 'short_name' => 'JPN', 'flag_emoji' => '🇯🇵', 'confederation' => 'AFC'],
                ['name' => 'Tunisia', 'short_name' => 'TUN', 'flag_emoji' => '🇹🇳', 'confederation' => 'CAF'],
            ],

            // Group G
            'G' => [
                ['name' => 'Belgium', 'short_name' => 'BEL', 'flag_emoji' => '🇧🇪', 'confederation' => 'UEFA'],
                ['name' => 'Egypt', 'short_name' => 'EGY', 'flag_emoji' => '🇪🇬', 'confederation' => 'CAF'],
                ['name' => 'Iran', 'short_name' => 'IRN', 'flag_emoji' => '🇮🇷', 'confederation' => 'AFC'],
                ['name' => 'New Zealand', 'short_name' => 'NZL', 'flag_emoji' => '🇳🇿', 'confederation' => 'OFC'],
            ],

            // Group H
            'H' => [
                ['name' => 'Spain', 'short_name' => 'ESP', 'flag_emoji' => '🇪🇸', 'confederation' => 'UEFA'],
                ['name' => 'Saudi Arabia', 'short_name' => 'KSA', 'flag_emoji' => '🇸🇦', 'confederation' => 'AFC'],
                ['name' => 'Uruguay', 'short_name' => 'URU', 'flag_emoji' => '🇺🇾', 'confederation' => 'CONMEBOL'],
                ['name' => 'Cabo Verde', 'short_name' => 'CPV', 'flag_emoji' => '🇨🇻', 'confederation' => 'CAF'],
            ],

            // Group I
            'I' => [
                ['name' => 'France', 'short_name' => 'FRA', 'flag_emoji' => '🇫🇷', 'confederation' => 'UEFA'],
                ['name' => 'Senegal', 'short_name' => 'SEN', 'flag_emoji' => '🇸🇳', 'confederation' => 'CAF'],
                ['name' => 'Norway', 'short_name' => 'NOR', 'flag_emoji' => '🇳🇴', 'confederation' => 'UEFA'],
                ['name' => 'Iraq', 'short_name' => 'IRQ', 'flag_emoji' => '🇮🇶', 'confederation' => 'AFC'],
            ],

            // Group J
            'J' => [
                ['name' => 'Argentina', 'short_name' => 'ARG', 'flag_emoji' => '🇦🇷', 'confederation' => 'CONMEBOL'],
                ['name' => 'Austria', 'short_name' => 'AUT', 'flag_emoji' => '🇦🇹', 'confederation' => 'UEFA'],
                ['name' => 'Algeria', 'short_name' => 'ALG', 'flag_emoji' => '🇩🇿', 'confederation' => 'CAF'],
                ['name' => 'Jordan', 'short_name' => 'JOR', 'flag_emoji' => '🇯🇴', 'confederation' => 'AFC'],
            ],

            // Group K
            'K' => [
                ['name' => 'Portugal', 'short_name' => 'POR', 'flag_emoji' => '🇵🇹', 'confederation' => 'UEFA'],
                ['name' => 'Colombia', 'short_name' => 'COL', 'flag_emoji' => '🇨🇴', 'confederation' => 'CONMEBOL'],
                ['name' => 'Uzbekistan', 'short_name' => 'UZB', 'flag_emoji' => '🇺🇿', 'confederation' => 'AFC'],
                ['name' => 'Congo DR', 'short_name' => 'COD', 'flag_emoji' => '🇨🇩', 'confederation' => 'CAF'],
            ],

            // Group L
            'L' => [
                ['name' => 'England', 'short_name' => 'ENG', 'flag_emoji' => '🏴', 'confederation' => 'UEFA'],
                ['name' => 'Croatia', 'short_name' => 'CRO', 'flag_emoji' => '🇭🇷', 'confederation' => 'UEFA'],
                ['name' => 'Ghana', 'short_name' => 'GHA', 'flag_emoji' => '🇬🇭', 'confederation' => 'CAF'],
                ['name' => 'Panama', 'short_name' => 'PAN', 'flag_emoji' => '🇵🇦', 'confederation' => 'CONCACAF'],
            ],
        ];

        foreach ($teams as $groupLetter => $groupTeams) {

            $group = Group::where('letter', $groupLetter)->first();

            if (!$group) {
                continue;
            }

            foreach ($groupTeams as $teamData) {

                Team::updateOrCreate(
                    [
                        'short_name' => $teamData['short_name']
                    ],
                    [
                        'group_id' => $group->id,
                        'name' => $teamData['name'],
                        'flag_emoji' => $teamData['flag_emoji'],
                        'confederation' => $teamData['confederation'],
                    ]
                );
            }
        }
    }
}