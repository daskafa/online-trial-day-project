<?php

namespace App\Services;

use App\Enums\Enums;
use App\Interfaces\FixtureRepositoryInterface;
use App\Interfaces\LeagueTableRepositoryInterface;
use App\Interfaces\PlayedWeekRepositoryInterface;

class TournamentService
{
    private FixtureRepositoryInterface $fixtureRepository;
    private LeagueTableRepositoryInterface $leagueTableRepository;
    private PlayedWeekRepositoryInterface $playedWeekRepository;

    public function __construct(
        FixtureRepositoryInterface     $fixtureRepository,
        LeagueTableRepositoryInterface $leagueTableRepository,
        PlayedWeekRepositoryInterface  $playedWeekRepository
    )
    {
        $this->fixtureRepository = $fixtureRepository;
        $this->leagueTableRepository = $leagueTableRepository;
        $this->playedWeekRepository = $playedWeekRepository;
    }

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

    public function simulateWeek($fixtures, $week)
    {
        $weekFixtures = $fixtures->where(Enums::FIXTURE_WEEEK_FIELD, $week);
        $isPlayedThisWeek = $weekFixtures->map(function ($fixture) {
            return is_null($fixture->home_team_score) || is_null($fixture->away_team_score);
        })->contains(false);

        if (!$isPlayedThisWeek) {
            foreach ($weekFixtures as $fixture) {
                $homeTeam = $fixture->homeTeam;
                $awayTeam = $fixture->awayTeam;

                $generatedScore = $this->generateScore($homeTeam, $awayTeam);
                $this->fixtureRepository->updateFixtureByWeek($fixture, $generatedScore);

                $newFixture = $this->fixtureRepository->getFixtureById($fixture->id);
                $this->leagueTableRepository->updateLeagueTable($newFixture);
            }

            $this->playedWeekRepository->incrementPlayedWeek($week);
        }
    }

    public function generateScore($homeTeam, $awayTeam)
    {
        $homeTeamPower = $homeTeam->team_power;
        $awayTeamPower = $awayTeam->team_power;

        $homeTeamSupporterPower = $homeTeam->supporter_power;
        $awayTeamSupporterPower = $awayTeam->supporter_power;

        $homeTeamGoalkeeperPower = $homeTeam->goalkeeper_power;
        $awayTeamGoalkeeperPower = $awayTeam->goalkeeper_power;

        $homeTeamScore = random_int(0, $homeTeamPower + $homeTeamSupporterPower + $homeTeamGoalkeeperPower);
        $awayTeamScore = random_int(0, $awayTeamPower + $awayTeamSupporterPower + $awayTeamGoalkeeperPower);

        if ($homeTeamScore > 8 || $awayTeamScore > 8) {
            return $this->generateScore($homeTeam, $awayTeam);
        }

        return [
            'homeTeamScore' => $homeTeamScore,
            'awayTeamScore' => $awayTeamScore,
        ];
    }
}
