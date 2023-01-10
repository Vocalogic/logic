// Admin Layout (LUNO)
// @author: WrrapTheme ( https://themeforest.net/user/wrraptheme )
// @design by: ThemeMakker Infotech LLP.
// @event-namespace: LUNO
// Copyright 2021 WrrapTheme

if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

// LUNO version update dynamic
window.addEventListener("load", () => {
    var first = document.getElementById("Changelog");
    first.outerHTML = '<span class="badge bg-primary ms-auto">v1.1.6</span>';
});

// general js project js
$(function() {
    "use strict";
    let root = document.documentElement;

    // main sidebar toggle js
    $('.menu-toggle').on('click', function () {
        $('.sidebar').toggleClass('open');
    });

    // Rightbar toggle js
    $('.btn-right a').on('click', function () {
        $('.rightbar').toggleClass('open');
    });

    // layout a sidebar mini version
    $('.sidebar-mini-btn').on('click', function () {
        $('.sidebar').toggleClass('sidebar-mini');
    });
    $(".hamburger-icon").on('click', function () {
        $(this).toggleClass("active");
    });
    $(".inbox .fa-star").on('click', function () {
        $(this).toggleClass("active");
    });

    // search result div show/hide
    $(".main-search input").on("focus",function(){
        $('.search-result').addClass("show");
    });
    $(".main-search input").on("blur",function(){
        setTimeout(function(){
            $('.search-result').removeClass("show");
        },200);
    });

    // google font setting
    $('.font_setting input:radio').on('click', function ()  {
		var others = $("[name='" + this.name + "']").map(function () {
			return this.value
		}).get().join(" ")
		console.log(others)
		$('body').removeClass(others).addClass(this.value)
    });

    // Prevents menu from closing when clicked inside header menu
    if(document.getElementById("NotificationsDiv")) {
        document.getElementById("NotificationsDiv").addEventListener('click', function (event) { 
            event.stopPropagation(); 
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    
});

// theme color setting
$(function() {
    "use strict";

    // main theme color setting js
    let root = document.documentElement;
    $('.choose-skin li').on('click', function() {
        var $body = $('body');
        var $this = $(this);
        var existTheme = $('.choose-skin li.active').data('theme');
        $('.choose-skin li').removeClass('active');
        $this.addClass('active');
        $body.attr('data-luno','theme-' + $this.data('theme'));
    });

    // Dynamic theme color setting
    $('#primaryColorPicker').colorpicker().on('changeColor', function() {
        root.style.setProperty('--primary-color', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#secondaryColorPicker').colorpicker().on('changeColor', function() {
        root.style.setProperty('--secondary-color', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#BodyColorPicker').colorpicker().on('changeColor', function() {
        root.style.setProperty('--body-color', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#CardColorPicker').colorpicker().on('changeColor', function() {
        root.style.setProperty('--card-color', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#BorderColorPicker').colorpicker().on('changeColor', function() {
        root.style.setProperty('--border-color', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#chartColorPicker1').colorpicker().on('changeColor', function() {
        root.style.setProperty('--chart-color1', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#chartColorPicker2').colorpicker().on('changeColor', function() {
        root.style.setProperty('--chart-color2', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#chartColorPicker3').colorpicker().on('changeColor', function() {
        root.style.setProperty('--chart-color3', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#chartColorPicker4').colorpicker().on('changeColor', function() {
        root.style.setProperty('--chart-color4', $(this).colorpicker('getValue', '#ffffff'));
    });
    $('#chartColorPicker5').colorpicker().on('changeColor', function() {
        root.style.setProperty('--chart-color5', $(this).colorpicker('getValue', '#ffffff'));
    });

    // LTR/RTL active js
    $('.theme-rtl input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('body').addClass("rtl_mode");
        } else {
            $('body').removeClass("rtl_mode");
        }
        var left = $('.scale-left');
        var right = $('.scale-right');
        left.addClass("scale-right");
        left.removeClass("scale-left");
        right.addClass("scale-left");
        right.removeClass("scale-right");
    });

    // 0. horizontal menu
    $('.h-menu-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('body').addClass("h-menu");
        } else {
            $('body').removeClass("h-menu");
        }
    });
    // 1. Page Header Fix
    $('.pageheader-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('.page-header').addClass("sticky-top");
        } else {
            $('.page-header').removeClass("sticky-top");
        }
    });
    // 2. Page Header dark
    $('.pageheader-dark-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('.page-header').addClass("dark");
        } else {
            $('.page-header').removeClass("dark");
        }
    });
    // 3. Border Radius none
    $('.radius-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('body').addClass("radius-0");
        } else {
            $('body').removeClass("radius-0");
        }
    });
    // 4. Enable Dark! ( Sidebar )
    $('.sidebardark-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('.sidebar').addClass("dark");
        } else {
            $('.sidebar').removeClass("dark");
        }
    });
    // 5. Background Image (Sidebar)
    $('.imagebg-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('.bg-images').addClass("show");
            $('.sidebar').addClass("sidebar-img-bg");
        } else {
            $('.bg-images').removeClass("show");
            $('.sidebar').removeClass("sidebar-img-bg");
        }
    });
    $('.sidebar-img').on('click', function () {
        var eleId = $(this).attr("id");
        eleId = eleId.split("-");
        root.style.setProperty('--sidebar-img', 'url(https://alui.thememakker.com/html/assets/images/sidebar-bg/sidebar-'+eleId[1]+'.jpg)');
        $('.sidebar-img-active').removeClass('sidebar-img-active');
        $('.sidebar-img-'+eleId[1]).addClass('sidebar-img-active');
    });
    // 6. Container Fluid
    $('.fluid-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('.container').addClass("container-fluid");
            $('.container').removeClass("container");
        } else {
            $('.container-fluid').addClass("container");
            $('.container-fluid').removeClass("container-fluid");
        }
    });
    // 7. Card box shadow
    $('.shadow-switch input:checkbox').on('click', function () {
        if($(this).is(":checked")) {
            $('.card').addClass("shadow-active");
        } else {
            $('.card').removeClass("shadow-active");
        }
    });
});

// light and dark theme setting js
$(function() {
    "use strict";
    var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
    var toggleHcSwitch = document.querySelector('.theme-high-contrast input[type="checkbox"]');
    var toggleTDSwitch = document.querySelector('.theme-dark input[type="checkbox"]');
    var currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);
    
        if (currentTheme === 'dark') {
            toggleSwitch.checked = true;
            toggleTDSwitch.checked = false;
            toggleHcSwitch.checked = false;
        }
        if (currentTheme === 'theme-dark') {
            toggleTDSwitch.checked = true;
            toggleHcSwitch.checked = false;
            toggleSwitch.checked = false;
        }
        if (currentTheme === 'high-contrast') {
            toggleHcSwitch.checked = true;
            toggleSwitch.checked = false;
            toggleTDSwitch.checked = false;
        }
    }
    function switchTheme(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            toggleHcSwitch.checked = false;
            toggleTDSwitch.checked = false;
        }
        else {        
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }    
    }
    function switchTD(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'theme-dark');
            localStorage.setItem('theme', 'theme-dark');
            toggleSwitch.checked = false;
            toggleHcSwitch.checked = false;
        }
        else {        
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }  
    }
    function switchHc(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'high-contrast');
            localStorage.setItem('theme', 'high-contrast');
            toggleSwitch.checked = false;
            toggleTDSwitch.checked = false;
        }
        else {        
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }  
    }
    toggleSwitch.addEventListener('change', switchTheme, false);
    toggleHcSwitch.addEventListener('change', switchHc, false);
    toggleTDSwitch.addEventListener('change', switchTD, false);

    // quick action light/dark
    $(".quick-light-dark").on("click",function() {
        $(this).toggleClass("active");
        var currentTheme = localStorage.getItem('theme');
        if(currentTheme == 'light') {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            $('#theme-switch').prop('checked', true);
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            $('#theme-switch').prop('checked', false);
        }
    });
});

// Setup new project page js
$(function() {
    $('.next').on('click',function(){

    var nextId = $(this).parents('.tab-pane').next().attr("id");
        $('a[href=\\#'+nextId+']').tab('show');
        return false;
    })

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        //update progress
        var step = $(e.target).data('bs-step');
        var percent = (parseInt(step) / 5) * 100;

        $('.progress-bar').css({width: percent + '%'});
        //$('.progress-bar').text("Step " + step + " of 5");
        //e.relatedTarget // previous tab
    })

    $('.first').on('click',function(){
        $('#myWizard a:first').tab('show')
    })
});

// Password Meter
$(function() {
    $(".password-meter .form-control").on("input", function() {
        var matchedCriteria = 0;            
        var password = $(this).val();
        var upperCase= new RegExp('[A-Z]');
        var lowerCase= new RegExp('[a-z]');
        var numbers = new RegExp('[0-9]');
        var specialChars = new RegExp('^(?=.*?[#?!@$%^&*-]).{1,}$');
        if(password.length > 7) {
            matchedCriteria++;
        }
        if(password.length > 0 && password.match(upperCase)) {
            matchedCriteria++;
        }
        if(password.length > 0 && password.match(lowerCase)) {
            matchedCriteria++;
        }
        if(password.length > 0 && password.match(numbers)) {
            matchedCriteria++;
        }
        if(password.length > 0 && password.match(specialChars)) {
            matchedCriteria++;
        }
        $(".password-meter .progress-bar")[0].style.width = (matchedCriteria*20)+"%";
    })
})

// Image file input
$(function() {
    $(".image-input .form-control").on("change",function(){
        var url = URL.createObjectURL(this.files[0]);
        $(this).parent().parent().children(".avatar-wrapper")[0].style.background = "url(" + url + ") no-repeat";
    })
})

// card full screen js
$(function() {
    const DIV_CARD = 'div.card';
    $('.card-fullscreen').on('click', function(e) {
        var $card = $(this).closest(DIV_CARD);

        $card.toggleClass('fullscreen');

        e.preventDefault();
        return false;
    });
})

// Do not add in your project
document.onkeydown = function (e) { 
    if (event.keyCode == 123) { 
      return false; 
    } 
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) { 
       return false; 
    } 
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) { 
       return false; 
    } 
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) { 
       return false; 
    } 
    if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) { 
      return false; 
    } 
}
$(document).on("contextmenu",function(e){
     return false;
});

var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5c6d4867f324050cfe342c69/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
})();