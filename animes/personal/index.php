<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');
use Functions\Utils;
?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Attack on Titan");
    ?>
    <link href="<?php echo Utils::getDependencies("Personal", "css")?>" rel="stylesheet">
    <script src="<?php echo Utils::getDependencies("Personal")?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
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
            <div class="col-6">
                <div id="title">
                    <h2>Attack on Titan</h2>
                </div>
                <div id = "details">
                    <span>70 vídeos</span>
                    <span>70 algo</span>
                </div>
                <div class="rating" id = "rating-average">
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
            <div class="col-3">
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
            <div class = "previous-season"><i class="fa-solid fa-angle-left"></i> <span>TEMPORADA ANTERIOR</span></div>
            <div class = "next-season disable"><span>PRÓXIMA TEMPORADA</span> <i class="fa-solid fa-angle-right"></i></div>
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
                            <span class = "reviews-filters-filter-title">Mais Antigo</span>
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
                            <span class = "reviews-filters-filter-title">Filtro</span>
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
            <div class = "reviews-section">
                <div class = "review-post mt-3">
                    <div class = "row">
                        <div class = "col-2 review-post-user no select">
                            <img draggable="false" class = "img-fluid mx-auto" src = "https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png">
                            <div class = "review-post-username">Kurookami</div>
                        </div>
                        <div class = "col-9">
                            <div class = "review-post-rating">
                                <div class = "rating" style = "position: relative;" >
                                    <span class = "reviews-classified-as">Classificaste como 5 Estrelas</span><?php
                                    for($i = 5; $i > 0; $i--){
                                        $text = "Classificar com " . $i . ($i == 1 ? " estrela" : " estrelas");
                                        ?>
                                        <i data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $text?>" class="fa-solid fa-star star"></i>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <form class = "cyrus-form">
                                <div class = "cyrus-form-inputs">
                                    <label class = "cyrus-label">
                                        <input class = "cyrus-input" type ="text" placeholder="Título">
                                    </label>
                                    <div class = "reviews-self-char-notification"><span>Mínimo de 8 caracteres</span></div>
                                    <label class = "cyrus-label">
                                        <textarea class = "cyrus-input reviews-self-textarea" placeholder="Descrição"></textarea>
                                    </label>
                                    <div class = "reviews-self-char-notification"><span>0/200 caracteres</span></div>
                                    <label class = "cyrus-label-checkbox mt-2">
                                        <span class = "cyrus-hover-pointer">
                                            <input class = "cyrus-input-checkbox-null" type = "checkbox">
                                            <span class="cyrus-input-checkbox-checkmark"></span>
                                            <span>Marcar como Spoiler</span>
                                        </span>
                                    </label>
                                </div>
                                <div class = "cyrus-form-buttons">
                                    <input data-toggle="tooltip" data-placement="top" title="Tooltip on top" class = "cyrus-input" type = "reset" value="CANCELAR">
                                    <input class = "cyrus-input" type = "submit" value = "PUBLICAR">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id = "reviews-list" class = "mt-3">
                    <?php
                    for($x = 0; $x < 5; $x++){

                    ?>
                    <div class = "review">
                        <div class = "row">
                            <div class = "col-2 review-post-user no-select">
                                <img draggable="false" class = "mx-auto" src = "https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png">
                            </div>
                            <div class = "col-9">
                                <span>
                                    <span class = "review-username">Kurookami</span>
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
                                    <div class = "review-description ">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris fringilla nunc at arcu rhoncus facilisis. Donec at justo eget eros auctor porttitor ut in magna. Etiam porta commodo dolor. Sed a enim dapibus, placerat erat sit amet, rhoncus ipsum. Fusce ut lobortis turpis, a hendrerit leo. Vivamus dui ipsum, tristique vulputate vulputate nec, cursus non enim. Proin molestie ante a lorem congue, quis tincidunt ligula consectetur. Sed id tempus mi, sed finibus nulla.
                                            Curabitur sodales viverra dapibus. Aenean fermentum dui turpis, non consectetur sapien posuere in. Duis gravida vitae arcu sed rhoncus. Integer vel ex dapibus, dapibus dolor vel, tincidunt mi. Nullam eget suscipit lorem. Integer a nibh non purus aliquam efficitur. Nullam consequat condimentum nulla, vitae mollis ipsum dignissim sit amet. Suspendisse potenti. Praesent tristique dolor mauris, a suscipit sem ultricies ut.
                                            Suspendisse fermentum erat nunc, consequat mattis dolor posuere nec. Vivamus pretium in ligula in dapibus. Nulla facilisi. Donec lectus ligula, sagittis eu tincidunt eget, aliquam a mauris. Maecenas et purus luctus, pretium tellus ac, aliquet augue. Phasellus sollicitudin justo sit amet ligula vulputate, eget vehicula orci rutrum. Phasellus placerat rhoncus convallis. Curabitur eleifend, justo sed tempus finibus, neque nulla varius urna, sit amet ultrices urna metus in nibh. Sed sed sodales urna, nec pretium orci. Phasellus rhoncus ac nisl id lobortis. Morbi sit amet elit laoreet, viverra dui sit amet, efficitur nunc. Nulla cursus ante id tempor sodales.
                                            Fusce luctus lacus libero. Integer bibendum lacinia urna, id faucibus ipsum hendrerit ut. Fusce bibendum tellus sit amet accumsan malesuada. Nam facilisis nibh vestibulum ex condimentum, ut lobortis ex pharetra. Mauris porta tristique cursus. Duis cursus magna id iaculis ornare. Duis ultrices nunc nisl, nec porttitor est volutpat ut. Vestibulum non congue metus, tempor consequat tellus. Proin vitae ex a nisi volutpat fringilla. Vivamus sit amet consequat ante, in dignissim magna. Nullam vitae lobortis ligula, a sodales tellus. Sed luctus risus id interdum efficitur.
                                            Vivamus euismod ipsum quis facilisis congue. Proin et tincidunt velit. Quisque sit amet porta metus. Sed non eros ut diam consectetur rhoncus. Nam at dui lacus. Quisque nibh mi, bibendum sit amet nunc nec, imperdiet euismod leo. Mauris blandit odio eleifend nisi aliquet maximus laoreet non arcu.
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <button class = "cyrus-btn cyrus-btn-simple">MOSTRAR MAIS</button>
                                </div>
                                <div class ="evaluate-review mt-2">
                                    <span data-positive="86">86</span> de <span data-total = "100">100</span> pessoas consideraram esta crítica útil. É útil para si? <button class = "cyrus-btn cyrus-btn-simple evaluate-review-button">SIM</button> | <button class = "cyrus-btn cyrus-btn-simple">NÃO</button>
                                </div>
                            </div>
                        </div>
                        <!--<hr class = "w-25 mx-auto">-->
                    </div>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>

    </div>
</div>

<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>

<script>
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
</body>
</html>