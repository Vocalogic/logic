/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Team Init Js File
*/

//Fiter Js
var list = document.querySelectorAll(".team-list");
if (list) {
    var buttonGroups = document.querySelectorAll('.filter-button');
    if (buttonGroups) {
        Array.from(buttonGroups).forEach(function (btnGroup) {
            btnGroup.addEventListener('click', onButtonGroupClick);
        });
    }
}

function onButtonGroupClick(event) {
    if (event.target.id === 'list-view-button' || event.target.parentElement.id === 'list-view-button') {
        document.getElementById("list-view-button").classList.add("active");
        document.getElementById("grid-view-button").classList.remove("active");
        Array.from(list).forEach(function (el) {
            el.classList.add("list-view-filter");
            el.classList.remove("grid-view-filter");
        });

    } else {
        document.getElementById("grid-view-button").classList.add("active");
        document.getElementById("list-view-button").classList.remove("active");
        Array.from(list).forEach(function (el) {
            el.classList.remove("list-view-filter");
            el.classList.add("grid-view-filter");
        });
    }
}



var url="assets/json/";
var allmemberlist = '';

// Reading JSON with Fetch API
fetch(url+"team-member-list.json")
    .then(res => res.json())
    .then((data) => {
        allmemberlist = data;
        loadTeamData(allmemberlist);
}).catch(err => console.error(err));

// load team data
function loadTeamData(datas) {
    document.querySelector("#team-member-list").innerHTML = '';

    Array.from(datas).forEach(function (teamData, index) {
        var checkBookmark = teamData.bookmark ? "active" : "";
        var isUserProfile = teamData.memberImg ? '<img src="'+teamData.memberImg+'" alt="" class="member-img img-fluid d-block rounded-circle" />'
                    : '<div class="avatar-title border bg-light text-primary rounded-circle text-uppercase">' + teamData.nickname + '</div>';

        document.querySelector("#team-member-list").innerHTML +=
        '<div class="col">\
            <div class="card team-box">\
                <div class="team-cover">\
                    <img src="'+teamData.coverImg+'" alt="" class="img-fluid" />\
                </div>\
                <div class="card-body p-4">\
                    <div class="row align-items-center team-row">\
                        <div class="col team-settings">\
                            <div class="row">\
                                <div class="col">\
                                    <div class="flex-shrink-0 me-2">\
                                        <button type="button" class="btn btn-light btn-icon rounded-circle btn-sm favourite-btn ' + checkBookmark + '">\
                                            <i class="ri-star-fill fs-14"></i>\
                                        </button>\
                                    </div>\
                                </div>\
                                <div class="col text-end dropdown">\
                                    <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">\
                                        <i class="ri-more-fill fs-17"></i>\
                                    </a>\
                                    <ul class="dropdown-menu dropdown-menu-end">\
                                        <li><a class="dropdown-item edit-list" href="#addmemberModal"  data-bs-toggle="modal" data-edit-id="'+teamData.id+'"><i class="ri-pencil-line me-2 align-bottom text-muted"></i>Edit</a></li>\
                                        <li><a class="dropdown-item remove-list" href="#removeMemberModal" data-bs-toggle="modal" data-remove-id="'+teamData.id+'"><i class="ri-delete-bin-5-line me-2 align-bottom text-muted"></i>Remove</a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="col-lg-4 col">\
                            <div class="team-profile-img">\
                                <div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0">'+isUserProfile+'</div>\
                                <div class="team-content">\
                                    <a class="member-name" data-bs-toggle="offcanvas" href="#member-overview" aria-controls="member-overview">\
                                        <h5 class="fs-16 mb-1">'+teamData.memberName+'</h5>\
                                    </a>\
                                    <p class="text-muted member-designation mb-0">'+teamData.position+'</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="col-lg-4 col">\
                            <div class="row text-muted text-center">\
                                <div class="col-6 border-end border-end-dashed">\
                                    <h5 class="mb-1 projects-num">'+teamData.projects+'</h5>\
                                    <p class="text-muted mb-0">Projects</p>\
                                </div>\
                                <div class="col-6">\
                                    <h5 class="mb-1 tasks-num">'+teamData.tasks+'</h5>\
                                    <p class="text-muted mb-0">Tasks</p>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="col-lg-2 col">\
                            <div class="text-end">\
                                <a href="pages-profile.html" class="btn btn-light view-btn">View Profile</a>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>\
        </div>';
        bookmarkBtn();
        editMemberList();
        removeItem();
        memberDetailShow();
    });
}


// favourite btn
function bookmarkBtn() {
    Array.from(document.querySelectorAll(".favourite-btn")).forEach(function (item) {
        item.addEventListener("click", function () {
            if (item.classList.contains("active")) {
                item.classList.remove("active");
            } else {
                item.classList.add("active");
            }
        });
    });
}
bookmarkBtn();

var editlist = false;

// member image
document.querySelector("#member-image-input").addEventListener("change", function () {
    var preview = document.querySelector("#member-img");
    var file = document.querySelector("#member-image-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load",function () {
        preview.src = reader.result;
    },false);
    if (file) {
        reader.readAsDataURL(file);
    }
});

function editMemberList() {
    var getEditid = 0;
    Array.from(document.querySelectorAll(".edit-list")).forEach(function (elem) {
        elem.addEventListener('click', function (event) {
            getEditid = elem.getAttribute('data-edit-id');
            allmemberlist = allmemberlist.map(function (item) {
                if (item.id == getEditid) {
                    editlist = true;
                    document.getElementById("createMemberLabel").innerHTML = "Edit Member";
                    document.getElementById("addNewMember").innerHTML = "Save";

                    if(item.memberImg == ""){
                        document.getElementById("member-img").src = "assets/images/users/user-dummy-img.jpg";
                    }else{
                        document.getElementById("member-img").src = item.memberImg;
                    }

                    document.getElementById("cover-img").src = item.coverImg;
                    document.getElementById("memberid-input").value = item.id;
                    document.getElementById('teammembersName').value = item.memberName;
                    document.getElementById('designation').value = item.position;
                    document.getElementById('project-input').value = item.projects;
                    document.getElementById('task-input').value = item.tasks;
                    document.getElementById("memberlist-form").classList.remove('was-validated');
                }
                return item;
            });
        });
    });
};


// cover image
document.querySelector("#cover-image-input").addEventListener("change", function () {
    var preview = document.querySelector("#cover-img");
    var file = document.querySelector("#cover-image-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load",function () {
        preview.src = reader.result;
    },false);
    if (file) {
        reader.readAsDataURL(file);
    }
});

Array.from(document.querySelectorAll(".addMembers-modal")).forEach(function (elem) {
    elem.addEventListener('click', function (event) {
      document.getElementById("createMemberLabel").innerHTML = "Add New Members";
      document.getElementById("addNewMember").innerHTML = "Add Member";
      document.getElementById("teammembersName").value = "";
      document.getElementById("designation").value = "";

      document.getElementById("cover-img").src = "assets/images/small/img-9.jpg";
      document.getElementById("member-img").src = "assets/images/users/user-dummy-img.jpg";

      document.getElementById("memberlist-form").classList.remove('was-validated');
    });
});

// Form Event
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    var inputName = document.getElementById('teammembersName').value;
                    var inputDesignation = document.getElementById('designation').value;
                    var memberImg = document.getElementById("member-img").src;
                    var coverImg = document.getElementById("cover-img").src;

                    var memberImgValue = memberImg.substring(
                        memberImg.indexOf("/as") + 1
                    );

                    var memberImageValue
                    if(memberImgValue == "assets/images/users/user-dummy-img.jpg"){
                        memberImageValue = ""
                    }else{
                        memberImageValue = memberImg
                    }

                    var str = inputName;
                    var matches = str.match(/\b(\w)/g);
                    var acronym = matches.join(''); // JSON
                    var nicknameValue = acronym.substring(0,2)

                    if (inputName !== "" && inputDesignation !== "" && !editlist) {
                        var newMemberId = findNextId();
                        var newMember = {
                            'id': newMemberId,
                            "coverImg": coverImg,
                            "bookmark": false,
                            "memberImg": memberImageValue,
                            "nickname": nicknameValue,
                            'memberName': inputName,
                            'position': inputDesignation,
                            'projects': "0",
                            'tasks': "0"
                        };

                        allmemberlist.push(newMember);
                        
                        sortElementsById();
                        
                    }else if(inputName !== "" && inputDesignation !== "" && editlist){
                        var getEditid = 0;
                        getEditid = document.getElementById("memberid-input").value;
                        allmemberlist = allmemberlist.map(function (item) {
                            if (item.id == getEditid) {
                                var editObj = {
                                    'id': getEditid,
                                    "coverImg": coverImg,
                                    "bookmark": item.bookmark,
                                    "memberImg": memberImg,
                                    "nickname": nicknameValue,
                                    'memberName': inputName,
                                    'position': inputDesignation,
                                    'projects': document.getElementById('project-input').value,
                                    'tasks': document.getElementById('task-input').value
                                }
                                return editObj;
                            }
                            return item;
                        });
                        editlist = false;
                    }

                    loadTeamData(allmemberlist)
                    document.getElementById("createMemberBtn-close").click();
                }
                form.classList.add('was-validated');
            }, false)
        })
})()



function fetchIdFromObj(member) {
    return parseInt(member.id);
}

function findNextId() {
    if (allmemberlist.length === 0) {
        return 0;
    }
    var lastElementId = fetchIdFromObj(allmemberlist[allmemberlist.length - 1]),
        firstElementId = fetchIdFromObj(allmemberlist[0]);
    return (firstElementId >= lastElementId) ? (firstElementId + 1) : (lastElementId + 1);
}

function sortElementsById() {
    var manymember = allmemberlist.sort(function (a, b) {
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
    loadTeamData(manymember);
}

function removeItem() {
    var getid = 0;
    Array.from(document.querySelectorAll(".remove-list")).forEach(function (item) {
        item.addEventListener('click', function (event) {
            getid = item.getAttribute('data-remove-id');
            document.getElementById("remove-item").addEventListener("click", function () {
                function arrayRemove(arr, value) {
                    return arr.filter(function (ele) {
                        return ele.id != value;
                    });
                }
                var filtered = arrayRemove(allmemberlist, getid);

                allmemberlist = filtered;

                loadTeamData(allmemberlist);
                document.getElementById("close-removeMemberModal").click();
            });
        });
    });
}

function memberDetailShow() {
    Array.from(document.querySelectorAll(".team-box")).forEach(function (item) {
        item.querySelector(".member-name").addEventListener("click", function () {
            
            var memberName = item.querySelector(".member-name h5").innerHTML;
            var memberDesignation = item.querySelector(".member-designation").innerHTML;

            var memberProfileImg
            if(item.querySelector(".member-img")){
                memberProfileImg = item.querySelector(".member-img").src;
            }else{
                memberProfileImg = "assets/images/users/user-dummy-img.jpg"
            }
            var memberCoverImg = item.querySelector(".team-cover img").src;
            var memberProject = item.querySelector(".projects-num").innerHTML;
            var memberTask = item.querySelector(".tasks-num").innerHTML;

            document.querySelector("#member-overview .profile-img").src = memberProfileImg;
            document.querySelector("#member-overview .team-cover img").src = memberCoverImg;

            document.querySelector("#member-overview .profile-name").innerHTML = memberName;
            document.querySelector("#member-overview .profile-designation").innerHTML = memberDesignation;

            document.querySelector("#member-overview .profile-project").innerHTML = memberProject;
            document.querySelector("#member-overview .profile-task").innerHTML = memberTask;
        });
    }); 
}

// Search product list
var searchMemberList = document.getElementById("searchMemberList");
searchMemberList.addEventListener("keyup", function () {
    var inputVal = searchMemberList.value.toLowerCase();
    function filterItems(arr, query) {
        return arr.filter(function (el) {
            return (el.memberName.toLowerCase().indexOf(query.toLowerCase()) !== -1 || el.position.toLowerCase().indexOf(query.toLowerCase()) !== -1)
        })
    }

    var filterData = filterItems(allmemberlist, inputVal);
    if (filterData.length == 0) {
        document.getElementById("noresult").style.display = "block";
        document.getElementById("teamlist").style.display = "none";
    } else {
        document.getElementById("noresult").style.display = "none";
        document.getElementById("teamlist").style.display = "block";
    }

    loadTeamData(filterData);
});