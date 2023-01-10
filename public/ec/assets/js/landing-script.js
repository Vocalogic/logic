/*=====================
   Hide header on scroll down js
   ==========================*/
$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $('header').addClass('active')
    } else {
        $('header').removeClass('active')
    }
});

$(window)
    .scroll(function () {
        var windscroll = $(window).scrollTop();
        if (windscroll >= 100) {
            $("section").each(function (i) {
                // The number at the end of the next line is how pany pixels you from the top you want it to activate.
                if ($(this).position().top <= windscroll - 0) {
                    $(".navbar-collapse .navbar-nav .nav-item .nav-link.active").removeClass("active");
                    $(".navbar-collapse .navbar-nav .nav-item .nav-link").eq(i).addClass("active");
                }
            });
        } else {
            $(".navbar-collapse .navbar-nav .nav-item .nav-link.active").removeClass("active");
            $(".navbar-collapse .navbar-nav .nav-item .nav-link:first").addClass("active");
        }
    })
    .scroll();