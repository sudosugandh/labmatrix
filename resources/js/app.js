import './bootstrap';
import './form-loading';
import './mobile-menu';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/** Used by flash-toasts Blade; avoids broken HTML when JSON is embedded inside x-data="…". */
window.createFlashToasts = function (items) {
    return {
        items: Array.isArray(items) ? items : [],
        dismiss(id) {
            this.items = this.items.filter((t) => t.id !== id);
        },
        init() {
            this.items.forEach((t) => {
                setTimeout(() => this.dismiss(t.id), 6500);
            });
        },
    };
};

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
