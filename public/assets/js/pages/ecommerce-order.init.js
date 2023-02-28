/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Ecommerce-order Init Js File
*/

var str_dt = function formatDate(date) {
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var d = new Date(date),
        time_s = (d.getHours() + ':' + d.getMinutes());
        var t = time_s.split(":");
        var hours = t[0];
        var minutes = t[1];
        var newformat = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        month = '' + monthNames[(d.getMonth())],
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;
    return [day + " " + month+","+ year +" <small class='text-muted'>"+ hours + ':' + minutes + ' ' + newformat +"</small>"];
};

var isChoiceEl = document.getElementById("idStatus");
var choices = new Choices(isChoiceEl, {
    searchEnabled: false,
});

var isPaymentEl = document.getElementById("idPayment");
var choices = new Choices(isPaymentEl, {
    searchEnabled: false,
});

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
        "customer_name",
        "product_name",
        "date",
        "amount",
        "payment",
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
var orderList = new List("orderList", options).on("updated", function (list) {
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
        orderList.add({
            id: '<a href="apps-ecommerce-order-details.html" class="fw-medium link-primary">#VZ'+element.id+'</a>',
            customer_name: element.customer_name,
            product_name: element.product_name,
            date: str_dt(element.date),
            amount: element.amount,
            payment:element.payment,
            status: isStatus(element.status)
        });
        orderList.sort('id', { order: "desc" });
        refreshCallbacks();
    });
    orderList.remove("id", `<a href="apps-ecommerce-order-details.html" class="fw-medium link-primary">#VZ2101</a>`);
}
xhttp.open("GET", "assets/json/orders-list.init.json");
xhttp.send();

isCount = new DOMParser().parseFromString(
    orderList.items.slice(-1)[0]._values.id,
    "text/html"
);

var isValue = isCount.body.firstElementChild.innerHTML;

var idField = document.getElementById("orderId"),
    customerNameField = document.getElementById("customername-field"),
    productNameField = document.getElementById("productname-field"),
    dateField = document.getElementById("date-field"),
    amountField = document.getElementById("amount-field"),
    paymentField = document.getElementById("payment-field"),
    statusField = document.getElementById("delivered-status"),
    addBtn = document.getElementById("add-btn"),
    editBtn = document.getElementById("edit-btn"),
    removeBtns = document.getElementsByClassName("remove-item-btn"),
    editBtns = document.getElementsByClassName("edit-item-btn");
refreshCallbacks();
//filterOrder("All");

var tabEl = document.querySelectorAll('a[data-bs-toggle="tab"]');
Array.from(tabEl).forEach(function (item) {
    item.addEventListener("shown.bs.tab", function (event) {
        filterOrder(event.target.id);
    });
});

function filterOrder(isValue) {
    var values_status = isValue;
    orderList.filter(function (data) {
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

    orderList.update();
}

function updateList() {
    var values_status = document.querySelector("input[name=status]:checked").value;

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
        document.getElementById("exampleModalLabel").innerHTML = "Edit Order";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Update";
    } else if (e.relatedTarget.classList.contains("add-btn")) {
        document.getElementById("modal-id").style.display = "none";
        document.getElementById("exampleModalLabel").innerHTML = "Add Order";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Add Order";
    } else {
        document.getElementById("exampleModalLabel").innerHTML = "List Order";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "none";
    }
});
ischeckboxcheck();

document.getElementById("showModal").addEventListener("hidden.bs.modal", function () {
    clearFields();
});

document.querySelector("#orderList").addEventListener("click", function () {
    ischeckboxcheck();
});

var table = document.getElementById("orderTable");
// save all tr
var tr = table.getElementsByTagName("tr");
var trlist = table.querySelectorAll(".list tr");

function SearchData() {
    var isstatus = document.getElementById("idStatus").value;
    var payment = document.getElementById("idPayment").value;
    var pickerVal = document.getElementById("demo-datepicker").value;

    var date1 = pickerVal.split(" to ")[0];
    var date2 = pickerVal.split(" to ")[1];

    orderList.filter(function (data) {
        matchData = new DOMParser().parseFromString(
            data.values().status,
            "text/html"
        );
        var status = matchData.body.firstElementChild.innerHTML;
        var statusFilter = false;
        var paymentFilter = false;
        var dateFilter = false;

        if (status == "all" || isstatus == "all") {
            statusFilter = true;
        } else {
            statusFilter = status == isstatus;
        }

        if (data.values().payment == "all" || payment == "all") {
            paymentFilter = true;
        } else {
            paymentFilter = data.values().payment == payment;
        }

        if (
            new Date(data.values().date.slice(0, 12)) >= new Date(date1) &&
            new Date(data.values().date.slice(0, 12)) <= new Date(date2)
        ) {
            dateFilter = true;
        } else {
            dateFilter = false;
        }

        if (statusFilter && paymentFilter && dateFilter) {
            return statusFilter && paymentFilter && dateFilter;
        } else if (statusFilter && paymentFilter && pickerVal == "") {
            return statusFilter && paymentFilter;
        } else if (paymentFilter && dateFilter && pickerVal == "") {
            return paymentFilter && dateFilter;
        }
    });
    orderList.update();
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
                if (
                    customerNameField.value !== "" &&
                    productNameField.value !== "" &&
                    dateField.value !== "" &&
                    amountField.value !== "" &&
                    paymentField.value !== "" && !editlist
                ) {
                    orderList.add({
                        id: '<a href="apps-ecommerce-order-details.html" class="fw-medium link-primary">#VZ'+count+"</a>",
                        customer_name: customerNameField.value,
                        product_name: productNameField.value,
                        date: dateField.value,
                        amount: "$" + amountField.value,
                        payment: paymentField.value,
                        status: isStatus(statusField.value),
                    });
                    orderList.sort('id', { order: "desc" });
                    document.getElementById("close-modal").click();
                    clearFields();
                    refreshCallbacks();
                    filterOrder("All");
                    count++;
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Order inserted successfully!',
                        showConfirmButton: false,
                        timer: 2000,
                        showCloseButton: true
                    });
                } else if (
                    customerNameField.value !== "" &&
                    productNameField.value !== "" &&
                    dateField.value !== "" &&
                    amountField.value !== "" &&
                    paymentField.value !== "" && editlist
                ){
                    var editValues = orderList.get({
                        id: idField.value,
                    });
                    Array.from(editValues).forEach(function (x) {
                        isid = new DOMParser().parseFromString(x._values.id, "text/html");
                        var selectedid = isid.body.firstElementChild.innerHTML;
                        if (selectedid == itemId) {
                            x.values({
                                id: '<a href="javascript:void(0);" class="fw-medium link-primary">'+idField.value+"</a>",
                                customer_name: customerNameField.value,
                                product_name: productNameField.value,
                                date: dateField.value.slice(0, 14) +'<small class="text-muted">' +dateField.value.slice(14, 22),
                                amount: amountField.value,
                                payment: paymentField.value,
                                status: isStatus(statusField.value),
                            });
                        }
                    });
                    document.getElementById("close-modal").click();
                    clearFields();
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Order updated Successfully!',
                        showConfirmButton: false,
                        timer: 2000,
                        showCloseButton: true
                    });
                }
            }
        }, false)
    });
var example = new Choices(paymentField);
var statusVal = new Choices(statusField);
var productnameVal = new Choices(productNameField);

function isStatus(val) {
    switch (val) {
        case "Delivered":
            return (
                '<span class="badge badge-soft-success text-uppercase">' +
                val +
                "</span>"
            );
        case "Cancelled":
            return (
                '<span class="badge badge-soft-danger text-uppercase">' +
                val +
                "</span>"
            );
        case "Inprogress":
            return (
                '<span class="badge badge-soft-secondary text-uppercase">' +
                val +
                "</span>"
            );
        case "Pickups":
            return (
                '<span class="badge badge-soft-info text-uppercase">' + val + "</span>"
            );
        case "Returns":
            return (
                '<span class="badge badge-soft-primary text-uppercase">' +
                val +
                "</span>"
            );
        case "Pending":
            return (
                '<span class="badge badge-soft-warning text-uppercase">' +
                val +
                "</span>"
            );
    }
}

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
    if (removeBtns){
        Array.from(removeBtns).forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.target.closest("tr").children[1].innerText;
                itemId = e.target.closest("tr").children[1].innerText;
                var itemValues = orderList.get({
                    id: itemId,
                });
    
                Array.from(itemValues).forEach(function (x) {
                    deleteid = new DOMParser().parseFromString(x._values.id, "text/html");
    
                    var isElem = deleteid.body.firstElementChild;
                    var isdeleteid = deleteid.body.firstElementChild.innerHTML;
    
                    if (isdeleteid == itemId) {
                        document.getElementById("delete-record").addEventListener("click", function () {
                            orderList.remove("id", isElem.outerHTML);
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
                var itemValues = orderList.get({
                    id: itemId,
                });

                Array.from(itemValues).forEach(function (x) {
                    isid = new DOMParser().parseFromString(x._values.id, "text/html");
                    var selectedid = isid.body.firstElementChild.innerHTML;
                    if (selectedid == itemId) {
                        editlist = true;
                        idField.value = selectedid;
                        customerNameField.value = x._values.customer_name;
                        productNameField.value = x._values.product_name;
                        dateField.value = x._values.date;
                        amountField.value = x._values.amount;

                        if (example) example.destroy();
                        example = new Choices(paymentField, {
                            searchEnabled: false
                        });
                        var selected = x._values.payment;
                        example.setChoiceByValue(selected);

                        if (productnameVal) productnameVal.destroy();
                        productnameVal = new Choices(productNameField, {
                            searchEnabled: false,
                        });
                        var selectedproduct = x._values.product_name;
                        productnameVal.setChoiceByValue(selectedproduct);

                        if (statusVal) statusVal.destroy();
                        statusVal = new Choices(statusField, {
                            searchEnabled: false
                        });
                        val = new DOMParser().parseFromString(x._values.status, "text/html");
                        var statusSelec = val.body.firstElementChild.innerHTML;
                        statusVal.setChoiceByValue(statusSelec);

                        flatpickr("#date-field", {
                            enableTime: true,
                            dateFormat: "d M, Y, h:i K",
                            defaultDate: x._values.date,
                        });
                    }
                });
            });
        });
    }
}

function clearFields() {
    
    customerNameField.value = "";
    productNameField.value = "";
    dateField.value = "";
    amountField.value = "";
    paymentField.value = "";

    if (example) example.destroy();
    example = new Choices(paymentField);

    if (productnameVal) productnameVal.destroy();
    productnameVal = new Choices(productNameField);

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
                    orderList.remove("id", `<a href="apps-ecommerce-order-details.html" class="fw-medium link-primary">` + ids_array[i] +`</a>`);
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