import './bootstrap';
import './form-loading';
import './mobile-menu';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const saGrowthCanvas = document.getElementById('saPlatformGrowthChart');
const saGrowthConfigEl = document.getElementById('saPlatformGrowthChartConfig');
if (saGrowthCanvas && saGrowthConfigEl) {
    try {
        const chartConfig = JSON.parse(saGrowthConfigEl.textContent);
        import('chart.js/auto').then(({ default: Chart }) => {
            new Chart(saGrowthCanvas, chartConfig);
        });
    } catch (e) {
        console.error('Super admin chart failed to load', e);
    }
}
