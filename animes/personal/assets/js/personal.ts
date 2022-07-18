import {Request as API} from "../../../../resources/js/Request";
//import {cyrusAlert} from "../../../../resources/js/cyrus";
import {AnimeFlags} from "../../../../resources/js/models";


$(document).ready(async function () {

    $("#comment-rating i").each(function () {
        $(this).click(function () {
            $(this).addClass("star-selected");
            $(this).nextAll("i").addClass("star-selected");
            $(this).prevAll("i").removeClass("star-selected");
            $(this).parent().find(".reviews-classified-as").data("rating", $(this).data("star")).text("Classificaste como " + $(this).data("star") + " " + ($(this).data("star") == 1 ? "Estrela" : "Estrelas")).trigger("datachange");
        });
        $(this).hover(function () {
            $(this).addClass("star-selected");
            $(this).nextAll("i").addClass("star-selected");
            $(this).prevAll("i").removeClass("star-selected");
        }, function () {
            let rating: string = $(this).parent().find(".reviews-classified-as").data("rating");
            $(this).removeClass("star-selected");
            $(this).parent().find("i").each(function () {
                if ($(this).data("star") <= rating) {
                    $(this).addClass("star-selected");
                } else {
                    $(this).removeClass("star-selected");
                }
            })
        });
    });

    $("#form0-description").on("input", function () {
        // @ts-ignore
        $(this).parent().nextAll(".reviews-self-char-notification").html($(this).val().length + "/" + $(this).attr("maxlength") + " caracteres.");
    });

    $("#form0-title").on("input", function () {
        if ($.trim(<string>$("#form0-title").val()).length > 0 && $.trim(<string>$("#form0-description").val()).length > 0 && $("#comment-rating [data-rating]").data("rating") > 0) {
            $("#form0-submit").prop("disabled", false);
        } else {
            $("#form0-submit").prop("disabled", true);
        }
    });

    $("#form0-description").on("input", function () {
        if ($.trim(<string>$("#form0-title").val()).length > 0 && $.trim(<string>$("#form0-description").val()).length > 0 && $("#comment-rating [data-rating]").data("rating") > 0) {
            $("#form0-submit").prop("disabled", false);
        } else {
            $("#form0-submit").prop("disabled", true);
        }
    });


    $("#comment-rating [data-rating]").on("datachange", function () {

        if ($.trim(<string>$("#form0-title").val()).length > 0 && $.trim(<string>$("#form0-description").val()).length > 0 && $("#comment-rating [data-rating]").data("rating") > 0) {
            $("#form0-submit").prop("disabled", false);
        } else {
            $("#form0-submit").prop("disabled", true);
        }
    });

    $("#form0").submit(function (e) {
        e.preventDefault();
    });

    $("#form0-submit").click(async function (e) {
        e.preventDefault();
        let title: string | number | string[] | undefined = $("#form0-title").val();
        let description: string | number | string[] | undefined = $("#form0-description").val();
        let rating: number = $("#comment-rating [data-rating]").data("rating");
        let spoiler: boolean = $("#form0-spoiler").prop("checked");


        await API.requestService("session", "getSession", {}, []).then(async (result: any) => {
            if (result.status) {
                if ("data" in result) {
                    let user = result.data[0];
                    // @ts-ignore
                    cyrusAlert("warning", "Processando o seu pedido...");
                    API.requestType("Anime", "update", {
                        "id": getParameter("anime"),
                        "relations": {
                            "COMMENTANIMES":
                                [
                                    {
                                        "user": user?.id,
                                        "post_date": null,
                                        "title": title,
                                        "description": description,
                                        "spoiler": spoiler,
                                        "classification": rating
                                    }
                                ]
                        }
                    }, [], false).then((result: any) => {
                        if (result.status) {
                            // @ts-ignore
                            cyrusAlert("success", result.description);
                            $("#form0").trigger("reset");
                            $("#form0-description").parent().nextAll(".reviews-self-char-notification").html("0/" + $("#form0-description").attr("maxlength") + " caracteres.");
                            dataQuery();
                        } else {
                            // @ts-ignore
                            cyrusAlert("danger", "Ocorreu um erro ao processar o seu pedido!");
                        }
                    });
                }
            }
        });

    });

    await dataQuery();

    $("#currentReviewOrder").change(async function () {
        await dataQuery();
    });
    $("#review-current-filter").change(async function () {
        await dataQuery();
    });

});
// @ts-ignore
window.dataQueryPeronal = () => dataQuery();

async function dataQuery() {

    $("#reviews-list").html("");

    let formData : any[string] = {
        "id": getParameter("anime"),
    };

    await API.requestType("Anime", "query", formData, [AnimeFlags.COMMENTANIMES.name], false, true).then((result: any) => {
        if (result.status) {
            if(result.data){
                let results = result.data[0];

                for(let i = results.comments.length-1; i >= 0; i--){
                    let item;
                    if($("#currentReviewOrder").data("selected") == "older"){
                        item = results.comments[results.comments.length-1 - i];
                    } else {
                        item = results.comments[i];
                    }

                    if($("#review-current-filter").data("selected") !== "all"){
                        if(item.classification != $("#review-current-filter").data("selected")) continue;
                    }

                    let ye = new Intl.DateTimeFormat('pt', { year: 'numeric' }).format(item?.date);
                    let mo = new Intl.DateTimeFormat('pt', { month: 'long' }).format(item?.date);
                    let da = new Intl.DateTimeFormat('pt', { day: '2-digit' }).format(item?.date);
                    let date : string = `${da} de `+ mo[0].toUpperCase() + mo.substring(1) +` de ${ye}`;

                    $("#reviews-list").append($("<div>").attr("class", "review").append(
                            $("<div>").attr("class", "row").append(
                                $("<div>").attr("class", "col-2 review-post-user no-select").append(
                                    $("<img>").prop("draggable", false).attr("class", "mx-auto").attr("src", item?.user?.profile_image?.path)
                                )
                            ).append(
                                $("<div>").attr("class", "col-9").append(
                                    $("<span>").append(
                                        $("<span>").attr("class", "review-username me-2").html(item?.user?.username)
                                    ).append(
                                        $("<span>").attr("class", "review-date float-right").html(date)
                                    )
                                ).append(
                                    $("<div>").attr("class", "review-star mt-3").append(
                                        $("<i>").attr("class", "fa-solid fa-star star static" + (item?.classification >= 1 ? " filled" : ""))
                                    ).append(
                                        $("<i>").attr("class", "fa-solid fa-star star static" + (item?.classification >= 2 ? " filled" : ""))
                                    ).append(
                                        $("<i>").attr("class", "fa-solid fa-star star static" + (item?.classification >= 3 ? " filled" : ""))
                                    ).append(
                                        $("<i>").attr("class", "fa-solid fa-star star static" + (item?.classification >= 4 ? " filled" : ""))
                                    ).append(
                                        $("<i>").attr("class", "fa-solid fa-star star static" + (item?.classification >= 5 ? " filled" : ""))
                                    )
                                ).append(
                                    $("<div>").attr("class", "mt-3").append(
                                        $("<h3>").attr("class", "review-title").html(item?.title)
                                    ).append(
                                        $("<div>").attr("class", "review-description " + (item.spoiler ? "spoiler" : "")).attr("data-collapsible", "true").attr("data-spoiler", item.spoiler).append(
                                            $("<p>").html(item?.description)
                                        )
                                    )
                                ).append(
                                    $("<div>").append(
                                        //<button data-collapse = "true" class = "cyrus-btn cyrus-btn-simple">MOSTRAR MAIS</button>
                                        $("<button>").attr("class", "cyrus-btn cyrus-btn-simple").text("MOSTRAR MAIS").attr("data-collapse", "true").attr("data-spoiler", item.spoiler).click(function () {
                                            let isSpoiler : boolean = $(this).data("spoiler");
                                            if ($(this).data("collapse") === true) {
                                                $(this).parent().parent().find("[data-collapsible]").addClass("expanded").removeClass(isSpoiler ? "spoiler" : "");
                                                $(this).text("MOSTRAR MENOS");
                                                $(this).data("collapse", false);
                                            } else {
                                                $(this).data("collapse", true);
                                                $(this).parent().parent().find("[data-collapsible]").removeClass("expanded").addClass(isSpoiler ? "spoiler" : "");;
                                                $(this).text("MOSTRAR MAIS");
                                            }
                                        })
                                    )
                                )
                            )
                        )
                    );
                }
            }
        } else {
            // @ts-ignore
            cyrusAlert("danger", "Ocorreu um erro ao processar o seu pedido!");
            console.error(result);
        }
    });
}

function getParameter(parameter: string) {

    // Address of the current window
    let address = window.location.search

    // Returns a URLSearchParams object instance
    let parameterList = new URLSearchParams(address)

    // Returning the respected value associated
    // with the provided key
    return parameterList.get(parameter)
}