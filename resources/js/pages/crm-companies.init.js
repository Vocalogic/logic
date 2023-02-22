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
var editlist = false;

//Table
var options = {
    valueNames: [
        "id",
        "name",
        "owner",
        "industry_type",
        "star_value",
        "location",
        "employee",
        "website",
        "contact_email",
        "since",
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
var companyList = new List("companyList", options).on("updated", function (list) {
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
        companyList.add({
            id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ' + raw.id + "</a>",
            name: raw.name,
            owner: raw.owner,
            desc: raw.desc,
            industry_type: raw.industry_type,
            star_value: raw.star_value,
            location: raw.location,
            employee: raw.employee,
            website: raw.website,
            contact_email: raw.contact_email,
            since: raw.since,
            image_src: raw.image_src
        });
        companyList.sort('id', { order: "desc" });
        refreshCallbacks();
    });
    companyList.remove("id", `<a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>`);
}
xhttp.open("GET", "assets/json/company-list.json");
xhttp.send();

isCount = new DOMParser().parseFromString(
    companyList.items.slice(-1)[0]._values.id,
    "text/html"
);

// customer image
document.querySelector("#company-logo-input").addEventListener("change", function () {
    var preview = document.querySelector("#companylogo-img");
    var file = document.querySelector("#company-logo-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load",function () {
        preview.src = reader.result;
    },false);
    if (file) {
        reader.readAsDataURL(file);
    }
});

var isValue = isCount.body.firstElementChild.innerHTML;

var idField = document.getElementById("id-field"),
    companyNameField = document.getElementById("companyname-field"),
    companyLogoImg = document.getElementById("companylogo-img"),
    ownerField = document.getElementById("owner-field"),
    industry_typeField = document.getElementById("industry_type-field"),
    star_valueField = document.getElementById("star_value-field"),
    locationField = document.getElementById("location-field"),
    employeeField = document.getElementById("employee-field"),
    websiteField = document.getElementById("website-field"),
    contact_emailField = document.getElementById("contact_email-field"),
    sinceField = document.getElementById("since-field"),

    addBtn = document.getElementById("add-btn"),
    editBtn = document.getElementById("edit-btn"),
    removeBtns = document.getElementsByClassName("remove-item-btn"),
    editBtns = document.getElementsByClassName("edit-item-btn");
    viewBtns = document.getElementsByClassName("view-item-btn");
refreshCallbacks();

document.getElementById("showModal").addEventListener("show.bs.modal", function (e) {
    if (e.relatedTarget.classList.contains("edit-item-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Edit Company";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Update";
    } else if (e.relatedTarget.classList.contains("add-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Add Company";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Add Company";
    } else {
        document.getElementById("exampleModalLabel").innerHTML = "List Company";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "none";
    }
});
ischeckboxcheck();

document.getElementById("showModal").addEventListener("hidden.bs.modal", function () {
    clearFields();
});

document.querySelector("#companyList").addEventListener("click", function () {
    ischeckboxcheck();
});

var table = document.getElementById("customerTable");
// save all tr
var tr = table.getElementsByTagName("tr");
var trlist = table.querySelectorAll(".list tr");

var count = 11;
var forms = document.querySelectorAll('.tablelist-form')
Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            if (
                companyNameField.value !== "" &&
                ownerField.value !== "" &&
                industry_typeField.value !== "" &&
                star_valueField.value !== "" &&
                locationField.value !== "" &&
                employeeField.value !== "" &&
                websiteField.value !== "" &&
                contact_emailField.value !== "" &&
                sinceField.value !== "" && !editlist) {
                companyList.add({
                    id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ' + count + "</a>",
                    image_src: companyLogoImg.src,
                    name: companyNameField.value,
                    owner: ownerField.value,
                    industry_type: industry_typeField.value,
                    star_value: star_valueField.value,
                    location: locationField.value,
                    employee: employeeField.value,
                    website: websiteField.value,
                    contact_email: contact_emailField.value,
                    since: sinceField.value
                    
                });
                companyList.sort('id', { order: "desc" });
                document.getElementById("close-modal").click();
                clearFields();
                refreshCallbacks();
                count++;
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Company inserted successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true
                });
            }else if(
                companyNameField.value !== "" &&
                ownerField.value !== "" &&
                industry_typeField.value !== "" &&
                star_valueField.value !== "" &&
                locationField.value !== "" &&
                employeeField.value !== "" &&
                websiteField.value !== "" &&
                contact_emailField.value !== "" &&
                sinceField.value !== "" && editlist) {
                    var editValues = companyList.get({
                        id: idField.value,
                    });
                    Array.from(editValues).forEach(function (x) {
                        isid = new DOMParser().parseFromString(x._values.id, "text/html");
                        var selectedid = isid.body.firstElementChild.innerHTML;
                        if (selectedid == itemId) {
                            x.values({
                                id: `<a href="javascript:void(0);" class="fw-medium link-primary">${idField.value}</a>`,
                                image_src: companyLogoImg.src,
                                name: companyNameField.value,
                                owner: ownerField.value,
                                industry_type: industry_typeField.value,
                                star_value: star_valueField.value,
                                location: locationField.value,
                                employee: employeeField.value,
                                website: websiteField.value,
                                contact_email: contact_emailField.value,
                                since: sinceField.value
                            });
                        }
                    });
                    document.getElementById("close-modal").click();
                    clearFields();
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Company updated Successfully!',
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
                var itemValues = companyList.get({
                    id: itemId,
                });
    
                Array.from(itemValues).forEach(function (x) {
                    deleteid = new DOMParser().parseFromString(x._values.id, "text/html");
    
                    var isElem = deleteid.body.firstElementChild;
                    var isdeleteid = deleteid.body.firstElementChild.innerHTML;
    
                    if (isdeleteid == itemId) {
                        document.getElementById("delete-record").addEventListener("click", function () {
                            companyList.remove("id", isElem.outerHTML);
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
                var itemValues = companyList.get({
                    id: itemId,
                });
    
                Array.from(itemValues).forEach(function (x) {
                    isid = new DOMParser().parseFromString(x._values.id, "text/html");
                    var selectedid = isid.body.firstElementChild.innerHTML;
                    if (selectedid == itemId) {
                        editlist = true;
                        idField.value = selectedid;
                        companyLogoImg.src = x._values.image_src;
                        companyNameField.value = x._values.name;
                        ownerField.value = x._values.owner;
                        industry_typeField.value = x._values.industry_type;
                        star_valueField.value = x._values.star_value;
                        locationField.value = x._values.location;
                        employeeField.value = x._values.employee;
                        websiteField.value = x._values.website;
                        contact_emailField.value = x._values.contact_email;
                        sinceField.value = x._values.since;
                    }
                });
            });
        });
    }

    Array.from(viewBtns).forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.target.closest("tr").children[1].innerText;
            itemId = e.target.closest("tr").children[1].innerText;
            var itemValues = companyList.get({
                id: itemId,
            });

            Array.from(itemValues).forEach(function (x) {
                isid = new DOMParser().parseFromString(x._values.id, "text/html");
                var selectedid = isid.body.firstElementChild.innerHTML;
                if (selectedid == itemId) {
                    var codeBlock = `
                        <div class="card-body text-center">
                            <div class="position-relative d-inline-block">
                                <div class="avatar-md">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <img src="${x._values.image_src}" alt="" class="avatar-sm rounded-circle object-cover">
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-3 mb-1">${x._values.name}</h5>
                            <p class="text-muted">${x._values.owner}</p>

                            <ul class="list-inline mb-0">
                                <li class="list-inline-item avatar-xs">
                                    <a href="javascript:void(0);"
                                        class="avatar-title bg-soft-success text-success fs-15 rounded">
                                        <i class="ri-global-line"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item avatar-xs">
                                    <a href="javascript:void(0);"
                                        class="avatar-title bg-soft-danger text-danger fs-15 rounded">
                                        <i class="ri-mail-line"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item avatar-xs">
                                    <a href="javascript:void(0);"
                                        class="avatar-title bg-soft-warning text-warning fs-15 rounded">
                                        <i class="ri-question-answer-line"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fw-semibold mb-3">Information</h6>
                            <p class="text-muted mb-4">${x._values.desc}</p>
                            <div class="table-responsive table-card">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium" scope="row">Industry Type</td>
                                            <td>${x._values.industry_type}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium" scope="row">Location</td>
                                            <td>${x._values.location}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium" scope="row">Employee</td>
                                            <td>${x._values.employee}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium" scope="row">Rating</td>
                                            <td>${x._values.star_value} <i class="ri-star-fill text-warning align-bottom"></i></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium" scope="row">Website</td>
                                            <td>
                                                <a href="javascript:void(0);"
                                                    class="link-primary text-decoration-underline">${x._values.website}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium" scope="row">Contact Email</td>
                                            <td>${x._values.contact_email}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium" scope="row">Since</td>
                                            <td>${x._values.since}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                    document.getElementById('company-view-detail').innerHTML = codeBlock;
                }
            });
        });
    });
}

function clearFields() {
    companyLogoImg.src = "assets/images/users/multi-user.jpg";
    companyNameField.value = "";
    ownerField.value = "";
    industry_typeField.value = "";
    star_valueField.value = "";
    locationField.value = "";
    employeeField.value = "";
    websiteField.value = "";
    contact_emailField.value = "";
    sinceField.value = "";
}

// Delete Multiple Records
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
                    companyList.remove("id", `<a href="javascript:void(0);" class="fw-medium link-primary">${ids_array[i]}</a>`);
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