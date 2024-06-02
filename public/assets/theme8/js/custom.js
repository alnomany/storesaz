/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
!function(e){e.fn.niceSelect=function(t){function s(t){t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class")||"").addClass(t.attr("disabled")?"disabled":"").attr("tabindex",t.attr("disabled")?null:"0").html('<span class="current"></span><ul class="list"></ul>'));var s=t.next(),n=t.find("option"),i=t.find("option:selected");s.find(".current").html(i.data("display")||i.text()),n.each(function(t){var n=e(this),i=n.data("display");s.find("ul").append(e("<li></li>").attr("data-value",n.val()).attr("data-display",i||null).addClass("option"+(n.is(":selected")?" selected":"")+(n.is(":disabled")?" disabled":"")).html(n.text()))})}if("string"==typeof t)return"update"==t?this.each(function(){var t=e(this),n=e(this).next(".nice-select"),i=n.hasClass("open");n.length&&(n.remove(),s(t),i&&t.next().trigger("click"))}):"destroy"==t?(this.each(function(){var t=e(this),s=e(this).next(".nice-select");s.length&&(s.remove(),t.css("display",""))}),0==e(".nice-select").length&&e(document).off(".nice_select")):console.log('Method "'+t+'" does not exist.'),this;this.hide(),this.each(function(){var t=e(this);t.next().hasClass("nice-select")||s(t)}),e(document).off(".nice_select"),e(document).on("click.nice_select",".nice-select",function(t){var s=e(this);e(".nice-select").not(s).removeClass("open"),s.toggleClass("open"),s.hasClass("open")?(s.find(".option"),s.find(".focus").removeClass("focus"),s.find(".selected").addClass("focus")):s.focus()}),e(document).on("click.nice_select",function(t){0===e(t.target).closest(".nice-select").length&&e(".nice-select").removeClass("open").find(".option")}),e(document).on("click.nice_select",".nice-select .option:not(.disabled)",function(t){var s=e(this),n=s.closest(".nice-select");n.find(".selected").removeClass("selected"),s.addClass("selected");var i=s.data("display")||s.text();n.find(".current").text(i),n.prev("select").val(s.data("value")).trigger("change")}),e(document).on("keydown.nice_select",".nice-select",function(t){var s=e(this),n=e(s.find(".focus")||s.find(".list .option.selected"));if(32==t.keyCode||13==t.keyCode)return s.hasClass("open")?n.trigger("click"):s.trigger("click"),!1;if(40==t.keyCode){if(s.hasClass("open")){var i=n.nextAll(".option:not(.disabled)").first();i.length>0&&(s.find(".focus").removeClass("focus"),i.addClass("focus"))}else s.trigger("click");return!1}if(38==t.keyCode){if(s.hasClass("open")){var l=n.prevAll(".option:not(.disabled)").first();l.length>0&&(s.find(".focus").removeClass("focus"),l.addClass("focus"))}else s.trigger("click");return!1}if(27==t.keyCode)s.hasClass("open")&&s.trigger("click");else if(9==t.keyCode&&s.hasClass("open"))return!1});var n=document.createElement("a").style;return n.cssText="pointer-events:auto","auto"!==n.pointerEvents&&e("html").addClass("no-csspointerevents"),this}}(jQuery);


$(document).ready(function() {
    /********* On scroll heder Sticky *********/
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
            $(".notification-bar").slideUp('slow');
        } else {
            $("header").removeClass("head-sticky");
            $(".notification-bar").slideDown('slow');
        }
    });
     /********* Wrapper top space ********/
     var header_hright = $('header').outerHeight();
     $('header').next('.wrapper').css('margin-top', header_hright + 'px');
    /********* Mobile Menu ********/
    $('.mobile-menu-button').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll active-menu');
            $(".mobile-menu-wrapper").toggleClass("active-menu");
            
        }, 50);
    });
    $('body').on('click','.overlay.menu-overlay, .menu-close-icon svg', function(e){
        e.preventDefault();
        $('body').removeClass('no-scroll active-menu');
        $(".mobile-menu-wrapper").removeClass("active-menu");
        $('.overlay').removeClass('menu-overlay');
    });
    /********* Cart Popup ********/
    $('.cart-header').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll cartOpen');
            $('.overlay').addClass('cart-overlay');
        }, 50);
    });
    $('body').on('click','.overlay.cart-overlay, .closecart', function(e){
        e.preventDefault();
        $('.overlay').removeClass('cart-overlay');
        $('body').removeClass('no-scroll cartOpen');
    });
    /********* Mobile Filter Popup ********/
    $('.filter-title').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll filter-open');
            $('.overlay').addClass('active');
        }, 50);
    });
    $('body').on('click','.overlay.active, .close-filter', function(e){
        e.preventDefault();
        $('.overlay').removeClass('active');
        $('body').removeClass('no-scroll filter-open');
    });
     /*********  Header Search Popup  ********/ 
     $(".search-header a").click(function() { 
        $(".omnisearch").toggleClass("show"); 
        $("body").addClass("no-scroll");
    });
    $(".search-header a").click(function() { 
        $(".overlay").addClass("active"); 
    });
    $('.remove-btn').click(function(){
        $("body").toggleClass("no-scroll");  
    });
    // $(".overlay").click(function() { 
    //     $(".overlay").removeClass("active"); 
    //     $(".omnisearch").removeClass("show"); 
    //     $("body").removeClass("no-scroll"); 
    // });
    /******* Cookie Js *******/
    $('.cookie-close').click(function () {
        $('.cookie').slideUp();
    });
    /******* Subscribe popup Js *******/
    $('.close-sub-btn').click(function () {
        $('.subscribe-popup').slideUp();
        $(".subscribe-overlay").removeClass("open");
    });
    /********* qty spinner ********/
    var quantity = 0;
    $('.quantity-increment').click(function(){;
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        $(t).val(quantity + 1);
    });
    $('.quantity-decrement').click(function(){
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        if(quantity > 1){
            $(t).val(quantity - 1);
        }
    });
    $("#checkout-btn").click(function() { 
        $("#Checkout").toggleClass("show"); 
        $("body").toggleClass("no-scroll");
        $(".mask-body").addClass("active");
    });
    $(".close-button").click(function() { 
        $("#Checkout").removeClass("show"); 
        $(".mask-body").removeClass("active");
        $("body").removeClass("no-scroll");
    });
    /******  Nice Select  ******/
    $('.custom-select').niceSelect();
    // $('select').niceSelect();
    /*********  Multi-level accordion nav  ********/
    $('.acnav-label').click(function () {
        var label = $(this);
        var parent = label.parent('.has-children');
        var list = label.siblings('.acnav-list');
        if (parent.hasClass('is-open')) {
            list.slideUp('fast');
            parent.removeClass('is-open');
        }
        else {
            list.slideDown('fast');
            parent.addClass('is-open');
        }
    });
    /****  TAB Js ****/
    $('ul.tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');
        $(this).closest('.tabs-wrapper').find('.tab-link').removeClass('active');
        $(this).addClass('active');
        $(this).closest('.tabs-wrapper').find('.tab-content').removeClass('active');
        $(this).closest('.tabs-wrapper').find('.tab-content#' + tab_id).addClass('active');
        $(this).closest('.tabs-wrapper').find('.slick-slider').slick('refresh');
    });

    if ($('.testimonial-slider').length > 0) {
        $('.testimonial-slider').slick({
            arrows: true,
            dots: false,
            infinite: true,
            speed: 800,
            slidesToShow:3,
            autoplay:true,
            prevArrow: '<button class="slide-arrow slick-prev"><i class="fa fa-chevron-right"></i></button>',
            nextArrow: '<button class="slide-arrow slick-next"><i class="fa fa-chevron-right"></i></button>',
            responsive: [
                {
                breakpoint: 1100,
                settings: {
                    slidesToShow:3,
                }
            },
              {
                breakpoint: 992,
                settings: {
                    slidesToShow:2,
                }
              },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow:2,
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow:1,
                }
              }
        ]
        });
    }

     /** PDP slider **/
     $('.pdp-main-slider').slick({
        dots: false,
        infinite: true,
        speed: 1000,
        loop: true,
        slidesToShow: 1,
        arrows: false,
        asNavFor: '.pdp-thumb-slider',
    });
    $('.pdp-thumb-slider').slick({
        prevArrow: '<button class="slide-arrow slick-prev"><i class="fa fa-chevron-right"></i></button>',
        nextArrow: '<button class="slide-arrow slick-next"><i class="fa fa-chevron-right"></i></button>',
        dots: false,
        asNavFor: '.pdp-main-slider',
        speed: 1000,
        slidesToScroll: 1,
        touchMove: true,
        focusOnSelect: true,
        loop: true,
        infinite: true,
        arrows:false,
        focusOnSelect: true,
        vertical: false,
        verticalSwiping: false,
        slidesToShow: 4,
        responsive: [{
                breakpoint: 1261,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 1260,
                    settings: {
                        vertical: false,
                        verticalSwiping:false,
                    }
                },
            {
                breakpoint: 992,
                    settings: {
                        vertical: false,
                        verticalSwiping:false,
                    }
                }
        ]
    });
    if ($('.related-product-slider').length > 0) {
        $('.related-product-slider').slick({
            arrows: true,
            dots: false,
            infinite: true,
            speed: 800,
            slidesToShow:4,
            prevArrow: '<button class="slide-arrow slick-prev"><i class="fa fa-chevron-right"></i></button>',
            nextArrow: '<button class="slide-arrow slick-next"><i class="fa fa-chevron-right"></i></button>',
            responsive: [
                {
                breakpoint: 1100,
                settings: {
                    slidesToShow:3,
                }
            },
              {
                breakpoint: 992,
                settings: {
                    slidesToShow:2,
                }
              },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow:2,
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow:1,
                }
              }
        ]
        });
    }
});


// Modal Window
(() => {
    const modalBtns = Array.from(document.querySelectorAll(".modal-target"));
    modalBtns.forEach(btn => {
      btn.onclick = function() {
        const modal = btn.getAttribute('data-modal');
        document.getElementById(modal).classList.toggle("active");
        document.querySelector("body").classList.toggle("no-scroll");
      }
    });
    const closeBtns = Array.from(document.querySelectorAll(".close-button"));
    closeBtns.forEach(btn => {
      btn.onclick = function() {
        let modal = btn.closest('.modal');
        btn.closest('.modal-popup').classList.toggle("active");
        document.querySelector("body").classList.toggle("no-scroll");
      }
    });
    window.onclick = function(event) {
      if (event.target.className === "modal") {
        event.target.style.display = "none";
      }
    }
})();
