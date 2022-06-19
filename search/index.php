<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');

use Enumerators\Month;
use Functions\Utils;
use Objects\Anime;
use Objects\Video;
use Objects\VideoType;
use Others\Routing;

//Sort Array by a property: https://stackoverflow.com/questions/4282413/sort-array-of-objects-by-object-fields

$anime_id = 82;

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);

    echo getHead(" - Procurar");
    ?>
    <script src = "<?php echo Utils::getDependencies("Search", "js", false) ?>"></script>
    <link href = "<?php echo Utils::getDependencies("Search", "css", false) ?>" rel = "stylesheet">
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <!-- CONTENT HERE -->
        <div class = "search">
            <div class = "content-wrapper">
                <form id = "form-query" class = "d-flex justify-content-center">
                    <label class = "cyrus-label-noborder">
                        <input class = "cyrus-input-noborder" type = "text" placeholder="Procurar...">
                        <div class = "reset" id = "reset-query-form">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </label>
                </form>
            </div>
        </div>
    <div class = "content-wrapper">


        <!-- another one: https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQeFFzcwr5GxNtVi7E4paPpwVYH0jANWM4Dbw&usqp=CAU-->
        <div class = "results">
            <h4>Principais Resultados</h4>
            <div class = "results-wrapper">
                <?php for($i = 0; $i < 1; $i++){?>
                <div class = "cyrus-card">
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
                <?php }?>

                <div class = "cyrus-card">
                    <a class = "cyrus-card-link" href = "<?php echo Routing::getRouting("episode") . "?anime=" . $anime_id?>" title = "Shingeki no Kyojin - Temporada 3 - Episódio 25"></a>
                    <div class = "cyrus-card-image">
                        <img class = "c-opacity-70" src = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQeFFzcwr5GxNtVi7E4paPpwVYH0jANWM4Dbw&usqp=CAU">
                        <div class = "cyrus-card-duration">
                            <span>24m</span>
                        </div>
                        <i class="fa-solid fa-play cyrus-card-center"></i>
                    </div>
                    <div class = "cyrus-card-body">
                        <div class = "cyrus-card-description">
                            <div class = "cyrus-card-description-info">
                                <span>Horimiya</span>
                            </div>
                        </div>
                        <div class = "m-0 cyrus-card-title">
                            <h4 class = "cyrus-card-title">Temporada 2, Episódio 1 - Teste</h4>
                        </div>
                        <div class = "m-0 cyrus-card-description">
                            <div class = "cyrus-card-description-type">
                                <span>Episódio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class = "series results">
            <h4>Séries</h4>
            <div class = "results-wrapper">
                <?php
                for($i = 0; $i < 4; $i++){
                ?>
                <div class = "cyrus-card cyrus-card-flex">
                    <a class = "cyrus-card-link" href = "<?php echo Routing::getRouting("animes") . "?anime=" . $anime_id?>" title = "Shingeki no Kyojin - Temporada 3 - Episódio 25"></a>
                    <div class = "cyrus-card-image-catalog">
                        <img src = "https://curso1000.com.br/wp-content/uploads/2022/05/cropped-soyxfamily-destacada.webp">
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
                <?php } ?>
            </div>
        </div>

        <?php
        $videoTypes = VideoType::find();
        foreach($videoTypes as $type){
        ?>
        <div class = "videos results">
            <h4><?php echo $type->getName();?></h4>
            <div class = "results-wrapper results-wrapper-videos">
                <?php for($i = 0; $i < 4; $i++){?>
                <div class = "cyrus-card cyrus-card-flex">
                    <a class = "cyrus-card-link" href = "<?php echo Routing::getRouting("episode") . "?anime=" . $anime_id?>" title = "Shingeki no Kyojin - Temporada 3 - Episódio 25"></a>
                    <div class = "cyrus-card-image-flex">
                        <img class = "c-opacity-70" src = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQeFFzcwr5GxNtVi7E4paPpwVYH0jANWM4Dbw&usqp=CAU">
                        <div class = "cyrus-card-duration">
                            <span>24m</span>
                        </div>
                        <i class="fa-solid fa-play cyrus-card-center"></i>
                    </div>
                    <div class = "cyrus-card-body">
                        <div class = "cyrus-card-description">
                            <div class = "cyrus-card-description-info">
                                <span>Horimiya</span>
                            </div>
                        </div>
                        <div class = "m-0 cyrus-card-title">
                            <h4 class = "cyrus-card-title">Temporada 2, Episódio 1 - Teste</h4>
                        </div>
                        <div class = "m-0 cyrus-card-description">
                            <div class = "cyrus-card-description-type">
                                <span><?php echo $type->getName(); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>