/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: CRM-companies Js File
*/


// list js
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


var perPage = 8;

//Table
var options = {
    valueNames: [
        "id",
        "name",
        "createBy",
        "apikey",
        "status",
        "create_date",
        "expiry_date",
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
var apiKeyList = new List("apiKeyList", options).on("updated", function (list) {
    list.matchingItems.length == 0 ?
        (document.getElementsByClassName("noresult")[0].style.display = "block") :
        (document.getElementsByClassName("noresult")[0].style.display = "none");
    var isFirst = list.i == 1;
    var isLast = list.i > list.matchingItems.length - list.page;
    // make the Prev and Nex buttons disabled on first and last pages accordingly
    document.querySelector(".pagination-prev.disabled") ? -

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
    Array.from(json_records).forEach(function (element) {
        apiKeyList.add({
            id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ' + element.id + "</a>",
            name: element.name,
            createBy: element.createBy,
            apikey: '<input type="text" class="form-control apikey-value" readonly value="' + element.apikey + '">',
            status: isStatus(element.status),
            create_date: element.create_date,
            expiry_date: element.expiry_date
        });
        apiKeyList.sort('id', { order: "desc" });

        if(element.status == "Active"){
            document.querySelector(".disable-btn").innerHTML = "Disable"
        }else if (element.status == "Disable"){
            document.querySelector(".disable-btn").innerHTML = "Enable"
        }
        refreshCallbacks();
    });
    apiKeyList.remove("id", `<a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>`);
}

xhttp.open("GET", "assets/json/api-key-list.json");
xhttp.send();

function isStatus(val) {
    switch (val) {
        case "Disable":
            return ('<span class="badge badge-soft-danger">' + val + "</span>");
        case "Active":
            return ('<span class="badge badge-soft-success">' + val + "</span>");
    }
}



document.getElementById("api-key-modal").addEventListener("show.bs.modal", function (e) {
    if (e.relatedTarget.classList.contains("edit-item-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Rename API name";
        document.getElementById("api-key-modal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").style.display = "none";
        document.getElementById("createApi-btn").style.display = "none";
        document.getElementById("edit-btn").style.display = "block";
    } else if (e.relatedTarget.classList.contains("create-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Create API Key";
        document.getElementById("api-key-modal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("edit-btn").style.display = "none";
        document.getElementById("createApi-btn").style.display = "block";
        document.getElementById("add-btn").style.display = "none";
    } else if (e.relatedTarget.classList.contains("regenerate-api-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Regenerate API";
        document.getElementById("api-key-modal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").style.display = "none";
        document.getElementById("createApi-btn").style.display = "none";
        document.getElementById("edit-btn").style.display = "block";
    }
});


var idField = document.getElementById("apikeyId"),
    apiKeyNameField = document.getElementById("api-key-name"),
    apiKeyField = document.getElementById("api-key"),
    addBtn = document.getElementById("add-btn"),
    editBtn = document.getElementById("edit-btn"),
    removeBtns = document.getElementsByClassName("remove-item-btn"),
    editBtns = document.getElementsByClassName("edit-item-btn");
refreshCallbacks();
ischeckboxcheck();
document.getElementById("api-key-modal").addEventListener("hidden.bs.modal", function () {
    clearFields();
});

document.querySelector("#apiKeyList").addEventListener("click", function () {
    ischeckboxcheck();
});


// generateApiID
function generateApiID() {
    var d = new Date().getTime();

    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now();
    }

    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : r & 0x3 | 0x8).toString(16);
    });

    return uuid;
}

document.querySelectorAll(".create-btn").forEach(function (item) {
    item.addEventListener("click", function () {
        document.getElementById("api-key").value = generateApiID();
    })
});

var now = new Date();
var current = now.toUTCString().slice(5, 16);

now.setMonth(now.getMonth() + 6);
var nextDate = now.toUTCString().slice(5, 16);

var count = 13;

document.getElementById("createApi-btn").addEventListener("click", function (e) {
    var text;
    if (apiKeyNameField.value.length == 0) {
        var errorMsg = document.getElementById("api-key-error-msg");
        errorMsg.classList.remove("d-none");

        setTimeout(() => document.getElementById("api-key-error-msg").classList.add("d-none"), 2000);
        text = "Please enter api key name";
        errorMsg.innerHTML = text;
        return false;
    }

    if (apiKeyNameField.value.length > 0) {
        document.getElementById("apikey-element").style.display = "block";
        e.target.style.display = "none";
        document.getElementById("add-btn").style.display = "block";
    }
});

addBtn.addEventListener("click", function (e) {
    var errorMsg = document.getElementById("api-key-error-msg");
    errorMsg.classList.remove("d-none");

    setTimeout(() => document.getElementById("api-key-error-msg").classList.add("d-none"), 2000);

    var text;
    if (apiKeyNameField.value.length == 0) {
        text = "Please enter api key name";
        errorMsg.innerHTML = text;
        return false;
    }

    if (
        apiKeyNameField.value !== "" &&
        apiKeyField.value !== ""
    ) {
        apiKeyList.add({
            id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ' + count + "</a>",
            name: apiKeyNameField.value,
            createBy: document.querySelector("#page-header-user-dropdown .user-name-text").innerHTML,
            apikey: '<input type="text" class="form-control apikey-value" readonly value="' + apiKeyField.value + '">',
            status: isStatus("Active"),
            create_date: current,
            expiry_date: nextDate
        });

        apiKeyList.sort('id', { order: "desc" });
        document.getElementById("close-modal").click();
        clearFields();
        refreshCallbacks();
        count++;
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Application inserted successfully!',
            showConfirmButton: false,
            timer: 2000,
            showCloseButton: true
        });
    }
});

editBtn.addEventListener("click", function (e) {
    document.getElementById("exampleModalLabel").innerHTML = "Rename API name";
    var editValues = apiKeyList.get({
        id: idField.value,
    });

    Array.from(editValues).forEach(function (x) {
        isid = new DOMParser().parseFromString(x._values.id, "text/html");
        var selectedid = isid.body.firstElementChild.innerHTML;
        if (selectedid == itemId) {
            x.values({
                id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ' + idField.value + "</a>",
                name: apiKeyNameField.value,
                createBy: x._values.createBy,
                apikey: '<input type="text" class="form-control apikey-value" readonly value="' + apiKeyField.value + '">',
                status: x._values.status,
                create_date: x._values.create_date,
                expiry_date: x._values.expiry_date
            });
        }

        document.getElementById("close-modal").click();
    });

    Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'API name updated Successfully!',
        showConfirmButton: false,
        timer: 2000,
        showCloseButton: true
    });
});

// ischeckboxcheck
function ischeckboxcheck() {
    Array.from(document.getElementsByName("chk_child")).forEach(function (x) {
        x.addEventListener("change", function (e) {
            if (x.checked == true) {
                e.target.closest("tr").classList.add("table-active");
            } else {
                e.target.closest("tr").classList.remove("table-active");
            }

            var checkedCount = document.querySelectorAll('[name="chk_child"]:checked').length;
            if (e.target.closest("tr").classList.contains("table-active")) {
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block' : document.getElementById("remove-actions").style.display = 'none';
            } else {
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block' : document.getElementById("remove-actions").style.display = 'none';
            }
        });
    });
}

// refreshCallbacks
function refreshCallbacks() {
    // editBtns
    Array.from(editBtns).forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.target.closest("tr").children[1].innerText;
            itemId = e.target.closest("tr").children[1].innerText;
            var itemValues = apiKeyList.get({
                id: itemId,
            });

            Array.from(itemValues).forEach(function (x) {
                isid = new DOMParser().parseFromString(x._values.id, "text/html");
                var selectedid = isid.body.firstElementChild.innerHTML;
                if (selectedid == itemId) {
                    document.getElementById("apikey-element").style.display = "block";
                    idField.value = selectedid;
                    apiKeyNameField.value = x._values.name;
                    apiKeyField.value = new DOMParser().parseFromString(x._values.apikey, "text/html").body.querySelector(".apikey-value").value;
                }
            });
        });
    });

    // regenerate api
    Array.from(document.querySelectorAll(".regenerate-api-btn")).forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.target.closest("tr").children[1].innerText;
            itemId = e.target.closest("tr").children[1].innerText;
            var itemValues = apiKeyList.get({
                id: itemId,
            });

            Array.from(itemValues).forEach(function (x) {
                isid = new DOMParser().parseFromString(x._values.id, "text/html");
                var selectedid = isid.body.firstElementChild.innerHTML;
                if (selectedid == itemId) {
                    document.getElementById("apikey-element").style.display = "block";
                    idField.value = selectedid;
                    apiKeyNameField.value = x._values.name;
                    apiKeyField.value = generateApiID();
                }
            });
        });
    });

    // removeBtns
    Array.from(removeBtns).forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.target.closest("tr").children[1].innerText;
            itemId = e.target.closest("tr").children[1].innerText;
            var itemValues = apiKeyList.get({
                id: itemId,
            });

            Array.from(itemValues).forEach(function (x) {
                deleteid = new DOMParser().parseFromString(x._values.id, "text/html");

                var isElem = deleteid.body.firstElementChild;
                var isdeleteid = deleteid.body.firstElementChild.innerHTML;

                if (isdeleteid == itemId) {
                    document.getElementById("delete-record").addEventListener("click", function () {
                        apiKeyList.remove("id", isElem.outerHTML);
                        document.getElementById("deleteRecord-close").click();
                    });
                }
            });
        });
    });
}

// clearFields
function clearFields() {
    apiKeyNameField.value = "";
    apiKeyField.value = "";
    document.getElementById("apikey-element").style.display = "none";
    document.getElementById("add-btn").style.display = "none";
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
function deleteMultiple() {
    ids_array = [];
    var items = document.querySelectorAll('.form-check [value=option1]');
    for (i = 0; i < items.length; i++) {
        if (items[i].checked == true) {
            var trNode = items[i].parentNode.parentNode.parentNode;
            var id = trNode.querySelector("td a").innerHTML;
            ids_array.push(id);
        }
    };
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
                    apiKeyList.remove("id", `<a href="javascript:void(0);" class="fw-medium link-primary">` + ids_array[i] + `</a>`);
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
};