import {Request as API}  from "../../../resources/js/Request";
import {Anime, AnimeFlags, Video, VideoType} from "../../../resources/js/models";

let Routing : any = {};


$(document).ready(function(){
    //$("html").css({visibility: "hidden"});
    API.requestService("utilities", "getRouting", {}, []).then((result: any) => {
        if (result.status) {
            if("data" in result && result.data.length > 0) {
                Routing = result.data[0];
            }
        }
        $("#reset-query-form").click(function(){
                $("#form-query").trigger("reset");
            })

        $("#field-query").on('input',function(){
                let title : string = String($("#field-query").val());
                if(title.trim().length == 0){
                    $("#main").remove()
                    $("#series").remove();
                    $("#videos").remove();
                    return;
                }
                API.requestService("search", "search", {
                    title: title
                }, [AnimeFlags.ALL.name]).then((result: any) => {
                    if(result.status) {
                        if (result.data) {
                            let main_results = $("<div>").attr("class", "results-wrapper");
                            let main_wrapper = $("<div>").attr("class", "results").attr("id", "main").append(
                                $("<h4>").html("Principais Resultados")
                            );
                            $("#main").remove()
                            $("#series").remove();
                            $("#videos").remove();
                            for (let i = 0; i < 3; i++) {
                                let card = $("<div>").attr("class", "cyrus-card");
                                if (result.data.length - 1 >= i) {
                                    let item = result.data[i];
                                    if (item instanceof Anime) {
                                        let seasonsTotal = item.seasons !== null ? item.seasons.length : 0;
                                        let videosTotal = item.videos !== null ? item.videos.length : 0;
                                        let videosFromSeasons: any[] = item.seasons.map(e => e.videos);

                                        for (let v = 0; v < videosFromSeasons.length; v++) videosTotal += videosFromSeasons[v].length;
                                        card.append(
                                            $("<a>").attr("class", "cyrus-card-link").attr("href", Routing?.animes + "?anime=" + item.id).attr("title", item.title)
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-image").append(
                                                $("<img>").attr("src", item.cape?.path)
                                            )
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-body").append(
                                                $("<div>").attr("class", "cyrus-card-title").append(
                                                    $("<h4>").attr("class", "cyrus-card-title").html(item.title)
                                                )
                                            ).append(
                                                $("<div>").attr("class", "cyrus-card-description").append(
                                                    $("<div>").attr("class", "cyrus-card-description-info").append(
                                                        $("<span>").html((seasonsTotal > 0 ? seasonsTotal + (seasonsTotal == 1 ? " Temporadas, " : " Temporadas, ") : "") + videosTotal + (videosTotal == 1 ? " Video" : " Vídeos"))
                                                    )
                                                ).append(
                                                    $("<div>").attr("class", "cyrus-card-description-type").append(
                                                        $("<span>").html("Série")
                                                    )
                                                )
                                            )
                                        );
                                    } else if (item instanceof Video) {

                                        let duration = Math.round(item.duration / 60);
                                        let title = (item.season !== null ? "Temporada " + item.season?.numeration : "") + (item.numeration !== null ? "Episódio " + item.numeration : "") + ((item.season !== null || item.anime !== null) ? " - " : "") + item.title
                                        card.append(
                                            $("<a>").attr("class", "cyrus-card-link").attr("href", Routing?.episode + "?episode=" + item.id).attr("title", item.title)
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-image").append(
                                                $("<img>").attr("class", "c-opacity-70").attr("src", item.thumbnail?.path)
                                            ).append(
                                                $("<div>").attr("class", "cyrus-card-duration").append(
                                                    $("<span>").html(duration + "m")
                                                )
                                            ).append(
                                                $("<i>").attr("class", "fa-solid fa-play cyrus-card-center")
                                            )
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-body").append(
                                                $("<div>").attr("class", "cyrus-card-description").append(
                                                    $("<div>").attr("class", "cyrus-card-description-info").append(
                                                        $("<span>").html(item.anime?.title)
                                                    )
                                                )
                                            ).append(
                                                $("<div>").attr("class", "m-0 cyrus-card-title").append(
                                                    $("<h4>").attr("class", "cyrus-card-title").html(title)
                                                )
                                            ).append(
                                                $("<div>").attr("class", "m-0 cyrus-card-description").append(
                                                    $("<div>").attr("class", "cyrus-card-description-type").append(
                                                        $("<span>").html("Episódio")
                                                    )
                                                )
                                            )
                                        );
                                    } else {
                                        continue;
                                    }
                                    main_results.append(card);

                                }
                            }
                            main_wrapper.append(main_results);
                            $("#content-results").append(main_wrapper);

                            let series: Anime[] = result.data.filter((value: any) => value instanceof Anime);
                            if (series.length > 0) {
                                let series_wrapper = $("<div>").attr("class", "results").attr("id", "series").append(
                                    $("<h4>").html("Séries")
                                );
                                let series_results = $("<div>").attr("class", "results-wrapper");
                                for (let i = 0; i < series.length; i++) {
                                    let card = $("<div>").attr("class", "cyrus-card  cyrus-card-flex");
                                    let item = series[i];
                                    let seasonsTotal = item.seasons !== null ? item.seasons.length : 0;
                                    let videosTotal = item.videos !== null ? item.videos.length : 0;
                                    let videosFromSeasons: any[] = item.seasons.map(e => e.videos);
                                    for (let v = 0; v < videosFromSeasons.length; v++) videosTotal += videosFromSeasons[v].length;
                                    card.append(
                                        $("<a>").attr("class", "cyrus-card-link").attr("href", Routing?.animes + "?anime=" + item.id).attr("title", item.title)
                                    ).append(
                                        $("<div>").attr("class", "cyrus-card-image-catalog").append(
                                            $("<img>").attr("src", item.profile?.path)
                                        )
                                    ).append(
                                        $("<div>").attr("class", "cyrus-card-body").append(
                                            $("<div>").attr("class", "cyrus-card-title").append(
                                                $("<h4>").attr("class", "cyrus-card-title").html(item.title)
                                            )
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-description").append(
                                                $("<div>").attr("class", "cyrus-card-description-info").append(
                                                    $("<span>").html((seasonsTotal > 0 ? seasonsTotal + (seasonsTotal == 1 ? " Temporadas, " : " Temporadas, ") : "") + videosTotal + (videosTotal == 1 ? " Video" : " Vídeos"))
                                                )
                                            ).append(
                                                $("<div>").attr("class", "cyrus-card-description-type").append(
                                                    $("<span>").html("Série")
                                                )
                                            )
                                        )
                                    );
                                    series_results.append(card);
                                }
                                series_wrapper.append(series_results);
                                $("#content-results").append(series_wrapper);
                            }


                            let videos: Video[] = result.data.filter((value: any) => value instanceof Video);
                            let videosType: VideoType[] = videos.map(value => value.video_type);
                            if (videos.length > 0) {
                                let videos_global = $("<div>").attr("id", "videos");
                                for(let t = 0; t < videosType.length; t++) {
                                    let type : VideoType = videosType[t];
                                    let videosByType : Video[] = videos.filter(value => value.video_type == type);
                                    let video_results = $("<div>").attr("class", "results").attr("id", "series").append(
                                        $("<h4>").html(type !== null ? type.name : "Desconhecido")
                                    );
                                    let videos_wrapper = $("<div>").attr("class", "results-wrapper results-wrapper-videos");
                                    for(let i = 0; i < videosByType.length; i++){
                                        let item : Video = videosByType[i];
                                        let card = $("<div>").attr("class", "cyrus-card  cyrus-card-flex");
                                        let duration = Math.round(item.duration / 60);
                                        let title = (item.season !== null ? "Temporada " + item.season?.numeration : "") + (item.numeration !== null ? "Episódio " + item.numeration : "") + ((item.season !== null || item.anime !== null) ? " - " : "") + item.title
                                        card.append(
                                            $("<a>").attr("class", "cyrus-card-link").attr("href", Routing?.episode + "?episode=" + item.id).attr("title", item.title)
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-image-flex").append(
                                                $("<img>").attr("class", "c-opacity-70").attr("src", item.thumbnail?.path)
                                            ).append(
                                                $("<div>").attr("class", "cyrus-card-duration").append(
                                                    $("<span>").html(duration + "m")
                                                )
                                            ).append(
                                                $("<i>").attr("class", "fa-solid fa-play cyrus-card-center")
                                            )
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-body").append(
                                                $("<div>").attr("class", "cyrus-card-description").append(
                                                    $("<div>").attr("class", "cyrus-card-description-info").append(
                                                        $("<span>").html(item.anime?.title)
                                                    )
                                                )
                                            ).append(
                                                $("<div>").attr("class", "m-0 cyrus-card-title").append(
                                                    $("<h4>").attr("class", "cyrus-card-title").html(title)
                                                )
                                            ).append(
                                                $("<div>").attr("class", "m-0 cyrus-card-description").append(
                                                    $("<div>").attr("class", "cyrus-card-description-type").append(
                                                        $("<span>").html("Episódio")
                                                    )
                                                )
                                            )
                                        );
                                        videos_wrapper.append(card);
                                    }
                                    video_results.append(videos_wrapper);
                                    videos_global.append(video_results);
                                }
                                $("#content-results").append(videos_global);
                            }
                        }
                    } else {

                    }
                });
            });
        //pageLoaded();
    });
})

/*function pageLoaded(){
    $("html").css({visibility: "visible"});
}*/