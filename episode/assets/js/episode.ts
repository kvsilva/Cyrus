import {Request as API} from "../../../resources/js/Request";
import {User, UserFlags, VideoFlags} from "../../../resources/js/models";

let user : User|null = null;

$(document).ready(async function () {
    await API.requestService("session", "getSession", {}, []).then(async (result: any) => {
        if (result.status) {
            if ("data" in result) {
                user = result.data[0];
                API.requestType("User", "update", {
                    "id": user?.id,
                    "relations": {
                        "VideoHistory":
                            [
                                {
                                    //@ts-ignore
                                    "video": getParameter("episode"),
                                    "date": null,
                                    "watched_until": null
                                }
                            ]
                    }
                }, [UserFlags.VIDEOHISTORY.name], false).then((result: any) => {
                    if (result.status) {
                        if ("data" in result) {
                            //@ts-ignore
                            let loc: any[] = result.data[0].video_history?.filter((value: any) => value.video?.id == parseInt(<string>getParameter("episode")));
                            if (loc.length > 0) {
                                // @ts-ignore
                                document.getElementById("player0")?.currentTime = loc[0].watched_until;
                            }
                        }
                    }
                });
            }
            updateTime();
        }
    });

    await dataQuery();


    $("#form0-description").on("input", function () {
        // @ts-ignore
        $(this).parent().nextAll(".reviews-self-char-notification").html($(this).val().length + "/" + $(this).attr("maxlength") + " caracteres.");
    });

    $("#form0-description").on("input", function () {
        if ($.trim(<string>$("#form0-description").val()).length > 0) {
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
        let description: string | number | string[] | undefined = $("#form0-description").val();
        let spoiler: boolean = $("#form0-spoiler").prop("checked");


        await API.requestService("session", "getSession", {}, []).then(async (result: any) => {
            if (result.status) {
                if ("data" in result) {
                    let user = result.data[0];
                    // @ts-ignore
                    cyrusAlert("warning", "Processando o seu pedido...");
                    API.requestType("Video", "update", {
                        //@ts-ignore
                        "id": getParameter("episode"),
                        "relations": {
                            "COMMENTVIDEOS":
                                [
                                    {
                                        "user": user?.id,
                                        "post_date": null,
                                        "description": description,
                                        "spoiler": spoiler,
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

});



// @ts-ignore
window.dataQueryEpisode = () => dataQuery();

async function dataQuery() {

    $("#reviews-list").html("");

    let formData : any[string] = {
        //@ts-ignore
        "id": getParameter("anime"),
    };

    await API.requestType("Video", "query", formData, [VideoFlags.COMMENTVIDEOS.name], false, true).then((result: any) => {
        if (result.status) {
            if(result.data){
                let results = result.data[0];

                for(let i = results.comments.length-1; i >= 0; i--){
                    let item = results.comments[i];



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
                                )/*.append(
                    $("<span>").attr("class", "review-options").append(
                        $("<button>").attr("class", "cyrus-btn cyrus-btn-simple").append(
                            $("<i>").attr("class", "fa-solid fa-flag")
                        )
                    ).append(
                        $("<button>").attr("class", "cyrus-btn cyrus-btn-simple").append(
                            $("<i>").attr("class", "fa-solid fa-share-nodes")
                        )
                    )
                )*/.append(
                                    $("<div>").attr("class", "mt-3").append(
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

    /*
    * <div class = "review">
                        <div class = "row">
                            <div class = "col-2 review-post-user no-select">
                                <img draggable="false" class = "mx-auto" src = "http://localhost/Cyrus/resources/site/resources/129.jpg">
                            </div>
                            <div class = "col-9">
                                <span>
                                    <span class = "review-username"><?php echo $_SESSION["user"]?->getUsername()?></span>
                                    <span class = "review-date float-right">10 de Janeiro de 2021</span>
                                </span>
                                <span class = "review-options">
                                    <button class = "cyrus-btn cyrus-btn-simple"><i class="fa-solid fa-flag"></i></button>
                                    <button class = "cyrus-btn cyrus-btn-simple"><i class="fa-solid fa-share-nodes"></i></button>
                                </span>
                                <div class = "review-star mt-3">
                                    <?php
                                    for($i = 0; $i < 5; $i++){ ?>
                                        <i class="fa-solid fa-star star static filled"></i>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class = "mt-3">
                                    <h3 class = "review-title">Lorem ipsum dolor sit amet</h3>
                                    <div class = "review-description" data-collapsible = "true">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris fringilla nunc at arcu rhoncus facilisis. Donec at justo eget eros auctor porttitor ut in magna. Etiam porta commodo dolor. Sed a enim dapibus, placerat erat sit amet, rhoncus ipsum. Fusce ut lobortis turpis, a hendrerit leo. Vivamus dui ipsum, tristique vulputate vulputate nec, cursus non enim. Proin molestie ante a lorem congue, quis tincidunt ligula consectetur. Sed id tempus mi, sed finibus nulla.
                                            Curabitur sodales viverra dapibus. Aenean fermentum dui turpis, non consectetur sapien posuere in. Duis gravida vitae arcu sed rhoncus. Integer vel ex dapibus, dapibus dolor vel, tincidunt mi. Nullam eget suscipit lorem. Integer a nibh non purus aliquam efficitur. Nullam consequat condimentum nulla, vitae mollis ipsum dignissim sit amet. Suspendisse potenti. Praesent tristique dolor mauris, a suscipit sem ultricies ut.
                                            Suspendisse fermentum erat nunc, consequat mattis dolor posuere nec. Vivamus pretium in ligula in dapibus. Nulla facilisi. Donec lectus ligula, sagittis eu tincidunt eget, aliquam a mauris. Maecenas et purus luctus, pretium tellus ac, aliquet augue. Phasellus sollicitudin justo sit amet ligula vulputate, eget vehicula orci rutrum. Phasellus placerat rhoncus convallis. Curabitur eleifend, justo sed tempus finibus, neque nulla varius urna, sit amet ultrices urna metus in nibh. Sed sed sodales urna, nec pretium orci. Phasellus rhoncus ac nisl id lobortis. Morbi sit amet elit laoreet, viverra dui sit amet, efficitur nunc. Nulla cursus ante id tempor sodales.
                                            Fusce luctus lacus libero. Integer bibendum lacinia urna, id faucibus ipsum hendrerit ut. Fusce bibendum tellus sit amet accumsan malesuada. Nam facilisis nibh vestibulum ex condimentum, ut lobortis ex pharetra. Mauris porta tristique cursus. Duis cursus magna id iaculis ornare. Duis ultrices nunc nisl, nec porttitor est volutpat ut. Vestibulum non congue metus, tempor consequat tellus. Proin vitae ex a nisi volutpat fringilla. Vivamus sit amet consequat ante, in dignissim magna. Nullam vitae lobortis ligula, a sodales tellus. Sed luctus risus id interdum efficitur.
                                            Vivamus euismod ipsum quis facilisis congue. Proin et tincidunt velit. Quisque sit amet porta metus. Sed non eros ut diam consectetur rhoncus. Nam at dui lacus. Quisque nibh mi, bibendum sit amet nunc nec, imperdiet euismod leo. Mauris blandit odio eleifend nisi aliquet maximus laoreet non arcu.
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <button data-collapse = "true" class = "cyrus-btn cyrus-btn-simple">MOSTRAR MAIS</button>
                                </div>
                                <!--<div class ="evaluate-review mt-2">
                                    <span data-positive="86">86</span> de <span data-total = "100">100</span> pessoas consideraram esta crítica útil. É útil para si? <button class = "cyrus-btn cyrus-btn-simple evaluate-review-button">SIM</button> | <button class = "cyrus-btn cyrus-btn-simple">NÃO</button>
                                </div>-->
                            </div>
                        </div>
                        <!--<hr class = "w-25 mx-auto">-->
                    </div>*/
}


function updateTime(){
    if(user !== null) {
        setTimeout(function () {
            // @ts-ignore
            let time = Math.floor(document.getElementById("player0")?.currentTime);
            API.requestType("User", "update", {
                "id": user?.id,
                "relations":{
                    "VideoHistory":
                        [
                            {
                                //@ts-ignore
                                "video": getParameter("episode"),
                                "date": null,
                                "watched_until": time
                            }
                        ]
                }
            }, [UserFlags.VIDEOHISTORY.name]).then((result: any) => {
                if (result.status) {
                    if ("data" in result) {

                    }

                }
            });
            updateTime();
        }, 10000);
    }
}