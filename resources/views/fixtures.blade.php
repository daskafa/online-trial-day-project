@extends('layouts.master')
@section('content')
    <h2 class="text-4xl font-extrabold py-3">Generated Fixtures</h2>
    <div class="mb-3">
        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Home Team</span>
        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Away Team</span>
    </div>
    <div class="grid mb-3 border border-gray-200 rounded-lg shadow-sm md:grid-cols-2">
        @foreach($groupFixtureByWeeks as $key => $groupFixtureByWeek)
            <figure class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-tl-lg md:border-r">
                <blockquote class="max-w-2xl mx-auto text-gray-500">
                    <h3 class="text-2xl font-semibold text-gray-700">{{ $key . '.st Week' }}</h3>
                    <div class="mt-4">
                        @foreach($groupFixtureByWeek as $groupFixtureByWeekItem)
                            <div class="grid grid-cols-3 mt-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $groupFixtureByWeekItem->homeTeam->name }}</span>
                                <span class="mx-2">vs</span>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $groupFixtureByWeekItem->awayTeam->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </blockquote>
            </figure>
        @endforeach
    </div>
    <a href="{{ url('simulation') }}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2">
        <svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1.984v14.032a1 1 0 0 0 1.506.845l12.006-7.016a.974.974 0 0 0 0-1.69L2.506 1.139A1 1 0 0 0 1 1.984Z"/>
        </svg>
        Start Simulation
    </a>
@endsection
