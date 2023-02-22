/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: CRM-leads Js File
*/


// list js
function formatDate(date) {
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    var d = new Date(date),
        month = '' + monthNames[(d.getMonth())],
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;
    return [day + " " + month, year].join(', ');
};

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
var editlist = false;

//Table
var options = {
    valueNames: [
            "id",
            "name",
            "company_name",
            "date",
            "leads_score",
            "phone",
            "location",
        "tags",
        { attr: "src", name: "image_src" }
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
var leadsList = new List("leadsList", options).on("updated", function (list) {
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

const xhttp = new XMLHttpRequest();
xhttp.onload = function () {
    var json_records = JSON.parse(this.responseText);
    Array.from(json_records).forEach(function (raw){
        var tags = raw.tags;
        var tagHtml = '';
        Array.from(tags).forEach((tag, index) => {
            tagHtml += '<span class="badge badge-soft-primary me-1">'+tag+'</span>'
        })

        leadsList.add({
            id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ'+raw.id+"</a>",
            image_src: raw.image_src,
            name: raw.name,
            company_name: raw.company_name,
            date: formatDate(raw.date),
            leads_score: raw.leads_score,
            phone: raw.phone,
            location: raw.location,
            tags: tagHtml,
        });
        leadsList.sort('id', { order: "desc" });
        refreshCallbacks();
    });
    leadsList.remove("id", `<a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a>`);
}
xhttp.open("GET", "assets/json/leads-list.json");
xhttp.send();

// customer image
document.querySelector("#lead-image-input").addEventListener("change", function () {
    var preview = document.querySelector("#lead-img");
    var file = document.querySelector("#lead-image-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load",function () {
        preview.src = reader.result;
    },false);
    if (file) {
        reader.readAsDataURL(file);
    }
});

isCount = new DOMParser().parseFromString(
    leadsList.items.slice(-1)[0]._values.id,
    "text/html"
);

var isValue = isCount.body.firstElementChild.innerHTML;

var idField = document.getElementById("id-field"),
    leadNameField = document.getElementById("leadname-field"),
    leadImg = document.getElementById("lead-img"),
    company_nameField = document.getElementById("company_name-field"),
    dateField = document.getElementById("date-field"),
    leads_scoreField = document.getElementById("leads_score-field"),
    phoneField = document.getElementById("phone-field"),
    locationField = document.getElementById("location-field"),
    addBtn = document.getElementById("add-btn"),
    editBtn = document.getElementById("edit-btn"),
    removeBtns = document.getElementsByClassName("remove-item-btn"),
    editBtns = document.getElementsByClassName("edit-item-btn");
refreshCallbacks();

document.getElementById("showModal").addEventListener("show.bs.modal", function (e) {
    if (e.relatedTarget.classList.contains("edit-item-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Edit Lead";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Update";
    } else if (e.relatedTarget.classList.contains("add-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Add Lead";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Add Lead";
    } else {
        document.getElementById("exampleModalLabel").innerHTML = "List Lead";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "none";
    }
});
ischeckboxcheck();

document.getElementById("showModal").addEventListener("hidden.bs.modal", function () {
    clearFields();
});

document.querySelector("#leadsList").addEventListener("click", function () {
    ischeckboxcheck();
});

var table = document.getElementById("customerTable");
// save all tr
var tr = table.getElementsByTagName("tr");
var trlist = table.querySelectorAll(".list tr");

var count = 11;
// multiple Remove CancelButton
var tagInputField = new Choices('#taginput-choices', {
    removeItemButton: true,
  }
);

var tagInputFieldValue = tagInputField.getValue(true);
var tagHtmlValue = '';
Array.from(tagInputFieldValue).forEach((tag, index) => {
  tagHtmlValue += '<span class="badge badge-soft-primary me-1">'+tag+'</span>'
})

var forms = document.querySelectorAll('.tablelist-form')
Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            if (
                leadNameField.value !== "" &&
                company_nameField.value !== "" &&
                dateField.value !== "" &&
                leads_scoreField.value !== "" &&
                phoneField.value !== "" &&
                locationField.value !== "" && !editlist) {
                var tagInputFieldValue = tagInputField.getValue(true);
                var tagHtmlValue = '';
                Array.from(tagInputFieldValue).forEach((tag, index) => {
                    tagHtmlValue += '<span class="badge badge-soft-primary me-1">' + tag + '</span>'
                })
                leadsList.add({
                    id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ'+count+"</a>",
                    image_src: leadImg.src,
                    name: leadNameField.value,
                    company_name: company_nameField.value,
                    date: formatDate(dateField.value),
                    leads_score: leads_scoreField.value,
                    phone: phoneField.value,
                    location: locationField.value,
                    tags: tagHtmlValue,
                });
                leadsList.sort('id', { order: "desc" });
                document.getElementById("close-modal").click();
                clearFields();
                refreshCallbacks();
                count++;
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Lead inserted successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true
                });
            }else if(
                leadNameField.value !== "" &&
                company_nameField.value !== "" &&
                dateField.value !== "" &&
                leads_scoreField.value !== "" &&
                phoneField.value !== "" &&
                locationField.value !== "" && editlist) {
                    var editValues = leadsList.get({
                        id: idField.value,
                    });
                    Array.from(editValues).forEach(function (x) {
                        isid = new DOMParser().parseFromString(x._values.id, "text/html");
                        var selectedid = isid.body.firstElementChild.innerHTML;
                        var tagInputFieldValue = tagInputField.getValue(true);
                        var tagHtmlValue = '';
                        Array.from(tagInputFieldValue).forEach((tag, index) => {
                            tagHtmlValue += '<span class="badge badge-soft-primary me-1">' + tag + '</span>'
                        })
                        if (selectedid == itemId) {
                            x.values({
                                id: '<a href="javascript:void(0);" class="fw-medium link-primary">'+idField.value+"</a>",
                                image_src: leadImg.src,
                                name: leadNameField.value,
                                company_name: company_nameField.value,
                                date: formatDate(dateField.value),
                                leads_score: leads_scoreField.value,
                                phone: phoneField.value,
                                tags: tagHtmlValue,
                                location: locationField.value,
                            });
                        }
                    });
                    document.getElementById("close-modal").click();
                    clearFields();
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Lead updated Successfully!',
                        showConfirmButton: false,
                        timer: 2000,
                        showCloseButton: true
                    });
            }
        }
    }, false)
})


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
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block': document.getElementById("remove-actions").style.display = 'none';
            } else {
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block': document.getElementById("remove-actions").style.display = 'none';
            }
        });
    });
}

function refreshCallbacks() {
    if(removeBtns){
        Array.from(removeBtns).forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.target.closest("tr").children[1].innerText;
                itemId = e.target.closest("tr").children[1].innerText;
                var itemValues = leadsList.get({
                    id: itemId,
                });
    
                Array.from(itemValues).forEach(function (x) {
                    deleteid = new DOMParser().parseFromString(x._values.id, "text/html");
    
                    var isElem = deleteid.body.firstElementChild;
                    var isdeleteid = deleteid.body.firstElementChild.innerHTML;
    
                    if (isdeleteid == itemId) {
                        document.getElementById("delete-record").addEventListener("click", function () {
                            leadsList.remove("id", isElem.outerHTML);
                            document.getElementById("deleteRecord-close").click();
                        });
                    }
                });
            });
        });
    }

    if(editBtns){
        Array.from(editBtns).forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.target.closest("tr").children[1].innerText;
                itemId = e.target.closest("tr").children[1].innerText;
                var itemValues = leadsList.get({
                    id: itemId,
                });
    
                Array.from(itemValues).forEach(function (x) {
                    isid = new DOMParser().parseFromString(x._values.id, "text/html");
                    var selectedid = isid.body.firstElementChild.innerHTML;
                    var tagBadge = new DOMParser().parseFromString(x._values.tags, "text/html").body.querySelectorAll("span.badge");
                    if (selectedid == itemId) {
                        editlist = true;
                        idField.value = selectedid;
                        leadImg.src = x._values.image_src;
                        leadNameField.value = x._values.name;
                        company_nameField.value = x._values.company_name;
                        dateField.value = x._values.date;
                        leads_scoreField.value = x._values.leads_score;
                        phoneField.value = x._values.phone;
                        if(tagBadge){
                            Array.from(tagBadge).forEach((item) => {
                                tagInputField.setChoiceByValue(item.innerHTML);
                            })
                        }
                        locationField.value = x._values.location;
                        flatpickr("#date-field", {
                            dateFormat: "d M, Y",
                            defaultDate: x._values.date,
                        });
                    }
                });
            });
        });
    }
}

function clearFields() {
    leadImg.src = "assets/images/users/user-dummy-img.jpg";
    leadNameField.value = "";
    company_nameField.value = "";
    dateField.value = "";
    leads_scoreField.value = "";
    phoneField.value = "";
    locationField.value = "";
    tagInputField.removeActiveItems();
    tagInputField.setChoiceByValue("0");
}

function deleteMultiple(){
    ids_array = [];
    var items = document.getElementsByName('chk_child');
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
                    leadsList.remove("id", `<a href="javascript:void(0);" class="fw-medium link-primary">${ids_array[i]}</a>`);
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

document.querySelector(".pagination-next").addEventListener("click", function () {
    (document.querySelector(".pagination.listjs-pagination")) ? (document.querySelector(".pagination.listjs-pagination").querySelector(".active")) ?
    document.querySelector(".pagination.listjs-pagination").querySelector(".active").nextElementSibling.children[0].click(): '': '';
});

document.querySelector(".pagination-prev").addEventListener("click", function () {
    (document.querySelector(".pagination.listjs-pagination")) ? (document.querySelector(".pagination.listjs-pagination").querySelector(".active")) ?
    document.querySelector(".pagination.listjs-pagination").querySelector(".active").previousSibling.children[0].click(): '': '';
});