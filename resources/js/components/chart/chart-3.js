export const initChartThree = () => {
    const chartElement = document.querySelector('#chartThree');

    if (chartElement) {
        // Tangkap data dari Blade. Jika kosong, pakai data dummy berskala ribuan
        const cultivateData = chartElement.dataset.cultivate ? JSON.parse(chartElement.dataset.cultivate) : [1800, 1900, 1700, 1600, 1750, 1650, 1700, 2050, 2300, 2100, 2400, 2350];
        const harvestData = chartElement.dataset.harvest ? JSON.parse(chartElement.dataset.harvest) : [400, 300, 500, 400, 550, 400, 700, 1000, 1100, 1200, 1500, 1400];
        const categoriesData = chartElement.dataset.categories ? JSON.parse(chartElement.dataset.categories) : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        const chartThreeOptions = {
            series: [
                {
                    name: "Penanaman",
                    data: cultivateData,
                },
                {
                    name: "Panen",
                    data: harvestData,
                },
            ],
            legend: {
                show: false,
                position: "top",
                horizontalAlign: "left",
            },
            colors: ["#465FFF", "#9CB9FF"], // Mempertahankan warna biru terang & pudar bawaan Anda
            chart: {
                fontFamily: "Outfit, sans-serif",
                height: 310,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            fill: {
                gradient: {
                    enabled: true,
                    opacityFrom: 0.55,
                    opacityTo: 0,
                },
            },
            stroke: {
                curve: "straight",
                width: ["2", "2"],
            },
            markers: {
                size: 0,
            },
            labels: {
                show: false,
                position: "top",
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
            },
            dataLabels: {
                enabled: false,
            },
            tooltip: {
                y: {
                    // Menambahkan pemisah ribuan pada tooltip
                    formatter: function (val) {
                        return val.toLocaleString();
                    }
                }
            },
            xaxis: {
                type: "category",
                categories: categoriesData, // Kategori bulan terisi otomatis dari Blade
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                tooltip: {
                    enabled: false // Sengaja dimatikan karena sudah digantikan format tooltip Y
                },
            },
            yaxis: {
                title: {
                    style: {
                        fontSize: "0px",
                    },
                },
            },
        };

        const chart = new ApexCharts(chartElement, chartThreeOptions);
        chart.render();
        return chart;
    }
}

export default initChartThree;