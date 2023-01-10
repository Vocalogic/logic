if ("undefined" == typeof jQuery) throw new Error("jQuery plugins need to be before this file");
window.addEventListener("load", () => {
    document.getElementById("Changelog").outerHTML = '<span class="badge bg-primary ms-auto">v1.1.7</span>'
}), $(function () {
    "use strict";
    document.documentElement;
    $(".menu-toggle").on("click", function () {
        $(".sidebar").toggleClass("open")
    }), $(".btn-right a").on("click", function () {
        $(".rightbar").toggleClass("open")
    }), $(".sidebar-mini-btn").on("click", function () {
        $(".sidebar").toggleClass("sidebar-mini")
    }), $(".hamburger-icon").on("click", function () {
        $(this).toggleClass("active")
    }), $(".inbox .fa-star").on("click", function () {
        $(this).toggleClass("active")
    }), $(".main-search input").on("focus", function () {
        $(".search-result").addClass("show")
    }), $(".main-search input").on("blur", function () {
        setTimeout(function () {
            $(".search-result").removeClass("show")
        }, 200)
    }), $(".font_setting input:radio").on("click", function () {
        var e = $("[name='" + this.name + "']").map(function () {
            return this.value
        }).get().join(" ");
        console.log(e), $("body").removeClass(e).addClass(this.value)
    }), $(".select-all.form-check-input").on("change", function () {
        var t = $(this).is(":checked"),
            e = $(this).parent().parent().parent().parent().parent().find(".row-selectable");
        0 < e.length && e.each(function (e) {
            $(this).find(".form-check-input")[0].checked = t
        })
    }), document.getElementById("NotificationsDiv") && document.getElementById("NotificationsDiv").addEventListener("click", function (e) {
        e.stopPropagation()
    });
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (e) {
        return new bootstrap.Tooltip(e)
    })
}), $(function () {
    "use strict";
    let t = document.documentElement;
    $(".choose-skin li").on("click", function () {
        var e = $("body"), t = $(this);
        $(".choose-skin li.active").data("theme");
        $(".choose-skin li").removeClass("active"), t.addClass("active"), e.attr("data-luno", "theme-" + t.data("theme"))
    }), $("#primaryColorPicker").colorpicker().on("changeColor", function () {
        t.style.setProperty("--primary-color", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#secondaryColorPicker").colorpicker().on("changeColor", function () {
        t.style.setProperty("--secondary-color", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#BodyColorPicker").colorpicker().on("changeColor", function () {
        t.style.setProperty("--body-color", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#CardColorPicker").colorpicker().on("changeColor", function () {
        t.style.setProperty("--card-color", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#BorderColorPicker").colorpicker().on("changeColor", function () {
        t.style.setProperty("--border-color", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#chartColorPicker1").colorpicker().on("changeColor", function () {
        t.style.setProperty("--chart-color1", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#chartColorPicker2").colorpicker().on("changeColor", function () {
        t.style.setProperty("--chart-color2", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#chartColorPicker3").colorpicker().on("changeColor", function () {
        t.style.setProperty("--chart-color3", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#chartColorPicker4").colorpicker().on("changeColor", function () {
        t.style.setProperty("--chart-color4", $(this).colorpicker("getValue", "#ffffff"))
    }), $("#chartColorPicker5").colorpicker().on("changeColor", function () {
        t.style.setProperty("--chart-color5", $(this).colorpicker("getValue", "#ffffff"))
    }), $(".theme-rtl input:checkbox").on("click", function () {
        $(this).is(":checked") ? $("body").addClass("rtl_mode") : $("body").removeClass("rtl_mode");
        var e = $(".scale-left"), t = $(".scale-right");
        e.addClass("scale-right"), e.removeClass("scale-left"), t.addClass("scale-left"), t.removeClass("scale-right")
    }), $(".h-menu-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? $("body").addClass("h-menu") : $("body").removeClass("h-menu")
    }), $(".pageheader-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? $(".page-header").addClass("sticky-top") : $(".page-header").removeClass("sticky-top")
    }), $(".pageheader-dark-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? $(".page-header").addClass("dark") : $(".page-header").removeClass("dark")
    }), $(".radius-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? $("body").addClass("radius-0") : $("body").removeClass("radius-0")
    }), $(".sidebardark-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? $(".sidebar").addClass("dark") : $(".sidebar").removeClass("dark")
    }), $(".imagebg-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? ($(".bg-images").addClass("show"), $(".sidebar").addClass("sidebar-img-bg")) : ($(".bg-images").removeClass("show"), $(".sidebar").removeClass("sidebar-img-bg"))
    }), $(".sidebar-img").on("click", function () {
        var e = (e = $(this).attr("id")).split("-");
        t.style.setProperty("--sidebar-img", "url(https://www.wrraptheme.com/templates/luno/assets/img/sidebar-bg/sidebar-" + e[1] + ".jpg)"), $(".sidebar-img-active").removeClass("sidebar-img-active"), $(".sidebar-img-" + e[1]).addClass("sidebar-img-active")
    }), $(".fluid-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? ($(".container").addClass("container-fluid"), $(".container").removeClass("container")) : ($(".container-fluid").addClass("container"), $(".container-fluid").removeClass("container-fluid"))
    }), $(".shadow-switch input:checkbox").on("click", function () {
        $(this).is(":checked") ? $(".card").addClass("shadow-active") : $(".card").removeClass("shadow-active")
    })
}), $(function () {
    "use strict";
    var t = document.querySelector('.theme-switch input[type="checkbox"]'),
        c = document.querySelector('.theme-high-contrast input[type="checkbox"]'),
        o = document.querySelector('.theme-dark input[type="checkbox"]'), e = localStorage.getItem("theme");
    e && (document.documentElement.setAttribute("data-theme", e), "dark" === e && (t.checked = !0, o.checked = !1, c.checked = !1), "theme-dark" === e && (o.checked = !0, c.checked = !1, t.checked = !1), "high-contrast" === e && (c.checked = !0, t.checked = !1, o.checked = !1)), t.addEventListener("change", function (e) {
        e.target.checked ? (document.documentElement.setAttribute("data-theme", "dark"), localStorage.setItem("theme", "dark"), c.checked = !1, o.checked = !1) : (document.documentElement.setAttribute("data-theme", "light"), localStorage.setItem("theme", "light"))
    }, !1), c.addEventListener("change", function (e) {
        e.target.checked ? (document.documentElement.setAttribute("data-theme", "high-contrast"), localStorage.setItem("theme", "high-contrast"), t.checked = !1, o.checked = !1) : (document.documentElement.setAttribute("data-theme", "light"), localStorage.setItem("theme", "light"))
    }, !1), o.addEventListener("change", function (e) {
        e.target.checked ? (document.documentElement.setAttribute("data-theme", "theme-dark"), localStorage.setItem("theme", "theme-dark"), t.checked = !1, c.checked = !1) : (document.documentElement.setAttribute("data-theme", "light"), localStorage.setItem("theme", "light"))
    }, !1), $(".quick-light-dark").on("click", function () {
        $(this).toggleClass("active"), "light" == localStorage.getItem("theme") ? (document.documentElement.setAttribute("data-theme", "dark"), localStorage.setItem("theme", "dark"), $("#theme-switch").prop("checked", !0)) : (document.documentElement.setAttribute("data-theme", "light"), localStorage.setItem("theme", "light"), $("#theme-switch").prop("checked", !1))
    })
}), $(function () {
    $(".next").on("click", function () {
        var e = $(this).parents(".tab-pane").next().attr("id");
        return $("a[href=\\#" + e + "]").tab("show"), !1
    }), $('a[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        e = $(e.target).data("bs-step"), e = parseInt(e) / 5 * 100;
        $("#CreateNew .progress-bar").css({width: e + "%"})
    }), $(".first").on("click", function () {
        $("#myWizard a:first").tab("show")
    })
}), $(function () {
    $(".password-meter .form-control").on("input", function () {
        var e = 0, t = $(this).val(), c = new RegExp("[A-Z]"), o = new RegExp("[a-z]"), r = new RegExp("[0-9]"),
            a = new RegExp("^(?=.*?[#?!@$%^&*-]).{1,}$");
        7 < t.length && e++, 0 < t.length && t.match(c) && e++, 0 < t.length && t.match(o) && e++, 0 < t.length && t.match(r) && e++, 0 < t.length && t.match(a) && e++, $(".password-meter .progress-bar")[0].style.width = 20 * e + "%"
    })
}), $(function () {
    $(".image-input .form-control").on("change", function () {
        var e = URL.createObjectURL(this.files[0]);
        $(this).parent().parent().children(".avatar-wrapper")[0].style.background = "url(" + e + ") no-repeat"
    })
}), $(function () {
    $(".card-fullscreen").on("click", function (e) {
        return $(this).closest("div.card").toggleClass("fullscreen"), e.preventDefault(), !1
    })
});
