/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: job candidate list init js
*/

var url = "assets/json/";
var allcandidateList = '';

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
getJSON("job-candidate-list.json", function (err, data) {
    if (err !== null) {
        console.log("Something went wrong: " + err);
    } else {
        allcandidateList = data;
        loadCandidateListData(allcandidateList, currentPage);
        paginationEvents();
    }
});

function loadCandidateListData(datas, page) {
    var pages = Math.ceil(datas.length / itemsPerPage)
    if (page < 1) page = 1
    if (page > pages) page = pages
    document.querySelector("#candidate-list").innerHTML = '';

    for (var i = (page - 1) * itemsPerPage; i < (page * itemsPerPage) && i < datas.length; i++) {
        // Array.from(datas).forEach(function (listData, index){

        if (datas[i]) {
            var bookmark = datas[i].bookmark ? "active" : "";

            var isUserProfile = datas[i].userImg ? '<img src="' + datas[i].userImg + '" alt="" class="member-img img-fluid d-block rounded" />'
                : '<div class="avatar-title border bg-light text-primary rounded text-uppercase fs-16">' + datas[i].nickname + '</div>';
            document.querySelector("#candidate-list").innerHTML += '<div class="col-md-6 col-lg-12">\
        <div class="card mb-0">\
            <div class="card-body">\
                <div class="d-lg-flex align-items-center">\
                    <div class="flex-shrink-0">\
                        <div class="avatar-sm rounded">'+ isUserProfile + '</div>\
                    </div>\
                    <div class="ms-lg-3 my-3 my-lg-0">\
                        <a href="pages-profile.html"><h5 class="fs-16 mb-2">'+ datas[i].candidateName + '</h5></a>\
                        <p class="text-muted mb-0">'+ datas[i].designation + '</p>\
                    </div>\
                    <div class="d-flex gap-4 mt-0 text-muted mx-auto">\
                        <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+ datas[i].location + '</div>\
                        <div><i class="ri-time-line text-primary me-1 align-bottom"></i> '+ isStatus(datas[i].type) + '</div>\
                    </div>\
                    <div class="d-flex flex-wrap gap-2 align-items-center mx-auto my-3 my-lg-0">\
                        <div class="badge text-bg-success">\
                            <i class="mdi mdi-star me-1"></i>'+ datas[i].rating[0] + '\
                        </div>\
                        <div class="text-muted">'+ datas[i].rating[1] + '</div>\
                    </div>\
                    <div>\
                        <a href="#!" class="btn btn-soft-success">View Details</a>\
                        <a href="#!" class="btn btn-ghost-danger btn-icon custom-toggle '+ bookmark + '" data-bs-toggle="button">\
                            <span class="icon-on"><i class="ri-bookmark-line align-bottom"></i></span>\
                            <span class="icon-off"><i class="ri-bookmark-3-fill align-bottom"></i></span>\
                        </a>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>'
        }
        // })
    }

    selectedPage();
    currentPage == 1 ? prevButton.parentNode.classList.add('disabled') : prevButton.parentNode.classList.remove('disabled');
    currentPage == pages ? nextButton.parentNode.classList.add('disabled') : nextButton.parentNode.classList.remove('disabled');
}

function isStatus(val) {
    switch (val) {
        case "Part Time":
            return ('<span class="badge badge-soft-danger">' + val + "</span>");
        case "Full Time":
            return ('<span class="badge badge-soft-success">' + val + "</span>");
        case "Freelancer":
            return ('<span class="badge badge-soft-secondary">' + val + "</span>");
    }
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
        return Math.ceil(allcandidateList.length / itemsPerPage);
    };

    function clickPage() {
        document.addEventListener('click', function (e) {
            if (e.target.nodeName == "A" && e.target.classList.contains("clickPageNumber")) {
                currentPage = e.target.textContent;
                loadCandidateListData(allcandidateList, currentPage);
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
            loadCandidateListData(allcandidateList, currentPage);
        }
    });

    nextButton.addEventListener('click', function () {
        if (currentPage < numPages()) {
            currentPage++;
            loadCandidateListData(allcandidateList, currentPage);
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
            return el.designation.toLowerCase().indexOf(query.toLowerCase()) !== -1 || el.candidateName.toLowerCase().indexOf(query.toLowerCase()) !== -1
        })
    }

    var filterData = filterItems(allcandidateList, inputVal);

    if (filterData.length == 0) {
        document.getElementById("pagination-element").style.display = "none";
    } else {
        document.getElementById("pagination-element").style.display = "flex";
    }

    var pageNumber = document.getElementById('page-num');
    pageNumber.innerHTML = "";
    var dataPageNum = Math.ceil(filterData.length / itemsPerPage)
    // for each page
    for (var i = 1; i < dataPageNum + 1; i++) {
        pageNumber.innerHTML += "<div class='page-item'><a class='page-link clickPageNumber' href='javascript:void(0);'>" + i + "</a></div>";
    }
    loadCandidateListData(filterData, currentPage);
});