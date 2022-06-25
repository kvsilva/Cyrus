import {Request as API} from "../../../../resources/js/Request.js";

$(document).ready(function(){
    API.requestService("Animes", "getCalendar", {}, []).then((result: any) => {
        if (result.status) {
            if ("data" in result && result.data.length > 0) {

            }
        }
    });
});

$(window).scroll(function() {
    // @ts-ignore
    if ($(this).scrollTop() > 70) {
        $('#letters-list').addClass('letter-full-list-sticky');
        $("#anime-full-list").css({"padding-top": "54px"})
    } else {
        $('#letters-list').removeClass('letter-full-list-sticky');
        $("#anime-full-list").css({"padding-top": "0"})
    }
});