export const initChartOne = () => {
    const chartElement = document.querySelector('#chartOne');
    if (!chartElement) return;

    // Ambil dan parse data dari HTML, berikan nilai default jika kosong
    const seriesData = chartElement.dataset.series ? JSON.parse(chartElement.dataset.series) : [];
    const categoriesData = chartElement.dataset.categories ? JSON.parse(chartElement.dataset.categories) : [];

    const chartOneOptions = {
        series: [{
            name: "Penanaman",
            data: seriesData, // Masukkan data dinamis di sini
        }],
        colors: ["#465fff"],
        chart: {
            fontFamily: "Outfit, sans-serif",
            type: "bar",
            height: 180,
            toolbar: { show: false },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "39%",
                borderRadius: 5,
                borderRadiusApplication: "end",
            },
        },
        dataLabels: { enabled: false },
        stroke: {
            show: true,
            width: 4,
            colors: ["transparent"],
        },
        xaxis: {
            categories: categoriesData, // Masukkan kategori dinamis di sini
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        legend: {
            show: true,
            position: "top",
            horizontalAlign: "left",
            fontFamily: "Outfit",
            markers: { radius: 99 },
        },
        yaxis: { title: false },
        grid: {
            yaxis: { lines: { show: true } },
        },
        fill: { opacity: 1 },
        tooltip: {
            x: { show: false },
            y: {
                formatter: function (val) {
                    return val;
                },
            },
        },
    };

    const chart = new ApexCharts(chartElement, chartOneOptions);
    chart.render();

    return chart;
};

export default initChartOne;