
(function( $ ) {

    /* Vlastní funkce
    ========================================================*/

    function get_lang(){
        if ( $("body").hasClass( 'english' ) ) {
            return "en";
        } else {
            return "cs";
        }
    }

    /* Jednotlivé akce
    ========================================================*/

    //sidebar menu functionality
    jQuery(document).ready( function(){  
                
        //mobilní menu
        $( "#mobile-menu li" ).each(function( index, element ){

            var classes = $(this).attr('class');

            //add arrows to elements with children  
            if (  classes.indexOf("menu-item-has-children") >= 0 ) {

                $(this).prepend('<i class="arrow"> </i>');
            }  

        });

        //click functionality
        $("#mobile-menu .arrow").click(function(){
              
            if ( $("#mobile-menu > .menu-item-has-children").hasClass("showing-ul") ) {
                //exclude this element
                var myParent = $(this).closest("#mobile-menu > .menu-item-has-children");
                $("#mobile-menu > .menu-item-has-children").not(myParent).removeClass("showing-ul").children(".sub-menu").slideUp();
            }

            //show signling
            if ( $(this).parent().hasClass("showing-ul") )  {
                $(this).siblings(".sub-menu").slideUp();
                $(this).parent().removeClass("showing-ul");
            } else {
                //
                $(this).siblings(".sub-menu").slideDown();
                $(this).parent().addClass("showing-ul");
            }       

        });

        //block gallery
        if ( $('.wp-block-gallery').length > 0 ) {          
            //fire up gallery
            $('.wp-block-gallery').lightGallery({
                selector : '.wp-block-image a'
            });
        }

		//lightgallery
		if ( $('.lightgallery').length > 0 ) {
		    //fire up individual gallery
			$('.lightgallery').lightGallery();
		}

		//entry content gallery image
		if ( $('.entry-content .wp-block-image a img').length > 0 ) {
			//add gallery items src
			$('a', $(".entry-content .wp-block-image")).each(function () {
			    
			    var src = $(this).attr('href');
			    var caption = $(this).next('figcaption').text();

			    if ( src !== undefined && src !== "" ) {
			    	$(this).attr('data-src', src);
			    } 
			    if ( caption !== undefined && caption !== "" ) {
			    	$(this).closest( 'a' ).attr('data-sub-html', caption);
			    }

			});

			$(".entry-content .wp-block-image a").click( function (e){
				e.preventDefault();
			});

			//fire up gallery
		    $('.entry-content .wp-block-image').lightGallery({
		    	selector: '.entry-content .wp-block-image a'
		    });
		}

        if ( jQuery(".digi-block-references-slider").length > 0 ) {
             
            jQuery('.digi-block-references-slider').slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                arrows: true, 
                //dots: false,
                infinite: true,
                fade: false,
                speed: 1000,
                //variableWidth: false,
                prevArrow: '<span class="slick-prev"></span>',
                nextArrow: '<span class="slick-next"></span>',
                responsive: [
                    {
                      breakpoint: 1024,
                      settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,

                      }
                    },
                    {
                      breakpoint: 800,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 480,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    }
        
                  ]
            }); 

        }

    }); 

    //show or hide the top menu
    $(window).on( "scroll load", function() {

        if ($(window).scrollTop() >= 50 ) {
            
            if ( !$('body').hasClass('scrolled')){

                $('body').addClass('scrolled');
                $('#main-menu-container').hide().fadeIn(); 

            }
                                                  
        //reverse it back    
        } else if ($(window).scrollTop() < 50){
            
            if ($('body').hasClass('scrolled')){

                $('body').removeClass('scrolled');

            }

        }
            
    });

    //init on load
    jQuery(window).on( "load", function() {
        var slideout = new Slideout({
            'panel': document.getElementById('panel'),
            'menu': document.getElementById('menu'),
            'padding': 256,
            'side': 'right',
            'tolerance': 70
        });

        $('.js-slideout-toggle, .slideout-menu .close-button').on('click', function(){
            slideout.toggle();
        }); 


        (function ($) {

            var rankMath = {
                accordion: function () {
    
                    $('.rank-math-block').find('.rank-math-answer').hide();
    
                    $('.rank-math-block').find('.rank-math-question').click(function () {  
    
                        //global class
                        $('.rank-math-block').addClass('active');
    
                        //Expand or collapse this panel
                        $(this).nextAll('.rank-math-answer').eq(0).slideToggle('fast', function () {
                            if ($(this).hasClass('collapse')) {
                                $(this).removeClass('collapse');
    
                                $('.rank-math-block').removeClass('active');
    
                            } else {
                                $(this).addClass('collapse');
                            }
                        });
                        //Hide the other panels
                        $(".rank-math-answer").not($(this).nextAll('.rank-math-answer').eq(0)).slideUp('fast');
    
    
                    });
        
                    $('.rank-math-block .rank-math-question').click(function () {
                        $('.rank-math-block .rank-math-question').not($(this)).removeClass('collapse');
                        if ($(this).hasClass('collapse')) {
                            $(this).removeClass('collapse');
                        }
                        else {
                            $(this).addClass('collapse');
                        }
                    });
                }
            };
        
            rankMath.accordion();
        
        })(jQuery);
    });

    // Cookie banner
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Define dataLayer and the gtag function.
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }

    $(document).ready(function () {
        var consent_cookie = getCookie('cc_cookie');
        if (!consent_cookie) {
            gtag('consent', 'default', {
                'functional_storage': 'granted',
                'security_storage': 'granted',
                'analytics_storage': 'denied',
                'personalization_storage': 'denied',
                'ad_storage': 'denied',
                'ad_user_data': 'denied',
                'ad_personalization': 'denied'
            });
        }

        var cookieconsent = initCookieConsent();
        cookieconsent.run({
            current_lang: 'cs',
            page_scripts: true,
            autorun: true,
            delay: 0,
            autoclear_cookies: true,
            theme_css: globaldata.theme_url + '/assets/styles/specific-css/cookiebanner.css',
            gui_options: {
                consent_modal: {
                    layout: 'box',               // box/cloud/bar 
                    position: 'top center',     // bottom/top + left/right/center
                    transition: 'slide'             // zoom/slide
                },
                settings_modal: {
                    layout: 'box',                 // box/bar
                    transition: 'slide',            // zoom/slide
                }
            },

            onAccept: function (cookies) {
                if ( consent_cookie ) {
                    gtag('consent', 'update', {
                      'functional_storage' : 'granted'
                    });
                    gtag('consent', 'update', {
                      'security_storage' : 'granted'
                    });
                    if(!cookieconsent.allowedCategory('performance')){
                      gtag('consent', 'update', {
                        'analytics_storage' : 'denied'
                      });
                      gtag('consent', 'update', {
                        'personalization_storage' : 'granted'
                      });
                    }
                    if(!cookieconsent.allowedCategory('tracking')){
                      gtag('consent', 'update', {
                        'ad_storage': 'denied',
                      });
                      gtag('consent', 'update', {
                          'ad_user_data': 'denied',
                      });
                      gtag('consent', 'update', {
                        'ad_personalization': 'denied',
                      });
                    } 
                }

                if(cookieconsent.allowedCategory('necessary')){
                    var dataLayer = window.dataLayer || [];
                    dataLayer.push({
                        event:"CookieConsent",
                        consentType:"necessary"
                    });
                }
            
                if(cookieconsent.allowedCategory('performance')){
                    var dataLayer = window.dataLayer || [];
                    dataLayer.push({
                        event:"CookieConsent",
                        consentType:"performance"
                    });
                    gtag('consent', 'update', {
                        'analytics_storage': 'granted',
                    });
                    gtag('consent', 'update', {
                        'personalization_storage' : 'granted'
                    });
                }
            
                if(cookieconsent.allowedCategory('tracking')){
                    var dataLayer = window.dataLayer || [];
                    dataLayer.push({
                        event:"CookieConsent",
                        consentType:"tracking"
                    });
                    gtag('consent', 'update', {
                        'ad_storage': 'granted',
                    });
                    gtag('consent', 'update', {
                        'ad_user_data': 'granted',
                    });
                    gtag('consent', 'update', {
                        'ad_personalization': 'granted',
                    });
                }

            },

            onChange : function( ) {

                var gtagType = 'update';
                var dataLayer = window.dataLayer || [];
   
                if(cookieconsent.allowedCategory('necessary')){
                    dataLayer.push({
                        event:"CookieConsent",
                        consentType:"necessary"
                    });
                    gtag('consent', gtagType, {
                        'personalization_storage' : 'granted'
                    });
                } else {
                    gtag('consent', gtagType, {
                        'personalization_storage' : 'denied'
                    });
                }            
                            
                if(cookieconsent.allowedCategory('performance')){
                    dataLayer.push({
                        event:"CookieConsent",
                        consentType:"performance"
                    });
                    gtag('consent', gtagType, {
                        'analytics_storage': 'granted',
                    });
                    gtag('consent', gtagType, {
                        'personalization_storage' : 'granted'
                    });
                } else {
                    gtag('consent', gtagType, {
                        'analytics_storage': 'denied',
                    });
                    gtag('consent', gtagType, {
                        'personalization_storage' : 'denied'
                    });
                }
                
                if(cookieconsent.allowedCategory('tracking')){
                    dataLayer.push({
                        event:"CookieConsent",
                        consentType:"tracking"
                    });
                    gtag('consent', gtagType, {
                        'ad_storage': 'granted',
                    });
                    gtag('consent', 'update', {
                            'ad_user_data': 'granted',
                    });
                    gtag('consent', 'update', {
                        'ad_personalization': 'granted',
                    });
                } else {
                    gtag('consent', gtagType, {
                        'ad_storage': 'denied',
                    });
                    gtag('consent', 'update', {
                            'ad_user_data': 'denied',
                    });
                    gtag('consent', 'update', {
                        'ad_personalization': 'denied',
                    });
                }
  
                dataLayer.push({
                    'event': 'cookie_consent_update',
                });
            },

            languages: {
                'en': {
                    consent_modal: {
                        title: globaldata.title,
                        description: globaldata.consent_description,
                        primary_btn: {
                            text: globaldata.primarybtn_text,
                            role: 'accept_all'  //'accept_selected' or 'accept_all'
                        },
                        secondary_btn: {
                            text: globaldata.secondarybtn_text,
                            role: 'settings'   //'settings' or 'accept_necessary'
                        },
                        third_btn: {
                            text: globaldata.thirdbtn_text,
                            role: "accept_necessary", //'accept_necessary'
                        },
                    },
                    settings_modal: {
                        title: globaldata.title,
                        save_settings_btn: globaldata.pref_btn_text,
                        accept_all_btn: globaldata.primarybtn_text,
                        reject_all_btn: globaldata.thirdbtn_text,
                        close_btn_label: globaldata.cookie_close_btn_text,
                        cookie_table_headers: [
                            { col1: globaldata.cookie_table_header1 },
                            { col2: globaldata.cookie_table_header2 },


                        ],
                        blocks: [
                            {
                                title: globaldata.cookiemaintitle,
                                description: globaldata.block_description,
                            }, {
                                title: globaldata.func_cookie_title,
                                description: globaldata.func_cookie_desc,
                                toggle: {
                                    value: 'necessary',
                                    enabled: true,
                                    readonly: true
                                },
                                cookie_table: [
                                    {
                                        col1: 'cc_cookie',
                                        col2: globaldata.func_cookie_table2,
                                    },
                                    {
                                        col1: 'wp*, wordpress-*',
                                        col2: globaldata.func_cookie_wp_session,
                                    },
                                ]
                            }, {
                                title: globaldata.anal_cookie_title,
                                description: globaldata.anal_cookie_desc,
                                toggle: {
                                    value: 'performance',
                                    enabled: true,
                                    readonly: false
                                },
                                cookie_table: [
                                    {
                                        col1: '_ga/_ga*, _gid',
                                        col2: globaldata.anal_cookie_table_desc1,
                                    },
                                    {
                                        col1: '_gcl_au',
                                        col2: globaldata.anal_cookie_table_desc2,
                                    },
                                ]
                            }
                            , {
                                title: globaldata.market_cookie_title,
                                description: globaldata.market_cookie_desc,
                                toggle: {
                                    value: 'tracking',
                                    enabled: false,
                                    readonly: false
                                },
                                cookie_table: [
                                    {
                                        col1: '1P_JAR, CONSENT, NID',
                                        col2: globaldata.google_marketing,
                                    },
                                    {
                                        col1: '_fbp',
                                        col2: globaldata.fb_pixel,
                                    },
                                    {
                                        col1: 'fr',
                                        col2: globaldata.fb_fb_marketing1,
                                    },
                                    {
                                        col1: 'sid',
                                        col2: globaldata.anal_cookie_table_desc_seznam,
                                    },
                                ]
                            }
                        ]
                    }
                }
            }
        });

        if (!cookieconsent.validCookie('cc_cookie')) {
            var dataLayer = window.dataLayer || [];
            dataLayer.push({
                event: "CookieConsent",
                consentType: "empty"
            });

        }
        var el = $('#c-inr-i #c-txt .cc-link').remove();
        el.removeClass('cc-link').addClass('c-bn rj-all');
        $('#cm #c-bns').append(el);
        $('.rj-all').on('click', function() { $('#s-rall-bn').click(); });
    });


    // Custom project code
    $(document).ready(function () {
        // Add your code here :)
    });
    
})(jQuery);