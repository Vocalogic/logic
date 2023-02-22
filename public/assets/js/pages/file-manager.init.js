/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: file-manager Js File
*/

// get colors array from the string
function getChartColorsArray(chartId) {
  if (document.getElementById(chartId) !== null) {
    var colors = document.getElementById(chartId).getAttribute("data-colors");
    colors = JSON.parse(colors);
    return colors.map(function (value) {
      var newValue = value.replace(" ", "");
      if (newValue.indexOf(",") === -1) {
        var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
        if (color) return color;
        else return newValue;;
      } else {
        var val = value.split(',');
        if (val.length == 2) {
          var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
          rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
          return rgbaColor;
        } else {
          return newValue;
        }
      }
    });
  }
}


// Simple Donut Charts
var chartDonutBasicColors = getChartColorsArray("simple_dount_chart");
if (chartDonutBasicColors) {
  var options = {
    series: [27.01, 20.87, 33.54, 37.58],
    chart: {
      height: 330,
      type: 'donut',
    },
    legend: {
      position: 'bottom'
    },
    labels: ["Documents", "Media", "Others", "Free Space"],
    dataLabels: {
      dropShadow: {
        enabled: false,
      }
    },
    colors: chartDonutBasicColors
  };

  var chart = new ApexCharts(document.querySelector("#simple_dount_chart"), options);
  chart.render();
}

var url="assets/json/";
var allFileList = '';
var editFlag = false;

//mail list by json
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
getJSON("filemanager-filelist.json", function (err, data) {
  if (err !== null) {
    console.log("Something went wrong: " + err);
  } else {
    allFileList = data;
    loadFileData(allFileList);
  }
});

// load product data
function loadFileData(datas) {
  document.querySelector("#file-list").innerHTML = '';
  Array.from(datas).forEach(function (fileData, index) {
    var fileIconElm
    if (fileData.fileName.includes(".")) {
      var fileIcon = fileData.fileName.split(".");
      function isStatus(val) {
        switch (val) {
          case "png":
            return (
              '<i class="ri-gallery-fill align-bottom text-success"></i>'
            );
          case "jpg":
            return (
              '<i class="ri-gallery-fill align-bottom text-success"></i>'
            );
          case "pdf":
            return (
              '<i class="ri-file-pdf-fill align-bottom text-danger"></i>'
            );
          case "docx":
            return (
              '<i class="ri-file-text-fill align-bottom text-secondary"></i>'
            );
          case "txt":
            return (
              '<i class="ri-file-text-fill align-bottom text-secondary"></i>'
            );
          default:
            return (
              '<i class="ri-file-text-fill align-bottom text-secondary"></i>'
            );
        }
      }

      fileIconElm = isStatus(fileIcon[1])
    } else {
      fileIconElm = '<i class="ri-folder-2-fill align-bottom text-warning"></i>'
    }

    var checkStarred = fileData.starred ? "active" : "";

    document.querySelector("#file-list").innerHTML +=
      '<tr>\
        <td>\
            <input class="form-control filelist-id" type="hidden" value="' + fileData.id + '" id="filelist-' + fileData.id + '">\
            <div class="d-flex align-items-center">\
                <div class="flex-shrink-0 fs-17 me-2 filelist-icon">'+ fileIconElm + '</div>\
                <div class="flex-grow-1 filelist-name">'+ fileData.fileName + '</div>\
                <div class="d-none filelist-type">'+ fileData.filetype + '</div>\
            </div>\
        </td>\
        <td>'+ fileData.fileItem + '</td>\
        <td class="filelist-size">'+ fileData.fileSize + '</td>\
        <td class="filelist-create">'+ fileData.date + '</td>\
        <td>\
          <div class="d-flex gap-3 justify-content-center">\
            <button type="button" class="btn btn-ghost-primary btn-icon btn-sm favourite-btn ' + checkStarred + '">\
                  <i class="ri-star-fill fs-13 align-bottom"></i>\
            </button>\
            <div class="dropdown">\
              <button class="btn btn-light btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">\
              <i class="ri-more-fill align-bottom"></i>\
              </button>\
              <ul class="dropdown-menu dropdown-menu-end">\
                <li><a class="dropdown-item viewfile-list" href="#">View</a></li>\
                <li><a class="dropdown-item edit-list" href="#createFileModal" data-bs-toggle="modal" data-edit-id='+ fileData.id + ' role="button">Rename</a></li>\
                <li class="dropdown-divider"></li>\
                <li><a class="dropdown-item remove-list" href="#removeFileItemModal" data-id='+ fileData.id + ' data-bs-toggle="modal" role="button">Delete</a></li>\
              </ul>\
            </div>\
          </div>\
        </td>\
      </tr>';

    favouriteBtn();
    removeSingleItem();
    editFileList();
    fileDetailShow();
  });
}

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

Array.from(document.querySelectorAll('.file-manager-menu a')).forEach(function (fileTab) {
  fileTab.addEventListener("click", function () {
    var folderListelm = document.querySelector(".file-manager-menu a.active");
    if (folderListelm) folderListelm.classList.remove("active");
    fileTab.classList.add('active');

    var tabname = fileTab.querySelector('.file-list-link').innerHTML;
    document.getElementById("file-list").innerHTML = '';

    if (tabname != 'My Drive'){
      document.getElementById("filetype-title").innerHTML = tabname;
    }else{
      document.getElementById("filetype-title").innerHTML = "Recent file";
    }
    
    if (tabname != 'My Drive' && tabname != 'Important' && tabname != 'Recents') {
      var filterData = allFileList.filter(filelist => filelist.filetype === tabname);
      document.getElementById("folder-list").style.display = "none";
    } else if(tabname == "Important"){
      var filterData = allFileList.filter(filelist => filelist.starred == true);
      document.getElementById("folder-list").style.display = "none";
    } else {
      var filterData = allFileList;
      document.getElementById("folder-list").style.display = "block";
    }

    if(tabname == 'Recents'){
      document.getElementById("folder-list").style.display = "none";
    }

    loadFileData(filterData);
  })
});


//Create a new folder
var createFolderForms = document.querySelectorAll('.createfolder-form')
Array.prototype.slice.call(createFolderForms).forEach(function (form) {
  form.addEventListener('submit', function (event) {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      event.preventDefault();
      var folderName = document.getElementById("foldername-input").value;
      var uniqueid = Math.floor(Math.random() * 100);
      folderlisthtml =
        '<div class="col-xxl-3 col-sm-6 folder-card">\
        <div class="card bg-light shadow-none" id="folder-'+ uniqueid + '">\
            <div class="card-body">\
                <div class="d-flex mb-1">\
                    <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">\
                        <input class="form-check-input" type="checkbox" value="" id="folderlistCheckbox_'+ uniqueid + '">\
                        <label class="form-check-label" for="folderlistCheckbox_'+ uniqueid + '"></label>\
                    </div>\
                    <div class="dropdown">\
                        <button class="btn btn-ghost-primary btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">\
                            <i class="ri-more-2-fill fs-16 align-bottom"></i>\
                        </button>\
                        <ul class="dropdown-menu dropdown-menu-end">\
                            <li><a class="dropdown-item view-item-btn" href="javascript:void(0);">Open</a></li>\
                            <li><a class="dropdown-item edit-folder-list" href="#createFolderModal" data-bs-toggle="modal" role="button">Rename</a></li>\
                            <li><a class="dropdown-item" href="#removeFolderModal" data-bs-toggle="modal" role="button">Delete</a></li>\
                        </ul>\
                    </div>\
                </div>\
                <div class="text-center">\
                <div class="mb-2">\
                    <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>\
                </div>\
                    <h6 class="fs-15 folder-name">'+ folderName + '</h6>\
                </div>\
                <div class="hstack mt-4 text-muted">\
                    <span class="me-auto"><b>0</b> Files</span>\
                    <span><b>0</b>GB</span>\
                </div>\
            </div>\
        </div>\
    </div>';


      if (folderName !== "" && !editFlag) {
        var folderListdata = document.getElementById("folderlist-data");
        folderListdata.insertAdjacentHTML("afterbegin", folderlisthtml);
        var addFolderClose = document.getElementById("addFolderBtn-close");
        addFolderClose.click();
        editFolderList();
      } else if (folderName !== "" && editFlag) {
        var getEditid = 0;
        getEditid = document.getElementById("folderid-input").value;
        document.getElementById(getEditid).querySelector('.folder-name').innerHTML = folderName
        var addFolderClose = document.getElementById("addFolderBtn-close");
        addFolderClose.click();
        editFlag = false;
        document.getElementById("addNewFolder").innerHTML = "Add Folder";
        document.getElementById("createFolderModalLabel").innerHTML = "Create Folder";
        document.getElementById("folderid-input").value = "";
        document.getElementById("foldername-input").value = "";
      }
      document.getElementById("folderid-input").value = "";
      document.getElementById("foldername-input").value = "";
    }
    form.classList.add('was-validated');
  }, false)
});

function editFolderList() {
  Array.from(document.querySelectorAll(".folder-card")).forEach(function (item) {
    Array.from(item.querySelectorAll(".edit-folder-list")).forEach(function (subitem) {
      subitem.addEventListener('click', function (event) {
        
        var editid = item.querySelector(".card").getAttribute('id');
        var getEditid = editid.split("-")[1];
        var checkid = item.querySelector(".form-check .form-check-input").getAttribute('id')
        var getCheckid = checkid.split("_")[1];

        if (getEditid == getCheckid) {
          editFlag = true;
          document.getElementById("addNewFolder").innerHTML = "Save";
          document.getElementById("createFolderModalLabel").innerHTML = "Folder Rename";
          document.getElementById("folderid-input").value = 'folder-' + getEditid;
          document.getElementById("foldername-input").value = item.querySelector(".folder-name").innerHTML;
        }
      });
    });
  });
};

editFolderList();

// Remove folder from list
var removeProduct = document.getElementById('removeFolderModal')
if (removeProduct) {
    removeProduct.addEventListener('show.bs.modal', function (e) {
        document.getElementById('remove-folderList').addEventListener('click', function (event) {
            e.relatedTarget.closest('.folder-card').remove();
            document.getElementById("close-removeFoldermodal").click();
        });
    });
};

// date & time
var date = new Date().toUTCString().slice(5, 16);

function editFileList() {
  var getEditid = 0;
  Array.from(document.querySelectorAll(".edit-list")).forEach(function (elem) {
    elem.addEventListener('click', function (event) {
      getEditid = elem.getAttribute('data-edit-id');

      allFileList = allFileList.map(function (item) {
        if (item.id == getEditid) {
          editFlag = true;
          document.getElementById("addNewFile").innerHTML = "Save";
          document.getElementById("createFileModalLabel").innerHTML = "File Rename";
          document.getElementById("filename-input").value = item.fileName;
          document.getElementById("fileid-input").value = item.id;
        }
        return item;
      });
    });
  });
}

Array.from(document.querySelectorAll(".createFile-modal")).forEach(function (elem) {
  elem.addEventListener('click', function (event) {
    document.getElementById("addNewFile").innerHTML = "Create";
    document.getElementById("createFileModalLabel").innerHTML = "Create File";
    document.getElementById("filename-input").value = "";
    document.getElementById("fileid-input").value = "";
    document.getElementById("createfile-form").classList.remove("was-validated");
  });
});

Array.from(document.querySelectorAll(".create-folder-modal")).forEach(function (elem) {
  elem.addEventListener('click', function (event) {
    document.getElementById("addNewFolder").innerHTML = "Add Folder";
    document.getElementById("createFolderModalLabel").innerHTML = "Create Folder";
    document.getElementById("folderid-input").value = "";
    document.getElementById("foldername-input").value = "";
    document.getElementById("createfolder-form").classList.remove("was-validated");
  });
});


var createFileForms = document.querySelectorAll('.createfile-form')
Array.prototype.slice.call(createFileForms).forEach(function (form) {
  form.addEventListener('submit', function (event) {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      event.preventDefault();

      var fileName = document.getElementById("filename-input").value;
      var uniqueid = Math.floor(Math.random() * 100);

      if (fileName !== "" && !editFlag) {
        var newObj = {
          "id": uniqueid,
          "fileName": fileName + ".txt",
          "filetype": "Documents",
          "fileItem": "01",
          "fileSize": "0 KB",
          "date": date,
          "starred": false
        };

        allFileList.push(newObj);
        sortElementsById();
        document.getElementById("addFileBtn-close").click();
      } else if (fileName !== "" && editFlag) {
        var getEditid = 0;
        getEditid = document.getElementById("fileid-input").value;
        allFileList = allFileList.map(function (item) {
          if (item.id == getEditid) {
            var editObj = {
              "id": item.id,
              "fileName": document.getElementById("filename-input").value,
              "filetype": item.filetype,
              "fileItem": item.fileItem,
              "fileSize": item.fileSize,
              "date": item.date,
              "starred": item.starred,
            };
            return editObj;
          }
          return item;
        });

        editFlag = false
        document.getElementById("addFileBtn-close").click();
      }

      loadFileData(allFileList);

      document.getElementById("addNewFile").innerHTML = "Create";
      document.getElementById("createFileModalLabel").innerHTML = "Create File";
    }
    form.classList.add('was-validated');
  }, false)
});


function fetchIdFromObj(member) {
  return parseInt(member.id);
}

function sortElementsById() {
  var manyfile = allFileList.sort(function (a, b) {
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
  loadFileData(manyfile);
}

function removeSingleItem() {
  var getid = 0;
  Array.from(document.querySelectorAll(".remove-list")).forEach(function (item) {
    item.addEventListener('click', function (event) {
      getid = item.getAttribute('data-id');
      document.getElementById("remove-fileitem").addEventListener("click", function () {
        function arrayRemove(arr, value) {
          return arr.filter(function (ele) {
            return ele.id != value;
          });
        }
        var filtered = arrayRemove(allFileList, getid);

        allFileList = filtered;

        loadFileData(allFileList);
        document.getElementById("close-removefilemodal").click();
        document.getElementById("file-overview").style.display = "none";
        document.getElementById("folder-overview").style.display = "block";
      });
    });
  });
}

function fileDetailShow() {
  var bodyElement = document.getElementsByTagName('body')[0];
  Array.from(document.querySelectorAll(".close-btn-overview")).forEach(function (item) {
    item.addEventListener("click", function () {
        bodyElement.classList.remove("file-detail-show");
    });
  });

  Array.from(document.querySelectorAll("#file-list tr")).forEach(function (item) {
    item.querySelector(".viewfile-list").addEventListener("click", function () {
      bodyElement.classList.add("file-detail-show");
      document.getElementById("file-overview").style.display = "block";
      document.getElementById("folder-overview").style.display = "none";

      var filelistId = item.querySelector(".filelist-id").value;
      var filelistIcon = item.querySelector(".filelist-icon i").className;
      var filelistName = item.querySelector(".filelist-name").innerHTML;
      var filelistSize = item.querySelector(".filelist-size").innerHTML;
      var filelistCreate = item.querySelector(".filelist-create").innerHTML;
      var filelistType = item.querySelector(".filelist-type").innerHTML;
      

      document.querySelector("#file-overview .file-icon i").className = filelistIcon;
      Array.from(document.querySelectorAll("#file-overview .file-name")).forEach(function (elm) {
        elm.innerHTML = filelistName;
      });
      Array.from(document.querySelectorAll("#file-overview .file-size")).forEach(function (elm) {
        elm.innerHTML = filelistSize;
      });
      Array.from(document.querySelectorAll("#file-overview .create-date")).forEach(function (elm) {
        elm.innerHTML = filelistCreate;
      });
      document.querySelector("#file-overview .file-type").innerHTML = filelistType;

      document.querySelector("#file-overview .remove-file-overview").setAttribute("data-remove-id", filelistId);
      if(item.querySelector(".favourite-btn").classList.contains("active")){
        document.querySelector("#file-overview .favourite-btn").classList.add("active");
      }else{
        document.querySelector("#file-overview .favourite-btn").classList.remove("active");
      }
    });
});
  var isShowMenu = false;
    var emailMenuSidebar = document.getElementsByClassName('file-manager-sidebar');
    Array.from(document.querySelectorAll(".file-menu-btn")).forEach(function (item) {
        item.addEventListener("click", function () {
          Array.from(emailMenuSidebar).forEach(function (elm) {
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
}

function removefileOverview() {
  var getid = 0;
  Array.from(document.querySelectorAll(".remove-file-overview")).forEach(function (item) {
    item.addEventListener('click', function (event) {
      getid = item.getAttribute('data-remove-id');
      document.getElementById("remove-fileitem").addEventListener("click", function () {
        var filtered = '';
        function arrayRemove(arr, value) {
          return arr.filter(function (ele) {
            return ele.id != value;
          });
        }
        filtered = arrayRemove(allFileList, getid);
        allFileList = filtered;
        loadFileData(allFileList);
        document.getElementById("close-removefilemodal").click();
        document.getElementsByTagName('body')[0].classList.remove("file-detail-show");
      });
    });
  });
}
removefileOverview();

function windowResize() {
  var windowSize = document.documentElement.clientWidth;
  if (windowSize < 1400) {
    document.body.classList.remove("file-detail-show");
  } else {
    document.body.classList.add("file-detail-show");
  }
}


windowResize();
window.addEventListener("resize", windowResize);

