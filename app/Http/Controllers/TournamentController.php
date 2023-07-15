<?php

namespace App\Http\Controllers;

use App\Enums\Enums;
use App\Interfaces\FixtureRepositoryInterface;
use App\Interfaces\LeagueTableRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use App\Services\TournamentService;

class TournamentController extends Controller
{
    private TeamRepositoryInterface $teamRepository;
    private TournamentService $tournamentService;
    private FixtureRepositoryInterface $fixtureRepository;
    private LeagueTableRepositoryInterface $leagueTableRepository;

    public function __construct(
        TeamRepositoryInterface        $teamRepository,
        TournamentService              $tournamentService,
        FixtureRepositoryInterface     $fixtureRepository,
        LeagueTableRepositoryInterface $leagueTableRepository
    )
    {
        $this->teamRepository = $teamRepository;
        $this->tournamentService = $tournamentService;
        $this->fixtureRepository = $fixtureRepository;
        $this->leagueTableRepository = $leagueTableRepository;
    }

    public function teams()
    {
        $teams = $this->teamRepository->getTeams();

        return view('teams', [
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

        return view('fixtures', [
            'rounds' => $rounds,
            'groupFixtureByWeeks' => $groupFixtureByWeeks
        ]);
    }

    public function simulation()
    {
        $leagueTables = $this->leagueTableRepository->getLeagueTables();
        $teams = $this->teamRepository->getTeams();

        if ($leagueTables->isEmpty()) {
            $this->leagueTableRepository->prepareLeagueTable($teams);
            $leagueTables = $this->leagueTableRepository->getLeagueTables();
        }

        $firstWeekFixtures = $this->fixtureRepository->getFirstWeekFixtures();

        return view('simulation', [
            'teams' => $teams,
            'leagueTables' => $leagueTables,
            'firstWeekFixtures' => $firstWeekFixtures
        ]);
    }
}
