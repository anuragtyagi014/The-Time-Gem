jQuery(function($) {
    $('.tab-link').click( function() {
	
        var tabID = $(this).attr('data-tab');
        
        $(this).addClass('active').siblings().removeClass('active');
        
        $('#tab-'+tabID).addClass('active').siblings().removeClass('active');
    });
    
});



var topDist = $("#model_2").position();
$(document).scroll(function () {
    var scroll = $(this).scrollTop();
    if (scroll > $("#model_2").offset().top) {
        $('#tab-navigation').css({"position":"fixed","left":"50%","transform": "translateX(-50%)",'padding': '0'});
        $('#tab-navigation').addClass('wrapper-n container');
    } else {
        $('#tab-navigation').css({"position":"relative","top":"auto"});
        $('#tab-navigation').removeClass('wrapper-n container');
    }
});