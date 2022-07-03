import { Request as API } from "./Request";
$(document).ready(function () {
    if ($("#user-menu-btn")) {
        $("#user-menu-btn").click(function () {
            $("#user-menu-list").toggleClass("state-open");
            $("#user-menu-btn").toggleClass("header-btn-hovered");
        });
    }
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        // @ts-ignore
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    $(".cyrus-carousel-next").click(function () {
        $(this).parent().parent().children(".cyrus-carousel-items").children(".cyrus-carousel-items-wrapper").each(function () {
            let cols = parseInt(getComputedStyle(document.body).getPropertyValue('--carousel-cols-count'));
            let totalItems = 0;
            $(this).children("div").each(function () {
                totalItems += 1;
            });
            let maxScrollTimes = Math.ceil(totalItems / cols);
            let scrolledTimes = parseInt($(this).data("scrolled-times"));
            if (isNaN(scrolledTimes))
                scrolledTimes = 0;
            if (scrolledTimes < maxScrollTimes) {
                scrolledTimes += 1;
                $(this).parent().parent().parent().find('[data-arrow="next"]').removeClass("cyrus-carousel-arrow-hidden");
                if (scrolledTimes >= maxScrollTimes) {
                    $(this).parent().parent().parent().find('[data-arrow="next"]').addClass("cyrus-carousel-arrow-hidden"); //.css({"visibility": "hidden"});
                }
                else if (scrolledTimes > 1) {
                    $(this).parent().parent().parent().find('[data-arrow="previous"]').removeClass("cyrus-carousel-arrow-hidden"); //.css({"visibility": "visible"});
                }
                $(this).data("scrolled-times", scrolledTimes);
                //let leftPos = $(this).scrollLeft();
                // @ts-ignore
                $(this).animate({ scrollLeft: $(this).width() * (scrolledTimes - 1) }, 500);
            }
            else if (scrolledTimes >= maxScrollTimes) {
                $(this).parent().parent().parent().find('[data-arrow="next"]').addClass("cyrus-carousel-arrow-hidden"); //.css({"visibility": "hidden"});
            }
        });
    }).trigger("click");
    $(".cyrus-carousel-previous").click(function () {
        $(this).parent().parent().children(".cyrus-carousel-items").children(".cyrus-carousel-items-wrapper").each(function () {
            let scrolledTimes = parseInt($(this).data("scrolled-times"));
            if (isNaN(scrolledTimes))
                scrolledTimes = 1;
            if (scrolledTimes > 1) {
                scrolledTimes -= 1;
                $(this).data("scrolled-times", scrolledTimes);
                if (scrolledTimes == 1) {
                    $(this).parent().parent().parent().find('[data-arrow="previous"]').addClass("cyrus-carousel-arrow-hidden"); //.css({"visibility": "hidden"});
                }
                else {
                    $(this).parent().parent().parent().find('[data-arrow="next"]').removeClass("cyrus-carousel-arrow-hidden"); //({"visibility": "visible"});
                }
                // @ts-ignore
                $(this).animate({ scrollLeft: $(this).width() * (scrolledTimes - 1) }, 500);
            }
            else if (scrolledTimes == 1) {
                $(this).parent().parent().parent().find('[data-arrow="previous"]').addClass("cyrus-carousel-arrow-hidden"); //.css({"visibility": "hidden"});
            }
        });
    }).trigger("click");
    /* User Menu*/
    $(".dropdown").find(".dropdown-menu").each(function () {
        let alreadySelected = false;
        let select = true;
        $(this).parent().find(".dropdown-toggle").each(function () {
            $(this).find("[data-selected]").each(function () {
                if ($(this).data("selected") == null || $(this).data("selected") == "null") {
                    select = false;
                    $(this).html("Nenhum");
                }
            });
        });
        if (select) {
            $(this).find("li").each(function () {
                if ($(this).hasClass("selected")) {
                    alreadySelected = true;
                }
            });
            if (!alreadySelected) {
                $(this).find("li:first-child").addClass("selected").each(function () {
                    $(this).parent().parent().find(".dropdown-toggle").find("span").data("selected", $(this).data("id")).text($(this).html());
                });
            }
            else {
                $(this).find("li.selected").each(function () {
                    $(this).parent().parent().find(".dropdown-toggle").find("span").data("selected", $(this).data("id")).text($(this).html());
                });
            }
        }
    });
    $(".dropdown").find(".dropdown-menu li").click(function () {
        $(this).parent().find(".selected").removeClass("selected");
        $(this).addClass("selected");
        $(this).parent().parent().find(".dropdown-toggle").find("span").data("selected", $(this).data("id")).text($(this).html()).trigger("change");
    });
    $("#cyrus-logout").click(function () {
        API.requestService("Authentication", "logout", {}, []).then((result) => {
            if (result.status) {
                location.reload();
            }
        });
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $("input[type=file]").each(function () {
    });
});
export function cyrusAlert(alertType, alertHtml) {
    $("#alerts").find(".show").removeClass("show");
    $("#alerts").find("div").addClass("cyrus-item-hidden");
    let currentTimestamp = getCurrentTimestamp();
    $("#alerts").data("last-alert", currentTimestamp);
    switch (alertType.toLowerCase()) {
        case "success":
            $("#alert-success").find("span").html(alertHtml);
            $("#alerts").parent().removeClass("cyrus-item-hidden");
            $("#alert-success").addClass("show").removeClass("cyrus-item-hidden");
            break;
        case "danger":
            $("#alert-danger").find("span").html(alertHtml);
            $("#alerts").parent().removeClass("cyrus-item-hidden");
            $("#alert-danger").addClass("show").removeClass("cyrus-item-hidden");
            break;
        case "warning":
            $("#alert-warning").find("span").html(alertHtml);
            $("#alerts").parent().removeClass("cyrus-item-hidden");
            $("#alert-warning").addClass("show").removeClass("cyrus-item-hidden");
            break;
        default:
            console.error(`Alert Type (${alertType}) not supported!`);
            break;
    }
    setTimeout(function () {
        if ($("#alerts").data("last-alert") == currentTimestamp) {
            $("#alerts").find(".show").removeClass("show");
            $("#alerts").parent().addClass("cyrus-item-hidden");
            //$("#modals").find("div").addClass("cyrus-item-hidden");
        }
    }, 3000);
}
export function getCurrentTimestamp() {
    return Math.round(window.performance && window.performance.now() && window.performance.timing && window.performance.timing.navigationStart ? window.performance.now() + window.performance.timing.navigationStart : Date.now());
}
// @ts-ignore
window.cyrusAlert = (alertType, alertHtml) => cyrusAlert(alertType, alertHtml);
