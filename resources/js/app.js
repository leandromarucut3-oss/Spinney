import './bootstrap';

import Alpine from 'alpinejs';
import {
	Chart,
	LineController,
	LineElement,
	PointElement,
	LinearScale,
	CategoryScale,
	Filler,
	Tooltip,
	Legend
} from 'chart.js';

window.Alpine = Alpine;

Chart.register(
	LineController,
	LineElement,
	PointElement,
	LinearScale,
	CategoryScale,
	Filler,
	Tooltip,
	Legend
);

window.Chart = Chart;

Alpine.start();
