/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Job application init Js File
*/

var checkAll = document.getElementById("checkAll");
if (checkAll) {
    checkAll.onclick = function () {
        var checkboxes = document.querySelectorAll('.form-check-all input[type="checkbox"]');
        var checkedCount = document.querySelectorAll('.form-check-all input[type="checkbox"]:checked').length;
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
            if (checkboxes[i].checked) {
                checkboxes[i].closest("tr").classList.add("table-active");
            } else {
                checkboxes[i].closest("tr").classList.remove("table-active");
            }
        }

        (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'none' : document.getElementById("remove-actions").style.display = 'block';
    };
}

var isChoiceEl = document.getElementById("idStatus");
var choices = new Choices(isChoiceEl, {
    searchEnabled: false,
});

var isTypeEl = document.getElementById("idType");
var choices = new Choices(isTypeEl, {
    searchEnabled: false,
});

var perPage = 8;
var editlist = false;

//Table
var options = {
    valueNames: [
        "id",
        "company",
        "designation",
        "date",
        "contacts",
        "type",
        "status",
    ],
    page: perPage,
    pagination: true,
    plugins: [
        ListPagination({
            left: 2,
            right: 2,
        }),
    ],
};


// Init list
var applicationList = new List("applicationList", options).on("updated", function (list) {
    list.matchingItems.length == 0 ?
        (document.getElementsByClassName("noresult")[0].style.display = "block") :
        (document.getElementsByClassName("noresult")[0].style.display = "none");
    var isFirst = list.i == 1;
    var isLast = list.i > list.matchingItems.length - list.page;
    // make the Prev and Nex buttons disabled on first and last pages accordingly
    document.querySelector(".pagination-prev.disabled") ?- 

        document.querySelector(".pagination-prev.disabled").classList.remove("disabled") : "";
    document.querySelector(".pagination-next.disabled") ?
        document.querySelector(".pagination-next.disabled").classList.remove("disabled") : "";
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

    if (list.matchingItems.length == perPage) {
        document.querySelector(".pagination.listjs-pagination").firstElementChild.children[0].click()
    }

    if (list.matchingItems.length > 0) {
        document.getElementsByClassName("noresult")[0].style.display = "none";
    } else {
        document.getElementsByClassName("noresult")[0].style.display = "block";
    }
});


const xhttp = new XMLHttpRequest();
xhttp.onload = function () {
    var json_records = JSON.parse(this.responseText);
    Array.from(json_records).forEach(function(element){
        applicationList.add({
            id: '<a href="#" class="fw-medium link-primary">#VZ'+element.id+'</a>',
            company: '<div class="d-flex align-items-center">\
            <div class="flex-shrink-0">\
                <img src="'+element.company[0]+'" alt="" class="avatar-xxs rounded-circle image_src object-cover">\
            </div>\
            <div class="flex-grow-1 ms-2 name">'+element.company[1]+'</div>\
        </div>',
            designation: element.designation,
            date: element.date,
            contacts: element.contacts,
            type:element.type,
            status: isStatus(element.status)
        });
        applicationList.sort('id', { order: "desc" });
        refreshCallbacks();
    });
    applicationList.remove("id", `<a href="#" class="fw-medium link-primary">#VZ001</a>`);
}
xhttp.open("GET", "assets/json/application-list.json");
xhttp.send();

function isStatus(val) {
    switch (val) {
        case "Approved":
            return ('<span class="badge badge-soft-success text-uppercase">' + val + "</span>");
        case "New":
            return ('<span class="badge badge-soft-info text-uppercase">' + val + "</span>");
        case "Pending":
            return ('<span class="badge badge-soft-warning text-uppercase">' + val + "</span>");
        case "Rejected":
            return ('<span class="badge badge-soft-danger text-uppercase">' + val + "</span>");
    }
}

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

var idField = document.getElementById("applicationId"),
companyLogoImg = document.getElementById("companylogo-img"),
companyField = document.getElementById("company-field"),
designationField = document.getElementById("designation-field"),
dateField = document.getElementById("date-field"),
contactField = document.getElementById("contact-field"),
statusField = document.getElementById("status-input"),
typeField = document.getElementById("type-input"),
addBtn = document.getElementById("add-btn"),
editBtn = document.getElementById("edit-btn"),
removeBtns = document.getElementsByClassName("remove-item-btn"),
editBtns = document.getElementsByClassName("edit-item-btn");
refreshCallbacks();

var tabEl = document.querySelectorAll('a[data-bs-toggle="tab"]');
Array.from(tabEl).forEach(function (item) {
    item.addEventListener("shown.bs.tab", function (event) {
        filterOrder(event.target.id);
    });
});

function filterOrder(isValue) {
    var values_status = isValue;
    applicationList.filter(function (data) {
        var statusFilter = false;
        matchData = new DOMParser().parseFromString(
            data.values().status,
            "text/html"
        );
        var status = matchData.body.firstElementChild.innerHTML;
        if (status == "All" || values_status == "All") {
            statusFilter = true;
        } else {
            statusFilter = status == values_status;
        }
        return statusFilter;
    });

    applicationList.update();
}

var example = new Choices(typeField, {
    searchEnabled: false,
});
var statusVal = new Choices(statusField, {
    searchEnabled: false,
});

document.getElementById("showModal").addEventListener("show.bs.modal", function (e) {
    if (e.relatedTarget.classList.contains("edit-item-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Edit Application";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Update";
    } else if (e.relatedTarget.classList.contains("add-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Add Application";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Add Application";
    } else {
        document.getElementById("exampleModalLabel").innerHTML = "List Application";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "none";
    }
});
ischeckboxcheck();
document.getElementById("showModal").addEventListener("hidden.bs.modal", function () {
    clearFields();
});


document.querySelector("#applicationList").addEventListener("click", function () {
    refreshCallbacks();
    ischeckboxcheck();
});

var table = document.getElementById("jobListTable");
// save all tr
var tr = table.getElementsByTagName("tr");
var trlist = table.querySelectorAll(".list tr");

function filterData() {
    var isstatus = document.getElementById("idStatus").value;
    var isType = document.getElementById("idType").value;
    var pickerVal = document.getElementById("demo-datepicker").value;

    var date1 = pickerVal.split(" to ")[0];
    var date2 = pickerVal.split(" to ")[1];

    applicationList.filter(function (data) {
        matchData = new DOMParser().parseFromString(
            data.values().status,
            "text/html"
        );
        var status = matchData.body.firstElementChild.innerHTML;
        var statusFilter = false;
        var typeFilter = false;
        var dateFilter = false;
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
            new Date(data.values().date.slice(0, 12)) >= new Date(date1) &&
            new Date(data.values().date.slice(0, 12)) <= new Date(date2)
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
    applicationList.update();
}

var count = 13;
var forms = document.querySelectorAll('.tablelist-form')
Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            if (companyField.value !== "" &&
                designationField.value !== "" &&
                dateField.value !== "" &&
                contactField.value !== "" &&
                statusField.value !== "" &&
                typeField.value !== "" && !editlist){
                applicationList.add({
                    id: '<a href="#" class="fw-medium link-primary">#VZ'+count+'</a>',
                    company: '<div class="d-flex align-items-center">\
                    <div class="flex-shrink-0">\
                        <img src="'+ companyLogoImg.src + '" alt="" class="avatar-xxs rounded-circle image_src object-cover">\
                    </div>\
                    <div class="flex-grow-1 ms-2 name">'+companyField.value+'</div>\
                </div>',
                    designation: designationField.value,
                    date: dateField.value,
                    contacts: contactField.value,
                    type: typeField.value,
                    status: isStatus(statusField.value)
                });
                applicationList.sort('id', { order: "desc" });
                document.getElementById("close-modal").click();
                clearFields();
                refreshCallbacks();
                filterOrder("All");
                count++;
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Application inserted successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true
                });
            }else if(companyField.value !== "" &&
            designationField.value !== "" &&
            dateField.value !== "" &&
            contactField.value !== "" &&
            statusField.value !== "" &&
            typeField.value !== "" && editlist){
                var editValues = applicationList.get({
                    id: idField.value,
                });
                Array.from(editValues).forEach(function (x) {
                    isid = new DOMParser().parseFromString(x._values.id, "text/html");
                    var selectedid = isid.body.firstElementChild.innerHTML;
                    if (selectedid == itemId) {
                        x.values({
                            id: '<a href="javascript:void(0);" class="fw-medium link-primary">'+idField.value+"</a>",
                            company: '<div class="d-flex align-items-center">\
                            <div class="flex-shrink-0">\
                                <img src="'+companyLogoImg.src+'" alt="" class="avatar-xxs rounded-circle image_src object-cover">\
                            </div>\
                            <div class="flex-grow-1 ms-2 name">'+companyField.value+'</div>\
                        </div>',
                            designation: designationField.value,
                            date: dateField.value,
                            contacts: contactField.value,
                            type: typeField.value,
                            status: isStatus(statusField.value)
                        });
                    }
                    document.getElementById("close-modal").click();
                });
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Application updated Successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true
                });
            }
        }
    }, false)
})


function ischeckboxcheck() {
    Array.from(document.getElementsByName("checkAll")).forEach(function (x) {
        x.addEventListener("change", function (e) {
            if (x.checked == true) {
                e.target.closest("tr").classList.add("table-active");
            } else {
                e.target.closest("tr").classList.remove("table-active");
            }

            var checkedCount = document.querySelectorAll('[name="checkAll"]:checked').length;
            if (e.target.closest("tr").classList.contains("table-active")) {
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block': document.getElementById("remove-actions").style.display = 'none';
            } else {
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block': document.getElementById("remove-actions").style.display = 'none';
            }
        });
    });
}

function refreshCallbacks() {
    Array.from(removeBtns).forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.target.closest("tr").children[1].innerText;
            itemId = e.target.closest("tr").children[1].innerText;
            var itemValues = applicationList.get({
                id: itemId,
            });

            Array.from(itemValues).forEach(function (x) {
                deleteid = new DOMParser().parseFromString(x._values.id, "text/html");

                var isElem = deleteid.body.firstElementChild;
                var isdeleteid = deleteid.body.firstElementChild.innerHTML;

                if (isdeleteid == itemId) {
                    document.getElementById("delete-record").addEventListener("click", function () {
                        applicationList.remove("id", isElem.outerHTML);
                        document.getElementById("deleteRecord-close").click();
                    });
                }
            });
        });
    });

    Array.from(editBtns).forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.target.closest("tr").children[1].innerText;
            itemId = e.target.closest("tr").children[1].innerText;
            var itemValues = applicationList.get({
                id: itemId,
            });

            Array.from(itemValues).forEach(function (x) {
                isid = new DOMParser().parseFromString(x._values.id, "text/html");
                var selectedid = isid.body.firstElementChild.innerHTML;
                if (selectedid == itemId) {
                    editlist = true;
                    idField.value = selectedid;
                    var companyElem = new DOMParser().parseFromString(x._values.company, "text/html").body.querySelector(".name").innerHTML;
                    companyField.value = companyElem;
                    companyLogoImg.src = new DOMParser().parseFromString(x._values.company, "text/html").body.querySelector(".image_src").src
                    designationField.value = x._values.designation;
                    dateField.value = x._values.date;
                    contactField.value = x._values.contacts;

                    if (statusVal) statusVal.destroy();
                    statusVal = new Choices(statusField, {
                        searchEnabled: false
                    });
                    val = new DOMParser().parseFromString(x._values.status, "text/html");
                    var statusSelec = val.body.firstElementChild.innerHTML;
                    statusVal.setChoiceByValue(statusSelec);

                    if (example) example.destroy();
                    example = new Choices(typeField, {
                        searchEnabled: false
                    });
                    var selected = x._values.type;
                    example.setChoiceByValue(selected);

                    flatpickr("#date-field", {
                        enableTime: false,
                        dateFormat: "d M, Y",
                        defaultDate: x._values.date,
                    });
                }
            });
        });
    });
}

function clearFields() {
    companyField.value = "";
    companyLogoImg.src = "";
    designationField.value = "";
    dateField.value = "";
    contactField.value = "";

    if (example) example.destroy();
    example = new Choices(typeField);

    if (statusVal) statusVal.destroy();
    statusVal = new Choices(statusField);
}

document.querySelector(".pagination-next").addEventListener("click", function () {
    document.querySelector(".pagination.listjs-pagination") ?
        document.querySelector(".pagination.listjs-pagination").querySelector(".active") ?
            document.querySelector(".pagination.listjs-pagination").querySelector(".active").nextElementSibling.children[0].click() : "" : "";
});
document.querySelector(".pagination-prev").addEventListener("click", function () {
    document.querySelector(".pagination.listjs-pagination") ?
        document.querySelector(".pagination.listjs-pagination").querySelector(".active") ?
            document.querySelector(".pagination.listjs-pagination").querySelector(".active").previousSibling.children[0].click() : "" : "";
});


// Delete Multiple Records
function deleteMultiple(){
    ids_array = [];
    var items = document.querySelectorAll('.form-check [value=option1]');
    for (i = 0; i < items.length; i++) {
        if (items[i].checked == true) {
            var trNode = items[i].parentNode.parentNode.parentNode;
            var id = trNode.querySelector("td a").innerHTML;
            ids_array.push(id);
        }
    }
    if (typeof ids_array !== 'undefined' && ids_array.length > 0) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
            cancelButtonClass: 'btn btn-danger w-xs mt-2',
            confirmButtonText: "Yes, delete it!",
            buttonsStyling: false,
            showCloseButton: true
        }).then(function (result) {
            if (result.value) {
                for (i = 0; i < ids_array.length; i++) {
                    applicationList.remove("id", `<a href="#" class="fw-medium link-primary">` + ids_array[i] +`</a>`);
                }
                document.getElementById("remove-actions").style.display = 'none';
                document.getElementById("checkAll").checked = false;
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Your data has been deleted.',
                    icon: 'success',
                    confirmButtonClass: 'btn btn-info w-xs mt-2',
                    buttonsStyling: false
                });
            }
        });
    } else {
        Swal.fire({
            title: 'Please select at least one checkbox',
            confirmButtonClass: 'btn btn-info',
            buttonsStyling: false,
            showCloseButton: true
        });
    }
}
