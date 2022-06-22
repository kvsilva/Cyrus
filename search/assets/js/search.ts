import {Request as API}  from "../../../resources/js/Request";
import {Anime, AnimeFlags, Video} from "../../../resources/js/models";

$(document).ready(function(){
    $("#reset-query-form").click(function(){
        $("#form-query").trigger("reset");
    })

   $("#field-query").on('input',function(){
       let title : string = String($("#field-query").val());
       API.requestService("search", "search", {
           title: title
       }, [AnimeFlags.ALL.name]).then((result: any) => {
           console.log(result);
           if(result.status){

               /*
               *
               * <div class = "cyrus-card">
                    <a class = "cyrus-card-link" href = "<?php echo Routing::getRouting("animes") . "?anime=" . $anime_id?>" title = "Shingeki no Kyojin - Temporada 3 - Episódio 25"></a>
                    <div class = "cyrus-card-image">
                        <img src = "https://images2.minutemediacdn.com/image/fetch/w_736,h_485,c_fill,g_auto,f_auto/https%3A%2F%2Fnetflixlife.com%2Ffiles%2Fimage-exchange%2F2022%2F04%2Fie_85541-1-850x560.jpeg">
                    </div>
                    <div class = "cyrus-card-body">
                        <div class = "cyrus-card-title">
                            <h4 class = "cyrus-card-title">Spy x Family</h4>
                        </div>
                        <div class = "cyrus-card-description">
                            <div class = "cyrus-card-description-info">
                                <span>2 Temporadas, 52 Vídeos</span>
                            </div>
                            <div class = "cyrus-card-description-type">
                                <span>Série</span>
                            </div>
                        </div>
                    </div>
                </div>
               *
               * */

               if(result.data){
                   let main_results = $("<div>").attr("class", "results-wrapper");
                   $("#main").find(".results-wrapper").remove();
                    for(let i = 0; i < 3 ; i++){
                        let card =  $("<div>").attr("class", "cyrus-card");
                        if(result.data.length-1 >= i){
                            let item = result.data[i];
                            if(item instanceof Anime) {
                                let seasonsTotal = item.seasons !== null ? item.seasons.length : 0;
                                let videosTotal = item.videos !== null ? item.videos.length : 0;
                                console.log(Array.isArray(item.seasons));
                                let videosFromSeasons : any[] = item.seasons.map(e => e.videos);
                                for(let v = 0; v < videosFromSeasons.length; v++) videosTotal += videosFromSeasons[v].length;
                                card.append(
                                    $("<a>").attr("class", "cyrus-card-link").attr("href", "www.google.com").attr("title", item.title)
                                ).append(
                                    $("<div>").attr("class", "cyrus-card-image").append(
                                        $("<img>").attr("src", "https://images2.minutemediacdn.com/image/fetch/w_736,h_485,c_fill,g_auto,f_auto/https%3A%2F%2Fnetflixlife.com%2Ffiles%2Fimage-exchange%2F2022%2F04%2Fie_85541-1-850x560.jpeg")
                                    )
                                ).append(
                                    $("<div>").attr("class", "cyrus-card-body").append(
                                        $("<div>").attr("class", "cyrus-card-title").append(
                                            $("<h4>").attr("class", "cyrus-card-title").html(item.title)
                                        )
                                    ).append(
                                        $("<div>").attr("class", "cyrus-card-description").append(
                                            $("<div>").attr("class", "cyrus-card-description-info").append(
                                                $("<span>").html((seasonsTotal > 0 ? seasonsTotal + " Temporadas, " : "") + videosTotal + " Vídeos")
                                            )
                                        ).append(
                                            $("<div>").attr("class", "cyrus-card-description-type").append(
                                                $("<span>").html("Série")
                                            )
                                        )
                                    )
                                );
                            } else if(item instanceof Video) {

                                let duration = Math.round(item.duration / 60);
                                let title = (item.season !== null ? "Temporada " + item.season?.numeration : "") + (item.numeration !== null ? "Episódio " + item.numeration : "") + ((item.season !== null || item.anime !== null) ? " - " : "") + item.title
                                card.append(
                                    $("<a>").attr("class", "cyrus-card-link").attr("href", "www.google.com").attr("title", item.title)
                                ).append(
                                    $("<div>").attr("class", "cyrus-card-image").append(
                                        $("<img>").attr("class", "c-opacity-70").attr("src", "https://images2.minutemediacdn.com/image/fetch/w_736,h_485,c_fill,g_auto,f_auto/https%3A%2F%2Fnetflixlife.com%2Ffiles%2Fimage-exchange%2F2022%2F04%2Fie_85541-1-850x560.jpeg")
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
                    $("#main").append(main_results);
               }

           } else {

           }
       });
   });
})