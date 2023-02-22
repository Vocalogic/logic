/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: apps-nft-item-details init js
*/

try {
    var setEndDate1 = "March 19, 2024 6:0:0";
    var setEndDate2 = "April 16, 2023 5:3:1";
    var setEndDate3 = "Dec 01, 2023 1:0:1";
    var setEndDate4 = "Nov 26, 2024 1:2:1";
    var setEndDate5 = "May 27, 2023 1:6:6";
    var setEndDate6 = "May 20, 2023 2:5:5";
    var setEndDate7 = "June 10, 2023 5:1:4";
    var setEndDate8 = "June 25, 2023 1:6:3";
    var setEndDate9 = "July 08, 2023 1:5:2";

    function startCountDownDate(dateVal) {
        var countDownDate = new Date(dateVal).getTime();
        return countDownDate;
    }

    function countDownTimer(start, targetDOM) {
        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = start - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor(
            (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
        );
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // add 0 at the beginning if days, hours, minutes, seconds values are less than 10
        days = days < 10 ? "0" + days : days;
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Output the result in an element with auction-item-x"
        document.querySelector("#" + targetDOM).textContent =
            days + " : " + hours + " : " + minutes + " : " + seconds;

        // If the count down is over, write some text
        if (distance < 0) {
            // clearInterval();
            document.querySelector("#" + targetDOM).textContent = "00 : 00 : 00 : 00";
        }
    }

    var cdd1 = startCountDownDate(setEndDate1);
    var cdd2 = startCountDownDate(setEndDate2);
    var cdd3 = startCountDownDate(setEndDate3);
    var cdd4 = startCountDownDate(setEndDate4);
    var cdd5 = startCountDownDate(setEndDate5);
    var cdd6 = startCountDownDate(setEndDate6);
    var cdd7 = startCountDownDate(setEndDate7);
    var cdd8 = startCountDownDate(setEndDate8);
    var cdd9 = startCountDownDate(setEndDate9);

    if (document.getElementById("auction-time-1"))
        setInterval(function () {
            countDownTimer(cdd1, "auction-time-1");
        }, 1000);
    if (document.getElementById("auction-time-2"))
        setInterval(function () {
            countDownTimer(cdd2, "auction-time-2");
        }, 1000);
    if (document.getElementById("auction-time-3"))
        setInterval(function () {
            countDownTimer(cdd3, "auction-time-3");
        }, 1000);
    if (document.getElementById("auction-time-4"))
        setInterval(function () {
            countDownTimer(cdd4, "auction-time-4");
        }, 1000);
    if (document.getElementById("auction-time-5"))
        setInterval(function () {
            countDownTimer(cdd5, "auction-time-5");
        }, 1000);
    if (document.getElementById("auction-time-6"))
        setInterval(function () {
            countDownTimer(cdd6, "auction-time-6");
        }, 1000);
    if (document.getElementById("auction-time-7"))
        setInterval(function () {
            countDownTimer(cdd7, "auction-time-7");
        }, 1000);
    if (document.getElementById("auction-time-8"))
        setInterval(function () {
            countDownTimer(cdd8, "auction-time-8");
        }, 1000);
    if (document.getElementById("auction-time-9"))
        setInterval(function () {
            countDownTimer(cdd9, "auction-time-9");
        }, 1000);
} catch (error) { }


// filter btn
var filterBtns = document.querySelectorAll(".filter-btns .nav-link");
var productItems = document.querySelectorAll(".product-item");

Array.from(filterBtns).forEach(function (button) {
  button.addEventListener("click", function (e) {
    e.preventDefault();

    for (var i = 0; i < filterBtns.length; i++) {
      filterBtns[i].classList.remove("active");
    }
    this.classList.add("active");

    var filter = e.target.dataset.filter;

    Array.from(productItems).forEach(function (item) {
      if (filter === "all") {
        item.style.display = "block";
      } else {
        if (item.classList.contains(filter)) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      }
    });
  });
});
