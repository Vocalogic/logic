/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Ticket detail init js
*/

// favourite btn
Array.from(document.querySelectorAll(".favourite-btn")).forEach(function (item) {
    item.addEventListener("click", function (event) {
        this.classList.toggle("active");
    });
});

var str_dt = function formatDate(date) {
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
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

var ticket_list = localStorage.getItem("ticket-list");
var options = localStorage.getItem("option");
var ticket_no = localStorage.getItem("ticket_no");
var ticket = JSON.parse(ticket_list);

if (ticket) {
    document.getElementById("ticket-title").innerHTML = "#VLZ" + ticket_no + " - " + ticket.tasks_name;
    document.getElementById("t-no").innerHTML = ticket_no;
    document.getElementById("create-date").innerHTML = str_dt(ticket.create_date);
    document.getElementById("due-date").innerHTML = str_dt(ticket.due_date);
    document.getElementById("c-date").innerHTML = str_dt(ticket.create_date);
    document.getElementById("d-date").innerHTML = str_dt(ticket.due_date);

    let status_badge;
    switch (ticket.status) {
        case 'New':
            status_badge = "info";
            break;
        case 'Open':
            status_badge = "success";
            break;
        case 'Inprogress':
            status_badge = "warning";
            break;
        case 'Closed':
            status_badge = "danger";
    }

    let priority_badge;
    switch (ticket.priority) {
        case 'Low':
            priority_badge = "success";
            break;
        case 'Medium':
            priority_badge = "warning";
            break;
        case 'High':
            priority_badge = "danger";
    }

    document.getElementById("ticket-status").classList.replace("bg-info", "bg-" + status_badge);
    document.getElementById("ticket-status").innerHTML = ticket.status;
    document.getElementById("ticket-priority").classList.replace("bg-danger", "bg-" + priority_badge);
    document.getElementById("ticket-priority").innerHTML = ticket.priority;
    var div = document.createElement("div");
    div.innerHTML = ticket.status;
    document.getElementById("t-status").value = div.innerText;
    document.getElementById("t-priority").classList.replace("bg-danger", "bg-" + priority_badge);
    document.getElementById("t-priority").innerHTML = ticket.priority;
    document.getElementById("ticket-client").innerHTML = ticket.client_name;
    document.getElementById("t-client").innerHTML = ticket.client_name;
}