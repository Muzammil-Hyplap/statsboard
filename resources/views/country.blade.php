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
                    <h2>State Wise User Distribution</h2>

                    <label for="countrySelect">Select Country:</label>
                    <select class="text-white bg-gray-800 border border-gray-700 rounded-lg" id="countrySelect">
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ $defaultCountry->id == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Bar Chart -->
                    <h3>Users Per State (Bar Chart)</h3>
                    <canvas id="userBarChart" width="400" height="200"></canvas>

                    <!-- Pie Chart -->
                    <h3>Users Per State (Pie Chart)</h3>
                    <canvas id="userPieChart" width="400" height="200"></canvas>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let userBarChart, userPieChart;

        function renderCharts(labels, data) {
            const barCtx = document.getElementById('userBarChart').getContext('2d');
            const pieCtx = document.getElementById('userPieChart').getContext('2d');

            // Destroy previous charts if they exist
            if (userBarChart) userBarChart.destroy();
            if (userPieChart) userPieChart.destroy();

            // Bar Chart
            userBarChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Users per State',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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

            // Pie Chart
            userPieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        function fetchData(countryId) {
            $.ajax({
                url: '{{ route("fetchStateData") }}',
                type: 'GET',
                data: { country_id: countryId },
                success: function(response) {
                    let labels = response.map(item => item.name);
                    let data = response.map(item => item.users_count);
                    renderCharts(labels, data);
                }
            });
        }

        $(document).ready(function() {
            let defaultCountry = $("#countrySelect").val();
            fetchData(defaultCountry);

            $("#countrySelect").change(function() {
                let selectedCountry = $(this).val();
                fetchData(selectedCountry);
            });
        });
    </script>
</x-app-layout>
