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

                    <!-- Country Selection -->
                    <label for="countrySelect">Select Country:</label>
                    <select class="text-white bg-gray-800 border border-gray-700 rounded-lg" id="countrySelect">
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ $defaultCountry->id == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- State Selection -->
                    <label for="stateSelect">Select State:</label>
                    <select class="text-white bg-gray-800 border border-gray-700 rounded-lg" id="stateSelect">
                        @foreach($userData as $state)
                        <option value="{{ $state->id }}" {{ $defaultState->id == $state->id ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                        @endforeach
                    </select>

                    <h3>Users Per State</h3>
                    <canvas id="userLineChart" width="400" height="200"></canvas>

                    <div class="flex flex-col md:flex-row gap-5 items-end">

                        

                        <div class="w-full md:w-1/2 min-h-lg">
                            <h3>Male vs Female Users</h3>
                            <canvas id="genderBarChart" width="400" height="200"></canvas>
                        </div>

                        <div class="w-full md:w-1/2 min-h-lg">
                            <h3>Users Per City</h3>
                            <canvas id="cityPieChart" width="400" height="200"></canvas>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="module">
        let userLineChart, cityPieChart;

        function renderCharts(labels, data) {
            const lineCtx = document.getElementById('userLineChart').getContext('2d');

            if (userLineChart) userLineChart.destroy();

            userLineChart = new Chart(lineCtx, {
                type: 'line',
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
                    responsive: true
                }
            });
        }

        function renderCityChart(labels, data) {
            const cityCtx = document.getElementById('cityPieChart').getContext('2d');

            if (cityPieChart) cityPieChart.destroy();

            cityPieChart = new Chart(cityCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(153, 102, 255, 0.6)'
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
                data: {
                    country_id: countryId
                },
                success: function(response) {
                    let labels = response.map(item => item.name);
                    let data = response.map(item => item.users_count);
                    renderCharts(labels, data);

                    $("#stateSelect").html("");
                    response.forEach(state => {
                        $("#stateSelect").append(new Option(state.name, state.id));
                    });

                    let firstState = $("#stateSelect").val();
                    fetchCityData(firstState);
                }
            });
        }

        function fetchCityData(stateId) {
            $.ajax({
                url: '{{ route("fetchCityData") }}',
                type: 'GET',
                data: {
                    state_id: stateId
                },
                success: function(response) {
                    let labels = response.map(item => item.name);
                    let data = response.map(item => item.users_count);
                    renderCityChart(labels, data);
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

            $("#stateSelect").change(function() {
                let selectedState = $(this).val();
                fetchCityData(selectedState);
            });
        });
    </script>

    <script>
        let genderBarChart;

        function renderGenderChart(maleCount, femaleCount) {
            const genderCtx = document.getElementById('genderBarChart').getContext('2d');

            if (genderBarChart) genderBarChart.destroy();

            genderBarChart = new Chart(genderCtx, {
                type: 'bar',
                data: {
                    labels: ["Male", "Female"],
                    datasets: [{
                        label: 'Users by Gender in a state',
                        data: [maleCount, femaleCount],
                        backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                        borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
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
                    responsive: true
                }
            });
        }

        function fetchGenderData(stateId) {
            $.ajax({
                url: '{{ route("fetchGenderData") }}',
                type: 'GET',
                data: {
                    state_id: stateId
                },
                success: function(response) {
                    console.log(response);
                    renderGenderChart(response.male_count, response.female_count);
                }
            });
        }

        $(document).ready(function() {
            let defaultState = $("#stateSelect").val();
            fetchGenderData(defaultState);

            $("#stateSelect").change(function() {
                let selectedState = $(this).val();
                console.log(selectedState);
                fetchGenderData(selectedState);
            });
        });
    </script>

</x-app-layout>