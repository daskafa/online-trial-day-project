<?php

namespace App\Services;

class TournamentService
{
    public function generateFixture($teams)
    {
        $teamsCount = $teams->count();

        $rounds = [];
        $matchesPerRound = $teamsCount / 2;

        for ($week = 0; $week < 2 * ($teamsCount - 1); $week++) {
            for ($match = 0; $match < $matchesPerRound; $match++) {
                $home = ($week + $match) % ($teamsCount - 1);
                $away = ($teamsCount - 1 - $match + $week) % ($teamsCount - 1);

                if ($match === 0) {
                    $away = $teamsCount - 1;
                }

                $rounds[$week][$match] = [
                    'home' => $teams[$home]->id,
                    'away' => $teams[$away]->id,
                ];
            }
        }

        return $rounds;
    }


    public function optimizeRounds($rounds)
    {
        $optimizedRounds = [];
        foreach ($rounds as $key => $round) {
            foreach ($round as $match) {
                $optimizedRounds[$key + 1][] = [
                    'home' => $match['home'],
                    'away' => $match['away'],
                ];
            }

        }

        $prepareRoundsForDBSave = [];
        foreach ($optimizedRounds as $key => $optimizedRound) {
            foreach ($optimizedRound as $optimizedRoundMatch) {
                $prepareRoundsForDBSave[] = [
                    'home_team_id' => $optimizedRoundMatch['home'],
                    'away_team_id' => $optimizedRoundMatch['away'],
                    'week' => $key,
                    'created_at' => now(),
                ];
            }
        }

        return $prepareRoundsForDBSave;
    }
}
