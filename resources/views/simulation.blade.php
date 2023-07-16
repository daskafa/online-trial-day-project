@extends('layouts.master')
@section('content')
    <div class="relative overflow-x-auto">
        <h2 class="text-4xl font-extrabold py-3">Simulation</h2>
        <table class="w-full text-sm text-left text-gray-500 border-collapse border border-gray-100 divide-y divide-gray-100">
            <thead class="text-xs text-gray-700 bg-gray-100">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Team Name
                </th>
                <th scope="col" class="px-6 py-3">
                    PTS
                </th>
                <th scope="col" class="px-6 py-3">
                    Played
                </th>
                <th scope="col" class="px-6 py-3">
                    W
                </th>
                <th scope="col" class="px-6 py-3">
                    D
                </th>
                <th scope="col" class="px-6 py-3">
                    L
                </th>
                <th scope="col" class="px-6 py-3">
                    GD
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($leagueTables as $leagueTable)
                <tr class="bg-white border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        @if($endOfTournament && $loop->first)
                            🎉
                        @endif
                        {{ $leagueTable->team->name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $leagueTable->points }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $leagueTable->played }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $leagueTable->won }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $leagueTable->drawn }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $leagueTable->lost }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $leagueTable->goal_difference }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="{{ $isLastFourWeek && !$endOfTournament ? 'grid grid-cols-2 gap-8' : '' }} mt-8">
            @if(!$weeklyFixtures->isEmpty())
                <table class="w-full text-sm text-left text-gray-500 border-collapse border border-gray-100 divide-y divide-gray-100">
                    <thead class="text-xs text-gray-700 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            {{ $currentWeek . '.st Week' }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($weeklyFixtures as $weeklyFixture)
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $weeklyFixture->homeTeam->name }} {{ $weeklyFixture->home_team_score }} - {{ $weeklyFixture->away_team_score }} {{ $weeklyFixture->awayTeam->name }}
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            @if($isLastFourWeek && !$endOfTournament)
                <table class="w-full text-sm text-left text-gray-500 border-collapse border border-gray-100 divide-y divide-gray-100">
                    <thead class="text-xs text-gray-700 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Championship Predictions
                        </th>
                        <td class="px-6 py-4">
                            %
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($estimatedResults as $key => $estimatedResult)
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $key }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $estimatedResult }}%
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($endOfTournament)
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-8">
                <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-5xl">
                    Tournament is over! Winner: <span class="underline underline-offset-3 decoration-8 decoration-blue-400"> {{ $leagueTables->first()->team->name }} 🎉</span>
                </h1>
                <p class="text-lg font-normal text-gray-500 lg:text-xl">
                    We have left behind another awesome season. See you next year!
                </p>
            </div>
        @endif
        <div class="flex justify-center {{ !$endOfTournament ? 'mt-6' : '' }}">
            <div class="inline-flex rounded-md shadow-sm" role="group">
                @if(!$endOfTournament)
                    <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                        <svg class="w-3 h-3 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 16">
                            <path d="M0 .984v14.032a1 1 0 0 0 1.506.845l12.006-7.016a.974.974 0 0 0 0-1.69L1.506.139A1 1 0 0 0 0 .984Z"/>
                        </svg>
                        Play All Weeks
                    </button>
                    <a href="{{ url('simulation', $nextWeek) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-gray-700">
                        <svg class="w-3 h-3 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
                        </svg>
                        Play Next Week
                    </a>
                @endif
                <a href="{{ url('reset-tournament') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border bg-red-500 border-red-200 rounded-r-md hover:bg-red-100 focus:z-10">
                    <svg class="w-3 h-3 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                        <path d="M17 4h-4V2a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v2H1a1 1 0 0 0 0 2h1v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1a1 1 0 1 0 0-2ZM7 2h4v2H7V2Zm1 14a1 1 0 1 1-2 0V8a1 1 0 0 1 2 0v8Zm4 0a1 1 0 0 1-2 0V8a1 1 0 0 1 2 0v8Z"/>
                    </svg>
                    Reset Data
                </a>
            </div>
        </div>
    </div>
@endsection
