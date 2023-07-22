<?php

namespace App\Http\Controllers;

use App\Enums\Enums;
use App\Interfaces\FixtureRepositoryInterface;
use App\Interfaces\LeagueTableRepositoryInterface;
use App\Interfaces\PlayedWeekRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use App\Services\TournamentService;
use Database\Seeders\TeamSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

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
            ->getFixtures() // todo: With ile relationları alıyor ama burada has() ile kontrol etmek çok daha faydalı olabilirdi.
            ->groupBy(Enums::FIXTURE_WEEEK_FIELD);

        $fixtureWeek = $this->playedWeekRepository->getPlayedWeek();
        $totalNumberOfWeeks = $this->fixtureRepository->totalNumberOfWeeks();

        $endOfTournament = (int)$totalNumberOfWeeks === (int)$fixtureWeek; // todo: Kod tekrarı(2) var, method'a alınabilirdi. Variable ismi daha iyi olabilirdi.

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
        if (!$this->fixtureRepository->checkIfFixtureExist()) {
            Session::flash('warning', 'Please start the simulation first!');

            return redirect('fixtures');
        }

        $leagueTables = $this->leagueTableRepository->getLeagueTables();
        $teams = $this->teamRepository->getTeams();

        if ($leagueTables->isEmpty()) {
            $this->leagueTableRepository->prepareLeagueTable($teams); // todo: Veritabanına kayıt yapılıyor isimlendirme çokta doğru değil.
        }

        $fixtures = $this->fixtureRepository->getFixtures();

        if (!is_null($week)) {
            $fixtureWeek = $week;
        } else {
            $fixtureWeek = $this->playedWeekRepository->getPlayedWeek();
        } // todo: null coalescing operator (??) ile tek satırda yazılabilirdi.

        $this->tournamentService->simulateWeek($fixtures, $fixtureWeek); // todo: Birden fazla tabloya kayıt yapılıyor. Bu işlemi DB::beginTransaction() ile yaparak try catch içerisine almak daha doğru olurdu.

        $weeklyFixtures = $this->fixtureRepository->getFixtureByWeek($fixtureWeek);

        $leagueTables = $this->leagueTableRepository->getLeagueTables();
        $leagueTables = $this->tournamentService->orderLeagueTable($leagueTables);

        $totalNumberOfWeeks = $this->fixtureRepository->totalNumberOfWeeks();

        $isLastFourWeek = $totalNumberOfWeeks - $fixtureWeek < 2; // todo: Son 2 hafta'ya girildiğinde true set ediliyor, ama variable ismi yanlış. (Ayrıca task'ta son 3 hafta'ya girildiğinde yazıyordu.)
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
        Schema::disableForeignKeyConstraints();
        $this->fixtureRepository->resetFixture();
        $this->leagueTableRepository->resetLeagueTable();
        $this->playedWeekRepository->resetPlayedWeek();
        $this->teamRepository->resetTeams();
        Schema::enableForeignKeyConstraints();

        app(TeamSeeder::class)->run();

        return redirect('/');
    }
}
