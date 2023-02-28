/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: nft Dashboard init js
*/

// get colors array from the string
function getChartColorsArray(chartId) {
    if (document.getElementById(chartId) !== null) {
        var colors = document.getElementById(chartId).getAttribute("data-colors");
        if (colors) {
            colors = JSON.parse(colors);
            return colors.map(function (value) {
                var newValue = value.replace(" ", "");
                if (newValue.indexOf(",") === -1) {
                    var color = getComputedStyle(document.documentElement).getPropertyValue(
                        newValue
                    );
                    if (color) return color;
                    else return newValue;
                } else {
                    var val = value.split(",");
                    if (val.length == 2) {
                        var rgbaColor = getComputedStyle(
                            document.documentElement
                        ).getPropertyValue(val[0]);
                        rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                        return rgbaColor;
                    } else {
                        return newValue;
                    }
                }
            });
        } else {
            console.warn('data-colors atributes not found on', chartId);
        }
    }
}

// Chart-1
var areachartmini1Colors = getChartColorsArray("mini-chart-1");
if (areachartmini1Colors) {
    var options1 = {
        series: [{
            data: [25, 66, 41, 89, 63, 25, 44, 12]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini1Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-1"), options1);
    chart1.render();

}

// Chart-2
var areachartmini2Colors = getChartColorsArray("mini-chart-2");
if (areachartmini2Colors) {
    var options1 = {
        series: [{
            data: [50, 15, 35, 62, 23, 56, 44, 12]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini2Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-2"), options1);
    chart1.render();

}

// Chart-3
var areachartmini3Colors = getChartColorsArray("mini-chart-3");
if (areachartmini3Colors) {
    var options1 = {
        series: [{
            data: [25, 35, 35, 89, 63, 25, 44, 12]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini3Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-3"), options1);
    chart1.render();
}

// Chart-4
var areachartmini4Colors = getChartColorsArray("mini-chart-4");
if (areachartmini4Colors) {
    var options1 = {
        series: [{
            data: [50, 15, 20, 34, 23, 56, 65, 41]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini4Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-4"), options1);
    chart1.render();
}

// Chart-5
var areachartmini5Colors = getChartColorsArray("mini-chart-5");
if (areachartmini5Colors) {
    var options1 = {
        series: [{
            data: [45, 53, 24, 89, 63, 60, 36, 50]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini5Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-5"), options1);
    chart1.render();
}

// Chart-6
var areachartmini6Colors = getChartColorsArray("mini-chart-6");
if (areachartmini6Colors) {
    var options1 = {
        series: [{
            data: [50, 15, 35, 62, 23, 56, 44, 12]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini6Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-6"), options1);
    chart1.render();
}

// Chart-7
var areachartmini7Colors = getChartColorsArray("mini-chart-7");
if (areachartmini7Colors) {
    var options1 = {
        series: [{
            data: [50, 15, 20, 34, 23, 56, 65, 41]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini7Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-7"), options1);
    chart1.render();
}

// Chart-5
var areachartmini8Colors = getChartColorsArray("mini-chart-8");
if (areachartmini8Colors) {
    var options1 = {
        series: [{
            data: [45, 53, 24, 89, 63, 60, 36, 50]
        }],
        chart: {
            type: 'line',
            width: 80,
            height: 30,
            sparkline: {
                enabled: true
            }

        },
        colors: areachartmini8Colors,
        stroke: {
            curve: 'smooth',
            width: 2.3,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    var chart1 = new ApexCharts(document.querySelector("#mini-chart-8"), options1);
    chart1.render();
}


// Deal Type Charts
var dealTypeChartsColors = getChartColorsArray("deal-type-charts");
if (dealTypeChartsColors) {
    var options = {
        series: [{
            name: 'Ethereum',
            data: [80, 50, 30, 40, 100, 20],
        },
        {
            name: 'Artwork Sold',
            data: [20, 30, 40, 80, 20, 80],
        },
        {
            name: 'Cancelation',
            data: [44, 76, 78, 13, 43, 10],
        }
        ],
        chart: {
            height: 270,
            type: 'radar',
            dropShadow: {
                enabled: true,
                blur: 1,
                left: 1,
                top: 1
            },
            toolbar: {
                show: false
            },
        },
        stroke: {
            width: 2
        },
        fill: {
            opacity: 0.2
        },
        legend: {
            show: false,
            fontWeight: 500,
            offsetX: 0,
            offsetY: -8,
            markers: {
                width: 8,
                height: 8,
                radius: 6,
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0
            }
        },
        markers: {
            size: 0
        },
        colors: dealTypeChartsColors,
        xaxis: {
            categories: ['2016', '2017', '2018', '2019', '2020', '2021']
        }
    };
    var chart = new ApexCharts(document.querySelector("#deal-type-charts"), options);
    chart.render();
}

// Featured NFTs Artworks Slider
var swiper = new Swiper(".marketplace-swiper", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 10,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 24,
        },
        1445: {
            slidesPerView: 3,
            spaceBetween: 24,
        },
    },
});

// collection-slider
var swiper = new Swiper(".collection-slider", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 10,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
});

//Popularity chart
var barchartColors = getChartColorsArray("market-overview");
if (barchartColors) {
    var options = {
        series: [{
            name: 'Like',
            data: [12.45, 16.2, 8.9, 11.42, 12.6, 18.1, 18.2, 14.16]
        }, {
            name: 'Share',
            data: [-11.45, -15.42, -7.9, -12.42, -12.6, -18.1, -18.2, -14.16]
        }],
        chart: {
            type: 'bar',
            height: 260,
            stacked: true,
            toolbar: {
                show: false
            },
        },
        stroke: {
            colors: "#000"
        },
        plotOptions: {
            bar: {
                columnWidth: '20%',
                borderRadius: [4, 4]
            },
        },
        colors: barchartColors,
        fill: {
            opacity: 1
        },
        dataLabels: {
            enabled: false,
            textAnchor: 'top',
        },  
        yaxis: {
            labels: {
                show: false,
                formatter: function (y) {
                    return y.toFixed(0) + "%";
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            labels: {
                rotate: -90
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#market-overview"), options);
    chart.render();
}


//  Basic Line Charts
var linechartBasicColors = getChartColorsArray("line_chart_basic");
if (linechartBasicColors) {
    var options = {
        series: [{
            name: "Artwork",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        },
        {
            name: "Auction",
            data: [40, 120, 83, 45, 31, 74, 35, 34, 78]
        },
        {
            name: "Creators",
            data: [95, 35, 20, 130, 64, 22, 43, 45, 31]
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: linechartBasicColors,
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        }
    };

    var chart = new ApexCharts(document.querySelector("#line_chart_basic"), options);
    chart.render();
}



//creators-by-locations world map with markers
var vectorMapWorldMarkersColors = getChartColorsArray("creators-by-locations");
if (vectorMapWorldMarkersColors) {
    var worldemapmarkers = new jsVectorMap({
        map: "world_merc",
        selector: "#creators-by-locations",
        zoomOnScroll: false,
        zoomButtons: false,
        selectedMarkers: [0, 5],
        regionStyle: {
            initial: {
                stroke: "#9599ad",
                strokeWidth: 0.25,
                fill: vectorMapWorldMarkersColors[0],
                fillOpacity: 1,
            },
        },
        markersSelectable: true,
        markers: [{
                name: "United States",
                coords: [37.0902, 95.7129],
                style: {
                    image: "assets/images/flags/us.svg",
                }
            },
            {
                name: "Russia",
                coords: [61.524, 105.3188],
                style: {
                    image: "assets/images/flags/russia.svg",
                }
            },
            {
                name: "Spain",
                coords: [40.4637, 3.7492],
                style: {
                    image: "assets/images/flags/spain.svg",
                }
            },
            {
                name: "Italy",
                coords: [41.8719, 12.5674],
                style: {
                    image: "assets/images/flags/italy.svg",
                }
            },
            {
                name: "Germany",
                coords: [51.1657, 10.4515],
                style: {
                    image: "assets/images/flags/germany.svg",
                }
            },
        ],
        markerStyle: {
            initial: {
                
                fill: vectorMapWorldMarkersColors[1],
            },
            selected: {
                fill: vectorMapWorldMarkersColors[2],
            },
        },
        labels: {
            markers: {
                render: function (marker) {
                    return marker.name;
                },
            },
        },
    });
}