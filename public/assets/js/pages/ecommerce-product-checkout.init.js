/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Ecommerce product Js File
*/


// Chocies Select plugin
document.addEventListener('DOMContentLoaded', function () {
    var genericExamples = document.querySelectorAll('[data-plugin="choices"]');
    if (genericExamples) {
        Array.from(genericExamples).forEach(function (genericExamp) {
            var element = genericExamp;
            new Choices(element, {
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'Search results here',
            });
        });
    }
});

// Checkout nav tab
var CheckoutTab = document.querySelectorAll(".checkout-tab");
if (CheckoutTab) {
    Array.from(document.querySelectorAll(".checkout-tab")).forEach(function (form) {

        // next tab
        var NextTab = form.querySelectorAll(".nexttab");
        if (NextTab) {
            Array.from(form.querySelectorAll(".nexttab")).forEach(function (nextButton) {
                var tabEl = form.querySelectorAll('button[data-bs-toggle="pill"]');
                if (tabEl) {
                    Array.from(tabEl).forEach(function (item) {
                        item.addEventListener('show.bs.tab', function (event) {
                            event.target.classList.add('done');
                        });
                    });
                    nextButton.addEventListener("click", function () {
                        var nextTab = nextButton.getAttribute('data-nexttab');
                        if (nextTab) {
                            document.getElementById(nextTab).click();
                        }
                    });
                }

            });
        }

        //Pervies tab
        var previesTab = document.querySelectorAll(".previestab");
        if (previesTab) {
            Array.from(form.querySelectorAll(".previestab")).forEach(function (prevButton) {

                prevButton.addEventListener("click", function () {
                    var prevTab = prevButton.getAttribute('data-previous');
                    if (prevTab) {
                        var totalDone = prevButton.closest("form").querySelectorAll(".custom-nav .done").length;
                        if (totalDone) {
                            for (var i = totalDone - 1; i < totalDone; i++) {
                                (prevButton.closest("form").querySelectorAll(".custom-nav .done")[i]) ? prevButton.closest("form").querySelectorAll(".custom-nav .done")[i].classList.remove('done'): '';
                            }
                            document.getElementById(prevTab).click();
                        }
                    }
                });
            });
        }

        // Step number click
        var tabButtons = form.querySelectorAll('button[data-bs-toggle="pill"]');
        if (tabButtons) {
            Array.from(tabButtons).forEach(function (button, i) {
                button.setAttribute("data-position", i);
                button.addEventListener("click", function () {
                    (form.querySelectorAll(".custom-nav .done").length > 0) ?
                    Array.from(form.querySelectorAll(".custom-nav .done")).forEach(function (doneTab) {
                        doneTab.classList.remove('done');
                    }): '';
                    for (var j = 0; j <= i; j++) {
                        tabButtons[j].classList.contains('active') ? tabButtons[j].classList.remove('done') : tabButtons[j].classList.add('done');
                    }
                });
            });
        }
    });
}