/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: mailbox init Js File
*/

var url="assets/json/";
var allmaillist = '';
const loader = document.querySelector("#elmLoader");
// showing loading

//mail list by json
var getJSON = function (jsonurl, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url + jsonurl, true);
    xhr.responseType = "json";
    xhr.onload = function () {
        var status = xhr.status;
        if (status === 200) {
            document.getElementById("elmLoader").innerHTML = '';
            callback(null, xhr.response);
        } else {
            callback(status, xhr.response);
        }
    };
    xhr.send();
};

// load mail data
function loadMailData(datas) {
    var triggerEl = document.querySelector('#mail-filter-navlist button[data-bs-target="#pills-primary"]')
    triggerEl.click()
    document.querySelector("#mail-list").innerHTML = '';

    Array.from(datas).forEach(function (mailData, index) {

        var checkReaded = mailData.readed ? "" : "unread";
        var checkStarred = mailData.starred ? "active" : "";
        var mailcounted = mailData.counted ? '(' + mailData.counted + ')' : "";

        document.querySelector("#mail-list").innerHTML +=
            '<li class="' + checkReaded + '">\
        <div class="col-mail col-mail-1">\
            <div class="form-check checkbox-wrapper-mail fs-14">\
                <input class="form-check-input" type="checkbox" value="' + mailData.id + '" id="checkbox-' + mailData.id + '">\
                <label class="form-check-label" for="checkbox-' + mailData.id + '"></label>\
            </div>\
            <input type="hidden" value=' + mailData.userImg + ' class="mail-userimg" />\
            <button type="button" class="btn avatar-xs p-0 favourite-btn fs-15 ' + checkStarred + '">\
            <i class="ri-star-fill"></i>\
            </button>\
            <a href="javascript: void(0);" class="title"><span class="title-name">' + mailData.name + '</span> ' + mailcounted + '</a>\
        </div>\
        <div class="col-mail col-mail-2">\
            <a href="javascript: void(0);" class="subject"><span class="subject-title">' + mailData.title + '</span> – <span class="teaser">' + mailData.description + '</span>\
            </a>\
            <div class="date">' + mailData.date + '</div>\
        </div>\
    </li>';
        favouriteBtn();
        emailDetailShow();
        emailDetailChange();
        checkBoxAll();
    });
}

// load social mail data
function loadSocialMailData(datas) {
    Array.from(datas).forEach(function (mailData, index) {
        var checkReaded = mailData.readed ? "" : "unread";
        var checkStarred = mailData.starred ? "active" : "";
        var mailcounted = mailData.counted ? '(' + mailData.counted + ')' : "";

        document.getElementById("social-mail-list").innerHTML +=
            '<li class="' + checkReaded + '">\
                <div class="col-mail col-mail-1">\
                    <div class="form-check checkbox-wrapper-mail fs-14">\
                        <input class="form-check-input" type="checkbox" value="' + mailData.id + '" id="checkbox-' + mailData.id + '">\
                        <label class="form-check-label" for="checkbox-' + mailData.id + '"></label>\
                    </div>\
                    <input type="hidden" value=' + mailData.userImg + ' class="mail-userimg" />\
                    <button type="button" class="btn avatar-xs p-0 favourite-btn fs-15 ' + checkStarred + '">\
                    <i class="ri-star-fill"></i>\
                    </button>\
                    <a href="javascript: void(0);" class="title"><span class="title-name">' + mailData.name + '</span> ' + mailcounted + '</a>\
                </div>\
                <div class="col-mail col-mail-2">\
                    <a href="javascript: void(0);" class="subject"><span class="subject-title">' + mailData.title + '</span> – <span class="teaser">' + mailData.description + '</span>\
                    </a>\
                    <div class="date">' + mailData.date + '</div>\
                </div>\
            </li>';
        emailDetailShow();
        emailDetailChange();
        checkBoxAll();
    });
}

// load promotions mail data
function loadPromotionsMailData(datas) {
    Array.from(datas).forEach(function (mailData, index) {
        var checkReaded = mailData.readed ? "" : "unread";
        var checkStarred = mailData.starred ? "active" : "";
        var mailcounted = mailData.counted ? '(' + mailData.counted + ')' : "";

        document.getElementById("promotions-mail-list").innerHTML +=
            '<li class="' + checkReaded + '">\
                <div class="col-mail col-mail-1">\
                    <div class="form-check checkbox-wrapper-mail fs-14">\
                        <input class="form-check-input" type="checkbox" value="' + mailData.id + '" id="checkbox-' + mailData.id + '">\
                        <label class="form-check-label" for="checkbox-' + mailData.id + '"></label>\
                    </div>\
                    <input type="hidden" value=' + mailData.userImg + ' class="mail-userimg" />\
                    <button type="button" class="btn avatar-xs p-0 favourite-btn fs-15 ' + checkStarred + '">\
                    <i class="ri-star-fill"></i>\
                    </button>\
                    <a href="javascript: void(0);" class="title"><span class="title-name">' + mailData.name + '</span> ' + mailcounted + '</a>\
                </div>\
                <div class="col-mail col-mail-2">\
                    <a href="javascript: void(0);" class="subject"><span class="subject-title">' + mailData.title + '</span> – <span class="teaser">' + mailData.description + '</span>\
                    </a>\
                    <div class="date">' + mailData.date + '</div>\
                </div>\
            </li>';
        emailDetailShow();
        emailDetailChange();
        checkBoxAll();
    });
}

// get json
getJSON("mail-list.init.json", function (err, data) {
    if (err !== null) {
        console.log("Something went wrong: " + err);
    } else {
        allmaillist = data[0].primary;
        socialmaillist = data[0].social;
        promotionsmaillist = data[0].promotions;

        loadMailData(allmaillist);
        loadSocialMailData(socialmaillist);
        loadPromotionsMailData(promotionsmaillist);
    }
});


// mail list click event
Array.from(document.querySelectorAll('.mail-list a')).forEach(function (mailTab) {
    mailTab.addEventListener("click", function () {
        var chatUserList = document.querySelector(".mail-list a.active");
        if (chatUserList) chatUserList.classList.remove("active");
        mailTab.classList.add('active');

        if (mailTab.querySelector('.mail-list-link').hasAttribute('data-type')) {
            var tabname = mailTab.querySelector('.mail-list-link').innerHTML;
            var filterData = allmaillist.filter(maillist => maillist.labeltype === tabname);
        } else {
            var tabname = mailTab.querySelector('.mail-list-link').innerHTML;
            document.getElementById("mail-list").innerHTML = '';
            if (tabname != 'All') {
                var filterData = allmaillist.filter(maillist => maillist.tabtype === tabname);
            } else {
                var filterData = allmaillist;
            }
            if (tabname != 'All' && tabname != 'Inbox') {
                document.getElementById("mail-filter-navlist").style.display = "none";
            } else {
                document.getElementById("mail-filter-navlist").style.display = "block";
            }
        }
        loadMailData(filterData);
        favouriteBtn();
    });
})

// favourite btn
function favouriteBtn() {
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
favouriteBtn();

// emailDetailShow
function emailDetailShow() {
    var bodyElement = document.getElementsByTagName('body')[0];
    Array.from(document.querySelectorAll(".message-list a")).forEach(function (item) {
        item.addEventListener("click", function (event) {
            bodyElement.classList.add("email-detail-show");
            Array.from(document.querySelectorAll(".message-list li.unread")).forEach(function (element) {
                if (element.classList.contains("unread")) {
                    event.target.closest('li').classList.remove("unread");
                }
            });
        });
    });

    Array.from(document.querySelectorAll(".close-btn-email")).forEach(function (item) {
        item.addEventListener("click", function () {
            bodyElement.classList.remove("email-detail-show");
        });
    });

    var isShowMenu = false;
    var emailMenuSidebar = document.getElementsByClassName('email-menu-sidebar');
    Array.from(document.querySelectorAll(".email-menu-btn")).forEach(function (item) {
        item.addEventListener("click", function () {
            Array.from(emailMenuSidebar).forEach(function (elm) {
                elm.classList.add("menubar-show");
                isShowMenu = true;
            });
        });
    });

    window.addEventListener('click', function (e) {
        if (document.querySelector(".email-menu-sidebar").classList.contains('menubar-show')) {
            if (!isShowMenu) {
                document.querySelector(".email-menu-sidebar").classList.remove("menubar-show");
            }
            isShowMenu = false;
        }
    });
    favouriteBtn();
}

// editor
ClassicEditor.create(document.querySelector('#email-editor')).then(function (editor) {
        editor.ui.view.editable.element.style.height = '200px';
    })
    .catch(function (error) {
        console.error(error);
    });

// check all
function checkBoxAll() {
    // checkbox-wrapper-mail
    Array.from(document.querySelectorAll(".checkbox-wrapper-mail input")).forEach(function (element) {
        element.addEventListener('click', function (el) {
            if (el.target.checked == true) {
                el.target.closest('li').classList.add("active");
            } else {
                el.target.closest('li').classList.remove("active");
            }
        });
    });

    // checkbox
    var checkboxes = document.querySelectorAll('.tab-pane.show .checkbox-wrapper-mail input');
    Array.from(checkboxes).forEach(function (element) {
        element.addEventListener('click', function (event) {
            var checkboxes = document.querySelectorAll('.tab-pane.show .checkbox-wrapper-mail input');
            var checkall = document.getElementById('checkall');
            var checkedCount = document.querySelectorAll('.tab-pane.show .checkbox-wrapper-mail input:checked').length;
            checkall.checked = checkedCount > 0;
            checkall.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;

            if (event.target.closest('li').classList.contains("active")) {
                (checkedCount > 0) ? document.getElementById("email-topbar-actions").style.display = 'block': document.getElementById("email-topbar-actions").style.display = 'none';
            } else {
                (checkedCount > 0) ? document.getElementById("email-topbar-actions").style.display = 'block': document.getElementById("email-topbar-actions").style.display = 'none';
            }
        });
    });


    function checkAll() {
        var checkboxes = document.querySelectorAll('.tab-pane.show .checkbox-wrapper-mail input');
        var checkedCount = document.querySelectorAll('.tab-pane.show .checkbox-wrapper-mail input:checked').length;
        Array.from(checkboxes).forEach(function (chkbox) {
            chkbox.checked = true;
            chkbox.parentNode.parentNode.parentNode.classList.add("active");
        });
        (checkedCount > 0) ? document.getElementById("email-topbar-actions").style.display = 'none' : document.getElementById("email-topbar-actions").style.display = 'block';

        if (checkedCount > 0) {
            Array.from(checkboxes).forEach(function (chkbox) {
                chkbox.checked = false;
                chkbox.parentNode.parentNode.parentNode.classList.remove("active");
            });
        } else {
            Array.from(checkboxes).forEach(function (chkbox) {
                chkbox.checked = true;
                chkbox.parentNode.parentNode.parentNode.classList.add("active");
            });
        }
        this.onclick = uncheckAll;
        removeItems();
    }

    function uncheckAll() {
        var checkboxes = document.querySelectorAll('.tab-pane.show .checkbox-wrapper-mail input');
        var checkedCount = document.querySelectorAll('.tab-pane.show .checkbox-wrapper-mail input:checked').length;
        Array.from(checkboxes).forEach(function (chkbox) {
            chkbox.checked = false;
            chkbox.parentNode.parentNode.parentNode.classList.remove("active");
        });
        (checkedCount > 0) ? document.getElementById("email-topbar-actions").style.display = 'none' : document.getElementById("email-topbar-actions").style.display = 'block';
        if (checkedCount > 0) {
            Array.from(checkboxes).forEach(function (chkbox) {
                chkbox.checked = false;
                chkbox.parentNode.parentNode.parentNode.classList.remove("active");
            });
        } else {
            Array.from(checkboxes).forEach(function (chkbox) {
                chkbox.checked = true;
                chkbox.parentNode.parentNode.parentNode.classList.add("active");
            });
        }
        this.onclick = checkAll;
    }

    var checkall = document.getElementById("checkall");
    checkall.onclick = checkAll;
}

//User current Id
var currentChatId = "users-chat";
scrollToBottom(currentChatId);
// // Scroll to Bottom
function scrollToBottom(id) {
    setTimeout(function () {
        var simpleBar = (document.getElementById(id).querySelector("#chat-conversation .simplebar-content-wrapper")) ?
            document.getElementById(id).querySelector("#chat-conversation .simplebar-content-wrapper") : ''

        var offsetHeight = document.getElementsByClassName("chat-conversation-list")[0] ?
            document.getElementById(id).getElementsByClassName("chat-conversation-list")[0].scrollHeight - window.innerHeight + 750 : 0;

        if (offsetHeight)
            simpleBar.scrollTo({
                top: offsetHeight,
                behavior: "smooth"
            });
    }, 100);
}

// removeItems
function removeItems() {
    var removeItem = document.getElementById('removeItemModal');
    removeItem.addEventListener('show.bs.modal', function (event) {
        document.getElementById("delete-record").addEventListener("click", function () {
            Array.from(document.querySelectorAll(".message-list li")).forEach(function (element) {
                var filtered = '';
                if (element.classList.contains("active")) {

                    var getid = element.querySelector('.form-check-input').value;

                    function arrayRemove(arr, value) {
                        return arr.filter(function (ele) {
                            return ele.id != value;
                        });
                    }

                    var filtered = arrayRemove(allmaillist, getid);

                    allmaillist = filtered;

                    element.remove();
                }
            });
            document.getElementById("btn-close").click();
            if (document.getElementById("email-topbar-actions"))
                document.getElementById("email-topbar-actions").style.display = 'none';
            checkall.indeterminate = false;
            checkall.checked = false;
        });
    })
}
removeItems();

function removeSingleItem() {
    var getid = 0;
    document.querySelectorAll(".remove-mail").forEach(function (item) {
        item.addEventListener('click', function (event) {
            getid = item.getAttribute('data-remove-id');
            document.getElementById("delete-record").addEventListener("click", function () {
                var filtered = '';
                function arrayRemove(arr, value) {
                    return arr.filter(function (ele) {
                        return ele.id != value;
                    });
                }
                filtered = arrayRemove(allmaillist, getid);
                allmaillist = filtered;
                loadMailData(allmaillist);
                document.getElementById("close-btn-email").click();
            });
        });
    });
}
removeSingleItem();

var markAllReadBtn = document.getElementById("mark-all-read");

markAllReadBtn.addEventListener('click', function (event) {
    if (document.querySelectorAll(".message-list li.unread").length === 0) {
        document.getElementById("unreadConversations").style.display = "block";
        setTimeout(hideclipboardNew, 1000);

        function hideclipboardNew() {
            document.getElementById("unreadConversations").style.display = "none";
        }
    };

    Array.from(document.querySelectorAll(".message-list li.unread")).forEach(function (element) {
        if (element.classList.contains("unread")) {
            element.classList.remove("unread");
        }
    });
});

var dummyUserImage = "assets/images/users/user-dummy-img.jpg";

// email chat detail element
var mailChatDetailElm = false;
document.querySelectorAll(".email-chat-list a").forEach(function (item) {
    if (item.classList.contains("active")) {
        document.getElementById("emailchat-detailElem").style.display = "block";
        var userListName = document.querySelector(".email-chat-list a.active").querySelector(".chatlist-user-name").innerHTML;
        var userListProfile = document.querySelector(".email-chat-list a.active").querySelector(".chatlist-user-image img").getAttribute("src");
        document.querySelector(".email-chat-detail .profile-username").innerHTML = userListName;
        document.getElementById("users-conversation").querySelectorAll(".left .chat-avatar").forEach(function (item) {
            if (userListProfile) {
                item.querySelector("img").setAttribute("src", userListProfile);
            } else {
                item.querySelector("img").setAttribute("src", dummyUserImage);
            }
        });
    }
    item.addEventListener("click", function (event) {
        document.getElementById("emailchat-detailElem").style.display = "block";
        mailChatDetailElm = true;

        // chat user list link active
        var chatUserList = document.querySelector(".email-chat-list a.active");
        if (chatUserList) chatUserList.classList.remove("active");
        this.classList.add("active");

        var currentChatId = "users-chat";
        scrollToBottom(currentChatId);

        //user Name and user Profile change on click
        var username = item.querySelector(".chatlist-user-name").innerHTML;
        var userProfile = item.querySelector(".chatlist-user-image img").getAttribute("src");

        document.querySelector(".email-chat-detail .profile-username").innerHTML = username;
        var conversationImg = document.getElementById("users-conversation");
        Array.from(conversationImg.querySelectorAll(".left .chat-avatar")).forEach(function (item) {
            if (userProfile) {
                item.querySelector("img").setAttribute("src", userProfile);
            } else {
                item.querySelector("img").setAttribute("src", dummyUserImage);
            }
        });
    });
});

document.getElementById("emailchat-btn-close").addEventListener("click", function () {
    document.getElementById("emailchat-detailElem").style.display = "none";
    mailChatDetailElm = false;
    document.querySelector(".email-chat-list a.active").classList.remove("active");
})

// emailDetailChange
function emailDetailChange() {
    Array.from(document.querySelectorAll(".message-list li")).forEach(function (item) {
        item.addEventListener("click", function () {
            var mailListId = item.querySelector(".checkbox-wrapper-mail .form-check-input").value
            document.querySelector(".remove-mail").setAttribute("data-remove-id", mailListId);;
            var subjectTitle = item.querySelector(".subject-title").innerHTML;
            document.querySelector(".email-subject-title").innerHTML = subjectTitle;

            var emailTitleLeftName = item.querySelector(".title-name").innerHTML;
            Array.from(document.querySelectorAll(".accordion-item.left")).forEach(function (subitem) {
                subitem.querySelector(".email-user-name").innerHTML = emailTitleLeftName;
                var userImg = item.querySelector(".mail-userimg").value;
                subitem.querySelector("img").setAttribute("src", userImg)
            });

            var messageUserName = document.querySelector(".user-name-text").innerHTML;
            var usermailProfile = document.querySelector(".header-profile-user").getAttribute("src");
            Array.from(document.querySelectorAll(".accordion-item.right")).forEach(function (subitem) {
                subitem.querySelector(".email-user-name-right").innerHTML = messageUserName;
                subitem.querySelector("img").setAttribute("src", usermailProfile);
            });
        });
    });
}

const triggerTabList = document.querySelectorAll('#mail-filter-navlist .nav-tabs button')
triggerTabList.forEach(triggerEl => {
    const tabTrigger = new bootstrap.Tab(triggerEl)

    triggerEl.addEventListener('click', event => {
        event.preventDefault()

        var activeTab = document.querySelector(".tab-content .tab-pane.show")

        tabTrigger.show()
    })
})


function resizeEvent(){
    var windowSize = document.documentElement.clientWidth;
    if (windowSize < 767) {
        var chatUserList = document.querySelector(".email-chat-list a.active");
        if (chatUserList) chatUserList.classList.remove("active");
        document.getElementById("emailchat-detailElem").style.display = "none";
    }
}
resizeEvent();

window.onresize = resizeEvent;


