/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Ecommerce product create Js File
*/

// ckeditor
itemid = 13;
ClassicEditor
    .create(document.querySelector('#ckeditor-classic'))
    .then(function (editor) {
        editor.ui.view.editable.element.style.height = '200px';
    })
    .catch(function (error) {
        console.error(error);
    });

// Dropzone
var dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
dropzonePreviewNode.itemid = "";
var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);
var dropzone = new Dropzone(".dropzone", {
    url: 'https://httpbin.org/post',
    method: "post",
    previewTemplate: previewTemplate,
    previewsContainer: "#dropzone-preview",
});

// Form Event
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // date & time
    var date = new Date().toUTCString().slice(5, 16);
    function currentTime() {
        var ampm = new Date().getHours() >= 12 ? "PM" : "AM";
        var hour =
            new Date().getHours() > 12
                ? new Date().getHours() % 12
                : new Date().getHours();
        var minute =
            new Date().getMinutes() < 10
                ? "0" + new Date().getMinutes()
                : new Date().getMinutes();
        if (hour < 10) {
            return "0" + hour + ":" + minute + " " + ampm;
        } else {
            return hour + ":" + minute + " " + ampm;
        }
    }
    setInterval(currentTime, 1000);

    // product image
    document.querySelector("#product-image-input").addEventListener("change", function () {
        var preview = document.querySelector("#product-img");
        var file = document.querySelector("#product-image-input").files[0];
        var reader = new FileReader();
        reader.addEventListener("load",function () {
            preview.src = reader.result;
        },false);
        if (file) {
            reader.readAsDataURL(file);
        }
    });

    

    // choices category input
    var prdoctCategoryInput = new Choices('#choices-category-input', {
        searchEnabled: false,
    });
    
    var editinputValueJson = sessionStorage.getItem('editInputValue');
    if (editinputValueJson) {
        var editinputValueJson = JSON.parse(editinputValueJson);
        document.getElementById("formAction").value = "edit";
        document.getElementById("product-id-input").value = editinputValueJson.id;
        document.getElementById("product-img").src = editinputValueJson.product.img;
        document.getElementById("product-title-input").value = editinputValueJson.product.title;
        document.getElementById("stocks-input").value = editinputValueJson.stock;
        document.getElementById("product-price-input").value = editinputValueJson.price;
        document.getElementById("orders-input").value = editinputValueJson.orders;
        prdoctCategoryInput.setChoiceByValue(editinputValueJson.product.category);
    }

   
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();

                    itemid++;
                    var productItemID = itemid;
                    var productTitleValue = document.getElementById("product-title-input").value;
                    var prdoctCategoryValue = prdoctCategoryInput.getValue(true);
                    var stockInputValue = document.getElementById("stocks-input").value;
                    var orderValue = document.getElementById("orders-input").value;
                    var productPriceValue = document.getElementById("product-price-input").value;
                    var productImageValue = document.getElementById("product-img").src;

                    var formAction = document.getElementById("formAction").value;
                    if (formAction == "add") {
                            if (sessionStorage.getItem('inputValue') != null) {
                            var inputValueJson = JSON.parse(sessionStorage.getItem('inputValue'));
                            var newObj = {
                                "id": productItemID+1,
                                "product": {
                                    "img": productImageValue,
                                    "title": productTitleValue,
                                    "category": prdoctCategoryValue
                                },
                                "stock": stockInputValue,
                                "price": productPriceValue,
                                "orders": orderValue,
                                "rating": "--",
                                "published": {
                                    "publishDate": date,
                                    "publishTime": currentTime()
                                }
                            };
                            inputValueJson.push(newObj);
                            sessionStorage.setItem('inputValue', JSON.stringify(inputValueJson));
                        } else {
                            var inputValueJson = [];
                            var newObj = {
                                "id": productItemID,
                                "product": {
                                    "img": productImageValue,
                                    "title": productTitleValue,
                                    "category": prdoctCategoryValue
                                },
                                "stock": stockInputValue,
                                "price": productPriceValue,
                                "orders": orderValue,
                                "rating": "--",
                                "published": {
                                    "publishDate": date,
                                    "publishTime": currentTime()
                                }
                            };
                            inputValueJson.push(newObj);
                            sessionStorage.setItem('inputValue', JSON.stringify(inputValueJson));
                        }
                    } else if (formAction == "edit") {
                        var editproductId = document.getElementById("product-id-input").value;
                        if (sessionStorage.getItem('editInputValue')) {
                            var editObj = {
                                "id": parseInt(editproductId),
                                "product": {
                                    "img": productImageValue,
                                    "title": productTitleValue,
                                    "category": prdoctCategoryValue
                                },
                                "stock": stockInputValue,
                                "price": productPriceValue,
                                "orders": orderValue,
                                "rating": editinputValueJson.rating,
                                "published": {
                                    "publishDate": date,
                                    "publishTime": currentTime()
                                }
                            };
                            sessionStorage.setItem('editInputValue', JSON.stringify(editObj));
                        }
                    } else {
                        console.log('Form Action Not Found.');
                    }
                    window.location.replace("apps-ecommerce-products.html");
                    return false;
                }

                form.classList.add('was-validated');

            }, false)
        })
})()