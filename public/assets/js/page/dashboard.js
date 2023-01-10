if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$(function() {
    "use strict";
    
    // LUNO Revenue
    var options = {
        chart: {
            height: 260,
            type: 'line',
            toolbar: {
                show: false,
            },
        },
        colors: ['var(--chart-color1)', 'var(--chart-color5)'],
        series: [{
            name: 'Income',
            type: 'line',
            data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
        }, {
            name: 'Expenses',
            type: 'line',
            data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
        }],
        stroke: {
            width: [2, 2]
        },        
        // labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
        xaxis: {
            type: 'datetime'
        },
        yaxis: [{
            title: {
                text: 'Income',
            },

        }, {
            opposite: true,
            title: {
                text: 'Expenses'
            }
        }]
    }
    var chart = new ApexCharts(document.querySelector("#apex-AudienceOverview"),options); chart.render();

    // Sales by Category
    var options = {
        chart: {
            height: 280,
            type: 'donut',
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            show: true,
        },
        colors: ['var(--chart-color1)', 'var(--chart-color2)', 'var(--chart-color3)'],
        series: [55, 35, 10],
    }
    var chart = new ApexCharts(document.querySelector("#apex-SalesbyCategory"),options); chart.render();
});
