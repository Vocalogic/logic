/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: job companies list init js
*/


var url = "assets/json/";
var allCompaniesList = '';

var prevButton = document.getElementById('page-prev');
var nextButton = document.getElementById('page-next');

// configuration variables
var currentPage = 1;
var itemsPerPage = 16;

// getJSON
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
getJSON("job-companies-list.json", function (err, data) {
    if (err !== null) {
        console.log("Something went wrong: " + err);
    } else {
        allCompaniesList = data;
        loadCompaniesListData(allCompaniesList, currentPage);
        paginationEvents();
    }
});

function loadCompaniesListData(datas, page) {
    var pages = Math.ceil(datas.length / itemsPerPage)
    if (page < 1) page = 1
    if (page > pages) page = pages
    document.querySelector("#companies-list").innerHTML = '';
    for (var i = (page - 1) * itemsPerPage; i < (page * itemsPerPage) && i < datas.length; i++) {
        // Array.from(datas).forEach(function (listData, index) {
        if (datas[i]) {
            document.querySelector("#companies-list").innerHTML += '<div class="col-xxl-3 col-md-6">\
    <div class="card companiesList-card">\
        <div class="card-body">\
            <div class="avatar-sm mx-auto">\
                <div class="avatar-title bg-light rounded">\
                    <img src="'+ datas[i].companyLogo + '" alt="" class="avatar-xxs companyLogo-img">\
                </div>\
            </div>\
            <div class="text-center">\
                <a href="#!">\
                    <h5 class="mt-3 company-name">'+ datas[i].companyName + '</h5>\
                </a>\
                <div class="d-none company-desc">'+ datas[i].companyDesc + '</div>\
                <p class="text-muted industry-type">'+ datas[i].industryType + '</p>\
                <div class="d-none">\
                    <span class="employee">'+ datas[i].employee + '</span>\
                    <span class="location">'+ datas[i].location + '</span>\
                    <span class="rating">'+ datas[i].rating + '</span>\
                    <span class="website">'+ datas[i].website + '</span>\
                    <span class="email">'+ datas[i].email + '</span>\
                    <span class="since">'+ datas[i].since + '</span>\
                </div>\
            </div>\
            <div>\
                <button type="button" class="btn btn-soft-primary w-100 viewcompany-list"><span class="vacancy">'+ datas[i].vacancy + '</span> Jobs Available</button>\
            </div>\
        </div>\
    </div>\
</div>';
        }
    }
    // })
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
        return Math.ceil(allCompaniesList.length / itemsPerPage);
    };

    function clickPage() {
        document.addEventListener('click', function (e) {
            if (e.target.nodeName == "A" && e.target.classList.contains("clickPageNumber")) {
                currentPage = e.target.textContent;
                loadCompaniesListData(allCompaniesList, currentPage);
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
            loadCompaniesListData(allCompaniesList, currentPage);
        }
    });

    nextButton.addEventListener('click', function () {
        if (currentPage < numPages()) {
            currentPage++;
            loadCompaniesListData(allCompaniesList, currentPage);
        }
    });

    pageNumbers();
    clickPage();
    selectedPage();
}

// jobDetailShow event
function jobDetailShow() {
    Array.from(document.querySelectorAll("#companies-list .companiesList-card")).forEach(function (item) {
        item.querySelector(".viewcompany-list").addEventListener("click", function () {
            var companyLogoImgVal = item.querySelector(".companyLogo-img").src;
            var companyNameVal = item.querySelector(".company-name").innerHTML;
            var companyDescVal = item.querySelector(".company-desc").innerHTML;
            var industryTypeVal = item.querySelector(".industry-type").innerHTML;
            var companyEmployeeVal = item.querySelector(".employee").innerHTML;
            var companyLocationVal = item.querySelector(".location").innerHTML;
            var companyRatingVal = item.querySelector(".rating").innerHTML;
            var companyWebsiteVal = item.querySelector(".website").innerHTML;
            var companyEmailVal = item.querySelector(".email").innerHTML;
            var companySinceVal = item.querySelector(".since").innerHTML;
            var jobVacancyVal = item.querySelector(".vacancy").innerHTML;

            document.querySelector("#company-overview .company-logo").src = companyLogoImgVal;
            document.querySelector("#company-overview .overview-companyname").innerHTML = companyNameVal;
            document.querySelectorAll("#company-overview .overview-industryType").forEach(function (elem) {
                elem.innerHTML = industryTypeVal;
            });
            document.querySelector("#company-overview .overview-companydesc").innerHTML = companyDescVal;
            document.querySelector("#company-overview .overview-company_location").innerHTML = companyLocationVal;
            document.querySelector("#company-overview .overview-employee").innerHTML = companyEmployeeVal;
            document.querySelector("#company-overview .overview-vacancy").innerHTML = jobVacancyVal;
            document.querySelector("#company-overview .overview-rating").innerHTML = companyRatingVal;
            document.querySelector("#company-overview .overview-website").innerHTML = companyWebsiteVal;
            document.querySelector("#company-overview .overview-email").innerHTML = companyEmailVal;
            document.querySelector("#company-overview .overview-since").innerHTML = companySinceVal;
        });
    });
}


// Search list
var searchElementList = document.getElementById("searchCompany");
searchElementList.addEventListener("keyup", function () {
    var inputVal = searchElementList.value.toLowerCase();

    function filterItems(arr, query) {
        return arr.filter(function (el) {
            return el.companyName.toLowerCase().indexOf(query.toLowerCase()) !== -1 || el.industryType.toLowerCase().indexOf(query.toLowerCase()) !== -1
        })
    }

    var filterData = filterItems(allCompaniesList, inputVal);

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
    loadCompaniesListData(filterData, currentPage);
});


flatpickr("#datepicker", {
    dateFormat: "d M, Y",
    defaultDate: new Date(),
    maxDate: new Date()
});


// filterdata
function filterData() {
    var pickerVal = document.getElementById("datepicker").value;
    var isType = document.getElementById("idType").value;

    var filterData = allCompaniesList.filter(function (data) {
        console.log(new Date(data.postDate) <= new Date(pickerVal))
        
        var dateFilter = false;
        var typeFilter = false;

        if (data.type == "all" || isType == "all") {
            typeFilter = true;
        } else {
            typeFilter = data.type == isType;
        }

        if (new Date(data.postDate) <= new Date(pickerVal)) {
            dateFilter = true;
        } else {
            dateFilter = false;
        }

        if (typeFilter && dateFilter) {
            return typeFilter && dateFilter;
        }else if (typeFilter && pickerVal == "") {
            return typeFilter;
        } else if (typeFilter && dateFilter && pickerVal == "") {
            return typeFilter && dateFilter;
        }
    });

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

    loadCompaniesListData(filterData, currentPage);
};