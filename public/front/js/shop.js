const quantityInput = document.querySelector('.quantity-input');
const minusButton = document.querySelector('.minus');
const plusButton = document.querySelector('.plus');

minusButton.addEventListener('click', () => {
    if (quantityInput.value > 1) {
        quantityInput.value--;
    }
});

plusButton.addEventListener('click', () => {
    quantityInput.value++;
});

const addImages = (gallery) => {
    $('.swiper-wrapper').each(function (index, wrapper) {
        $(wrapper).html('')
        gallery.forEach(image => {
            const swiperimage = $('<img>', {
                src: window.location.origin + '/' + image,
            });

            const swiperDiv = $('<div>', {
                class: 'swiper-slide'
            }).append(swiperimage);

            $(wrapper).append(swiperDiv);
        });

    })
    const swiper_thumbnail = new Swiper(".swiper_thumbnail", {
        slidesPerView: 4,
    })
    const swiper = new Swiper('.swiper_main', {
        loop: true,
        autoplay: {
            delay: 2000,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper_thumbnail,
        },
    })
}
let deleteCartItem = (event, slug) => {
    var cartJson = localStorage.getItem('cart');

    var cartObj = JSON.parse(cartJson);

    delete cartObj[slug];

    var updatedCartJson = JSON.stringify(cartObj);

    localStorage.setItem('cart', updatedCartJson);


    var $innerCart = $(event).closest('.inner-cart');
    var $nextElement = $innerCart.next();

    $innerCart.add($nextElement).effect('bounce', { times: 2, distance: 20 }, 'slow', function () {
        var $element = $(this);
        $element.slideUp(function () {
            $element.remove();
        });
    });
    toastrShow('Dropped!', `Product dropped.`, 'error', 'bottom-right')
}
let addToCart = (event) => {
    // localStorage.removeItem('cart');

    let product = $(event).data('detail');
    if (product.stock < Number($('#qty').val())) {
        toastrShow('Stock Limit', `You can order less than ${product.stock} units.`, 'error', 'bottom-right')
    }
    else {
        let cart = JSON.parse(localStorage.getItem('cart')) || {};

        cart[product.slug] = {
            name: product.name,
            price: Number(product.price),
            discount: Number(product.discount),
            image: product.image,
            qty: Number($('#qty').val())
        };

        localStorage.setItem('cart', JSON.stringify(cart));
        console.log(JSON.parse(localStorage.getItem('cart')));
        toastrShow('Cart', 'Added to cart!', 'success', 'bottom-right')
    }

}
$(document).on("click", ".quick-button", function () {
    $("#productmodal").modal({
        backdrop: true
    });
    let product = $(this).data('detail');
    $('#product-title').text(product['name']);
    $('#product-desc').html(product['short_desc']);
    addImages([product.image, ...product.images]);

    $('#qty').attr('max', product.stock)
    if (product.stock > 0) {

    }
    $('#addcart').attr('data-detail', JSON.stringify(product))
    $("#productmodal").modal('show');

});
let showCart = () => {
    let cart = JSON.parse(localStorage.getItem('cart'))
    $('#cartitems .parentrow').html('')
    let cartComponent = `<div class="col-md-12 inner-cart">
                            <div class="row">
                                <div class="col-md-3">
                                    <img class="img-fluid image" src="https://multimedia.bbycastatic.ca/multimedia/products/1500x1500/115/11534/11534527_2.jpg" alt="">
                                </div>
                                <div class="col-md-9">
                                    <div class="product-cart-header">
                                        <a href="" class="product-title">Product Title</a>
                                        <button class="btn btn-delete" onclick=""><i class="fa-solid fa-circle-xmark"></i></button>

                                    </div>
                                    <hr class="product">
                                    <div class="product-cart-desc">
                                        <p>Qty:<span class="qty">4 units</span></p>
                                        <p class="price">$4 <small>x4</small></p>

                                    </div>


                                </div>
                            </div>

                        </div>
                <hr class="product-cart-seprator">`


    Object.keys(cart).forEach(key => {
        const clonedCartHtml = $(cartComponent);
        clonedCartHtml.find('.image').attr('src', window.location.origin + '/' + cart[key]['image']);
        clonedCartHtml.find('.product-title').text(cart[key]['name'])
        clonedCartHtml.find('.qty').text(cart[key]['qty'])
        clonedCartHtml.find('.price').html(`$${cart[key]['price']} <small>x${cart[key]['qty']}</small>`)
        clonedCartHtml.find('.btn-delete').attr('onclick', `deleteCartItem(this,'${key}')`)
        $('#cartitems .parentrow').append($(clonedCartHtml).prop('outerHTML'))
        $('#cartitems .parentrow').append('<hr class="product-cart-seprator"/>')
    });

    $('#offcanvasRight').offcanvas('show');

}


