/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: job Dashboard init js
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

//  Dashed line chart
var linechartDashedColors = getChartColorsArray("line_chart_dashed");
if (linechartDashedColors) {
    var options = {
        chart: {
            height: 345,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false,
            }
        },
        colors: linechartDashedColors,
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: [3, 4, 3],
            curve: 'straight',
            dashArray: [0, 8, 5]
        },
        series: [{
            name: 'New Application',
            data: [89, 56, 74, 98, 72, 38, 64, 46, 84, 58, 46, 49]
        },
        {
            name: "Interview",
            data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
        },
        {
            name: " Hired",
            data: [36, 42, 60, 42, 13, 18, 29, 37, 36, 51, 32, 35]
        }
        ],
        markers: {
            size: 0,

            hover: {
                sizeOffset: 6
            }
        },
        xaxis: {
            categories: ['01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan', '09 Jan',
                '10 Jan', '11 Jan', '12 Jan'
            ],
        },
        grid: {
            borderColor: '#f1f1f1',
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#line_chart_dashed"),
        options
    );

    chart.render();
}

// Simple Donut Charts
var chartDonutBasicColors = getChartColorsArray("store-visits-source");
if (chartDonutBasicColors) {
    var options = {
        series: [44, 55, 41, 17, 15],
        labels: ["Direct", "Social", "Email", "Other", "Referrals"],
        chart: {
            height: 333,
            type: "donut",
        },
        legend: {
            position: "bottom",
        },
        stroke: {
            show: false
        },
        dataLabels: {
            dropShadow: {
                enabled: false,
            },
        },
        colors: chartDonutBasicColors,
    };

    var chart = new ApexCharts(
        document.querySelector("#store-visits-source"),
        options
    );
    chart.render();
}

// world map with markers
var vectorMapWorldMarkersColors = getChartColorsArray("sales-by-locations");
if (vectorMapWorldMarkersColors) {
    var worldemapmarkers = new jsVectorMap({
        map: "world_merc",
        selector: "#sales-by-locations",
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
            name: "Palestine",
            coords: [31.9474, 35.2272],
        },
        {
            name: "Russia",
            coords: [61.524, 105.3188],
        },
        {
            name: "Canada",
            coords: [56.1304, -106.3468],
        },
        {
            name: "Greenland",
            coords: [71.7069, -42.6043],
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

var jobListAllData = [
    ["Marketing Director", "Meta4Systems", "Vinninga, Sweden", "$250 - $800", "0-5 year", "Full Time"],
    ["UI/UX designer", "Zoetic Fashion", "Cullera, Spain", "$400+", "0-2 year", "Part Time"],
    ["Web Designer", "Force Medicines", "Ugashik, US", "$412 - $241 ", "3+ year", "Freelancer"],
    ["Full Stack Engineer", "Syntyce Solutions", "Zuweihir, UAE", "$650 - $900", "0-1+ year", "Full Time"],
    ["Assistant / Store Keeper", "Moetic Fashion", "Limestone, US", "$340 - $800", "0-3 year", "Intership"],
    ["Project Manager", "Themesbrand", "California, US", "$400 - $600", "3+ year", "Part Time"],
    ["Education Training", "Micro Design", "Germany", "$750 - $940", "1.5+ year", "Freelancer"],
    ["Graphic Designer", "Digitech Galaxy", "Mughairah, UAE", "$160 - $230", "2-3+ year", "Full Time"],
    ["React Developer", "iTest Factory", "Khabākhib, UAE", "$90 - $160", "5+ year", "Intership"],
    ["Executive, HR Operations", "Micro Design", "Texanna, US", "$50 - $120", "1-5 year", "Part Time"],
    ["Project Manager", "Meta4Systems", "Limestone, US", "$210 - $300", "0-2+ year", "Freelancer"],
    ["Full Stack Engineer", "Force Medicines", "Ugashik, US", "$120 - $180", "2-5 year", "Part Time"],
    ["Full Stack Engineer", "Digitech Galaxy", "Maidaq, UAE", "$900 - $1020", "3-5 year", "Full Time"],
    ["Marketing Director", "Zoetic Fashion", "Quesada, US", "$600 - $870", "0-5 year", "Freelancer"],
];

// recomended-jobs
if (document.getElementById("recomended-jobs")){
    var jobListAll = new gridjs.Grid({
        columns: [{
            name: 'Position',
            width: '150px',
        }, {
            name: 'Company Name',
            width: '250px',
        }, {
            name: 'Location',
            width: '250px',
        }, {
            name: 'Salary',
            width: '250px',
        }, {
            name: 'Experience',
            width: '150px',
        },{
            name: 'Job Type',
            width: '150px',
        }],
        sort: true,
        // search: true,
        pagination: {
            limit: 6
        },
        data: jobListAllData,
    }).render(document.getElementById("recomended-jobs"));

    // Search product list
    var searchResultList = document.getElementById("searchResultList");
    searchResultList.addEventListener("keyup", function () {
        var inputVal = searchResultList.value.toLowerCase();
        function filterItems(arr, query) {
            return arr.filter(function (el) {
                return el[0].toLowerCase().indexOf(query.toLowerCase()) !== -1 || el[1].toLowerCase().indexOf(query.toLowerCase()) !== -1
            })
        }

        var filterData = filterItems(jobListAllData, inputVal);
        
        jobListAll.updateConfig({
            data: filterData
        }).forceRender();
    });
}


// candidate-list

Array.from(document.querySelectorAll("#candidate-list li")).forEach(function (item) {
    item.querySelector("a").addEventListener("click", function () {
        var candidateName = item.querySelector(".candidate-name").innerHTML;
        var candidatePosition = item.querySelector(".candidate-position").innerHTML;
        var candidateImg = item.querySelector(".candidate-img").src

        document.getElementById("candidate-name").innerHTML = candidateName;
        document.getElementById("candidate-position").innerHTML = candidatePosition;
        document.getElementById("candidate-img").src = candidateImg;
    })
});


window.addEventListener("load", () => {
    var searchInput = document.getElementById("searchList"), // search box
        candidateList = document.querySelectorAll("#candidate-list li"); // all list items

    searchInput.onkeyup = () => {
        let search = searchInput.value.toLowerCase();

        for (let i of candidateList) {
            let item = i.querySelector(".candidate-name").innerHTML.toLowerCase();
            if (item.indexOf(search) == -1) { i.classList.add("d-none"); }
            else { i.classList.remove("d-none"); }
        }
    };
});

//  total jobs Charts
var chartRadialbarBasicColors = getChartColorsArray("total_jobs");
if (chartRadialbarBasicColors) {
    var options = {
        series: [95],
        chart: {
            type: 'radialBar',
            width: 105,
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: '70%'
                },
                track: {
                    margin: 1
                },
                dataLabels: {
                    show: true,
                    name: {
                        show: false
                    },
                    value: {
                        show: true,
                        fontSize: '16px',
                        fontWeight: 600,
                        offsetY: 8,
                    }
                }
            }
        },
        colors: chartRadialbarBasicColors
    };

    var chart = new ApexCharts(document.querySelector("#total_jobs"), options);
    chart.render();
}


//  apply jobs Charts
var chartRadialbarBasicColors = getChartColorsArray("apply_jobs");
if (chartRadialbarBasicColors) {
    var options = {
        series: [97],
        chart: {
            type: 'radialBar',
            width: 105,
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: '70%'
                },
                track: {
                    margin: 1
                },
                dataLabels: {
                    show: true,
                    name: {
                        show: false
                    },
                    value: {
                        show: true,
                        fontSize: '16px',
                        fontWeight: 600,
                        offsetY: 8,
                    }
                }
            }
        },
        colors: chartRadialbarBasicColors
    };

    var chart = new ApexCharts(document.querySelector("#apply_jobs"), options);
    chart.render();
}

//  interview_chart
var chartRadialbarBasicColors = getChartColorsArray("interview_chart");
if (chartRadialbarBasicColors) {
    var options = {
        series: [89],
        chart: {
            type: 'radialBar',
            width: 105,
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: '70%'
                },
                track: {
                    margin: 1
                },
                dataLabels: {
                    show: true,
                    name: {
                        show: false
                    },
                    value: {
                        show: true,
                        fontSize: '16px',
                        fontWeight: 600,
                        offsetY: 8,
                    }
                }
            },
        },
        colors: chartRadialbarBasicColors
    };

    var chart = new ApexCharts(document.querySelector("#interview_chart"), options);
    chart.render();
}


//  Hired Chart
var chartRadialbarBasicColors = getChartColorsArray("hired_chart");
if (chartRadialbarBasicColors) {
    var options = {
        series: [64],
        chart: {
            type: 'radialBar',
            width: 105,
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: '70%'
                },
                track: {
                    margin: 1
                },
                dataLabels: {
                    show: true,
                    name: {
                        show: false
                    },
                    value: {
                        show: true,
                        fontSize: '16px',
                        fontWeight: 600,
                        offsetY: 8,
                    }
                }
            }
        },
        colors: chartRadialbarBasicColors
    };

    var chart = new ApexCharts(document.querySelector("#hired_chart"), options);
    chart.render();
}

//  Rejected Chart
var chartRadialbarBasicColors = getChartColorsArray("rejected_chart");
if (chartRadialbarBasicColors) {
    var options = {
        series: [20],
        chart: {
            type: 'radialBar',
            width: 105,
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: '70%'
                },
                track: {
                    margin: 1
                },
                dataLabels: {
                    show: true,
                    name: {
                        show: false
                    },
                    value: {
                        show: true,
                        fontSize: '16px',
                        fontWeight: 600,
                        offsetY: 8,
                    }
                }
            }
        },
        colors: chartRadialbarBasicColors
    };

    var chart = new ApexCharts(document.querySelector("#rejected_chart"), options);
    chart.render();
}

//  New jobs Chart
var chartRadialbarBasicColors = getChartColorsArray("new_jobs_chart");
if (chartRadialbarBasicColors) {
    var options = {
        series: [80],
        chart: {
            type: 'radialBar',
            width: 105,
            sparkline: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: '70%'
                },
                track: {
                    margin: 1
                },
                dataLabels: {
                    show: true,
                    name: {
                        show: false
                    },
                    value: {
                        show: true,
                        fontSize: '16px',
                        fontWeight: 600,
                        offsetY: 8,
                    }
                }
            }
        },
        colors: chartRadialbarBasicColors
    };

    var chart = new ApexCharts(document.querySelector("#new_jobs_chart"), options);
    chart.render();
}   