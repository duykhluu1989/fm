jQuery(function($) {
    $(".navbar-toggle").on("click", function() {
        $(this).toggleClass("active");
    });

    // $(window).on("load", function() {
    //     $(".content").mCustomScrollbar();
    // });

    //matchHeight columm
    $('.boxmH').matchHeight();

    //CENTERED MODALS
    $(function() {
        function reposition() {
            var modal = $(this),
                dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');

            // Dividing by two centers the modal exactly, but dividing by three 
            // or four works better for larger screens.
            dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
        }
        // Reposition when a modal is shown
        $('.modal').on('show.bs.modal', reposition);
        // Reposition when the window is resized
        $(window).on('resize', function() {
            $('.modal:visible').each(reposition);
        });
    });

    
    $('[data-toggle="tooltip"]').tooltip(); 


    /* banner */
    $('.owl_banner').owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        dots: true,
        // nav: true,
        // navText: ["<i class='fa fa-chevron-left fa-3x'></i>", "<i class='fa fa-chevron-right fa-3x'></i>"]
    });


    $(".animsition").animsition({
        inClass: 'fade-in',
        outClass: 'fade-out',
        // inClass: 'overlay-slide-in-top',
        // outClass: 'overlay-slide-out-top',

        inDuration: 1500,
        outDuration: 800,
        linkElement: '.animsition-link',
        // e.g. linkElement: 'a:not([target="_blank"]):not([href^="#"])'
        loading: true,
        loadingParentElement: 'body', //animsition wrapper element
        loadingClass: 'animsition-loading',
        loadingInner: '', // e.g '<img src="loading.svg" />'
        timeout: false,
        timeoutCountdown: 5000,
        onLoadEvent: true,
        browser: ['animation-duration', '-webkit-animation-duration'],
        // "browser" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
        // The default setting is to disable the "animsition" in a browser that does not support "animation-duration".
        overlay: false,
        overlayClass: 'animsition-overlay-slide',
        overlayParentElement: 'body',
        transition: function(url) { window.location.href = url; }
    });


    $(".datetime").datepicker({
        "option": $.datepicker.regional["vn"],
        dateFormat: 'dd-mm-yy',
    });

    $('#date').datepicker({
        beforeShowDay: function(date) {
            var day = date.getDate();
            if (day > 25) {
                return [false];
            } else {
                return [true];
            }
        }
    });
    

    $(".btnYes").click(function(event) {
        event.preventDefault();
        $(this).addClass('disabled');
        $('.btnNo').addClass('disabled');
        $(".contactInfo").slideToggle("slow");
        $('html, body').animate({
            scrollTop: $("#contactInfo").offset().top - 40
        }, 1000);

    });

    $('.scroll_to_top').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 800);
    });


});

// scroller.init();

$(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
        $('.scroll_to_top').fadeIn();
    } else {
        $('.scroll_to_top').fadeOut();
    }
});

//---------------------------------------------------



//---------------------------------------------------