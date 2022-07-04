<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\Season;
use Objects\SeasonsArray;
use Objects\Video;

try {
    $animes = isset($_GET["anime"]) ? Anime::find(id: intval($_GET["anime"]), flags: [Entity::ALL]) : null;
} catch (ReflectionException $e) {
    Utils::goTo("animes");
}
if ($animes == null || $animes?->size() == 0) {
    Utils::goTo("animes");
}
$anime = $animes[0];
$videos = Video::find(sql: "anime = " . $anime->getId() . " AND season IS NULL");
$seasons = Season::find(anime: $anime->getId(), flags: [Season::VIDEOS]);
$genders = $anime->getGenders() === null ? new EntityArray(null) : $anime->getGenders();

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - " . $anime->getTitle());
    ?>
    <link href="<?php echo Utils::getDependencies("Personal", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Personal") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
include(Utils::getDependencies("Cyrus", "alerts", true));
?>

<div id="content">
    <div id="series_art">
        <div id="background">
            <img src="<?php echo $anime->getCape()?->getPath(); ?>" alt="<?php echo $anime->getTitle() . " Capa" ?>">
        </div>
        <div id="profile">
            <img src="<?php echo $anime->getProfile()?->getPath(); ?>"
                 alt="<?php echo $anime->getTitle() . " Perfil" ?>">
        </div>
    </div>
    <div class="content-wrapper">
        <div class="row" id="information">
            <div class="col-6">
                <div id="title">
                    <h2><?php echo $anime->getTitle() ?></h2>
                </div>
                <div id="details">
                    <?php if ($seasons->size() > 0) {
                        echo '<span>' . $seasons->size() . ($seasons->size() == 1 ? ' Temporada' : ' Temporadas') . '<span>';
                    } ?>
                    <span><?php echo $videos->size() . ($videos->size() == 1 ? " Video" : " Videos") ?></span>
                </div>
                <!--<div class="rating" id = "rating-average">
                    <i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i><i class="fa-solid fa-star star"></i>
                </div>-->
                <div id="synopsis">
                    <p class="text"><?php echo $anime->getSynopsis(); ?></p>
                </div>

                <div id="gender">
                    <?php
                    foreach ($genders as $gender) { ?>
                        <span><?php echo mb_strtoupper($gender->getName()); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="col-3">
                <?php if ($anime->getTrailer() !== null) { ?>
                    <div id="trailer">
                        <iframe width="420" height="315" src="<?php echo $anime->getTrailer() ?>">
                        </iframe>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row" id="episodes">
            <div class="controls row no-select">
                <div class="col">
                    <?php
                    $s = new Season();
                    $s->setName("Outros");
                    $s->setNumeration(0);

                    ?>
                    <?php if ($seasons->size() > 0 || $videos->size() > 0) { ?>
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                 data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                    if($seasons->size()>0){
                                ?>
                                <span id="currentSeason"
                                      data-season="<?php echo $seasons[0]->getId(); ?>"><?php echo "Temporada " . $seasons[0]->getNumeration() . " - " . $seasons[0]->getName(); ?></span>
                                <?php } else {
                                        ?>
                                        <span id="currentSeason"
                                              data-season="videos"><?php echo "Videos"; ?></span>
                                        <?php
                                    }?>
                            </div>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="availableSeasons">
                                <?php
                                $isFirst = true;
                                foreach ($seasons as $season) {
                                    echo '<li ' . ($isFirst ? ' class = "selected"' : '') . ' data-id="' . $season->getId() . '"> Temporada ' . $season->getNumeration() . " - " . $season->getName() . '</li>';
                                    $isFirst = false;
                                }

                                echo '<li ' . ($isFirst ? ' class = "selected"' : '') . ' data-id="videos"> Videos </li>';

                                ?>

                            </ul>
                        </div>
                    <?php } ?>
                </div>
                <!--<div class="col">
                    <dv class="order">
                        <i class="fa-solid fa-arrow-down-short-wide"></i>
                        <span id="currentOrder" data-order="recent">MAIS RECENTE</span>
                    </dv>
                </div>-->
            </div>
            <?php
            if ($seasons->size() > 0) {
                $isFirst = true;
                foreach ($seasons

                         as $season) {
                    ?>
                    <div class="row episodes-list <?php echo !$isFirst ? 'cyrus-item-hidden' : '' ?>"
                         data-seasonlist="<?php echo $season?->getNumeration() ?>">
                        <?php
                        foreach ($season->getVideos() as $video) {
                            ?>
                            <div class="episode">
                                <a class="episode_link"
                                   href="<?php echo Routing::getRouting("episode") . '?episode=' . $video->getId(); ?>"
                                   title="<?php echo $anime->getTitle() . ' - ' . ($video->getSeason() !== null ? 'Temporada ' . $video->getSeason()?->getNumeration() . ' ' : '') . $video->getTitle(); ?>"></a>
                                <div class="thumbnail">
                                    <img src="<?php echo $video->getThumbnail()?->getPath() ?>">
                                    <div class="duration">
                                        <span><?php echo round(($video->getDuration()) / 60) ?>m</span>
                                    </div>
                                    <i class="fa-solid fa-play play"></i>
                                </div>
                                <div class="series"><a
                                            href="?anime=<?php echo $anime->getId(); ?>"><?php echo $anime->getTitle() . ($video->getSeason() !== null ? " - Temporada " . $video->getSeason()?->getNumeration() : ""); ?></a>
                                </div>
                                <div class="title">Episódio <?php echo $video->getNumeration() ?>
                                    - <?php echo $video->getTitle(); ?></div>
                                <!--<div class = "reviews-count">
                                    15k <i class="fa-solid fa-comments"></i>
                                </div>-->
                            </div>
                            <?php
                        }
                        $isFirst = false;
                        ?>
                    </div>
                    <?php
                }
            }
            if ($videos->size() > 0){
            ?>

            <div class="row episodes-list <?php echo $seasons->size() > 0 ? 'cyrus-item-hidden' : '' ?>"
                 data-seasonlist="videos">
                <?php
                foreach ($videos as $video) {
                    ?>
                    <div class="episode">
                        <a class="episode_link"
                           href="<?php echo Routing::getRouting("episode") . '?episode=' . $video->getId(); ?>"
                           title="<?php echo $anime->getTitle() . ' - ' . ($video->getSeason() !== null ? 'Temporada ' . $video->getSeason()?->getNumeration() . ' ' : '') . $video->getTitle(); ?>"></a>
                        <div class="thumbnail">
                            <img src="<?php echo $video->getThumbnail()?->getPath() ?>">
                            <div class="duration"><span><?php echo round(($video->getDuration()) / 60) ?>m</span>
                            </div>
                            <i class="fa-solid fa-play play"></i>
                        </div>
                        <div class="series"><a
                                    href="?anime=<?php echo $anime->getId(); ?>"><?php echo $anime->getTitle() . ($video->getSeason() !== null ? " - Temporada " . $video->getSeason()?->getNumeration() : ""); ?></a>
                        </div>
                        <div class="title">Episódio <?php echo $video->getNumeration() ?>
                            - <?php echo $video->getTitle(); ?></div>
                        <!--<div class = "reviews-count">
                            15k <i class="fa-solid fa-comments"></i>
                        </div>-->
                    </div>
                    <?php
                }

                } ?>

            </div>
            <hr>
            <!--<div class="seasons-switch no-select">
                <div class="previous-season disable" id="previousSeason"><i class="fa-solid fa-angle-left"></i> <span>TEMPORADA ANTERIOR</span>
                </div>
                <div class="next-season <?php echo $seasons->size() < 2 ? 'disable' : '' ?>>" id="nextSeason"><span>PRÓXIMA TEMPORADA</span>
                    <i
                            class="fa-solid fa-angle-right"></i></div>
            </div>-->
            <div class="row" id="reviews">
                <div class="controller">
                    <div class="reviews-average-rating">
                        <span id="reviews-average-rating_value"><?php echo $anime?->getComments()->size() ?> Críticas </span>
                    </div>
                    <!--<div class="reviews-count">
                        <span id="reviews-average-count_value">4.9 <i class="fa-solid fa-star"></i> (45.2k)</span>
                    </div>-->
                    <div class="reviews-filters">
                        <div>
                            <div class="dropdown">
                                <div class="dropdown-toggle" type="button" id="dropdown-sort" data-bs-toggle="dropdown"
                                     aria-expanded="false">
                                    <i class="fa-solid fa-arrow-down-short-wide"></i>
                                    <span class="reviews-filters-filter-title" id="currentReviewOrder"
                                          data-selected="older">Mais Antigo</span>
                                </div>
                                <ul class="dropdown-menu" aria-labelledby="dropdown-sort" id="review-order">
                                    <li class="selected" data-id="older">Mais Antigo</li>
                                    <li data-id="recent">Mais Recente</li>
                                </ul>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="dropdown-toggle" id="dropdown-filter" data-bs-toggle="dropdown"
                                 aria-expanded="false">
                                <i class="fa-solid fa-sliders"></i>
                                <span class="reviews-filters-filter-title" id = "review-current-filter" data-selected="all">Todos</span>
                            </div>
                            <ul class="dropdown-menu" aria-labelledby="dropdown-filter" id="review-filters">
                                <li data-id="all" class="selected">Todos</li>
                                <li data-id="1">1 Estrela</li>
                                <li data-id="2">2 Estrelas</li>
                                <li data-id="3">3 Estrelas</li>
                                <li data-id="4">4 Estrelas</li>
                                <li data-id="5">5 Estrelas</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="reviews-section">
                    <div class="review-post mt-3">
                        <div class="row">
                            <div class="col-2 review-post-user no select">
                                <img draggable="false" class="img-fluid mx-auto"
                                     src="<?php echo $_SESSION["user"]?->getProfileImage()?->getPath() ?>">
                                <div class="review-post-username"><?php echo $_SESSION["user"]?->getUsername() ?></div>
                            </div>
                            <div class="col-9">
                                <div class="review-post-rating">
                                    <div class="rating" id="comment-rating" style="position: relative;">
                                    <span class="reviews-classified-as"
                                          data-rating="0">Classificaste como 0 Estrelas</span><?php
                                        for ($i = 5;
                                             $i > 0;
                                             $i--) {
                                            $text = "Classificar com " . $i . ($i == 1 ? " estrela" : " estrelas");
                                            ?>
                                            <i data-star="<?php echo $i ?>" data-bs-toggle="tooltip"
                                               data-bs-placement="bottom"
                                               title="<?php echo $text ?>" class="fa-solid fa-star star"></i>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <form class="cyrus-form" id="form0">
                                    <div class="cyrus-form-inputs">
                                        <label class="cyrus-label">
                                            <input class="cyrus-input" id="form0-title" type="text" placeholder="Título"
                                                   maxlength="50">
                                        </label>
                                        <div class="reviews-self-char-notification"><span>Mínimo de 8 caracteres</span>
                                        </div>
                                        <label class="cyrus-label">
                                        <textarea maxlength="800" class="cyrus-input reviews-self-textarea"
                                                  id="form0-description" placeholder="Descrição"></textarea>
                                        </label>
                                        <div class="reviews-self-char-notification"><span>0/800 caracteres</span></div>
                                        <label class="cyrus-label-checkbox mt-2">
                                        <span class="cyrus-hover-pointer">
                                            <input class="cyrus-input-checkbox-null" type="checkbox" id="form0-spoiler">
                                            <span class="cyrus-input-checkbox-checkmark"></span>
                                            <span>Marcar como Spoiler</span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="cyrus-form-buttons">
                                        <input data-toggle="tooltip" id="form0-reset" data-placement="top"
                                               title="Tooltip on top" class="cyrus-input" type="reset" value="CANCELAR">
                                        <input disabled class="cyrus-input" id="form0-submit" type="submit"
                                               value="PUBLICAR">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="reviews-list" class="mt-3">
                        <?php
                        for ($x = 0;
                             $x < 5;
                             $x++) {

                            ?>
                            <div class="review">
                                <div class="row">
                                    <div class="col-2 review-post-user no-select">
                                        <img draggable="false" class="mx-auto"
                                             src="https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png">
                                    </div>
                                    <div class="col-9">
                                <span>
                                    <span class="review-username"><?php echo $_SESSION["user"]?->getUsername() ?></span>
                                    <span class="review-date float-right">10 de Janeiro de 2021</span>
                                </span>
                                        <span class="review-options">
                                    <button class="cyrus-btn cyrus-btn-simple"><i class="fa-solid fa-flag"></i></button>
                                    <button class="cyrus-btn cyrus-btn-simple"><i
                                                class="fa-solid fa-share-nodes"></i></button>
                                </span>
                                        <div class="review-star mt-3">
                                            <?php
                                            for ($i = 0;
                                                 $i < 5;
                                                 $i++) { ?>
                                                <i class="fa-solid fa-star star static filled"></i>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="mt-3">
                                            <h3 class="review-title">Lorem ipsum dolor sit amet</h3>
                                            <div class="review-description" data-collapsible="true">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris
                                                    fringilla
                                                    nunc at arcu rhoncus facilisis. Donec at justo eget eros auctor
                                                    porttitor ut
                                                    in magna. Etiam porta commodo dolor. Sed a enim dapibus, placerat
                                                    erat
                                                    sit
                                                    amet, rhoncus ipsum. Fusce ut lobortis turpis, a hendrerit leo.
                                                    Vivamus
                                                    dui
                                                    ipsum, tristique vulputate vulputate nec, cursus non enim. Proin
                                                    molestie
                                                    ante a lorem congue, quis tincidunt ligula consectetur. Sed id
                                                    tempus
                                                    mi,
                                                    sed finibus nulla.
                                                    Curabitur sodales viverra dapibus. Aenean fermentum dui turpis, non
                                                    consectetur sapien posuere in. Duis gravida vitae arcu sed rhoncus.
                                                    Integer
                                                    vel ex dapibus, dapibus dolor vel, tincidunt mi. Nullam eget
                                                    suscipit
                                                    lorem.
                                                    Integer a nibh non purus aliquam efficitur. Nullam consequat
                                                    condimentum
                                                    nulla, vitae mollis ipsum dignissim sit amet. Suspendisse potenti.
                                                    Praesent
                                                    tristique dolor mauris, a suscipit sem ultricies ut.
                                                    Suspendisse fermentum erat nunc, consequat mattis dolor posuere nec.
                                                    Vivamus
                                                    pretium in ligula in dapibus. Nulla facilisi. Donec lectus ligula,
                                                    sagittis
                                                    eu tincidunt eget, aliquam a mauris. Maecenas et purus luctus,
                                                    pretium
                                                    tellus ac, aliquet augue. Phasellus sollicitudin justo sit amet
                                                    ligula
                                                    vulputate, eget vehicula orci rutrum. Phasellus placerat rhoncus
                                                    convallis.
                                                    Curabitur eleifend, justo sed tempus finibus, neque nulla varius
                                                    urna,
                                                    sit
                                                    amet ultrices urna metus in nibh. Sed sed sodales urna, nec pretium
                                                    orci.
                                                    Phasellus rhoncus ac nisl id lobortis. Morbi sit amet elit laoreet,
                                                    viverra
                                                    dui sit amet, efficitur nunc. Nulla cursus ante id tempor sodales.
                                                    Fusce luctus lacus libero. Integer bibendum lacinia urna, id
                                                    faucibus
                                                    ipsum
                                                    hendrerit ut. Fusce bibendum tellus sit amet accumsan malesuada. Nam
                                                    facilisis nibh vestibulum ex condimentum, ut lobortis ex pharetra.
                                                    Mauris
                                                    porta tristique cursus. Duis cursus magna id iaculis ornare. Duis
                                                    ultrices
                                                    nunc nisl, nec porttitor est volutpat ut. Vestibulum non congue
                                                    metus,
                                                    tempor consequat tellus. Proin vitae ex a nisi volutpat fringilla.
                                                    Vivamus
                                                    sit amet consequat ante, in dignissim magna. Nullam vitae lobortis
                                                    ligula, a
                                                    sodales tellus. Sed luctus risus id interdum efficitur.
                                                    Vivamus euismod ipsum quis facilisis congue. Proin et tincidunt
                                                    velit.
                                                    Quisque sit amet porta metus. Sed non eros ut diam consectetur
                                                    rhoncus.
                                                    Nam
                                                    at dui lacus. Quisque nibh mi, bibendum sit amet nunc nec, imperdiet
                                                    euismod
                                                    leo. Mauris blandit odio eleifend nisi aliquet maximus laoreet non
                                                    arcu.
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <button data-collapse="true" class="cyrus-btn cyrus-btn-simple">MOSTRAR MAIS
                                            </button>
                                        </div>
                                        <!--<div class ="evaluate-review mt-2">
                                            <span data-positive="86">86</span> de <span data-total = "100">100</span> pessoas consideraram esta crítica útil. É útil para si? <button class = "cyrus-btn cyrus-btn-simple evaluate-review-button">SIM</button> | <button class = "cyrus-btn cyrus-btn-simple">NÃO</button>
                                        </div>-->
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