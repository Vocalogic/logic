/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: apps-nft-explore init js
*/


var url="assets/json/";
var allproductlist = '';

//mail list by json
var getJSON = function (jsonurl, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url + jsonurl, true);
    xhr.responseType = "json";
    xhr.onload = function () {
        var status = xhr.status;
        if (status === 200) {
            callback(null, xhr.response);
        } else {
            callback(status, xhr.response);
        }
    };
    xhr.send();
};

// get json
getJSON("nft-explore-product-list.json", function (err, data) {
    if (err !== null) {
        console.log("Something went wrong: " + err);
    } else {
        allproductlist = data;
        loadProductData(allproductlist);
    }
});


// load mail data
function loadProductData(datas) {
    document.querySelector("#explorecard-list").innerHTML = '';

    Array.from(datas).forEach(function (prodctData, index) {
        var likeBtn = prodctData.like ? "active" : "";
        document.querySelector("#explorecard-list").innerHTML +=
            '<div class="col list-element">\
            <div class="card explore-box card-animate">\
                <div class="explore-place-bid-img">\
                    <input type="hidden" class="form-control" id="'+ prodctData.id + '">\
                    <div class="d-none">'+ prodctData.salesType + '</div>\
                    <img src="'+ prodctData.productImg + '" alt="" class="card-img-top explore-img" />\
                    <div class="bg-overlay"></div>\
                    <div class="place-bid-btn">\
                        <a href="#!" class="btn btn-success"><i class="ri-auction-fill align-bottom me-1"></i> Place Bid</a>\
                    </div>\
                </div>\
                <div class="bookmark-icon position-absolute top-0 end-0 p-2">\
                    <button type="button" class="btn btn-icon '+ likeBtn + '" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>\
                </div>\
                <div class="card-body">\
                    <p class="fw-medium mb-0 float-end"><i class="mdi mdi-heart text-danger align-middle"></i> '+ prodctData.totalLikes + ' </p>\
                    <h5 class="mb-1"><a href="apps-nft-item-details.html">'+ prodctData.title + '</a></h5>\
                    <p class="text-muted mb-0">'+ prodctData.category + '</p>\
                </div>\
                <div class="card-footer border-top border-top-dashed">\
                    <div class="d-flex align-items-center">\
                        <div class="flex-grow-1 fs-14">\
                            <i class="ri-price-tag-3-fill text-warning align-bottom me-1"></i> Highest: <span class="fw-medium">'+ prodctData.highBid + '</span>\
                        </div>\
                        <h5 class="flex-shrink-0 fs-14 text-primary mb-0">'+ prodctData.price + 'ETH</h5>\
                    </div>\
                </div>\
            </div>\
        </div>';
        loadMoreBtn();
    });
}

// Search product list
var searchProductList = document.getElementById("searchProductList");
searchProductList.addEventListener("keyup", function () {
    var inputVal = searchProductList.value.toLowerCase();
    function filterItems(arr, query) {
        return arr.filter(function (el) {
            return el.title.toLowerCase().indexOf(query.toLowerCase()) !== -1
        })
    }

    var filterData = filterItems(allproductlist, inputVal);
    if (filterData.length == 0) {
        document.getElementById("noresult").style.display = "block";
        document.getElementById("loadmore").style.display = "none";
    } else {
        document.getElementById("noresult").style.display = "none";
        document.getElementById("loadmore").style.display = "block";
    }
    loadProductData(filterData);
});

// choices category input
var productCategoryInput = new Choices(document.getElementById('select-category'), {
    searchEnabled: false,
});

productCategoryInput.passedElement.element.addEventListener('change', function (event) {
    var productCategoryValue = event.detail.value
    if (event.detail.value) {
        var filterData = allproductlist.filter(productlist => productlist.category === productCategoryValue);
        if (filterData.length == 0) {
            document.getElementById("noresult").style.display = "block";
            document.getElementById("loadmore").style.display = "none";
        } else {
            document.getElementById("noresult").style.display = "none";
            document.getElementById("loadmore").style.display = "block";
        }
    } else {
        var filterData = allproductlist;
    }
    loadProductData(filterData);
}, false);


// choices file-type
var productFileTypeInput = new Choices(document.getElementById('file-type'), {
    searchEnabled: false,
});

productFileTypeInput.passedElement.element.addEventListener('change', function (event) {
    var productFileTypeValue = event.detail.value
    if (event.detail.value) {
        var filterData = allproductlist.filter(productlist => productlist.productImg.split('.').pop() == productFileTypeValue);
        if (filterData.length == 0) {
            document.getElementById("noresult").style.display = "block";
            document.getElementById("loadmore").style.display = "none";
        } else {
            document.getElementById("noresult").style.display = "none";
            document.getElementById("loadmore").style.display = "block";
        }
    } else {
        var filterData = allproductlist;
    }
    loadProductData(filterData);
}, false);


// choices category input
var productCategoryInput = new Choices(document.getElementById('select-category'), {
    searchEnabled: false,
});

productCategoryInput.passedElement.element.addEventListener('change', function (event) {
    var productCategoryValue = event.detail.value
    if (event.detail.value) {
        var filterData = allproductlist.filter(productlist => productlist.category === productCategoryValue);
        if (filterData.length == 0) {
            document.getElementById("noresult").style.display = "block";
            document.getElementById("loadmore").style.display = "none";
        } else {
            document.getElementById("noresult").style.display = "none";
            document.getElementById("loadmore").style.display = "block";
        }
    } else {
        var filterData = allproductlist;
    }
    loadProductData(filterData);
}, false);


// choices sales input
var productSalesInputInput = new Choices(document.getElementById('all-sales-type'), {
    searchEnabled: false,
});

productSalesInputInput.passedElement.element.addEventListener('change', function (event) {
    var productCategoryValue = event.detail.value
    if (event.detail.value) {
        var filterData = allproductlist.filter(productlist => productlist.salesType === productCategoryValue);
        if (filterData.length == 0) {
            document.getElementById("noresult").style.display = "block";
            document.getElementById("loadmore").style.display = "none";
        } else {
            document.getElementById("noresult").style.display = "none";
            document.getElementById("loadmore").style.display = "block";
        }
    } else {
        var filterData = allproductlist;
    }
    loadProductData(filterData);
}, false);


/*********************
    range-product-price
**********************/
var rangeProductPrice = document.getElementById('range-product-price');

noUiSlider.create(rangeProductPrice, {
    start: [0, 1000], // Handle start position
    step: 10, // Slider moves in increments of '10'
    margin: 20, // Handles must be more than '20' apart
    connect: true, // Display a colored bar between the handles
    behaviour: 'tap-drag', // Move handle on tap, bar is draggable
    tooltips: [true, true],
    range: { // Slider can select '0' to '100'
        'min': 0,
        'max': 2000
    },
    format: wNumb({ decimals: 0 })
});

mergeTooltips(rangeProductPrice, 5, ' - ');

var minCostInput = document.getElementById('minCost'),
    maxCostInput = document.getElementById('maxCost');

var filterDataAll = '';

// When the slider value changes, update the input and span
rangeProductPrice.noUiSlider.on('change', function (values, handle) {
    var productListupdatedAll = allproductlist;
    if (handle) {
        maxCostInput.value = values[handle];

    } else {
        minCostInput.value = values[handle];
    }

    var maxvalue = maxCostInput.value;
    var minvalue = minCostInput.value;
    var filterDataAll = productListupdatedAll.filter(
        product => parseFloat(product.price) >= minvalue && parseFloat(product.price) <= maxvalue
    );
    loadProductData(filterDataAll);
});

/**
 * @param slider HtmlElement with an initialized slider
 * @param threshold Minimum proximity (in percentages) to merge tooltips
 * @param separator String joining tooltips
 */
function mergeTooltips(slider, threshold, separator) {

    var textIsRtl = getComputedStyle(slider).direction === 'rtl';
    var isRtl = slider.noUiSlider.options.direction === 'rtl';
    var isVertical = slider.noUiSlider.options.orientation === 'vertical';
    var tooltips = slider.noUiSlider.getTooltips();
    var origins = slider.noUiSlider.getOrigins();

    // Move tooltips into the origin element. The default stylesheet handles this.
    Array.from(tooltips).forEach(function (tooltip, index) {
        if (tooltip) {
            origins[index].appendChild(tooltip);
        }
    });
    if (slider)
        slider.noUiSlider.on('update', function (values, handle, unencoded, tap, positions) {

            var pools = [
                []
            ];
            var poolPositions = [
                []
            ];
            var poolValues = [
                []
            ];
            var atPool = 0;

            // Assign the first tooltip to the first pool, if the tooltip is configured
            if (tooltips[0]) {
                pools[0][0] = 0;
                poolPositions[0][0] = positions[0];
                poolValues[0][0] = values[0];
            }

            for (var i = 1; i < positions.length; i++) {
                if (!tooltips[i] || (positions[i] - positions[i - 1]) > threshold) {
                    atPool++;
                    pools[atPool] = [];
                    poolValues[atPool] = [];
                    poolPositions[atPool] = [];
                }

                if (tooltips[i]) {
                    pools[atPool].push(i);
                    poolValues[atPool].push(values[i]);
                    poolPositions[atPool].push(positions[i]);
                }
            }

            Array.from(pools).forEach(function (pool, poolIndex) {
                var handlesInPool = pool.length;

                for (var j = 0; j < handlesInPool; j++) {
                    var handleNumber = pool[j];

                    if (j === handlesInPool - 1) {
                        var offset = 0;

                        Array.from(poolPositions[poolIndex]).forEach(function (value) {
                            offset += 1000 - value;
                        });

                        var direction = isVertical ? 'bottom' : 'right';
                        var last = isRtl ? 0 : handlesInPool - 1;
                        var lastOffset = 1000 - poolPositions[poolIndex][last];
                        offset = (textIsRtl && !isVertical ? 100 : 0) + (offset / handlesInPool) - lastOffset;

                        // Center this tooltip over the affected handles
                        tooltips[handleNumber].innerHTML = poolValues[poolIndex].join(separator);
                        tooltips[handleNumber].style.display = 'block';
                        tooltips[handleNumber].style[direction] = offset + '%';
                    } else {
                        // Hide this tooltip
                        tooltips[handleNumber].style.display = 'none';
                    }
                }
            });
        });
}


// loadmore Js
function _toConsumableArray(arr) {
    if (Array.isArray(arr)) {
        for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) {
            arr2[i] = arr[i];
        }
        return arr2;
    } else {
        return Array.from(arr);
    }
}

function loadMoreBtn() {
    var loadmore = document.querySelector("#loadmore");
    if (loadmore) {
        var currentItems = 10;
        loadmore.addEventListener("click", function (e) {

            var elementList = [].concat(
                _toConsumableArray(document.querySelectorAll("#explorecard-list .list-element"))
            );
            if (elementList) {
                for (var i = currentItems; i < currentItems + 5; i++) {
                    if (elementList[i]) {
                        elementList[i].style.display = "block";
                    }
                }
                currentItems += 5;

                // Load more button will be hidden after list fully loaded
                if (currentItems >= elementList.length) {
                    event.target.style.display = "none";
                }
            }
        });
    }
}