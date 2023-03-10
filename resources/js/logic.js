require('./bootstrap');
import $ from 'jquery';
window.$ = window.jQuery = require('jquery');

import axios from 'axios';
import * as Ladda from 'ladda';
import ApexCharts from 'apexcharts'
import Swal from 'sweetalert2/dist/sweetalert2.min.js';
import 'datatables.net';
import 'datatables.net-dt/css/jquery.dataTables.css';
import tinymce from 'tinymce/tinymce';
import 'tinymce/models/dom/model';
import 'tinymce/themes/silver/theme';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';
import 'tinymce/icons/default/icons';
import 'dropify';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import 'fancybox';


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

    //Ladda.bind('button[type=submit]');
    if ($('.ladda').length)
    {
        Ladda.bind(document.querySelector('.ladda'));
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
        let calendar = new Calendar(cal, {
            plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
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

    if ($('.tinymce').length)
    {
        window.tinymce.dom.Event.domLoaded = true;
        tinymce.init({
            selector: 'textarea.tinymce',
            height: 500,
            plugins: [
                'link', 'image', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    }
    // Datatables
    if ($('.datatable').length) {
        $('.datatable')
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


    // Fancybox
    if ($('.fancybox').length) {
        $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });
    }

    /**
     * The .live class uses the main layout's modal init and uses
     * an ajax call using the send() method to populate the modal-body.
     */
    if ($('.live').length) {
        $('.live').click(function (e) {
            e.preventDefault();
            let that = $(this);
            let url = that.attr('href');
            let title = that.attr('data-title');
            let direction = that.attr('data-position') ? that.attr('data-position') : "center";
            let size = that.attr('data-size') ? that.attr('data-size') : 'modal-lg';
            let target;
            if (that.attr('data-target'))
            {
                target = that.attr('data-target') ? that.attr('data-target') : "#liveModal";
            }
            else {
                target = "#liveModal";
                switch (direction) {
                    case 'center' : target = "#liveModal";
                        break;
                    case 'left' :  target = "#liveLeft";
                        break;
                    case 'right' :  target = "#liveRight";
                        break;
                }
            }
            send(url, 'GET', null, function (data) {
                let modal = $(target);
                modal.find('.modal-dialog').addClass(size);
                modal.find('.modal-title').text(title);
                modal.find('.modal-body').html(data);
                let options = {backdrop: true, keyboard: true};
                let myModal = bootstrap.Modal.getOrCreateInstance(target, options)
                myModal.show();
                Ladda.bind('button[type=submit]');

            });
        });

        // Add a confirm on live loaders.
        $('#liveModal').on("hide.bs.modal", function (e) {
            if (!confirm("Changes may be unsaved, Click Cancel to return or Ok to leave.")) return false;
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
            timer: 45000
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
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
        Toast.fire({
            text: message,
            title: title,
            icon: "error",
        });
        Ladda.stopAll();
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
            let colors = that.attr('data-color') ? that.attr('data-color') : 'primary';
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
                    colors: ['var(--vz-' + colors + ')'],
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

    Livewire.on('initLadda', () => {
        Ladda.bind('button[type=submit]');
    });


    Livewire.on('initDatePicker', () => {
        $('.datepicker').datepicker({});
    });

    Livewire.on('openSearch', () => {
        $(".search-result").addClass("show");
    });

    Livewire.on('closeSearch', () => {
        $(".search-result").removeClass("show").addClass("hide");
    });


});
