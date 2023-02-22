$(document).ready(function () {

    Livewire.on('initSignature', function () {
        const wrapper = document.getElementById("signature-pad");
        const canvas = wrapper.querySelector("canvas");
        const signaturePad = new SignaturePad(canvas, {
            // It's Necessary to use an opaque color when saving image as JPEG;
            // this option can be omitted if only saving as PNG or SVG
            backgroundColor: 'rgb(255, 255, 255)',
        });
        canvas.width = 750;
        signaturePad.addEventListener("endStroke", () => {
            // Once we have let go of the mouse, we will take the sigdata
            // and send it up to a session variable. This will be polled by
            // the execute LW component to see if a signature is found.
            const data = signaturePad.toDataURL();
            send("/signature/save", 'POST', {sig: data});
        }, { once: false });


    });

    // Dropify
    if ($('.drop').length) {
        $('.drop').dropify();
    }

    Livewire.on('initDrop', () => {
        $('.drop').dropify();
    });

    /**
     * New Confirmation SWAL Triggers
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

    /**
     * Global Render Error message call
     * @param message
     */
    function renderError(title, message = null) {

        let Toast = Swal.mixin({
            toast: true,
            position: 'center',
            showConfirmButton: false,
            timer: 4000
        });

        Toast.fire({
            text: message,
            title: title,
            icon: "error",
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
    }

    /**
     * Send a directed url through sbus
     */
    Livewire.on('assistDirect', function (url) {
        window.location.assign(url);
    });

    /**
     * Send a reload command via sbus
     */
    Livewire.on('assistReload', function () {
        window.location.reload();
    });

    $('.scroll-to').click(function (e) {
        e.preventDefault();
        let that = $(this);
        let target = that.attr('data-target');
        $('html, body').animate(
            {
                scrollTop: $(target).offset().top
            }, 500);

    });

    /**
     * Spawn a meta requirements gatherer from ItemCartComponent
     */
    Livewire.on('cartMetaModal', function (title, url) {
        let target = "#cartMetaModal";
        send(url, 'GET', null, function (data) {
            let modal = $(target);
            modal.find('.modal-title').text(title);
            modal.find('.modal-body').html(data);
            modal.modal('show');
        });
    });

    /**
     * Dismiss the cart modal and add to cart.
     */
    Livewire.on('dismissCartMeta', function () {
        let target = "#cartMetaModal";
        let modal = $(target);
        modal.modal('close');
    });

    /**
     * Send a message via a Modal
     */
    Livewire.on("sendMessage", function (message) {
        let target = $("#liveModal");
        target.find(".modal-body").html(message);
        target.modal('show');
    });


    Livewire.on('reinitSlider', function () {
        if ($('.product-main').hasClass('slick-initialized')) {
            $('.product-main').slick('destroy');
        }
        if ($('.left-slider-image').hasClass('slick-initialized')) {
            $('.left-slider-image').slick('destroy');
        }

        $('.product-main').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.left-slider-image'
        });

        $('.left-slider-image').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.product-main',
            dots: false,
            focusOnSelect: true,
            vertical: true,
            responsive: [{
                breakpoint: 1400,
                settings: {
                    vertical: false,
                }
            },
                {
                    breakpoint: 992,
                    settings: {
                        vertical: true,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        vertical: false,
                    }
                }, {
                    breakpoint: 430,
                    settings: {
                        slidesToShow: 3,
                        vertical: false,
                    }
                },
            ]
        });

    });
});
