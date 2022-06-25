$(document).ready(function(){
})

$(window).scroll(function() {
    if ($(this).scrollTop() > 70) {
        $('#letters-list').addClass('letter-full-list-sticky');
        $("#anime-full-list").css({"padding-top": "54px"})
    } else {
        $('#letters-list').removeClass('letter-full-list-sticky');
        $("#anime-full-list").css({"padding-top": "0"})
    }
});