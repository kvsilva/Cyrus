
import {Request as API} from "./Request";

$(document).ready(function() {

    if ($("#user-menu-btn")) {
        $("#user-menu-btn").click(function () {
            $("#user-menu-list").toggleClass("state-open");
            $("#user-menu-btn").toggleClass("header-btn-hovered");
        });

    }
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        // @ts-ignore
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });


    $("[data-download]").click(function () {
        downloadURI($(this).data("href"), $(this).data("filename"));
    })

    $("[data-cyrus]").each(function () {
        if ($(this).is("input")) {
            switch ($(this).attr("type")?.toLowerCase()) {
                case "file":
                    $(this).on("dragover", function () {
                        $(this).parent().find("[data-dragged='false']").addClass("cyrus-item-hidden");
                        $(this).parent().find("[data-dragged='true']").removeClass("cyrus-item-hidden");
                    });
                    $(this).on("dragleave", function () {
                        $(this).parent().find("[data-dragged='true']").addClass("cyrus-item-hidden");
                        $(this).parent().find("[data-dragged='false']").removeClass("cyrus-item-hidden");
                    });
                    $(this).on("drop", function () {
                        $(this).parent().find("[data-dragged='true']").addClass("cyrus-item-hidden");
                        $(this).parent().find("[data-dragged='false']").removeClass("cyrus-item-hidden");
                    });
                    $(this).on("change", function () {
                        $("[data-for='" + $(this).attr("id") + "']").html("");
                        for (let i = 0; i < $(this).prop("files").length; i++) {
                            let file = $(this).prop("files")[i];
                            let element = $(this);

                            $("[data-for='" + $(this).attr("id") + "']").append(
                                $("<li>").attr("class", "cyrus-attachment").append(
                                    $("<i>").attr("class", "fa-solid fa-paperclip")
                                ).append(
                                    $("<span>").attr("class", "cyrus-attachment-link").html(file.name)
                                ).append(
                                    $("<i>").attr("class", "cyrus-attachment-remove fa-solid fa-xmark").click(({
                                        pos: i,
                                        e: element
                                    }), function (event) {
                                        let files: File[] = [];
                                        for (let m = 0; m < $(event.data.e).prop("files").length; m++) {
                                            if (m !== event.data.pos) files.push($(event.data.e).prop("files")[m]);
                                        }
                                        $(event.data.e).prop("files", FileListItems(files));
                                        element.trigger("change");
                                    })
                                )
                            )
                        }
                    });

                    break;
            }
        }
    })


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

    /* User Menu*/
    $(".dropdown").find(".dropdown-menu").each(function(){
        let alreadySelected = false;
        let select = true;
        $(this).parent().find(".dropdown-toggle").each(function(){
            $(this).find("[data-selected]").each(function(){
                if($(this).data("selected") == null || $(this).data("selected") == "null"){
                    select = false;
                    $(this).html("Nenhum");
                }
            })
        })
        if(select) {
            $(this).find("li").each(function () {
                if ($(this).hasClass("selected")) {
                    alreadySelected = true;
                }
            })
            if (!alreadySelected) {
                $(this).find("li:first-child").addClass("selected").each(function () {
                    $(this).parent().parent().find(".dropdown-toggle").find("span").data("selected", $(this).data("id")).text($(this).html());
                });
            } else {
                $(this).find("li.selected").each(function () {
                    $(this).parent().parent().find(".dropdown-toggle").find("span").data("selected", $(this).data("id")).text($(this).html());
                });
            }
        }
    });
    $(".dropdown").find(".dropdown-menu li").click(function(){
        $(this).parent().find(".selected").removeClass("selected");
        $(this).addClass("selected");
        $(this).parent().parent().find(".dropdown-toggle").first().find("span").first().data("selected", $(this).data("id")).text($(this).html()).trigger("change");
    });

    $("#cyrus-logout").click(function(){
        API.requestService("Authentication", "logout", {}, []).then((result: any) => {
            if (result.status) {
                location.reload();
            }
        });
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    $("input[type=file]").each(function(){

    });
});


export function cyrusAlert(alertType: string, alertHtml: string){
    $("#alerts").find(".show").removeClass("show");
    $("#alerts").find("div").addClass("cyrus-item-hidden");
    let currentTimestamp : number = getCurrentTimestamp();
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
    setTimeout(function (){
        if($("#alerts").data("last-alert") == currentTimestamp){
            $("#alerts").find(".show").removeClass("show");
            $("#alerts").parent().addClass("cyrus-item-hidden");
            //$("#modals").find("div").addClass("cyrus-item-hidden");
        }
    }, 3000);
}

export function getCurrentTimestamp(){
    return Math.round(window.performance && window.performance.now() && window.performance.timing && window.performance.timing.navigationStart ? window.performance.now() + window.performance.timing.navigationStart : Date.now());
}

// @ts-ignore
window.cyrusAlert = (alertType: string, alertHtml: string) => cyrusAlert(alertType, alertHtml);

// @ts-ignore
window.getParameter = (parameter: string) => getParameter(parameter);

export function getParameter(parameter: string) {

    // Address of the current window
    let address = window.location.search

    // Returns a URLSearchParams object instance
    let parameterList = new URLSearchParams(address)

    // Returning the respected value associated
    // with the provided key
    return parameterList.get(parameter)
}

// @ts-ignore
window.FileListItems = (files: any[]) => FileListItems(files);

export function FileListItems(files: any[]) {
    var b = new ClipboardEvent("").clipboardData || new DataTransfer()
    for (var i = 0, len = files.length; i < len; i++) b.items.add(files[i])
    return b.files
}

// @ts-ignore
window.downloadURI = (uri: string, name: string) => downloadURI(uri, name);

// @ts-ignore
function downloadURI(uri: string, name: string) {
    const link = document.createElement("a");
    link.download = name;
    link.href = uri;
    link.click();
}