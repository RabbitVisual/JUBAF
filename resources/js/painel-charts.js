import ApexCharts from 'apexcharts';
import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

function initDiretoriaSparklines() {
    const payload = window.painelDiretoriaDashboardData || {};
    const sparkline = Array.isArray(payload.sparkline) ? payload.sparkline : [];
    if (sparkline.length === 0) {
        return;
    }

    const inSeries = sparkline.map((entry) => Number(entry.in || 0));
    const outSeries = sparkline.map((entry) => Number(entry.out || 0));

    const charts = [
        { selector: '[data-kpi-chart="finance-in"]', series: inSeries, color: '#059669' },
        { selector: '[data-kpi-chart="finance-out"]', series: outSeries, color: '#e11d48' },
    ];

    charts.forEach((chartConfig) => {
        const element = document.querySelector(chartConfig.selector);
        if (!element) {
            return;
        }

        const chart = new ApexCharts(element, {
            chart: {
                type: 'area',
                sparkline: { enabled: true },
                height: 48,
                toolbar: { show: false },
            },
            stroke: { curve: 'smooth', width: 2 },
            fill: { opacity: 0.18 },
            colors: [chartConfig.color],
            series: [{ data: chartConfig.series }],
            tooltip: { enabled: false },
        });
        chart.render();
    });
}

function initTables() {
    document.querySelectorAll('[data-ui-datatable]').forEach((tableElement) => {
        if (tableElement.dataset.dtInit === '1') {
            return;
        }

        new DataTable(tableElement, {
            paging: true,
            searching: true,
            info: false,
            pageLength: 8,
            lengthChange: false,
            order: [],
            language: {
                search: 'Pesquisar:',
                paginate: {
                    previous: 'Anterior',
                    next: 'Proximo',
                },
                zeroRecords: 'Nenhum resultado encontrado',
            },
        });

        tableElement.dataset.dtInit = '1';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initDiretoriaSparklines();
    initTables();
});
