/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: pricing init js
*/
if (document.querySelectorAll(".plan-nav .nav-item .nav-link")) {
    Array.from(document.querySelectorAll(".plan-nav .nav-item .nav-link")).forEach(function (e) {
        var month = document.getElementsByClassName("month");
        var annual = document.getElementsByClassName("annual");
        if (e.classList.contains("active") == true) {
            var i = 0;
            Array.from(month).forEach(function (m){
                annual[i].style.display = "none";
                m.style.display = "block";
                i ++;  
            });
        }
    });
}

if (document.getElementById("month-tab")) {
    document.getElementById("month-tab").addEventListener("click", function () {
        var month = document.getElementsByClassName("month");
        var annual = document.getElementsByClassName("annual");
        var i = 0;
        Array.from(month).forEach(function (m){
            if (annual[i]) annual[i].style.display = "none";
            if (m) m.style.display = "block";
            i ++;
        });
    });
}

if (document.getElementById("annual-tab")) {
    document.getElementById("annual-tab").addEventListener("click", function () {
        var month = document.getElementsByClassName("month");
        var annual = document.getElementsByClassName("annual");

        var i = 0;
        Array.from(month).forEach(function (m){
            if (annual[i]) annual[i].style.display = "block";
            if (m) m.style.display = "none";
            i ++;
        });
    });
}