<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                      <canvas id="top-countries"></canvas>
                      <canvas id="top-states"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const  topCountries = document.getElementById('top-countries');
        const  topStates = document.getElementById('top-states');

        new Chart(topCountries, {
            type: 'bar',
            data: {
                labels: ["{!! join('", "', $countries['names']) !!}"],
                datasets: [{
                    label: 'Users in top 10 countries',
                    data: [{!! join(', ',$countries['data']) !!}],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


    </script>
</x-app-layout>
