/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: job grid list Js File
*/

var url = "assets/json/";
var allJobList = '';

var prevButton = document.getElementById('page-prev');
var nextButton = document.getElementById('page-next');

// configuration variables
var currentPage = 1;
var itemsPerPage = 8;

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
getJSON("job-grid-list.json", function (err, data) {
    if (err !== null) {
        console.log("Something went wrong: " + err);
    } else {
        allJobList = data;
        loadJobListData(allJobList, currentPage);
        paginationEvents();
    }
});

// load job list data
function loadJobListData(datas, page) {
    var pages = Math.ceil(datas.length / itemsPerPage)
    if (page < 1) page = 1
    if (page > pages) page = pages
    document.querySelector("#job-list").innerHTML = '';

    if (currentPage == 1) {
        itemsPerPage = 7;
        document.querySelector("#job-list").insertAdjacentHTML('afterbegin', '<div class="col-lg-3 col-md-6" id="job-widget">\
        <div class="card card-height-100 bg-info bg-job">\
            <div class="card-body p-5">\
                <h2 class="lh-base text-white">Velzon invites young professionals for an intership!</h2>\
                <p class="text-white-75 mb-0 fs-14">Don\'t miss your opportunity to improve your skills!</p>\
                <div class="mt-5 pt-2">\
                    <button type="button" class="btn btn-light w-100">View More <i class="ri-arrow-right-line align-bottom"></i></button>\
                </div>\
            </div>\
        </div>\
    </div>');
    } else {
        itemsPerPage = 8;
    }
    for (var i = (page - 1) * itemsPerPage; i < (page * itemsPerPage) && i < datas.length; i++) {
        // Array.from(datas).forEach(function (listData, index) {
        var tagHtmlValue = '';
        datas[i] && Array.from(datas[i].requirement).forEach(function (tag, index) {
            var tagClass = '';
            if (tag) {
                if (tag == "Full Time") {
                    tagClass = 'badge-soft-success'
                } else if (tag == "Freelance") {
                    tagClass = 'badge-soft-primary'
                } else if (tag == "Urgent") {
                    tagClass = 'badge-soft-danger'
                } else if (tag == "Part Time") {
                    tagClass = 'badge-soft-warning'
                } else if (tag == "Private") {
                    tagClass = 'badge-soft-info'
                } else {
                    tagClass = 'badge-soft-success'
                }
            }

            tagHtmlValue += '<span class="badge ' + tagClass + '">' + tag + '</span>'
        })

        if (datas[i]) {
            document.querySelector("#job-list").innerHTML += '<div class="col-lg-3 col-md-6">\
        <div class="card">\
            <div class="card-body">\
                <button type="button" class="btn btn-icon btn-soft-primary float-end" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>\
                <div class="avatar-sm mb-4">\
                    <div class="avatar-title bg-light rounded">\
                        <img src="'+ datas[i].companyLogo + '" alt="" class="avatar-xxs" />\
                    </div>\
                </div>\
                <a href="#!"><h5>'+ datas[i].jobTitle + '</h5></a>\
                <p class="text-muted">'+ datas[i].companyName + '</p>\
                <div class="d-flex gap-4 mb-3">\
                    <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+ datas[i].location + '</div>\
                    <div><i class="ri-time-line text-primary me-1 align-bottom"></i> '+ datas[i].postDate + '</div>\
                </div>\
                <p class="text-muted">'+ datas[i].description + '</p>\
                <div class="hstack gap-2">'+ tagHtmlValue + '</div>\
                <div class="mt-4 hstack gap-2">\
                    <a href="#!" class="btn btn-soft-primary w-100">Apply Job</a>\
                    <a href="apps-job-details.html" class="btn btn-soft-success w-100">Overview</a>\
                </div>\
            </div>\
        </div>\
    </div>'
        };
    }

    document.getElementById("total-result").innerHTML = datas.length
    selectedPage();
    var searchElementList = document.getElementById("searchJob");
    searchElementList.addEventListener("keyup", function () {
        var inputVal = searchElementList.value.toLowerCase();
        if(inputVal.length > 0){
            document.getElementById("job-widget").style.display = "none";
        }else{
            document.getElementById("job-widget").style.display = "block";
        }
    });

    if(datas.length > 0){
        document.getElementById("job-widget").style.display = "block";
    }else{
        document.getElementById("job-widget").style.display = "none";
    }

    currentPage == 1 ? prevButton.parentNode.classList.add('disabled') : prevButton.parentNode.classList.remove('disabled');
    currentPage == pages ? nextButton.parentNode.classList.add('disabled') : nextButton.parentNode.classList.remove('disabled');
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


// Search list
var searchElementList = document.getElementById("searchJob");
searchElementList.addEventListener("keyup", function () {
    var inputVal = searchElementList.value.toLowerCase();
    
    function filterItems(arr, query) {
        return arr.filter(function (el) {
            return el.jobTitle.toLowerCase().indexOf(query.toLowerCase()) !== -1
        })
    }

    var filterData = filterItems(allJobList, inputVal);

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

function filterData() {
    var isstatus = document.getElementById("idStatus").value;
    var pickerVal = document.getElementById("datepicker").value;
    var isType = document.getElementById("idType").value;

    var date1 = pickerVal.split(" to ")[0];
    var date2 = pickerVal.split(" to ")[1];

    var filterData = allJobList.filter(function (data) {
        var status = data.status;
        var statusFilter = false;
        var dateFilter = false;
        var typeFilter = false;

        if (status == "all" || isstatus == "all") {
            statusFilter = true;
        } else {
            statusFilter = status == isstatus;
        }

        data.requirement.map(function (item) {
            if (item == "all" || isType == "all") {
                typeFilter = true;
            } else {
                typeFilter = data.requirement.includes(isType)
            }
        })

        if (
            new Date(data.postDate) >= new Date(date1) &&
            new Date(data.postDate) <= new Date(date2)
        ) {
            dateFilter = true;
        } else {
            dateFilter = false;
        }
        
        if (statusFilter && typeFilter && dateFilter) {
            return statusFilter && typeFilter && dateFilter;
        } else if (statusFilter && typeFilter && pickerVal == "") {
            return statusFilter && typeFilter;
        } else if (typeFilter && dateFilter && pickerVal == "") {
            return typeFilter && dateFilter;
        }
    });

    var pageNumber = document.getElementById('page-num');
    pageNumber.innerHTML = "";
    var dataPageNum = Math.ceil(filterData.length / itemsPerPage)
    // for each page
    for (var i = 1; i < dataPageNum + 1; i++) {
        pageNumber.innerHTML += "<div class='page-item'><a class='page-link clickPageNumber' href='javascript:void(0);'>" + i + "</a></div>";
    }

    loadJobListData(filterData, currentPage);
}