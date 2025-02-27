<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-5">

                    <div class="flex flex-col md:flex-row gap-5 items-end">
                        <div class="w-full md:w-1/2 min-h-lg">
                            <canvas id="top-countries" class="h-full"></canvas>
                        </div>

                        <div class="w-full md:w-1/2 min-h-lg">
                            <canvas id="top-states" class="h-full"></canvas>
                        </div>

                    </div>

                    <canvas id="top-cities"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const topCountries = document.getElementById('top-countries');
        const topStates = document.getElementById('top-states');
        const topCities = document.getElementById('top-cities');

        new Chart(topCountries, {
            type: 'pie',
            data: {
                labels: ["{!! join('", "', $countries['names']) !!}"],
                datasets: [{
                    label: 'Users in top 10 countries',
                    data: [{!! join(', ', $countries['data'])!!}],
                borderWidth: 1
            }]
        },
        });

        new Chart(topStates, {
            type: 'bar',
            data: {
                labels: ["{!! join('", "', $states['names']) !!}"],
                datasets: [{
                    label: 'Users in top 10 states',
                    data: [{!! join(', ', $states['data'])!!}],
                borderWidth: 1,
            }]
        },
            options: {
            scales: {
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.4)'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0,0,0,0.4)'
                    }
                },

            },
        }
        });

        new Chart(topCities, {
            type: 'line',
            data: {
                labels: ["{!! join('", "', $cities['names']) !!}"],
                datasets: [{
                    label: 'Users in top 10 cities',
                    data: [{!! join(', ', $cities['data'])!!}],
                borderWidth: 1
            }]
        },
            options: {
            scales: {
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.4)'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0,0,0,0.4)'
                    }
                },

            },
        }
        });
    </script>
</x-app-layout>
