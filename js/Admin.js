$(document).ready(function() {
    // Layout
    $(".AdminSidebar-dropdown > a").on('click',function(){
        $(".AdminSidebar-submenu").slideUp(200);
        if (
            $(this).parent().hasClass("active")
        ) {
            $(".AdminSidebar-dropdown").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".AdminSidebar-dropdown").removeClass("active");
            $(this).next(".AdminSidebar-submenu").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $("#AdminSidebar-toggle").on('click',function(e){
        $('#AdminLayout').toggleClass('AdminSidebar-show');
    });
    $('.AdminSidebar-wrapper').on('click',function (e) {
        if (e.target === this){
            $('#AdminLayout').removeClass('AdminSidebar-show');
        }
    });
});