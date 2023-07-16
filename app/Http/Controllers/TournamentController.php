<?php

namespace App\Http\Controllers;

use App\Enums\Enums;
use App\Interfaces\FixtureRepositoryInterface;
use App\Interfaces\LeagueTableRepositoryInterface;
use App\Interfaces\PlayedWeekRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use App\Services\TournamentService;

class TournamentController extends Controller
{
    private TeamRepositoryInterface $teamRepository;
    private TournamentService $tournamentService;
    private FixtureRepositoryInterface $fixtureRepository;
    private LeagueTableRepositoryInterface $leagueTableRepository;
    private PlayedWeekRepositoryInterface $playedWeekRepository;

    public function __construct(
        TeamRepositoryInterface        $teamRepository,
        TournamentService              $tournamentService,
        FixtureRepositoryInterface     $fixtureRepository,
        LeagueTableRepositoryInterface $leagueTableRepository,
        PlayedWeekRepositoryInterface  $playedWeekRepository
    )
    {
        $this->teamRepository = $teamRepository;
        $this->tournamentService = $tournamentService;
        $this->fixtureRepository = $fixtureRepository;
        $this->leagueTableRepository = $leagueTableRepository;
        $this->playedWeekRepository = $playedWeekRepository;
    }

    public function teams()
    {
        $teams = $this->teamRepository->getTeams();

        return view('teams', [
            'menu' => 'teams',
            'teams' => $teams
        ]);
    }

    public function fixtures()
    {
        $teams = $this->teamRepository->getTeams();
        $rounds = $this->tournamentService->generateFixture($teams);

        $optimizedRounds = $this->tournamentService->optimizeRounds($rounds);
        $checkIfFixtureExist = $this->fixtureRepository->checkIfFixtureExist();

        if (!$checkIfFixtureExist) {
            $this->fixtureRepository->saveFixture($optimizedRounds);
        }

        $groupFixtureByWeeks = $this
            ->fixtureRepository
            ->getFixtures()
            ->groupBy(Enums::FIXTURE_WEEEK_FIELD);

        $fixtureWeek = $this->playedWeekRepository->getPlayedWeek();
        $totalNumberOfWeeks = $this->fixtureRepository->totalNumberOfWeeks();

        $endOfTournament = (int)$totalNumberOfWeeks === (int)$fixtureWeek;

        return view('fixtures', [
            'menu' => 'fixtures',
            'rounds' => $rounds,
            'groupFixtureByWeeks' => $groupFixtureByWeeks,
            'fixtureWeek' => $fixtureWeek,
            'endOfTournament' => $endOfTournament
        ]);
    }

    public function simulation($week = null)
    {
        $leagueTables = $this->leagueTableRepository->getLeagueTables();
        $teams = $this->teamRepository->getTeams();

        if ($leagueTables->isEmpty()) {
            $this->leagueTableRepository->prepareLeagueTable($teams);
        }

        $fixtures = $this->fixtureRepository->getFixtures();

        if (!is_null($week)) {
            $fixtureWeek = $week;
        } else {
            $fixtureWeek = $this->playedWeekRepository->getPlayedWeek();
        }

        $this->tournamentService->simulateWeek($fixtures, $fixtureWeek);

        $weeklyFixtures = $this->fixtureRepository->getFixtureByWeek($fixtureWeek);

        $leagueTables = $this->leagueTableRepository->getLeagueTables();
        $leagueTables = $this->tournamentService->orderLeagueTable($leagueTables);

        $totalNumberOfWeeks = $this->fixtureRepository->totalNumberOfWeeks();

        $isLastFourWeek = $totalNumberOfWeeks - $fixtureWeek < 2;
        if ($isLastFourWeek) {
            $estimatedResults = $this->tournamentService->championshipOddsPrediction($leagueTables, $totalNumberOfWeeks, $fixtureWeek);
        } else {
            $estimatedResults = [];
        }

        $endOfTournament = (int)$totalNumberOfWeeks === (int)$fixtureWeek;

        return view('simulation', [
            'menu' => 'simulation',
            'teams' => $teams,
            'leagueTables' => $leagueTables,
            'weeklyFixtures' => $weeklyFixtures,
            'currentWeek' => $fixtureWeek,
            'nextWeek' => $fixtureWeek + 1,
            'isLastFourWeek' => $isLastFourWeek,
            'estimatedResults' => $estimatedResults,
            'endOfTournament' => $endOfTournament
        ]);
    }

    public function resetTournament()
    {
        $this->fixtureRepository->resetFixture();
        $this->leagueTableRepository->resetLeagueTable();
        $this->playedWeekRepository->resetPlayedWeek();

        return redirect('/');
    }
}
