@extends('layouts.master')
@section('content')
    @if(Session::has('warning'))
        <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Warning:</span>
            <div>
                <span class="font-medium">{{ Session::get('warning') }}</span>
            </div>
        </div>
    @endif
    <h2 class="text-4xl font-extrabold py-3">Generated Fixtures</h2>
    <div class="mb-3">
        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Home Team</span>
        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Away Team</span>
    </div>
    <div class="grid mb-3 border border-gray-200 rounded-lg shadow-sm md:grid-cols-2">
        @foreach($groupFixtureByWeeks as $key => $groupFixtureByWeek)
            <figure class="flex flex-col items-center justify-center p-8 text-center {{ $key === $fixtureWeek ? 'bg-gray-50' : '' }} border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-tl-lg md:border-r">
                <blockquote class="max-w-2xl mx-auto text-gray-500">
                    <h3 class="text-2xl text-gray-700">
                        {{ $key . '.st Week' }}
                    </h3>
                    <div class="mt-4">
                        @foreach($groupFixtureByWeek as $groupFixtureByWeekItem)
                            <div class="grid grid-cols-3 mt-3">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $groupFixtureByWeekItem->homeTeam->name }}</span>
                                <span class="mx-2 italic">vs</span>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $groupFixtureByWeekItem->awayTeam->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </blockquote>
            </figure>
        @endforeach
    </div>
    <a href="{{ url('simulation') }}" type="button" class="text-white bg-gray-700 hover:bg-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2">
        @if($fixtureWeek === 1)
            <svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1.984v14.032a1 1 0 0 0 1.506.845l12.006-7.016a.974.974 0 0 0 0-1.69L2.506 1.139A1 1 0 0 0 1 1.984Z"/>
            </svg>
            Start Simulation
        @elseif($endOfTournament)
            <svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 1h10M6 5h10M6 9h10M1.49 1h.01m-.01 4h.01m-.01 4h.01"/>
            </svg>
            View Results
        @else
            <svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1.984v14.032a1 1 0 0 0 1.506.845l12.006-7.016a.974.974 0 0 0 0-1.69L2.506 1.139A1 1 0 0 0 1 1.984Z"/>
            </svg>
            Continue Simulation
        @endif
    </a>
@endsection
