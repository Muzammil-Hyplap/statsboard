import './bootstrap';

import Alpine from 'alpinejs';

import Chart from 'chart.js/auto'
import * as ChartGeo from 'chartjs-chart-geo'

// Register the controller
Chart.register(ChartGeo.ChoroplethController);
Chart.register(ChartGeo.ProjectionScale);
Chart.register(ChartGeo.ColorScale);
Chart.register(ChartGeo.GeoFeature);


window.Chart = Chart
window.ChartGeo = ChartGeo

window.Alpine = Alpine;

Alpine.start();
