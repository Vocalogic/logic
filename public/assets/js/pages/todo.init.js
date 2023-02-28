/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: todo init js
*/

var todoList = [{
    'id': '1',
    "checkedElem": false,
    'todo': 'Added Email Templates',
    "assignedto": [{
            "assigneeName": "Curtis Saenz",
            "assigneeImg": "assets/images/users/avatar-1.jpg",
        },{
            "assigneeName": "John Robles",
            "assigneeImg": "assets/images/users/avatar-3.jpg",
        }
    ],
    'dueDate': '03 Apr, 2022',
    'status': 'Inprogress',
    "priority": "High"
}, {
    'id': '2',
    "checkedElem": false,
    'todo': 'Additional Mailbox',
    "assignedto": [{
            "assigneeName": "Virgie Price",
            "assigneeImg": "assets/images/users/avatar-5.jpg",
        },{
            "assigneeName": "Diego Norris",
            "assigneeImg": "assets/images/users/avatar-9.jpg",
        },{
            "assigneeName": "Anthony Mills",
            "assigneeImg": "assets/images/users/avatar-10.jpg",
        }
    ],
    'dueDate': '02 Apr, 2022',
    'status': 'Pending',
    "priority": "Medium"
}, {
    'id': '3',
    "checkedElem": true,
    'todo': 'Make a creating an account profile',
    "assignedto": [{
            "assigneeName": "Virgie Price",
            "assigneeImg": "assets/images/users/avatar-5.jpg",
        },{
            "assigneeName": "Marian Angel",
            "assigneeImg": "assets/images/users/avatar-6.jpg",
        },{
            "assigneeName": "Johnnie Walton",
            "assigneeImg": "assets/images/users/avatar-7.jpg",
        },{
            "assigneeName": "Donna Weston",
            "assigneeImg": "assets/images/users/avatar-8.jpg",
        }
    ],
    'dueDate': '02 May, 2022',
    'status': 'Completed',
    "priority": "Low"
}, {
    'id': '4',
    "checkedElem": false,
    'todo': 'Added new tabs styles',
    "assignedto": [{
            "assigneeName": "James Forbes",
            "assigneeImg": "assets/images/users/avatar-2.jpg",
        }
    ],
    'dueDate': '01 May, 2022',
    'status': 'New',
    "priority": "Low"
}, {
    'id': '5',
    "checkedElem": false,
    'todo': 'Added bdge new style - gradient',
    "assignedto": [{
            "assigneeName": "John Robles",
            "assigneeImg": "assets/images/users/avatar-3.jpg",
        },{
            "assigneeName": "Anthony Mills",
            "assigneeImg": "assets/images/users/avatar-10.jpg",
        },{
            "assigneeName": "Diego Norris",
            "assigneeImg": "assets/images/users/avatar-9.jpg",
        }
    ],
    'dueDate': '01 May, 2022',
    'status': 'Inprogress',
    "priority": "Medium"
}, {
    'id': '6',
    "checkedElem": false,
    'todo': 'Added Back to Top button',
    "assignedto": [{
            "assigneeName": "Marian Angel",
            "assigneeImg": "assets/images/users/avatar-6.jpg",
        },{
            "assigneeName": "Johnnie Walton",
            "assigneeImg": "assets/images/users/avatar-7.jpg",
        }
    ],
    'dueDate': '30 Apr, 2022',
    'status': 'Inprogress',
    "priority": "High"
}, {
    'id': '7',
    "checkedElem": true,
    'todo': 'Added File Manager Apps',
    "assignedto": [{
            "assigneeName": "John Robles",
            "assigneeImg": "assets/images/users/avatar-3.jpg",
        },{
            "assigneeName": "Mary Gant",
            "assigneeImg": "assets/images/users/avatar-4.jpg",
        },{
            "assigneeName": "Virgie Price",
            "assigneeImg": "assets/images/users/avatar-5.jpg",
        }
    ],
    'dueDate': '29 Apr, 2022',
    'status': 'Completed',
    "priority": "Medium"
}, {
    'id': '8',
    "checkedElem": false,
    'todo': 'Datatable with jQuery cdn',
    "assignedto": [{
            "assigneeName": "Marian Angel",
            "assigneeImg": "assets/images/users/avatar-6.jpg",
        },{
            "assigneeName": "Johnnie Walton",
            "assigneeImg": "assets/images/users/avatar-7.jpg",
        },{
            "assigneeName": "Donna Weston",
            "assigneeImg": "assets/images/users/avatar-8.jpg",
        }
    ],
    'dueDate': '28 Apr, 2022',
    'status': 'Pending',
    "priority": "High"
}, {
    'id': '9',
    "checkedElem": false,
    'todo': 'Profile Page Structure',
    "assignedto": [{
            "assigneeName": "Mary Gant",
            "assigneeImg": "assets/images/users/avatar-4.jpg",
        },{
            "assigneeName": "Virgie Price",
            "assigneeImg": "assets/images/users/avatar-5.jpg",
        }
    ],
    'dueDate': '27 Apr, 2022',
    'status': 'New',
    "priority": "Low"
}, {
    'id': '10',
    "checkedElem": true,
    'todo': 'Make a creating an account profile',
    "assignedto": [{
            "assigneeName": "John Robles",
            "assigneeImg": "assets/images/users/avatar-3.jpg",
        }
    ],
    'dueDate': '26 Apr, 2022',
    'status': 'Completed',
    "priority": "Medium"
}, {
    'id': '11',
    "checkedElem": true,
    'todo': 'Change email option process',
    "assignedto": [{
            "assigneeName": "John Robles",
            "assigneeImg": "assets/images/users/avatar-3.jpg",
        },{
            "assigneeName": "Anthony Mills",
            "assigneeImg": "assets/images/users/avatar-10.jpg",
        },{
            "assigneeName": "Diego Norris",
            "assigneeImg": "assets/images/users/avatar-9.jpg",
        }
    ],
    'dueDate': '25 Apr, 2022',
    'status': 'Completed',
    "priority": "High"
}, {
    'id': '12',
    "checkedElem": false,
    'todo': 'Brand Logo design',
    "assignedto": [{
            "assigneeName": "James Forbes",
            "assigneeImg": "assets/images/users/avatar-2.jpg",
        },{
            "assigneeName": "Anthony Mills",
            "assigneeImg": "assets/images/users/avatar-10.jpg",
        },{
            "assigneeName": "Diego Norris",
            "assigneeImg": "assets/images/users/avatar-9.jpg",
        }
    ],
    'dueDate': '25 Apr, 2022',
    'status': 'New',
    "priority": "Medium",
}, {
    'id': '13',
    "checkedElem": false,
    'todo': 'Add Dynamic Contact List',
    "assignedto": [{
        "assigneeName": "Virgie Price",
        "assigneeImg": "assets/images/users/avatar-5.jpg",
    },{
        "assigneeName": "Marian Angel",
        "assigneeImg": "assets/images/users/avatar-6.jpg",
    },{
        "assigneeName": "Johnnie Walton",
        "assigneeImg": "assets/images/users/avatar-7.jpg",
    },{
        "assigneeName": "Donna Weston",
        "assigneeImg": "assets/images/users/avatar-8.jpg",
    }
],
    'dueDate': '24 Apr, 2022',
    'status': 'Inprogress',
    "priority": "Low"
}, {
    'id': '14',
    "checkedElem": true,
    'todo': 'Additional Calendar',
    "assignedto": [{
            "assigneeName": "Virgie Price",
            "assigneeImg": "assets/images/users/avatar-5.jpg",
        },{
            "assigneeName": "Diego Norris",
            "assigneeImg": "assets/images/users/avatar-9.jpg",
        },{
            "assigneeName": "Anthony Mills",
            "assigneeImg": "assets/images/users/avatar-10.jpg",
        }
    ],
    'dueDate': '23 Apr, 2022',
    'status': 'Completed',
    "priority": "Medium",
}, {
    'id': '15',
    "checkedElem": false,
    'todo': 'Added Select2',
    "assignedto": [{
            "assigneeName": "Curtis Saenz",
            "assigneeImg": "assets/images/users/avatar-1.jpg",
        },{
            "assigneeName": "John Robles",
            "assigneeImg": "assets/images/users/avatar-3.jpg",
        }
    ],
    'dueDate': '23 Apr, 2022',
    'status': 'Pending',
    "priority": "High"
}];

// add new project
//Create a new folder
var createFolderForms = document.querySelectorAll('.createProject-form')
Array.prototype.slice.call(createFolderForms).forEach(function (form) {
  form.addEventListener('submit', function (event) {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      event.preventDefault();
        var projectName = document.getElementById("projectname-input").value;
        var uniqueid = Math.floor(Math.random() * 100);
        projectlisthtml =
            '<li id="projectlist-' + uniqueid + '">\
        <a data-bs-toggle="collapse" href="#projectCollapse-'+ uniqueid + '" class="nav-link fs-13">' + projectName + '</a>\
        <div class="collapse" id="projectCollapse-'+ uniqueid + '">\
            <ul class="mb-0 sub-menu list-unstyled ps-3 vstack gap-2 mb-2">\
                <li>\
                    <a href="#!"><i class="ri-stop-mini-fill align-middle fs-15 text-danger"></i> v1.0.0</a>\
                </li>\
            </ul>\
        </div>\
    </li>';

        if (projectName !== "") {
            var projectListdata = document.getElementById("projectlist-data");
            projectListdata.insertAdjacentHTML("beforeend", projectlisthtml);
            var addProjectClose = document.getElementById("addProjectBtn-close");
            addProjectClose.click();
        }
    }
    form.classList.add('was-validated');
  }, false)
});

const projectModalEl = document.getElementById('createProjectModal')
projectModalEl.addEventListener('show.bs.modal', event => {
    document.getElementById("projectname-input").value = "";
    document.querySelectorAll(".createProject-form").forEach(function(item){
        item.classList.remove("was-validated");
    })
})

var editList = false;
flatpickr("#task-duedate-input", {
    dateFormat: "d M, Y",
});

Array.from(document.getElementsByClassName("createTask")).forEach(function (elem) {
    elem.addEventListener("click", function () {
        document.getElementById("createTaskLabel").innerHTML = "Create Task";
        document.getElementById("addNewTodo").innerHTML = "Add Task";
        clearFields();
    });
});

statusField = document.getElementById("task-status-input");
var statusVal = new Choices(statusField, {
    searchEnabled: false,
});

priorityField = document.getElementById("priority-field");
var priorityVal = new Choices(priorityField, {
    searchEnabled: false,
});



Array.from(document.getElementsByClassName("select-element")).forEach(function (elem) {
    Array.from(elem.querySelectorAll(".dropdown-menu ul li a")).forEach(function (subElem) {
        subElem.addEventListener("click", function () {
            subElem.classList.toggle("active");
            var assigneeMember = document.querySelectorAll('.select-element .dropdown-menu .dropdown-item.active');
            document.getElementById("total-assignee").innerHTML = assigneeMember.length;

            var imgPath = subElem.querySelector(".avatar-xxs img").getAttribute('src');

            var folderListdata = document.getElementById("assignee-member");
            if(subElem.classList.contains("active")){
                var nameelem = subElem.querySelector(".flex-grow-1").innerHTML;

                folderlisthtml =
                '<a href="javascript: void(0);" class="avatar-group-item mb-2" data-img="'+imgPath+'"  data-bs-toggle="tooltip" data -bs-placement="top" title="'+nameelem+'">\
                <img src="'+imgPath+'" alt="" class="rounded-circle avatar-xs" />\
                </a>';
                folderListdata.insertAdjacentHTML("beforeend", folderlisthtml);
                tooltipElm();
            }else{
                Array.from(folderListdata.querySelectorAll(".avatar-group-item")).forEach(function (item) {
                    var avatarImg = item.getAttribute('data-img');
                    if(imgPath == avatarImg){
                        item.remove();
                    }
                });
            }
        });
    });
});

// add & edit task list
document.getElementById("creattask-form").addEventListener("submit", function (e) {
    e.preventDefault();
    var inputTitle = document.getElementById('task-title-input').value,
        inputDate = document.getElementById('task-duedate-input').value
    var statusInputFieldValue = statusVal.getValue(true);
    var priorityInputFieldValue = priorityVal.getValue(true);

    var assignedtousers = []
    var assignedTo =  document.querySelectorAll('.select-element .dropdown-menu .dropdown-item.active');

    var errorMsg = document.getElementById("task-error-msg");
    errorMsg.style.display = "block";
    
    var text;
    if(inputTitle.length == 0){
      text = "Please enter task name";
      errorMsg.innerHTML = text;
      return false;
    }
    if(assignedTo.length == 0){
        text = "Please select team member";
        errorMsg.innerHTML = text;
        return false;
    }
    if(statusInputFieldValue == ""){
        text = "Please select task status";
        errorMsg.innerHTML = text;
        return false;
    }
    if(priorityInputFieldValue == ""){
        text = "Please select task priority";
        errorMsg.innerHTML = text;
        return false;
    }

    if(inputDate.length == 0){
        text = "Please select due date";
        errorMsg.innerHTML = text;
        return false;
    }
    
    if (assignedTo.length > 0) {
        Array.from(assignedTo).forEach(function (ele) {
            var imgpath = ele.querySelector(".avatar-xxs img").getAttribute('src');
            var namepath = ele.querySelector(".flex-grow-1").innerHTML;
            var obj = {};
            obj["assigneeName"] = namepath
            obj["assigneeImg"] = imgpath
            assignedtousers.push(obj);
        });
    }

    if (inputTitle !== "" && statusInputFieldValue !== "" && priorityInputFieldValue !== "" && !editList) {
        var newTodoId = findNextId(),
            newTodo = {
                'id': newTodoId,
                "checkedElem": false,
                'todo': inputTitle,
                'assignedto': assignedtousers,
                'dueDate': inputDate,
                'status': statusInputFieldValue,
                'priority': priorityInputFieldValue
            };
        todoList.push(newTodo);
        sortElementsById();
        document.getElementById("createTaskBtn-close").click();

    } else if (inputTitle !== "" && statusInputFieldValue !== "" && priorityInputFieldValue !== "" && editList) {
        var getEditid = 0;
        getEditid = document.getElementById("taskid-input").value;

        todoList = todoList.map(function (item) {
            if (item.id == getEditid) {
                if (statusVal.getValue(true) == "Completed") {
                    item.checkedElem = true
                } else {
                    item.checkedElem = false
                }
                var editObj = {
                    'id': document.getElementById("taskid-input").value,
                    "checkedElem": item.checkedElem,
                    'todo': inputTitle,
                    'assignedto': assignedtousers,
                    'dueDate': inputDate,
                    'status': statusInputFieldValue,
                    'priority': priorityInputFieldValue
                }
                return editObj;
            }
            return item;
        });
        editList = false;
        document.getElementById("createTaskBtn-close").click();
    }

    load(todoList)
    return true;
});


function fetchIdFromObj(todo) {
    return parseInt(todo.id);
}

function findNextId() {
    if (todoList.length === 0) {
        return 0;
    }
    var lastElementId = fetchIdFromObj(todoList[todoList.length - 1]),
        firstElementId = fetchIdFromObj(todoList[0]);
    return (firstElementId >= lastElementId) ? (firstElementId + 1) : (lastElementId + 1);
}

function clearFields() {
    document.getElementById('task-title-input').value = '';
    document.getElementById('task-duedate-input').value = '';

    document.getElementById('total-assignee').innerHTML = "0";

    Array.from(document.querySelectorAll('.select-element .dropdown-menu .dropdown-item.active')).forEach(function (ele) {
        ele.classList.remove("active")
    });

    Array.from(document.querySelectorAll("#assignee-member .avatar-group-item")).forEach(function (item) {
        item.remove();
    });

    var errorMsg = document.getElementById("task-error-msg");
    errorMsg.style.display = "none";

    statusVal.removeActiveItems();
    statusVal.setChoiceByValue("New");
    priorityVal.removeActiveItems();
    priorityVal.setChoiceByValue("");
}


document.getElementById('createTask').addEventListener('hidden.bs.modal', event => {
    clearFields();
});

function sortElementsById() {
    var manyTodos = todoList.sort(function (a, b) {
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
    load(manyTodos);
}

function sortElementsByName() {
    var manyTodos = todoList.sort(function (a, b) {
        var x = a.todo.toLowerCase();
        var y = b.todo.toLowerCase();
        if (x < y) {
            return -1;
        }
        if (x > y) {
            return 1;
        }
        return 0;
    })
    load(manyTodos);
}

// Search product list
var searchTaskList = document.getElementById("searchTaskList");
searchTaskList.addEventListener("keyup", function () {
    var inputVal = searchTaskList.value.toLowerCase();
    function filterItems(arr, query) {
        return arr.filter(function (el) {
            return el.todo.toLowerCase().indexOf(query.toLowerCase()) !== -1
        })
    }

    var filterData = filterItems(todoList, inputVal);
    if (filterData.length == 0) {
        document.getElementById("noresult").style.display = "block";
        document.getElementById("todo-task").style.display = "none";
    } else {
        document.getElementById("noresult").style.display = "none";
        document.getElementById("todo-task").style.display = "block";
    }

    load(filterData);
});

function loadList(manyTodos) {
    function elmLoader() {
        document.getElementById("elmLoader").innerHTML = '';
        drawList(manyTodos);
    }
    elmLoader();
}

var drake = dragula([document.getElementById("task-list")], {
    moves: function (el, container, handle) {
        return handle.classList.contains('task-handle');
    }
});

var scroll = autoScroll([
    document.querySelector("#todo-content"),
], {
    margin: 20,
    maxSpeed: 100,
    scrollWhenOutside: true,
    autoScroll: function () {
        return this.down && drake.dragging;
    }
});

function drawList(manyTodos) {
    document.getElementById("task-list").innerHTML = "";
    Array.from(manyTodos).forEach(function (singleTodo) {
        var checkinput = singleTodo.checkedElem ? "checked" : "";
        var assignedElem = singleTodo.assignedto;
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

        document.getElementById("task-list").innerHTML +=
            '<tr>\
            <td>\
                <div class="d-flex align-items-start">\
                    <div class="flex-shrink-0 me-3">\
                        <div class="task-handle px-1 bg-light rounded">: :</div>\
                    </div>\
                    <div class="flex-grow-1">\
                        <div class="form-check">\
                            <input class="form-check-input" type="checkbox" value="'+ singleTodo.id + '" id="todo' + singleTodo.id + '" ' + checkinput + '>\
                            <label class="form-check-label" for="todo' + singleTodo.id + '">' + singleTodo.todo + '</label>\
                        </div>\
                    </div>\
                </div>\
            </td>\
            <td>'+ imgHtml + '</td>\
            <td>' + singleTodo.dueDate + '</td>\
            <td>' + isStatus(singleTodo.status) + '</td>\
            <td>' + isPriority(singleTodo.priority) + '</td>\
            <td>\
            <div class="hstack gap-2">\
                <button class="btn btn-sm btn-soft-danger remove-list" data-bs-toggle="modal" data-bs-target="#removeTaskItemModal" data-remove-id='+ singleTodo.id + '><i class="ri-delete-bin-5-fill align-bottom"></i></button>\
                <button class="btn btn-sm btn-soft-info edit-list" data-bs-toggle="modal" data-bs-target="#createTask" data-edit-id='+ singleTodo.id + '><i class="ri-pencil-fill align-bottom"></i></button>\
            </div>\
            </td>\
        </tr>';

        editTodoList();
        removeSingleItem();
        checkTaskStatus();
        tooltipElm();
    });
}

var isShowMenu = false;
var todoMenuSidebar = document.getElementsByClassName('file-manager-sidebar');
Array.from(document.querySelectorAll(".file-menu-btn")).forEach(function (item) {
    item.addEventListener("click", function () {
        Array.from(todoMenuSidebar).forEach(function (elm) {
            elm.classList.add("menubar-show");
            isShowMenu = true;
        });
    });
});

window.addEventListener('click', function (e) {
    if (document.querySelector(".file-manager-sidebar").classList.contains('menubar-show')) {
        if (!isShowMenu) {
            document.querySelector(".file-manager-sidebar").classList.remove("menubar-show");
        }
        isShowMenu = false;
    }
});

function tooltipElm(){
    var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

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

function checkTaskStatus() {
    Array.from(document.querySelectorAll("#task-list tr")).forEach(function (item) {
        item.querySelector(".form-check .form-check-input").addEventListener("change", function () {
            var getid = this.value;
            if (this.checked) {
                todoList = todoList.map(function (item) {
                    if (item.id == getid) {
                        item.checkedElem = true
                        item.status = "Completed"
                    }
                    return item;
                });
            } else {
                todoList = todoList.map(function (item) {
                    if (item.id == getid) {
                        item.checkedElem = false
                        item.status = "Inprogress"
                    }
                    return item;
                });
            }
            load(todoList)
        });
    });
}

function editTodoList() {
    var getEditid = 0;
    Array.from(document.querySelectorAll(".edit-list")).forEach(function (elem) {
        elem.addEventListener('click', function (event) {
            getEditid = elem.getAttribute('data-edit-id');
            todoList = todoList.map(function (item) {
                if (item.id == getEditid) {
                    editList = true;
                    document.getElementById("createTaskLabel").innerHTML = "Edit Task";
                    document.getElementById("addNewTodo").innerHTML = "Save";
                    document.getElementById("taskid-input").value = item.id;
                    document.getElementById("task-title-input").value = item.todo;

                    flatpickr("#task-duedate-input", {
                        dateFormat: "d M, Y",
                        defaultDate: item.dueDate
                    });

                    var statusSelec = new DOMParser().parseFromString(item.status, "text/html").body;
                    statusVal.setChoiceByValue(statusSelec.innerHTML);

                    var prioritySelec = new DOMParser().parseFromString(item.priority, "text/html").body;
                    priorityVal.setChoiceByValue(prioritySelec.innerHTML);

                    Array.from(document.querySelectorAll(".select-element .dropdown-menu ul li a")).forEach(function (subElem) {
                        var nameelem = subElem.querySelector(".flex-grow-1").innerHTML;
                        
                        item.assignedto.map(function (subItem) {
                            if (subItem.assigneeName == nameelem) {
                                subElem.classList.add("active");
                                var folderListdata = document.getElementById("assignee-member");
                                if(subElem.classList.contains("active")){
                                    folderlisthtml =
                                    '<a href="javascript: void(0);" class="avatar-group-item mb-2" data-img="'+subItem.assigneeImg+'"  data-bs-toggle="tooltip" data -bs-placement="top" data-bs-title="'+subItem.assigneeName+'">\
                                    <img src="'+subItem.assigneeImg+'" alt="" class="rounded-circle avatar-xs" />\
                                    </a>';

                                    folderListdata.insertAdjacentHTML("beforeend", folderlisthtml);
                                    tooltipElm();
                                }
                            }

                            return subElem;
                        });
                    });
                    
                    var assigneelength = document.querySelectorAll('.select-element .dropdown-menu .dropdown-item.active').length;
                    document.getElementById("total-assignee").innerHTML = assigneelength

                }
                return item;
            });
        });
    });
};

function removeSingleItem() {
    var getid = 0;
    Array.from(document.querySelectorAll(".remove-list")).forEach(function (item) {
        item.addEventListener('click', function (event) {
            getid = item.getAttribute('data-remove-id');
            document.getElementById("remove-todoitem").addEventListener("click", function () {
                function arrayRemove(arr, value) {
                    return arr.filter(function (ele) {
                        return ele.id != value;
                    });
                }
                var filtered = arrayRemove(todoList, getid);

                todoList = filtered;

                load(todoList);
                document.getElementById("close-removetodomodal").click();
            });
        });
    });
}


// choices category input
var taskStatusInput = new Choices(document.getElementById('choices-select-status'), {
    searchEnabled: false,
});

taskStatusInput.passedElement.element.addEventListener('change', function (event) {
    var taskStatusValue = event.detail.value;
    if (event.detail.value) {
        var filterData = todoList.filter(taskList => taskList.status == taskStatusValue);
        if (filterData.length == 0) {
            document.getElementById("noresult").style.display = "block";
            document.getElementById("todo-task").style.display = "none";
        } else {
            document.getElementById("noresult").style.display = "none";
            document.getElementById("todo-task").style.display = "block";
        }
    } else {
        var filterData = todoList;
    }
    load(filterData);
}, false);


// choices category input
var taskSortListInput = new Choices(document.getElementById('choices-select-sortlist'), {
    searchEnabled: false,
});

taskSortListInput.passedElement.element.addEventListener('change', function (event) {
    var taskSortListValue = event.detail.value;
    if (taskSortListValue == "By ID") {
        sortElementsById();
    } else if (taskSortListValue == "By Name") {
        sortElementsByName();
    } else {
        var filterData = todoList;
        load(filterData);
    }
}, false);


function load(manyTodos) {
    loadList(manyTodos);
};

window.onload = function () {
    sortElementsById();
};