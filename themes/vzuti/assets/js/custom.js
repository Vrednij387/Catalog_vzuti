$(document).ready(function (){
    $('#show_text').click(function (){
        $('.additional-description').addClass('show');
        $(this).hide();

    });
    $('button.mobile-menu__close').click(function (){
        $('.col-mobile-menu-push').removeClass('show')
        $('#_mobile_iqitmegamenu-mobile').removeClass('show')
    })
    $('.mobile-menu__arrow').click(function (){
        $(this).parent().find('.mobile-menu__submenu').toggleClass('show');
        $(this).toggleClass('rotate')
    })


    document.addEventListener( 'scroll', event => {
        //console.log('scrollTop = ' + $(window).scrollTop())
        if($(window).scrollTop() > 70){
            $('.marketing-baner').hide();
        }
        else {
            $('.marketing-baner').show();
        }
    });

    $('#show_descr').click(function (){
        $('#above_description').addClass('show');
        $(this).hide();
    })

    /* faq */
    $('.faq-title').click(function (){
        $(this).parent().find('.faq-answer').toggleClass('open')
        $(this).toggleClass('rotate')
    })

    /* none-size */
    $('#none_size_open').click(function (){
        $('.none_size_info').addClass('open')
        $('.none_size .title').hide()


    })

    /* add rel="nofollow" */
   /* $('.footer_menu a').attr( 'rel', 'nofollow' );
    let lastFooterLink = document.querySelectorAll('.footer_menu a')

    console.log(lastFooterLink[5].setAttribute('rel', ''))*/


})