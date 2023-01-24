require('./bootstrap');

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    })
    // Select 2 Init
    if ($('.select2').length) {
        $('.select2').select2();
    }

    // Dropify
    if ($('.drop').length) {
        $('.drop').dropify();
    }

    // Steps
    if ($('.steps').length) {
        $('.steps').steps({});
    }

    // DatePicker
    if ($('.datepicker').length) {
        $('.datepicker').datepicker({});
    }

    // Date Range Picker
    if ($('.dtrange').length) {
        $('.dtrange').daterangepicker(
            {
                timePicker: true,
                'locale': {
                    format: 'YYYY-MM-DD hh:mm A'
                }
            }
        );
    }


    /**
     * Init all Tooltips
     * @type {*[]}
     */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {sanitize: false, html: true, container: 'body'})
    })

    /**
     * Full Calendar Init for Events
     */
    if ($('.lcal').length) {

        let cal = document.getElementById('lcal');
        let data = $('.lcal').attr('data-url');
        let calendar = new FullCalendar.Calendar(cal, {
            timeZone: 'UTC',
            initialView: 'dayGridMonth',
            events: data,
            editable: false,
            selectable: true,
            eventMouseEnter: function (info) {
                var tis = info.el;
                var popup = info.event.extendedProps.popup;
                var tooltip = '<div class="tooltipevent" style="top:' + ($(tis).offset().top - 5) + 'px;left:' + ($(tis).offset().left + ($(tis).width()) / 2) + 'px"><div>' + popup.title + '</div><div>' + popup.descri + '</div></div>';
                var $tooltip = $(tooltip).appendTo('body');
            },
            eventMouseLeave: function (info) {
                $(info.el).css('z-index', 8);
                $('.tooltipevent').remove();
            },
        });
        let tab = $('.lcal').attr('data-inside');
        if (!tab) {
            calendar.render();
        } else {
            $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function (e) {
                console.log(e.target.href);
                let target = e.target.href;
                if (target.includes(tab))
                    calendar.render();
            });

        }

    }

    // Datatables
    if ($('.datatable').length) {
        $('.datatable')
            .addClass('nowrap')
            .dataTable({
                responsive: true,
            });
    }

    /**
     * Manual Toast on like a redirect back with message.
     */
    if ($('.toasted').length) {
        $('.toasted').each(function (e) {
            let that = $(this);
            let Toast = Swal.mixin({
                toast: true,
                position: 'bottom-right',
                animation: true,
                width: '400px',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });

            Toast.fire({
                text: that.attr('data-message'),
                title: that.attr('data-title'),
                icon: that.attr('data-icon'),
            });
        });
    }

    /**
     * Confirmation SWAL Triggers - Uses send method so you can use normal
     * callbacks
     */
    $('body').on('click', '.confirm', function (e) {
        var that = this;
        e.preventDefault();
        var method = $(this).attr('data-method') ? $(this).attr('data-method') : "PUT";
        var title = $(this).attr('data-title') ? $(this).attr('data-title') : "Are you sure?";
        var confirmText = $(this).attr('data-confirm') ? $(this).attr('data-confirm') : "Proceed";
        var icon = $(this).attr('data-icon') ? $(this).attr('data-icon') : "warning";
        var location = $(this).attr('href'); // Show confirmation.
        let loadingMessage = $(this).attr('data-loading') ? $(this).attr('data-loading') : "Please Wait..";

        Swal.fire({
            text: $(this).attr('data-message'),
            title: title,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: confirmText,
        }).then(function (result) {
            if (result.isConfirmed) {
                let result = send(location, method, null, null, null, loadingMessage);
            } else {
            }
        });
    });

    // Lead Rating
    if ($('.rate').length) {
        let that = $('.rate');
        let url = that.attr('data-url');
        $('.rate').barrating('show', {
            theme: 'bars-square',
            showValues: true,
            showSelectedRating: false,
            onSelect: function (value, text) {
                send(url, 'POST', {value: value});
            }
        });
    }

    // Fancybox
    if ($('.fancybox').length) {
        $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });
    }

    if ($('.live').length) {
        $('.live').click(function (e) {
            e.preventDefault();
            let that = $(this);
            let url = that.attr('href');
            let title = that.attr('data-title');
            let target = that.attr('data-target') ? that.attr('data-target') : "#liveModal";
            send(url, 'GET', null, function (data) {
                let modal = $(target);
                modal.find('.modal-title').text(title);
                modal.find('.modal-body').html(data);
                modal.modal('show');
            });
        });

        // Add a confirm on live loaders.
        $('#liveModal').on("hide.bs.modal", function (e) {
            if (!confirm("Changes may be unsaved, Click Cancel to return or Ok to leave.")) return false;
        });

    }

    if ($('.liveLeft').length) {
        $('.liveLeft').click(function (e) {
            e.preventDefault();
            let that = $(this);
            let url = that.attr('href');
            let title = that.attr('data-title');
            let target = that.attr('data-target') ? that.attr('data-target') : "#liveLeft";
            send(url, 'GET', null, function (data) {
                let modal = $(target);
                modal.find('.modal-title').text(title);
                modal.find('.modal-body').html(data);
                modal.modal('show');
            });
        });
    }

    if ($('.liveRight').length) {
        $('.liveRight').click(function (e) {
            e.preventDefault();
            let that = $(this);
            let url = that.attr('href');
            let title = that.attr('data-title');
            let target = that.attr('data-target') ? that.attr('data-target') : "#liveRight";
            send(url, 'GET', null, function (data) {
                let modal = $(target);
                modal.find('.modal-title').text(title);
                modal.find('.modal-body').html(data);
                modal.modal('show');
            });
        });
    }

    // X-Editable
    if ($('.xedit').length) {

        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.buttons =
            '<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
            '<i class="fa fa-fw fa-check"></i>' +
            '</button>' +
            '<button type="button" class="btn btn-warning btn-sm editable-cancel">' +
            '<i class="fa fa-fw fa-times"></i>' +
            '</button>';
        $('.xedit').each(function (e) {
            let that = $(this);
            let type = $(this).attr('data-type') ? $(this).attr('data-type') : 'text';
            let pk = $(this).attr('data-id');
            let field = $(this).attr('data-field');
            let title = $(this).attr('data-title');
            let url = $(this).attr('data-url');
            let source = $(this).attr('data-source');
            let after = $(this).attr('data-after');
            $(this).editable({
                type: type,
                pk: pk,
                name: field,
                title: title,
                url: url,
                emptytext: 'Click to Edit',
                source: source,
                success: function () {
                    if (after) {
                        renderWait(that, "Please Wait..", 'facebook', '.card');
                        eval(after);
                    }
                }
            });
        });
    }

    /**
     * AJAX Method with default handlers
     * @param url
     * @param method
     * @param data
     * @param success
     * @param self - The object that called this for reassignment.
     */
    function send(url, method, parameters = null, success = null, callback = null, loadingMessage = "Please Wait..") {
        let csrf = $('meta[name="csrf-token"]').attr('content');
        let Toast = Swal.mixin({
            toast: true,
            position: 'center',
            showConfirmButton: false,
            timer: 4000
        });

        Toast.fire({
            text: loadingMessage,
            title: "Processing",
            icon: "info",
        });
        axios({
            method: method,
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': "XMLHttpRequest"
            },
            data: parameters,
            responseType: 'json'

        }).then(function (response) {
            Toast.close();
            if (callback) {
                callback(response.data);
                return;
            }
            if (response.data.hasOwnProperty("error")) {
                renderError(response.data.title, response.data.error);
                return;
            }


            if (response.data.hasOwnProperty('callback')) {
                let args = response.data.callback.split(":");
                let name = args[0];
                switch (name) {
                    case 'reload' :
                        window.location.reload();
                        break;
                    case 'redirect' :
                        window.location.assign(args[1]);
                        break;
                    case 'success' :
                        if (args.length > 2)
                            renderSuccess(args[1], args[2]);
                        else
                            renderSuccess(args[1]);
                        break;
                    case 'reload.modal' : // Reload a modal without refreshing the page.
                        // Syntax: [callback => 'reload.modal:url
                        $('#modal').remove(); // Remove old modal
                        $('.modal-backdrop').remove(); // and backdrop.
                        $('#modalArea').load(
                            args[1],
                            function () {
                                $('#modal').modal({
                                    show: true
                                });
                            });
                        break;
                }

            }
            if (success != null) {
                success(response.data);
            }
            return response.data;       // Default a return of the raw data if we made it here.
        })
            .catch(function (error) {
                renderError("There was a problem executing your request. If this persists, please open a bug report! " + error);
            });
    }

    /**
     * Show a toast message on the top right of the screen.
     * @param title
     * @param message
     */
    function renderSuccess(title, message = null) {
        let Toast = Swal.mixin({
            toast: true,
            position: 'bottom-right',
            showConfirmButton: false,
            timer: 4000,
            width: '400px',
            animation: true,
            timerProgressBar: true
        });
        Toast.fire({
            text: message,
            title: title,
            icon: "success",
        });
    }

    /**
     * Global Render Error message call - same as success but red.
     * @param message
     */
    function renderError(title, message = null) {
        let Toast = Swal.mixin({
            toast: true,
            position: 'middle',
            width: '400px',
            animation: true,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
        Toast.fire({
            text: message,
            title: title,
            icon: "error",
        });
    }

    /**
     * This is used on most forms to just lock the card with a please wait message
     * unless an anchor is specified (such as in a modal, etc.)
     */
    $('body').on('click', '.wait', function () {
        let that = $(this);
        let message = that.attr('data-message') ? that.attr('data-message') : 'Please Wait..';
        let effect = that.attr('data-effect') ? that.attr('data-effect') : 'facebook';
        let anchor = that.attr('data-anchor') ? that.attr('data-anchor') : '.card';
        renderWait(that, message, effect, anchor);
    });

    /**
     * Render a wait based on a card based on the child given
     * @param obj
     */
    function renderWait(obj, message, effect, anchor) {
        let loading = $(obj).parents(anchor).waitMe({
            effect: effect,
            text: message,
            bg: "var(--card-color)",
            color: 'var(--color-900)'
        });
    }

    // AJAX Chart Renderer
    /**
     * Response should in include series data array. ['name' => Text, 'data' => [], etc
     */
    if ($('.lchart').length) {
        $('.lchart').each(function (e) {
            let that = $(this);
            let ctype = that.attr('data-type') ? that.attr('data-type') : 'bar';
            let spark = that.attr('data-spark') ? true : false;
            let cheight = that.attr('data-height') ? that.attr('data-height') : 350;
            let ctitle = that.attr('data-title') ? that.attr('data-title') : "Set data-title";
            let cwait = that.attr('data-wait') ? that.attr('data-wait') : "Loading, Please Wait..";
            let curl = that.attr('data-url') ? that.attr('data-url') : "#";
            let showToolbar = that.attr('data-disable-toolbar') ? false : true;
            let yAxis = that.attr('data-y') ? that.attr('data-y') : '';
            let xAxis = that.attr('data-xtype') ? that.attr('data-xtype') : 'category';
            let colors = that.attr('data-color') ? that.attr('data-color') : 1;
            let options = {};

            if (spark) {
                options = {
                    chart: {
                        type: ctype,
                        height: 50,
                        sparkline: {
                            enabled: true
                        },
                    },
                    series: [],
                    noData: {
                        text: cwait
                    },
                    colors: ['var(--chart-color' + colors + ')'],
                }
            } else {
                options = {
                    chart: {
                        height: cheight,
                        type: ctype,
                        toolbar: {
                            show: showToolbar
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    yaxis: [{
                        title: {
                            text: yAxis,
                        },
                    }],
                    xaxis: {
                        type: xAxis
                    },
                    series: [],
                    title: {
                        text: ctitle,
                    },
                    colors: ['var(--chart-color' + colors + ')'],
                    noData: {
                        text: cwait
                    }
                }
            }


            let chart = new ApexCharts(
                document.querySelector("#" + that.attr('id')),
                options
            );
            chart.render();

            axios({
                method: 'GET',
                url: curl,
            }).then(function (response) {
                chart.updateOptions(response.data);
            })
        });
    }


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
    });


    // -- Livewire Emitter Handlers  --

    Livewire.on('initDrop', () => {
        $('.drop').dropify();
    });

    Livewire.on('initDatePicker', () => {
        $('.datepicker').datepicker({});
    });

    Livewire.on('openSearch', () => {
        $(".search-result").addClass("show");
    });

});
