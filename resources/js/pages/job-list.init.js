/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: job-list init js
*/

// get colors array from the string
function getChartColorsArray(chartId) {
    if (document.getElementById(chartId) !== null) {
        var colors = document.getElementById(chartId).getAttribute("data-colors");
        colors = JSON.parse(colors);
        return colors.map(function (value) {
            var newValue = value.replace(" ", "");
            if (newValue.indexOf(",") === -1) {
                var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                if (color) return color;
                else return newValue;;
            } else {
                var val = value.split(',');
                if (val.length == 2) {
                    var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                    rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                    return rgbaColor;
                } else {
                    return newValue;
                }
            }
        });
    }
}


// Simple Donut Charts
var chartDonutBasicColors = getChartColorsArray("simple_dount_chart");
if (chartDonutBasicColors) {
    var options = {
        series: [98, 63, 35],
        labels: ["New Application", "Approved", "Rejected"],
        chart: {
            height: 300,
            type: 'donut',
        },
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            dropShadow: {
                enabled: false,
            }
        },
        colors: chartDonutBasicColors
    };

    var chart = new ApexCharts(document.querySelector("#simple_dount_chart"), options);
    chart.render();
}

var url = "assets/json/";
var allJobList = '';
var editList = false;

var prevButton = document.getElementById('page-prev');
var nextButton = document.getElementById('page-next');

// configuration variables
var currentPage = 1;
var itemsPerPage = 3;

var getJSON = function (jsonurl, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url + jsonurl, true);
    xhr.responseType = "json";
    xhr.onload = function () {
        var status = xhr.status;
        if (status === 200) {
            callback(null, xhr.response);
        } else {
            callback(status, xhr.response);
        }
    };
    xhr.send();
};

// get json
getJSON("job-list.json", function (err, data) {
    if (err !== null) {
        console.log("Something went wrong: " + err);
    } else {
        allJobList = data;
        loadJobListData(allJobList, currentPage);
        paginationEvents();
        sortElementsById();
    }
});

function loadJobListData(datas, page) {
    var pages = Math.ceil(datas.length / itemsPerPage)
    if (page < 1) page = 1
    if (page > pages) page = pages
    document.querySelector("#job-list").innerHTML = '';

    for (var i = (page - 1) * itemsPerPage; i < (page * itemsPerPage) && i < datas.length; i++) {
        // Array.from(datas).forEach(function (listData, index){
        if(datas[i] && datas[i].tags){
            var tags = datas[i].tags;
            var tagHtml = '';
            Array.from(tags).forEach((tag, index) => {
                tagHtml += '<span class="badge badge-soft-primary me-1">' + tag + '</span>'
            })
        }
        if (datas[i]) {
        document.querySelector("#job-list").innerHTML += '<div class="card joblist-card">\
            <div class="card-body">\
                <div class="d-flex mb-4">\
                    <div class="avatar-sm">\
                        <div class="avatar-title bg-light rounded">\
                            <img src="'+ datas[i].companyLogo + '" alt="" class="avatar-xxs companyLogo-img">\
                        </div>\
                    </div>\
                    <div class="ms-3 flex-grow-1">\
                        <img src="'+ datas[i].coverImg + '" alt="" class="d-none cover-img">\
                        <a href="#!"><h5 class="job-title">'+ datas[i].jobTitle + '</h5></a>\
                        <p class="company-name text-muted mb-0">'+ datas[i].companyName + '</p>\
                    </div>\
                    <div>\
                        <button type="button" class="btn btn-ghost-primary btn-icon custom-toggle" data-bs-toggle="button">\
                            <span class="icon-on"><i class="ri-bookmark-line"></i></span>\
                            <span class="icon-off"><i class="ri-bookmark-fill"></i></span>\
                        </button>\
                    </div>\
                </div>\
                <p class="text-muted job-description">'+ datas[i].description + '</p>\
                <div>'+ tagHtml + '</div>\
            </div>\
            <div class="card-footer border-top-dashed">\
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">\
                    <div><i class="ri-briefcase-2-line align-bottom me-1"></i> <span class="job-type">'+ datas[i].type + '</span></div>\
                    <div class="d-none"><span class="job-experience">'+ datas[i].experience + '</span></div>\
                    <div><i class="ri-map-pin-2-line align-bottom me-1"></i>  <span class="job-location">'+ datas[i].location + '</span></div>\
                    <div><i class="ri-user-3-line align-bottom me-1"></i> '+ datas[i].applied + '</div>\
                    <div><i class="ri-time-line align-bottom me-1"></i> <span class="job-postdate">'+ datas[i].postDate + '</span></div>\
                    <div><a href="#!" class="btn btn-primary viewjob-list">View More <i class="ri-arrow-right-line align-bottom ms-1"></i></a></div>\
                </div>\
            </div>\
        </div>';
        }
        // });
    }
    
    document.getElementById("total-result").innerHTML = datas.length
    selectedPage();
    currentPage == 1 ? prevButton.parentNode.classList.add('disabled') : prevButton.parentNode.classList.remove('disabled');
    currentPage == pages ? nextButton.parentNode.classList.add('disabled') : nextButton.parentNode.classList.remove('disabled');
    jobDetailShow();
}

function selectedPage() {
    var pagenumLink = document.getElementById('page-num').getElementsByClassName('clickPageNumber');
    for (var i = 0; i < pagenumLink.length; i++) {
        if (i == currentPage - 1) {
            pagenumLink[i].parentNode.classList.add("active");
        } else {
            pagenumLink[i].parentNode.classList.remove("active");
        }
    }
};


// paginationEvents
function paginationEvents() {
    var numPages = function numPages() {
        return Math.ceil(allJobList.length / itemsPerPage);
    };

    function clickPage() {
        document.addEventListener('click', function (e) {
            if (e.target.nodeName == "A" && e.target.classList.contains("clickPageNumber")) {
                currentPage = e.target.textContent;
                loadJobListData(allJobList, currentPage);
            }
        });
    };

    function pageNumbers() {
        var pageNumber = document.getElementById('page-num');
        pageNumber.innerHTML = "";
        // for each page
        for (var i = 1; i < numPages() + 1; i++) {
            pageNumber.innerHTML += "<div class='page-item'><a class='page-link clickPageNumber' href='javascript:void(0);'>" + i + "</a></div>";
        }
    }

    prevButton.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            loadJobListData(allJobList, currentPage);
        }
    });

    nextButton.addEventListener('click', function () {
        if (currentPage < numPages()) {
            currentPage++;
            loadJobListData(allJobList, currentPage);
        }
    });

    pageNumbers();
    clickPage();
    selectedPage();
}

// multiple Remove CancelButton
var tagInputField = new Choices('#taginput-choices', {
    removeItemButton: true,
  }
);

// companylogo image
document.querySelector("#companylogo-image-input").addEventListener("change", function () {
    var preview = document.querySelector("#companylogo-img");
    var file = document.querySelector("#companylogo-image-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load",function () {
        preview.src = reader.result;
    },false);
    if (file) {
        reader.readAsDataURL(file);
    }
});

// cover image
document.querySelector("#cover-image-input").addEventListener("change", function () {
    var preview = document.querySelector("#modal-cover-img");
    var file = document.querySelector("#cover-image-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load",function () {
        preview.src = reader.result;
    },false);
    if (file) {
        reader.readAsDataURL(file);
    }
});

// Form Event
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
    var date = new Date().toUTCString().slice(5, 16);

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    
                    var jobTitleVal = document.getElementById("jobtitle-field").value;
                    var companyNameVal = document.getElementById("companyname-field").value;
                    var companyLogoImg = document.getElementById("companylogo-img").src;
                    var jobCoverImg = document.getElementById("modal-cover-img").src;
                    var jobTypeVal = document.getElementById("job_type-field").value;
                    var jobExperienceVal = document.getElementById("experience-field").value;
                    var locationVal = document.getElementById("location-field").value;
                    var descriptionVal = document.getElementById("description-field").value;
                    var tagInputFieldValue = tagInputField.getValue(true);

                    if (jobTitleVal !== "" && companyNameVal !== "" && jobTypeVal !== "" && locationVal !== "" && locationVal !== "" && !editList) {
                        var newJobId = findNextId();
                        var newJobList = {
                            'id': newJobId,
                            "coverImg": jobCoverImg,
                            "companyLogo": companyLogoImg,
                            "jobTitle": jobTitleVal,
                            "companyName": companyNameVal,
                            "description": descriptionVal,
                            "tags": tagInputFieldValue,
                            "type": jobTypeVal,
                            "experience": jobExperienceVal,
                            "location": locationVal,
                            "applied": "0 Applied",
                            "postDate": date
                        };
                        allJobList.push(newJobList);
                        sortElementsById();
                    }

                    loadJobListData(allJobList, currentPage);
                    document.getElementById("close-jobListModal").click();
                }
                form.classList.add('was-validated');
            }, false);
        })
})()

function fetchIdFromObj(member) {
    return parseInt(member.id);
}

function findNextId() {
    if (allJobList.length === 0) {
        return 0;
    }
    var lastElementId = fetchIdFromObj(allJobList[allJobList.length - 1]),
        firstElementId = fetchIdFromObj(allJobList[0]);
    return (firstElementId >= lastElementId) ? (firstElementId + 1) : (lastElementId + 1);
}

function sortElementsById() {
    var manyJobList = allJobList.sort(function (a, b) {
        var x = fetchIdFromObj(a);
        var y = fetchIdFromObj(b);

        if (x > y) {
            return -1;
        }
        if (x < y) {
            return 1;
        }
        return 0;
    })
    
    loadJobListData(manyJobList, currentPage);
}

// jobDetailShow event
function jobDetailShow() {
    Array.from(document.querySelectorAll("#job-list .joblist-card")).forEach(function (item) {
        item.querySelector(".viewjob-list").addEventListener("click", function () {
            var coverImgVal = item.querySelector(".cover-img").src;
            var companyLogoImgVal = item.querySelector(".companyLogo-img").src;
            var jobTitleVal = item.querySelector(".job-title").innerHTML;
            var companyNameVal = item.querySelector(".company-name").innerHTML;
            var jobDescVal = item.querySelector(".job-description").innerHTML;
            var jobTypeVal = item.querySelector(".job-type").innerHTML;
            var jobLocationVal = item.querySelector(".job-location").innerHTML;
            var jobPostdateVal = item.querySelector(".job-postdate").innerHTML;
            var jobExperienceVal = item.querySelector(".job-experience").innerHTML;
        
            document.querySelector("#cover-img").src = coverImgVal;
            document.querySelector("#job-overview .view-companylogo").src = companyLogoImgVal;
            document.querySelector("#job-overview .view-title").innerHTML = jobTitleVal;
            document.querySelector("#job-overview .view-companyname").innerHTML = companyNameVal;
            document.querySelector("#job-overview .view-location").innerHTML = jobLocationVal;
            document.querySelector("#job-overview .view-desc").innerHTML = jobDescVal;
            document.querySelector("#job-overview .view-type").innerHTML = jobTypeVal;
            document.querySelector("#job-overview .view-postdate").innerHTML = jobPostdateVal;
            document.querySelector("#job-overview .view-experience").innerHTML = jobExperienceVal;
        });
    });
}

// Search list
var searchElementList = document.getElementById("searchJob");
searchElementList.addEventListener("keyup", function () {
    var inputVal = searchElementList.value.toLowerCase();
    
    function filterItems(arr, query) {
        return arr.filter(function (el) {
            return el.jobTitle.toLowerCase().indexOf(query.toLowerCase()) !== -1 || el.companyName.toLowerCase().indexOf(query.toLowerCase()) !== -1
        })
    }

    var filterData = filterItems(allJobList, inputVal);
    if(inputVal.length > 0){
        document.getElementById("found-job-alert").classList.remove("d-none");
    }else{
        document.getElementById("found-job-alert").classList.add("d-none");
    }

    if(filterData.length == 0){
        document.getElementById("pagination-element").style.display = "none";
    }else{
        document.getElementById("pagination-element").style.display = "flex";
    }

    var pageNumber = document.getElementById('page-num');
    pageNumber.innerHTML = "";
    var dataPageNum = Math.ceil(filterData.length / itemsPerPage)
    // for each page
    for (var i = 1; i < dataPageNum + 1; i++) {
        pageNumber.innerHTML += "<div class='page-item'><a class='page-link clickPageNumber' href='javascript:void(0);'>" + i + "</a></div>";
    }
    loadJobListData(filterData, currentPage);
});

// clearFields
function clearFields() {
    document.getElementById("companylogo-img").src = "assets/images/users/multi-user.jpg";
    document.getElementById("jobtitle-field").value = "";
    document.getElementById("companyname-field").value = "";
    document.getElementById("job_type-field").value = "Full Time";
    document.getElementById("experience-field").value = "";
    document.getElementById("location-field").value = "";
    document.getElementById("Salary-field").value = "";
    document.getElementById("description-field").value = "";

    tagInputField.removeActiveItems();
    tagInputField.setChoiceByValue("");

    document.getElementById("createjob-form").classList.remove("was-validated");
}

document.getElementById('CreateJobModal').addEventListener('hidden.bs.modal', event => {
    clearFields();
});