/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: crypto-orders init init js
*/

// List Js
var perPage = 10;

//Table
var options = {
    valueNames: [
        "order_date",
        "currency_name",
        "type",
        "quantity_value",
        "order_value",
        "avg_price",
        "price",
        "status",
        { name: 'time', attr: 'data-timestamp' },
        { name: 'or_val', attr: 'data-orderval' },
        { name: 'sort-avg_price', attr: 'data-av-price' },
        { name: 'sort-price', attr: 'data-price' },
    ],
    page: perPage,
    pagination: true,
    plugins: [
        ListPagination({
            left: 2,
            right: 2
        })
    ]
};

// Init list
var ContactList = document.getElementById('contactList');
if (ContactList) {
    var contactList = new List("contactList", options).on("updated", function(list) {
        list.matchingItems.length == 0 ?
            (document.getElementsByClassName("noresult")[0].style.display = "block") :
            (document.getElementsByClassName("noresult")[0].style.display = "none");
        var isFirst = list.i == 1;
        var isLast = list.i > list.matchingItems.length - list.page;
        // make the Prev and Nex buttons disabled on first and last pages accordingly
        (document.querySelector(".pagination-prev.disabled")) ? document.querySelector(".pagination-prev.disabled").classList.remove("disabled"): '';
        (document.querySelector(".pagination-next.disabled")) ? document.querySelector(".pagination-next.disabled").classList.remove("disabled"): '';
        if (isFirst) {
            document.querySelector(".pagination-prev").classList.add("disabled");
        }
        if (isLast) {
            document.querySelector(".pagination-next").classList.add("disabled");
        }
        if (list.matchingItems.length <= perPage) {
            document.querySelector(".pagination-wrap").style.display = "none";
        } else {
            document.querySelector(".pagination-wrap").style.display = "flex";
        }

        if (list.matchingItems.length > 0) {
            document.getElementsByClassName("noresult")[0].style.display = "none";
        } else {
            document.getElementsByClassName("noresult")[0].style.display = "block";
        }
    });

    isCount = new DOMParser().parseFromString(
        contactList.items.slice(-1)[0]._values.id,
        "text/html"
    );
}

function filterData(){
    var isstatus = document.getElementById("idStatus").value;
    var isType = document.getElementById("idType").value;
    var pickerVal = document.getElementById("range-datepicker").value;

    var date1 = pickerVal.split(" to ")[0];
    var date2 = pickerVal.split(" to ")[1];

    contactList.filter(function (data) {
        matchData = new DOMParser().parseFromString(data.values().status, "text/html");
        var status = matchData.body.firstElementChild.innerHTML;
        var statusFilter = false;
        var dateFilter = false;
        var typeFilter = false;

        if (status == "all" || isstatus == "all") {
            statusFilter = true;
        } else {
            statusFilter = status == isstatus;
        }

        if (data.values().type == "all" || isType == "all") {
            typeFilter = true;
        } else {
            typeFilter = data.values().type == isType;
        }

        if (
            new Date(data.values().order_date.slice(0, 12)) >= new Date(date1) &&
            new Date(data.values().order_date.slice(0, 12)) <= new Date(date2)
        ) {
            dateFilter = true;
        } else {
            dateFilter = false;
        }

        if(statusFilter && typeFilter && dateFilter){
            return statusFilter && typeFilter && dateFilter
        }  else if (statusFilter && typeFilter && pickerVal == "") {
            return statusFilter && typeFilter;
        } else if (typeFilter && dateFilter && pickerVal == "") {
            return typeFilter && dateFilter;
        }
    });

    contactList.update();
}

var paginationNext = document.querySelector(".pagination-next");
if (paginationNext) {
    document.querySelector(".pagination-next").addEventListener("click", function() {
        (document.querySelector(".pagination.listjs-pagination")) ? (document.querySelector(".pagination.listjs-pagination").querySelector(".active")) ?
        document.querySelector(".pagination.listjs-pagination").querySelector(".active").nextElementSibling.children[0].click(): '': '';
    });
}
var paginationPrev = document.querySelector(".pagination-prev");
if (paginationPrev) {
    document.querySelector(".pagination-prev").addEventListener("click", function() {
        (document.querySelector(".pagination.listjs-pagination")) ? (document.querySelector(".pagination.listjs-pagination").querySelector(".active")) ?
        document.querySelector(".pagination.listjs-pagination").querySelector(".active").previousSibling.children[0].click(): '': '';
    });
}