<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Country') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3>Users Per State for <span id="countryName">{{ $defaultCountry->name }}</span></h3>
                <input type="hidden" id="defaultCountryId" value="{{ $defaultCountry->id }}">
                    <canvas id="stateChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let stateChart;

            function fetchChartData(countryId) {
                $.ajax({
                    url: "/users-by-state",
                    type: "GET",
                    data: {
                        country_id: countryId
                    },
                    success: function(response) {
                        const labels = response.map(item => item.state.name);
                        const data = response.map(item => item.user_count);

                        if (stateChart) {
                            stateChart.destroy();
                        }

                        const ctx = document.getElementById("stateChart").getContext("2d");
                        stateChart = new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: "Users per State",
                                    data: data,
                                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                                    borderColor: "rgba(54, 162, 235, 1)",
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }


                        });
                    }
                });
            }
            let defaultCountryId = $("#defaultCountryId").val();
            fetchChartData(defaultCountryId);
        });
    </script>
</x-app-layout>