<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Group;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GameSeeder extends Seeder
{
    /**
     * Seed Group Stage Gamees for the FIFA World Cup 2026.
     * Gamees start on June 11, 2026 (official tournament date).
     */
    public function run(): void
    {
        $stadiums = [
            'MetLife Stadium, New York',
            'AT&T Stadium, Dallas',
            'SoFi Stadium, Los Angeles',
            'Levi\'s Stadium, San Francisco',
            'Hard Rock Stadium, Miami',
            'Gillette Stadium, Boston',
            'Mercedes-Benz Stadium, Atlanta',
            'Arrowhead Stadium, Kansas City',
            'BC Place, Vancouver',
            'Estadio Azteca, Mexico City',
        ];

        // Sample games – Group A (USA, MEX, URU, PAN)
        $groupAGames = [
            ['USA', 'URU', '2026-06-12 19:00', $stadiums[2]],
            ['MEX', 'PAN', '2026-06-12 22:00', $stadiums[0]],
            ['USA', 'PAN', '2026-06-16 19:00', $stadiums[4]],
            ['URU', 'MEX', '2026-06-16 22:00', $stadiums[9]],
            ['PAN', 'URU', '2026-06-20 22:00', $stadiums[1]],
            ['MEX', 'USA', '2026-06-20 22:00', $stadiums[9]],
        ];

        // Sample games – Group B (ARG, ECU, CAN, CHI)
        $groupBGames = [
            ['ARG', 'CAN', '2026-06-13 19:00', $stadiums[0]],
            ['ECU', 'CHI', '2026-06-13 22:00', $stadiums[3]],
            ['ARG', 'CHI', '2026-06-17 19:00', $stadiums[1]],
            ['ECU', 'CAN', '2026-06-17 22:00', $stadiums[6]],
            ['CAN', 'CHI', '2026-06-21 22:00', $stadiums[8]],
            ['ARG', 'ECU', '2026-06-21 22:00', $stadiums[0]],
        ];

        // Sample games – Group C (BRA, JPN, COL, KSA)
        $groupCGames = [
            ['BRA', 'KSA', '2026-06-14 19:00', $stadiums[0]],
            ['JPN', 'COL', '2026-06-14 22:00', $stadiums[2]],
            ['BRA', 'JPN', '2026-06-18 19:00', $stadiums[4]],
            ['COL', 'KSA', '2026-06-18 22:00', $stadiums[1]],
            ['BRA', 'COL', '2026-06-22 22:00', $stadiums[0]],
            ['JPN', 'KSA', '2026-06-22 22:00', $stadiums[3]],
        ];

        // Sample Final and Semis
        $knockoutgames = [
            ['Final',       'TBD vs TBD',   '2026-07-19 20:00', $stadiums[0], null],
            ['Semi Final',  'TBD vs TBD',   '2026-07-14 20:00', $stadiums[0], null],
            ['Semi Final',  'TBD vs TBD',   '2026-07-15 20:00', $stadiums[2], null],
            ['Quarter Final','TBD vs TBD',  '2026-07-09 20:00', $stadiums[0], null],
            ['Quarter Final','TBD vs TBD',  '2026-07-10 20:00', $stadiums[1], null],
        ];

        $groupA = Group::where('letter', 'A')->first();
        $groupB = Group::where('letter', 'B')->first();
        $groupC = Group::where('letter', 'C')->first();

        $this->seedGroupGames($groupAGames, $groupA?->id, 'Group Stage');
        $this->seedGroupGames($groupBGames, $groupB?->id, 'Group Stage');
        $this->seedGroupGames($groupCGames, $groupC?->id, 'Group Stage');

        // Demo: first game of Group A is "completed" with a score
        $firstGame = Game::with(['homeTeam', 'awayTeam'])
            ->whereHas('homeTeam', fn($q) => $q->where('short_name', 'USA'))
            ->whereHas('awayTeam', fn($q) => $q->where('short_name', 'URU'))
            ->first();

        if ($firstGame) {
            $firstGame->update([
                'status'     => 'completed',
                'home_score' => 2,
                'away_score' => 1,
            ]);
        }

        // Demo: one live Game
        $secondGame = Game::with(['homeTeam', 'awayTeam'])
            ->whereHas('homeTeam', fn($q) => $q->where('short_name', 'MEX'))
            ->whereHas('awayTeam', fn($q) => $q->where('short_name', 'PAN'))
            ->first();

        if ($secondGame) {
            $secondGame->update([
                'status'     => 'live',
                'home_score' => 1,
                'away_score' => 0,
            ]);
        }
    }

    private function seedGroupGames(array $gameData, ?int $groupId, string $stage): void
    {
        foreach ($gameData as $index => $row) {
            [$homeShort, $awayShort, $dateStr, $stadium] = $row;

            $homeTeam = Team::where('short_name', $homeShort)->first();
            $awayTeam = Team::where('short_name', $awayShort)->first();

            if (!$homeTeam || !$awayTeam) continue;

            $gameDate = Carbon::parse($dateStr);

            Game::firstOrCreate(
                [
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'game_date'   => $gameDate,
                ],
                [
                    'group_id'            => $groupId,
                    'stadium'             => $stadium,
                    'stage'               => $stage,
                    'status'              => 'upcoming',
                    'prediction_deadline' => $gameDate,  // deadline = kick-off
                    'game_number'        => 'Game ' . ($index + 1),
                ]
            );
        }
    }
}