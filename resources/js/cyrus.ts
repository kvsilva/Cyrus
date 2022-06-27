$(document).ready(function() {
    if ($("#user-menu-btn")) {
        $("#user-menu-btn").click(function () {
            $("#user-menu-list").toggleClass("state-open");
            $("#user-menu-btn").toggleClass("header-btn-hovered");
        });

    }
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    /*setTimeout(function()
    {
        var max = 360;
        var tot, str;
        $('.text span').each(function() {
            str = String($(this).html());
            tot = str.length;
            str = (tot <= max)
                ? str
                : str.substring(0,(max - 3))+"...";
            $(this).html(str);
        });
    },500);*/
    $(".cyrus-carousel-next").click(function(){
        $(this).parent().parent().children(".cyrus-carousel-items").children(".cyrus-carousel-items-wrapper").each( function()
        {
            let cols : number = parseInt(getComputedStyle(document.body).getPropertyValue('--carousel-cols-count'));
            let totalItems : number = 0;
            $(this).children("div").each(function(){
                totalItems += 1;
            })
            let maxScrollTimes : number = Math.ceil(totalItems/cols);
            let scrolledTimes : number = parseInt($(this).data("scrolled-times"));
            if(isNaN(scrolledTimes)) scrolledTimes = 0;
            if(scrolledTimes < maxScrollTimes) {
                scrolledTimes +=  1;
                $(this).parent().parent().parent().find('[data-arrow="next"]').removeClass("cyrus-carousel-arrow-hidden");
                if(scrolledTimes >= maxScrollTimes) {
                    $(this).parent().parent().parent().find('[data-arrow="next"]').addClass("cyrus-carousel-arrow-hidden");//.css({"visibility": "hidden"});
                } else if (scrolledTimes > 1){
                    $(this).parent().parent().parent().find('[data-arrow="previous"]').removeClass("cyrus-carousel-arrow-hidden");//.css({"visibility": "visible"});
                }
                $(this).data("scrolled-times", scrolledTimes);
                //let leftPos = $(this).scrollLeft();
                // @ts-ignore
                $(this).animate({scrollLeft: $(this).width() * (scrolledTimes - 1)}, 500);
            } else if(scrolledTimes >= maxScrollTimes) {
                $(this).parent().parent().parent().find('[data-arrow="next"]').addClass("cyrus-carousel-arrow-hidden");//.css({"visibility": "hidden"});
            }
        });
    }).trigger("click");


    $(".cyrus-carousel-previous").click(function(){
        $(this).parent().parent().children(".cyrus-carousel-items").children(".cyrus-carousel-items-wrapper").each( function()
        {
            let scrolledTimes : number = parseInt($(this).data("scrolled-times"));
            if(isNaN(scrolledTimes)) scrolledTimes = 1;
            if(scrolledTimes > 1) {
                scrolledTimes -=  1;
                $(this).data("scrolled-times", scrolledTimes);
                if(scrolledTimes == 1) {
                    $(this).parent().parent().parent().find('[data-arrow="previous"]').addClass("cyrus-carousel-arrow-hidden");//.css({"visibility": "hidden"});
                } else {
                    $(this).parent().parent().parent().find('[data-arrow="next"]').removeClass("cyrus-carousel-arrow-hidden"); //({"visibility": "visible"});
                }
                // @ts-ignore
                $(this).animate({scrollLeft: $(this).width() * (scrolledTimes - 1)}, 500);
            } else if (scrolledTimes == 1) {
                $(this).parent().parent().parent().find('[data-arrow="previous"]').addClass("cyrus-carousel-arrow-hidden");//.css({"visibility": "hidden"});
            }
        });
    }).trigger("click");
});