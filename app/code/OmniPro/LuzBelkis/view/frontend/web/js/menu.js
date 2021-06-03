    require(['jquery'], function($){ 
        $( document ).ready(function() {
            // alert("primer alerta");
            $( ".auth__login-register .nav-tabs .nav-item " ).on( "click", function() {
   
                if($(this).hasClass("login")){
                    $(this).find('a').addClass("active");
                    $(".auth__login-register .nav-tabs .nav-item.register a ").removeClass("active");
                    $(".tab-content #login").addClass("active");
                    $(".tab-content #register").removeClass("active");
                }
                else if($(this).hasClass("register")){
                    $(this).find('a').addClass("active");
                    $(".auth__login-register .nav-tabs .nav-item.login a ").removeClass("active");
                    $(".tab-content #register").addClass("active");
                    $(".tab-content #login").removeClass("active");
                }
            });

            $('.auth__menu-ico').click(function() {
                $( ".sidenav-menu.bg-menu" ).toggleClass("active");
            });
            $('.closebtn').click(function() {
                $( ".sidenav-menu.bg-menu" ).toggleClass("active");
            });
            
        });    
    });

