/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Tasks-list init js
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
var perPage = 8;
var editlist = false;

//Table
var options = {
    valueNames: [
        "id",
        "project_name",
        "tasks_name",
        "client_name",
        "assignedto",
        "due_date",
        "status",
        "priority",
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
var tasksList = new List("tasksList", options).on("updated", function (list) {
    list.matchingItems.length == 0 ?
        (document.getElementsByClassName("noresult")[0].style.display = "block") :
        (document.getElementsByClassName("noresult")[0].style.display = "none");
    var isFirst = list.i == 1;
    var isLast = list.i > list.matchingItems.length - list.page;
    // make the Prev and Nex buttons disabled on first and last pages accordingly
    document.querySelector(".pagination-prev.disabled") ?
        document.querySelector(".pagination-prev.disabled").classList.remove("disabled") : "";
    document.querySelector(".pagination-next.disabled") ?
        document.querySelector(".pagination-next.disabled").classList.remove("disabled") : "";
    if (isFirst)
        document.querySelector(".pagination-prev").classList.add("disabled");
    if (isLast)
        document.querySelector(".pagination-next").classList.add("disabled");
    if (list.matchingItems.length <= perPage)
        document.querySelector(".pagination-wrap").style.display = "none";
    else
        document.querySelector(".pagination-wrap").style.display = "flex";
    if (list.matchingItems.length == perPage)
        document.querySelector(".pagination.listjs-pagination").firstElementChild.children[0].click()
    if (list.matchingItems.length > 0)
        document.getElementsByClassName("noresult")[0].style.display = "none";
    else
        document.getElementsByClassName("noresult")[0].style.display = "block";
});

const xhttp = new XMLHttpRequest();
xhttp.onload = function () {
    var json_records = JSON.parse(this.responseText);
    Array.from(json_records).forEach(function (raw) {

        var assignedElem = raw.assignedto;
        var showElem = 3;
        var imgHtml = '<div class="avatar-group flex-nowrap">';
        Array.from(assignedElem.slice(0, showElem)).forEach(function (img) {
            imgHtml += '<a href="javascript: void(0);" class="avatar-group-item" data-img="' + img.assigneeImg + '"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+img.assigneeName+'">\
                <img src="'+ img.assigneeImg + '" alt="" class="rounded-circle avatar-xxs" />\
            </a>';
        });
        if(assignedElem.length > showElem){
            var elemLength = assignedElem.length - showElem;
            imgHtml += '<a href="javascript: void(0);" class="avatar-group-item"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+elemLength+' More">\
            <div class="avatar-xxs">\
            <div class="avatar-title rounded-circle">'+elemLength+'+</div>\
            </div>\
        </a>'
        }
        imgHtml += '</div>';

        tasksList.add({
            id: '<a href="apps-tasks-details.html" class="fw-medium link-primary">#VLZ' + raw.id + "</a>",
            project_name: '<a href="apps-projects-overview.html" class="fw-medium link-primary">' + raw.project_name + "</a>",
            tasks_name: raw.tasks_name,
            client_name: raw.client_name,
            assignedto: imgHtml,
            due_date: raw.due_date,
            status: isStatus(raw.status),
            priority: isPriority(raw.priority)
        });
        tasksList.sort('id', { order: "desc" });
        refreshCallbacks();
        tooltipElm();
    });
    tasksList.remove("id", `<a href="apps-tasks-details.html" class="fw-medium link-primary">#VLZ501</a>`);
}
xhttp.open("GET", "assets/json/tasks-list.json");
xhttp.send();

isCount = new DOMParser().parseFromString(
    tasksList.items.slice(-1)[0]._values.id,
    "text/html"
);

var isValue = isCount.body.firstElementChild.innerHTML;

var idField = document.getElementById("tasksId"),
    projectNameField = document.getElementById("projectName-field"),
    tasksTitleField = document.getElementById("tasksTitle-field"),
    clientNameField = document.getElementById("clientName-field"),
    assignedtoNameField = 'Demo Assign', //document.getElementById("assignedtoName-field"),
    dateDueField = document.getElementById("duedate-field"),
    priorityField = document.getElementById("priority-field"),
    statusField = document.getElementById("ticket-status"),
    addBtn = document.getElementById("add-btn"),
    editBtn = document.getElementById("edit-btn"),
    removeBtns = document.getElementsByClassName("remove-item-btn"),
    editBtns = document.getElementsByClassName("edit-item-btn");
refreshCallbacks();
//filterOrder("All");

function tooltipElm(){
    var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

function filterOrder(isValue) {
    var values_status = isValue;
    tasksList.filter(function (data) {
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
    tasksList.update();
}

function updateList() {
    var values_status = document.querySelector(
        "input[name=status]:checked"
    ).value;

    data = userList.filter(function (item) {
        var statusFilter = false;

        if (values_status == "All") {
            statusFilter = true;
        } else {
            statusFilter = item.values().sts == values_status;
        }
        return statusFilter;
    });

    userList.update();
}

document.getElementById("showModal").addEventListener("show.bs.modal", function (e) {
    if (e.relatedTarget.classList.contains("edit-item-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Edit Task";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Update";
    } else if (e.relatedTarget.classList.contains("add-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Add Task";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Add Task";
    } else {
        document.getElementById("exampleModalLabel").innerHTML = "List Task";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "none";
    }
});

document.getElementById("showModal").addEventListener("hidden.bs.modal", function () {
    clearFields();
});

document.querySelector("#tasksList").addEventListener("click", function () {
    ischeckboxcheck();
});

var table = document.getElementById("tasksTable");
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
                projectNameField.value !== "" &&
                tasksTitleField.value !== "" &&
                clientNameField.value !== "" &&
                dateDueField.value !== "" &&
                priorityField.value !== "" &&
                statusField.value !== "" && !editlist
            ) {
                tasksList.add({
                    id: '<a href="apps-tasks-details.html" class="fw-medium link-primary">#VLZ'+count+"</a>",
                    project_name: '<a href="apps-projects-overview.html" class="fw-medium link-primary">'+projectNameField.value+"</a>",
                    tasks_name: tasksTitleField.value,
                    client_name: clientNameField.value,
                    assignedto: assignToUsers(),
                    due_date: fomateDate(dateDueField.value),
                    status: isStatus(statusField.value),
                    priority: isPriority(priorityField.value)
                });
                tasksList.sort('id', { order: "desc" });
                document.getElementById("close-modal").click();
                clearFields();
                refreshCallbacks();
                count++;
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Task inserted successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true
                });
            } else if (
                projectNameField.value !== "" &&
                tasksTitleField.value !== "" &&
                clientNameField.value !== "" &&
                dateDueField.value !== "" &&
                priorityField.value !== "" &&
                statusField.value !== "" && editlist
            ) {
                var editValues = tasksList.get({
                    id: idField.value,
                });
                Array.from(editValues).forEach(function (x) {
                    isid = new DOMParser().parseFromString(x._values.id, "text/html");
                    var selectedid = isid.body.firstElementChild.innerHTML;
                    if (selectedid == itemId) {
                        x.values({
                            id: '<a href="javascript:void(0);" class="fw-medium link-primary">'+idField.value+"</a>",
                            project_name: '<a href="apps-projects-overview.html" class="fw-medium link-primary">' +projectNameField.value+"</a>",
                            tasks_name: tasksTitleField.value,
                            client_name: clientNameField.value,
                            assignedto: assignToUsers(),
                            due_date: fomateDate(dateDueField.value),
                            status: isStatus(statusField.value),
                            priority: isPriority(priorityField.value)
                        });
                    }
                });
                document.getElementById("close-modal").click();
                clearFields();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Task updated Successfully!',
                    showConfirmButton: false,
                    timer: 2000,
                    showCloseButton: true
                });
            }
        }
    }, false)
})


var example = new Choices(priorityField, {
    searchEnabled: false,
});

var statusVal = new Choices(statusField, {
    searchEnabled: false,
});

function SearchData() {
    var isstatus = document.getElementById("idStatus").value;
    var pickerVal = document.getElementById("demo-datepicker").value;

    var date1 = pickerVal.split(" to ")[0];
    var date2 = pickerVal.split(" to ")[1];

    tasksList.filter(function (data) {
        matchData = new DOMParser().parseFromString(
            data.values().status,
            "text/html"
        );
        var status = matchData.body.firstElementChild.innerHTML;
        var statusFilter = false;
        var dateFilter = false;

        if (status == "all" || isstatus == "all") {
            statusFilter = true;
        } else {
            statusFilter = status == isstatus;
        }

        if (
            new Date(data.values().due_date.slice(0, 12)) >= new Date(date1) &&
            new Date(data.values().due_date.slice(0, 12)) <= new Date(date2)
        ) {
            dateFilter = true;
        } else {
            dateFilter = false;
        }

        if (statusFilter && dateFilter) {
            return statusFilter && dateFilter;
        } else if (statusFilter && pickerVal == "") {
            return statusFilter;
        } else if (dateFilter && pickerVal == "") {
            return dateFilter;
        }
    });
    tasksList.update();
}

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
    if (removeBtns){
        Array.from(removeBtns).forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.target.closest("tr").children[1].innerText;
                itemId = e.target.closest("tr").children[1].innerText;
                var itemValues = tasksList.get({
                    id: itemId,
                });
    
                Array.from(itemValues).forEach(function (x) {
                    deleteid = new DOMParser().parseFromString(x._values.id, "text/html");
                    var isElem = deleteid.body.firstElementChild;
                    var isdeleteid = deleteid.body.firstElementChild.innerHTML;
    
                    if (isdeleteid == itemId) {
                        document.getElementById("delete-record").addEventListener("click", function () {
                            tasksList.remove("id", isElem.outerHTML);
                            document.getElementById("deleteRecord-close").click();
                        });
                    }
                });
            });
        });
    }
    
    if (editBtns){
    Array.from(editBtns).forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.target.closest("tr").children[1].innerText;
            itemId = e.target.closest("tr").children[1].innerText;
            var itemValues = tasksList.get({
                id: itemId,
            });

            Array.from(itemValues).forEach(function (x) {
                isid = new DOMParser().parseFromString(x._values.id, "text/html");
                var selectedid = isid.body.firstElementChild.innerHTML;
                if (selectedid == itemId) {
                    editlist = true;
                    idField.value = selectedid;

                    project = new DOMParser().parseFromString(x._values.project_name, "text/html");
                    var projectName = project.body.firstElementChild.innerHTML;
                    statusVal.setChoiceByValue(statusSelec);

                    projectNameField.value = projectName;
                    tasksTitleField.value = x._values.tasks_name;
                    clientNameField.value = x._values.client_name;
                    dateDueField.value = x._values.due_date;

                    Array.from(document.querySelectorAll('input[name="assignedTo[]"]')).forEach(function (subElem) {
                        var checkedElem = subElem.parentElement;
                        var nameelem = checkedElem.querySelector(".flex-grow-1").innerHTML;

                        var assignElem = new DOMParser().parseFromString(x._values.assignedto, "text/html");
                        assignElem.querySelectorAll(".avatar-group .avatar-group-item").forEach(function(item){
                            if(item.getAttribute('data-bs-title') == nameelem){
                                subElem.setAttribute("checked", "checked");
                            };
                        });
                    });

                    if (statusVal) statusVal.destroy();
                    statusVal = new Choices(statusField, {
                        searchEnabled: false
                    });
                    val = new DOMParser().parseFromString(x._values.status, "text/html");
                    var statusSelec = val.body.firstElementChild.innerHTML;
                    statusVal.setChoiceByValue(statusSelec);

                    if (example) example.destroy();
                    example = new Choices(priorityField, {
                        searchEnabled: false
                    });
                    val = new DOMParser().parseFromString(x._values.priority, "text/html");
                    var selected = val.body.firstElementChild.innerHTML;
                    example.setChoiceByValue(selected);
                    //priorityField.value = x._values.priority;

                    flatpickr("#duedate-field", {
                        dateFormat: "d M, Y",
                        defaultDate: x._values.due_date,
                    });
                }
            });
        });
    });
}

}

function clearFields() {
    projectNameField.value = "";
    tasksTitleField.value = "";
    clientNameField.value = "";
    assignedtoNameField.value = "";
    dateDueField.value = "";

    document.querySelectorAll('input[name="assignedTo[]"]').forEach(function(item){
        item.removeAttribute("checked");
    });

    if (example)
        example.destroy();
    example = new Choices(priorityField);

    if (statusVal)
        statusVal.destroy();
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

function isStatus(val) {
    switch (val) {
        case "Pending":
            return ('<span class="badge badge-soft-warning text-uppercase">' + val + "</span>");
        case "Inprogress":
            return ('<span class="badge badge-soft-secondary text-uppercase">' + val + "</span>");
        case "Completed":
            return ('<span class="badge badge-soft-success text-uppercase">' + val + "</span>");
        case "New":
            return ('<span class="badge badge-soft-info text-uppercase">' + val + "</span>");
    }
}

function isPriority(val) {
    switch (val) {
        case "High":
            return ('<span class="badge bg-danger text-uppercase">' + val + "</span>");
        case "Low":
            return ('<span class="badge bg-success text-uppercase">' + val + "</span>");
        case "Medium":
            return ('<span class="badge bg-warning text-uppercase">' + val + "</span>");
    }
}

function fomateDate(date) {
    var dateObj = new Date(date);
    var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][dateObj.getMonth()];
    return dateObj.getDate() + ' ' + month + ', ' + dateObj.getFullYear();
}

function assignToUsers() {
    var assignedtousers = [];
    var assignedTo =  document.querySelectorAll('input[name="assignedTo[]"]:checked');
    Array.from(assignedTo).forEach(function (ele) {
        var checkedElem = ele.parentElement;
        var imgpath = checkedElem.querySelector(".avatar-xxs").getAttribute('src');
        var namepath = checkedElem.querySelector(".flex-grow-1").innerHTML;
        var obj = {};
        obj["assigneeName"] = namepath
        obj["assigneeImg"] = imgpath
        assignedtousers.push(obj);
    });

    var assignedElem = assignedtousers;
    var showElem = 3;
    var imgHtml = '<div class="avatar-group flex-nowrap">';
    Array.from(assignedElem.slice(0, showElem)).forEach(function (img) {
        imgHtml += '<a href="javascript: void(0);" class="avatar-group-item" data-img="' + img.assigneeImg + '"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+img.assigneeName+'">\
            <img src="'+ img.assigneeImg + '" alt="" class="rounded-circle avatar-xxs" />\
        </a>';
    });
    if(assignedElem.length > showElem){
        var elemLength = assignedElem.length - showElem;
        imgHtml += '<a href="javascript: void(0);" class="avatar-group-item"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+elemLength+' More">\
        <div class="avatar-xxs">\
        <div class="avatar-title rounded-circle">'+elemLength+'+</div>\
        </div>\
    </a>'
    }
    imgHtml += '</div>';
    return imgHtml;
}

function deleteMultiple() {
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
                    tasksList.remove("id", `<a href="apps-tasks-details.html" class="fw-medium link-primary">${ids_array[i]}</a>`);
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
        document.getElementById('checkAll').checked = false;
    } else {
        Swal.fire({
            title: 'Please select at least one checkbox',
            confirmButtonClass: 'btn btn-info',
            buttonsStyling: false,
            showCloseButton: true
        });
    }
}