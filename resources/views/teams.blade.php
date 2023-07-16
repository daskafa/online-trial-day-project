@extends('layouts.master')
@section('content')
    <div class="relative overflow-x-auto">
        <h2 class="text-4xl font-extrabold py-3">Tournament Teams</h2>
        <table class="w-full text-sm text-left text-gray-500 border-collapse border border-gray-100 divide-y divide-gray-100">
            <thead class="text-xs text-gray-700 bg-gray-100">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Team Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Team Power
                </th>
                <th scope="col" class="px-6 py-3">
                    Supporter Power
                </th>
                <th scope="col" class="px-6 py-3">
                    Goalkeeper Power
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($teams as $team)
                <tr class="bg-white border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $team->name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $team->team_power }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $team->supporter_power }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $team->goalkeeper_power }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="py-3">
            <a href="{{ url('fixtures') }}" type="button" class="text-white bg-gray-700 hover:bg-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2">
                <svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 14 3-3m-3 3 3 3m-3-3h16v-3m2-7-3 3m3-3-3-3m3 3H3v3"/>
                </svg>
                Generate Fixtures
            </a>
        </div>
    </div>
@endsection
