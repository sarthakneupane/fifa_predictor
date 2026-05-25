<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Group;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $fixtures = [

            // GROUP A
            [
                'group' => 'A',
                'home' => 'MEX',
                'away' => 'RSA',
                'date' => '2026-06-11 19:00:00',
                'stadium' => 'Estadio Azteca, Mexico City',
            ],
            [
                'group' => 'A',
                'home' => 'KOR',
                'away' => 'CZE',
                'date' => '2026-06-11 22:00:00',
                'stadium' => 'Estadio Guadalajara, Guadalajara',
            ],

            // GROUP B
            [
                'group' => 'B',
                'home' => 'CAN',
                'away' => 'BIH',
                'date' => '2026-06-12 19:00:00',
                'stadium' => 'Toronto Stadium, Toronto',
            ],
            [
                'group' => 'B',
                'home' => 'QAT',
                'away' => 'SUI',
                'date' => '2026-06-13 22:00:00',
                'stadium' => 'San Francisco Bay Area Stadium',
            ],

            // GROUP C
            [
                'group' => 'C',
                'home' => 'HAI',
                'away' => 'SCO',
                'date' => '2026-06-13 16:00:00',
                'stadium' => 'Boston Stadium',
            ],
            [
                'group' => 'C',
                'home' => 'BRA',
                'away' => 'MAR',
                'date' => '2026-06-13 21:00:00',
                'stadium' => 'New York New Jersey Stadium',
            ],

            // GROUP D
            [
                'group' => 'D',
                'home' => 'USA',
                'away' => 'PAR',
                'date' => '2026-06-12 22:00:00',
                'stadium' => 'Los Angeles Stadium',
            ],
            [
                'group' => 'D',
                'home' => 'AUS',
                'away' => 'TUR',
                'date' => '2026-06-13 18:00:00',
                'stadium' => 'BC Place Vancouver',
            ],

            // GROUP E
            [
                'group' => 'E',
                'home' => 'CIV',
                'away' => 'ECU',
                'date' => '2026-06-14 16:00:00',
                'stadium' => 'Philadelphia Stadium',
            ],
            [
                'group' => 'E',
                'home' => 'GER',
                'away' => 'CUW',
                'date' => '2026-06-14 21:00:00',
                'stadium' => 'Houston Stadium',
            ],

            // GROUP F
            [
                'group' => 'F',
                'home' => 'NED',
                'away' => 'JPN',
                'date' => '2026-06-14 19:00:00',
                'stadium' => 'Dallas Stadium',
            ],
            [
                'group' => 'F',
                'home' => 'SWE',
                'away' => 'TUN',
                'date' => '2026-06-14 22:00:00',
                'stadium' => 'Estadio Monterrey',
            ],

            // GROUP G
            [
                'group' => 'G',
                'home' => 'IRN',
                'away' => 'NZL',
                'date' => '2026-06-15 16:00:00',
                'stadium' => 'Los Angeles Stadium',
            ],
            [
                'group' => 'G',
                'home' => 'BEL',
                'away' => 'EGY',
                'date' => '2026-06-15 21:00:00',
                'stadium' => 'Seattle Stadium',
            ],

            // GROUP H
            [
                'group' => 'H',
                'home' => 'KSA',
                'away' => 'URU',
                'date' => '2026-06-15 18:00:00',
                'stadium' => 'Miami Stadium',
            ],
            [
                'group' => 'H',
                'home' => 'ESP',
                'away' => 'CPV',
                'date' => '2026-06-15 22:00:00',
                'stadium' => 'Atlanta Stadium',
            ],

            // GROUP I
            [
                'group' => 'I',
                'home' => 'FRA',
                'away' => 'SEN',
                'date' => '2026-06-16 18:00:00',
                'stadium' => 'Kansas City Stadium',
            ],
            [
                'group' => 'I',
                'home' => 'NOR',
                'away' => 'IRQ',
                'date' => '2026-06-16 21:00:00',
                'stadium' => 'Chicago Stadium',
            ],

            // GROUP J
            [
                'group' => 'J',
                'home' => 'ARG',
                'away' => 'JOR',
                'date' => '2026-06-17 19:00:00',
                'stadium' => 'Miami Stadium',
            ],
            [
                'group' => 'J',
                'home' => 'AUT',
                'away' => 'ALG',
                'date' => '2026-06-17 22:00:00',
                'stadium' => 'Dallas Stadium',
            ],

            // GROUP K
            [
                'group' => 'K',
                'home' => 'POR',
                'away' => 'COD',
                'date' => '2026-06-18 19:00:00',
                'stadium' => 'Boston Stadium',
            ],
            [
                'group' => 'K',
                'home' => 'COL',
                'away' => 'UZB',
                'date' => '2026-06-18 22:00:00',
                'stadium' => 'Seattle Stadium',
            ],

            // GROUP L
            [
                'group' => 'L',
                'home' => 'ENG',
                'away' => 'PAN',
                'date' => '2026-06-19 19:00:00',
                'stadium' => 'New York New Jersey Stadium',
            ],
            [
                'group' => 'L',
                'home' => 'CRO',
                'away' => 'GHA',
                'date' => '2026-06-19 22:00:00',
                'stadium' => 'Toronto Stadium',
            ],
        ];

        foreach ($fixtures as $index => $fixture) {

            $group = Group::where('letter', $fixture['group'])->first();

            $homeTeam = Team::where('short_name', $fixture['home'])->first();

            $awayTeam = Team::where('short_name', $fixture['away'])->first();

            if (!$group || !$homeTeam || !$awayTeam) {
                continue;
            }

            $gameDate = Carbon::parse($fixture['date']);

            Game::updateOrCreate(
                [
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'game_date' => $gameDate,
                ],
                [
                    'group_id' => $group->id,
                    'stadium' => $fixture['stadium'],
                    'stage' => 'Group Stage',
                    'status' => 'upcoming',
                    'prediction_deadline' => $gameDate,
                    'game_number' => 'Match ' . ($index + 1),
                ]
            );
        }

        // Demo completed match
        $completed = Game::first();

        if ($completed) {
            $completed->update([
                'status' => 'completed',
                'home_score' => 2,
                'away_score' => 1,
            ]);
        }

        // Demo live match
        $live = Game::skip(1)->first();

        if ($live) {
            $live->update([
                'status' => 'live',
                'home_score' => 1,
                'away_score' => 0,
            ]);
        }
    }
}