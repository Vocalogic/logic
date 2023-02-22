/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: select2 init js
*/

// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.js-example-basic-single').select2();

    $('.js-example-basic-multiple').select2();

    var data = [
        {
            id: 0,
            text: 'enhancement'
        },
        {
            id: 1,
            text: 'bug'
        },
        {
            id: 2,
            text: 'duplicate'
        },
        {
            id: 3,
            text: 'invalid'
        },
        {
            id: 4,
            text: 'wontfix'
        }
    ];

    $(".js-example-data-array").select2({
    data: data
    })

    
});

var formatState = function (state) {
  if (!state.id) {
    return state.text;
  }
  var baseUrl = "assets/images/flags/select2";
  var $state = $(
    '<span><img src="' + baseUrl + '/' + state.element.value.toLowerCase() + '.png" class="img-flag rounded" height="18" /> ' + state.text + '</span>'
  );
  return $state;
};

$(".js-example-templating").select2({
  templateResult: formatState
});

var formatState = function (state) {
  if (!state.id) {
    return state.text;
  }

  var baseUrl = "assets/images/flags/select2";
  var $state = $(
    '<span><img class="img-flag rounded" height="18" /> <span></span></span>'
  );

  // Use .text() instead of HTML string concatenation to avoid script injection issues
  $state.find("span").text(state.text);
  $state.find("img").attr("src", baseUrl + "/" + state.element.value.toLowerCase() + ".png");

  return $state;
};

$(".select-flag-templating").select2({
  templateSelection: formatState
});


$(".js-example-disabled").select2();
$(".js-example-disabled-multi").select2();

$(".js-programmatic-enable").on("click", function () {
  $(".js-example-disabled").prop("disabled", false);
  $(".js-example-disabled-multi").prop("disabled", false);
});

$(".js-programmatic-disable").on("click", function () {
  $(".js-example-disabled").prop("disabled", true);
  $(".js-example-disabled-multi").prop("disabled", true);
});