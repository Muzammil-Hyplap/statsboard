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
                        <canvas id="countries-world"></canvas>

                        <div class="w-full md:w-1/2 min-h-lg">
                            <canvas id="top-countries" class="h-full"></canvas>
                        </div>

                        <div class="w-full md:w-1/2 min-h-lg">
                            <canvas id="top-states" class="h-full"></canvas>
                        </div>

                    </div>

                    <canvas id="countries-gender"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script type="module">

        fetch('https://unpkg.com/world-atlas/countries-50m.json').then((r) => r.json()).then((data) => {

            const countries = ChartGeo.topojson.feature(data, data.objects.countries).features;

            const chart = new Chart(document.getElementById("countries-world"), {
                type: 'choropleth',
                data: {
                    labels: countries.map((d) => d.properties.name),
                    datasets: [{
                        label: 'Countries',
                        data: countries.map((d) => ({feature: d, value: Math.random()})),
                    }]
                },
                options: {
                    showOutline: true,
                    showGraticule: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                    },
                }
            });
        });

        const topCountries = document.getElementById('top-countries');
        const topStates = document.getElementById('top-states');
        const countriesGender = document.getElementById('countries-gender');

        new Chart(topCountries, {
            type: 'pie',
            data: {
                labels: ["{!! join('", "', array_keys($countries)) !!}"],
                datasets: [{
                    label: 'Users in top 10 countries',
                    data: [{!! join(', ', array_values($countries))!!}],
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

        new Chart(countriesGender, {
            type: 'line',
            data: {
                labels: [
                    "{!! join('", "', array_keys($countries)) !!}",
                ],
                datasets: [
                    {
                        label: 'Male users in top 10 cities',
                        data: [
                            {!! join(', ', array_values($countriesMale))!!},
                ],
                borderWidth: 1
            },
                    {
                label: 'Female users in top 10 cities',
                data: [
                    {!! join(', ', array_values($countriesFemale))!!},
                        ],
            borderWidth: 1
                    }
                ]
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
