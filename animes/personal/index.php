<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\AutoLoader.php');
AutoLoader::register();

use Functions\Utils;

$files = array(
    "jquery" => (Utils::getURL() . "/resources/dependencies/JQuery/jquery-3.6.0.min.js"),
    "bootstrap_js" => (Utils::getURL() . "/resources/dependencies/Bootstrap 5/js/bootstrap.bundle.min.js"),
    "bootstrap_css" => (Utils::getURL() . "/resources/dependencies/Bootstrap 5/css/bootstrap.min.css"),
    "fontawesome_css" => (Utils::getURL() . "/resources/dependencies/fontawesome-6.1.1/css/all.css"),
    "personal_css" => (Utils::getURL() . "/animes/personal/assets/css/personal.css"),
    "personal_js" => (Utils::getURL() . "/animes/personal/assets/js/personal.js"),
    "cyrus_css" => (Utils::getURL() . "/resources/css/cyrus.css"),
    "cyrus_js" => (Utils::getURL() . "/resources/js/cyrus.js"),
    "icon" => (Utils::getURL() . "/resources/images/logo.png"),
)
?>
<html lang="pt_PT">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- JQuery -->
    <script src="<?php echo $files['jquery']; ?>"></script>
    <!-- Bootstrap -->
    <link href="<?php echo $files['bootstrap_css']; ?>" rel="stylesheet">
    <script src="<?php echo $files['bootstrap_js']; ?>"></script>
    <!-- Font Awesome -->
    <link href="<?php echo $files['fontawesome_css']; ?>" rel="stylesheet">
    <!-- CSS -->
    <link href="<?php echo $files['personal_css']; ?>" rel="stylesheet">
    <link href="<?php echo $files['cyrus_css']; ?>" rel="stylesheet">
    <!-- JavaScript -->
    <link href="<?php echo $files['cyrus_js']; ?>" rel="stylesheet">
    <link href="<?php echo $files['personal_js']; ?>" rel="stylesheet">
    <!-- Title -->
    <title>Cyrus - Attack on Titan / Shingeki no Kyojin</title>
    <!-- Logo -->
    <link rel = "icon" href ="<?php echo $files['icon']; ?>" type = "image/x-icon">
</head>
<body>
<header>
</header>
<div id="content">
    <div id="series_art">
        <div id="background">
            <img src="https://i.pinimg.com/originals/13/a1/01/13a10172127bbf9da50b8ce6db35eeaa.png" alt="Attack on Titan">
        </div>
        <div id="profile">
            <img src="https://i1.wp.com/animesonlinegames.com/wp-content/uploads/2021/12/shingeki-no-kyojin-4-part-2-todos-os-episodios.jpg" alt="Attack on Titan">
        </div>
    </div>
    <div class="content-wrapper">
        <div class="row" id="information">
            <div class="col">
                <div id="title">
                    <h2>Attack on Titan</h2>
                </div>
                <div id = "details">
                    <span>70 vídeos</span>
                    <span>70 algo</span>
                </div>
                <div id="rating">
                    <i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i>
                </div>
                <div id="synopsis">
                    <p class="text">Eren Jaeger jurou eliminar todos os Titãs, mas em uma batalha desesperada ele se
                        torna aquilo que mais odeia. Com seus novos poderes, ele luta pela liberdade da humanidade,
                        combatendo os monstros que ameaçam seu lar. Mesmo depois de derrotar a Titã Fêmea, Eren não
                        consegue descansar - uma horda de Titãs se aproximam da Muralha Rose e a batalha em nome da
                        humanidade continua!</p>
                </div>

                <div id="gender">
                    <span>ACTION</span>
                    <span>ADVENTURE</span>
                    <span>DRAMA</span>
                    <span>FANTASY</span>
                    <span>THRILLER</span>
                </div>
            </div>
            <div class="col">
                <div id="trailer">
                    <iframe width="420" height="315"
                            src="https://www.youtube.com/embed/MWSR17vEVBw">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- https://localhost/Cyrus/animes/?anime=Shingeki+no+Kyojin&ep=26 -->
        <div class="row" id="episodes">
            <div class = "controls row no-select">
                <div class = "col">
                    <div class="dropdown">
                        <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Temporada 3
                        </div>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li>Temporada 1 - Season 1</li>
                            <li>Temporada 2 - Season 2</li>
                            <li class = "selected">Temporada 3 - Season 3</li>
                            <li>Temporada 4 - Final Season</li>
                        </ul>
                    </div>
                </div>
                <div class = "col">
                    <div class = "order">
                        <i class="fa-solid fa-arrow-down-short-wide"></i>
                        <span>MAIS RECENTE</span>
                    </div>
                </div>
                <!--<div class = "dropdown">
                    <i class="fa-solid fa-sort-down"></i>
                    <span class = "selected-item">Temporada 4 - Final Season</span>
                </div>-->
            </div>
            <div class = "row episodes-list">
                <?php
                for($i = 0; $i < 15; $i++){
                ?>
                <div class="episode">
                    <a class = "episode_link" href = "?anime=Shingeki+no+Kyojin&season=1&ep=26" title = "Shingeki no Kyojin - Temporada 3 - Episódio 25"></a>
                    <div class = "thumbnail">
                        <img src = "https://sm.ign.com/t/ign_me/review/a/attack-on-/attack-on-titan-season-3-episode-1-smoke-signal-review_zghr.1024.jpg">
                        <div class = "duration"><span>24m</span></div>
                        <i class="fa-solid fa-play play"></i>
                    </div>
                    <div class = "series"><a href="?anime=Shingeki+no+Kyojin">Shingeki no Kyojin - Temporada 3</a></div>
                    <div class = "title">Episódio <?php echo $i+10;?> - O titã Bestial</div>
                    <div class = "reviews-count">
                        15k <i class="fa-solid fa-comments"></i>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        <hr>
        <div class = "seasons-switch no-select">
            <div class = "previous-season"><i class="fa-solid fa-angle-left"></i> TEMPORADA ANTERIOR</div>
            <div class = "next-season disable">PRÓXIMA TEMPORADA <i class="fa-solid fa-angle-right"></i></div>
        </div>
        <div class="row" id="reviews">
            <div class = "controller">
                <div class = "reviews-average-rating">
                    <span id="reviews-average-rating_value">12 Críticas </span>
                </div>

                <div class = "reviews-count">
                    <span id="reviews-average-count_value">4.9 <i class="fa-solid fa-star"></i> (45.2k)</span>
                </div>
                <div class = "reviews-filters">
                    <div class="dropdown">
                        <div class="dropdown-toggle" type="button" id="dropdown-sort" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-arrow-down-short-wide"></i>
                            Mais Antigo
                        </div>
                        <ul class="dropdown-menu" aria-labelledby="dropdown-sort">
                            <li class = "selected">Mais Antigo</li>
                            <li>Mais Recente</li>
                            <li>Mais Útil</li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <div class="dropdown-toggle" id="dropdown-filter" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-sliders"></i>
                            Filtro
                        </div>
                        <ul class="dropdown-menu" aria-labelledby="dropdown-filter">
                            <li class = "selected">Todos</li>
                            <li>1 Estrela</li>
                            <li>2 Estrelas</li>
                            <li>3 Estrelas</li>
                            <li>4 Estrelas</li>
                            <li>5 Estrelas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class = "toDELETE">
            <?php
            for($i = 0; $i < 20; $i++){
                echo "<br>";
            }
            ?>
        </div>
    </div>
</div>

<footer>

</footer>

</body>
</html>