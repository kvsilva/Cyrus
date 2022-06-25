import { Request as API } from "../../../../resources/js/Request.js";
let Routing = {};
$(document).ready(function () {
    API.requestService("utilities", "getRouting", {}, []).then((result) => {
        if (result.status) {
            if ("data" in result) {
                Routing = result.data;
            }
        }
        API.requestService("Animes", "getAnimeList", {}, []).then((result2) => {
            var _a;
            if (result2.status) {
                if ("data" in result2) {
                    $("#anime-full-list").html("");
                    let isFirstSelected = true;
                    for (const letter in result2.data) {
                        let items = result2.data[letter];
                        if (result2.data[letter].length == 0) {
                            $('.letter-item[data-key="' + letter + '"]').addClass("letter-item-disabled");
                            continue;
                        }
                        $('.letter-item[data-key="' + letter + '"]').click(function () {
                            $('html, body').animate({
                                // @ts-ignore
                                scrollTop: $(document.getElementById("list-" + letter)).offset().top - 100
                            }, 500);
                        });
                        if (isFirstSelected) {
                            $('.letter-item[data-key="' + letter + '"]').addClass("letter-item-selected");
                            isFirstSelected = false;
                        }
                        let cards = $("<div>").attr("class", "animes");
                        for (let i = 0; i < items.length; i++) {
                            for (let x = 0; x < 15; x++) {
                                let card = $("<div>").attr("class", "cyrus-card cyrus-card-flex");
                                let item = items[i];
                                card.append($("<a>").attr("class", "cyrus-card-link").attr("href", (Routing === null || Routing === void 0 ? void 0 : Routing.animes) + "?anime=" + item.id).attr("title", item.title)).append($("<div>").attr("class", "cyrus-card-image-cape").append($("<img>").attr("src", (_a = item.cape) === null || _a === void 0 ? void 0 : _a.path))).append($("<div>").attr("class", "cyrus-card-body").append($("<div>").attr("class", "cyrus-card-title").append($("<h4>").attr("class", "cyrus-card-title").html(item.title))).append($("<div>").attr("class", "cyrus-card-description").append($("<div>").attr("class", "cyrus-card-description-text").append($("<span>").html(item.synopsis))).append($("<div>").attr("class", "cyrus-card-description-type").append($("<span>").html("Série")))));
                                cards.append(card);
                            }
                        }
                        let list = $("<div>").attr("class", "anime-list").attr("id", "list-" + letter).data("key", letter).append($("<div>").attr("class", "anime-letter").append($("<span>").text(letter))).append($("<div>").attr("class", "anime-letter-separator")).append(cards);
                        $("#anime-full-list").append(list);
                    }
                    $(window).scroll();
                }
            }
        });
    });
});
// @ts-ignore
$.fn.isInViewport = function () {
    // @ts-ignore
    const elementTop = $(this).offset().top;
    // @ts-ignore
    const elementBottom = elementTop + $(this).outerHeight();
    let viewportTop = $(window).scrollTop();
    if (viewportTop !== undefined)
        viewportTop += 105;
    // @ts-ignore
    const viewportBottom = viewportTop + $(window).height();
    // @ts-ignore
    return (elementBottom > viewportTop && elementTop < viewportBottom);
};
$(window).on('resize scroll', function () {
    let elements = $('.anime-list');
    if (elements.length > 0) {
        for (let i = 0; i < elements.length; i++) {
            let key = $($('.anime-list')[i]).data("key");
            // @ts-ignore
            if ($($('.anime-list')[i]).isInViewport()) {
                $('.letter-item-selected').removeClass("letter-item-selected");
                $('.letter-item[data-key="' + key + '"]').addClass("letter-item-selected");
                break;
            }
        }
    }
});
$(window).scroll(function () {
    // @ts-ignore
    if ($(this).scrollTop() > 70) {
        $('#letters-list').addClass('letter-full-list-sticky');
        $("#anime-full-list").css({ "padding-top": "54px" });
    }
    else {
        $('#letters-list').removeClass('letter-full-list-sticky');
        $("#anime-full-list").css({ "padding-top": "0" });
    }
});
