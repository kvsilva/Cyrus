<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\Season;
use Objects\SeasonsArray;
use Objects\Video;
use Services\Animes;
use Services\Users;


?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead();
    ?>
    <link href="<?php echo Utils::getDependencies("Home", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Home") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="section content-wrapper">
        <div class="content-wrapper">
            <div class="section-list">
                <div class="section-list-title ">
                    <span class="h2">Recomendações para Ti</span>
                </div>
                <div class="cyrus-feed-divider cyrus-feed-divider-2"></div>
            </div>
        </div>
        <div class="cyrus-carousel cyrus-carousel-cards">
            <div class="content-wrapper cyrus-carousel-wrapper">
                <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper cyrus-carousel-arrow-hidden"
                     data-arrow="previous">
                    <div role="button" class="cyrus-carousel-previous cyrus-carousel-arrow">
                        <i class="fa-solid fa-chevron-left"></i>
                    </div>
                </div>
                <div class="cyrus-carousel-items">
                    <div class="cyrus-carousel-items-wrapper">
                        <?php
                        $animes = Animes::getRandomizedList()->getBareReturn();
                        foreach ($animes[0] as $anime) {
                            ?>
                            <div class="cyrus-card cyrus-carousel-items-card">
                                <a class="cyrus-card-link"
                                   href="<?php echo Routing::getRouting("animes") . "?anime=" . $anime->getId() ?>"
                                   title="<?php echo $anime->getTitle(); ?>"></a>
                                <div class="cyrus-card-image-profile">
                                    <img src="<?php echo $anime->getProfile()?->getPath(); ?>">
                                </div>
                                <div class="cyrus-card-body">
                                    <div class="cyrus-card-title">
                                        <h4 class="cyrus-card-title"><?php echo $anime->getTitle(); ?></h4>
                                    </div>
                                    <div class="cyrus-card-description">
                                        <div class="cyrus-card-description-type"><span>Série</span></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper cyrus-carousel-arrow-hidden"
                     data-arrow="next">
                    <div role="button" class="cyrus-carousel-next cyrus-carousel-arrow">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_SESSION["user"])) {
        ?>
        <div id="keepWatchingVideos" class="section content-wrapper">
            <div class="content-wrapper">
                <div class="section-list">
                    <div class="section-list-title">
                        <span class="h2">Continue Assistindo</span>
                        <div class="float-end cyrus-feed-view-link">
                            <a class="link-nodecoration" href="#">Ver Histórico <i
                                        class="fa-solid fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <div class="cyrus-feed-divider cyrus-feed-divider-1"></div>
                </div>
            </div>
            <div class="cyrus-carousel cyrus-carousel-cards">
                <div class="content-wrapper cyrus-carousel-wrapper">
                    <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper cyrus-carousel-arrow-hidden"
                         data-arrow="previous">
                        <div role="button" class="cyrus-carousel-previous cyrus-carousel-arrow">
                            <i class="fa-solid fa-chevron-left"></i>
                        </div>
                    </div>
                    <div class="cyrus-carousel-items">
                        <div class="cyrus-carousel-items-wrapper">
                            <?php
                            $videos = Users::getKeepWatchingVideos()->getBareReturn();
                            foreach ($videos as $item) {
                                if(count($item) === 0) continue;
                                    $video = $item[0]["video"];
                                $remaining = $item[0]["remaining"];
                                ?>
                                <div class="cyrus-card cyrus-carousel-items-card">
                                    <a class="cyrus-card-link"
                                       href="<?php echo Routing::getRouting("episode") . "?episode=" . $video?->getId() ?>"
                                       title="<?php echo $video?->getTitle() ?>"></a>
                                    <div class="cyrus-card-image-cape">
                                        <img class="c-opacity-70"
                                             src="<?php echo $video?->getThumbnail()?->getPath() ?>">
                                        <div class="cyrus-card-duration">
                                            <span><?php echo floor($remaining / 60) . "m para terminar" ?></span></div>
                                        <i class="fa-solid fa-play cyrus-card-center"></i></div>
                                    <div class="cyrus-card-body">
                                        <div class="cyrus-card-description">
                                            <div class="cyrus-card-description-info">
                                                <span><?php echo $video?->getAnime()?->getTitle() ?> </span></div>
                                        </div>
                                        <div class="m-0 cyrus-card-title">
                                            <h4 class="cyrus-card-title">
                                                <?php
                                                echo ($video?->getSeason() !== null ? $video?->getSeason()?->getName() . " " : "") . $video?->getVideoType()?->getName() . " " . $video?->getNumeration() . " - " . $video?->getTitle();
                                                ?>
                                            </h4>
                                        </div>
                                        <div class="m-0 cyrus-card-description">
                                            <div class="cyrus-card-description-type">
                                                <span><?php echo $video?->getVideoType()?->getName() ?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper cyrus-carousel-arrow-hidden"
                         data-arrow="next">
                        <div role="button" class="cyrus-carousel-next cyrus-carousel-arrow">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } ?>
    <div id="lastReleasedVideos" class="section content-wrapper">
        <div class="content-wrapper">
            <div class="section-list">
                <div class="section-list-title">
                    <span class="h2">Últimos Vídeos Lançados</span>
                </div>
                <div class="cyrus-feed-divider cyrus-feed-divider-2"></div>
            </div>
        </div>
        <div class="cyrus-carousel cyrus-carousel-cards">
            <div class="content-wrapper cyrus-carousel-wrapper">
                <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper cyrus-carousel-arrow-hidden"
                     data-arrow="previous">
                    <div role="button" class="cyrus-carousel-previous cyrus-carousel-arrow">
                        <i class="fa-solid fa-chevron-left"></i>
                    </div>
                </div>
                <div class="cyrus-carousel-items">
                    <div class="cyrus-carousel-items-wrapper">
                        <?php
                        $videos = Animes::getLastReleasedVideos()->getBareReturn();
                        foreach ($videos[0] as $video) {
                            ?>
                            <div class="cyrus-card cyrus-carousel-items-card">
                                <a class="cyrus-card-link"
                                   href="<?php echo Routing::getRouting("episode") . "?episode=" . $video?->getId() ?>"
                                   title="<?php echo $video?->getTitle() ?>"></a>
                                <div class="cyrus-card-image-cape">
                                    <img class="c-opacity-70" src="<?php echo $video?->getThumbnail()?->getPath() ?>">
                                    <div class="cyrus-card-duration">
                                        <span><?php echo floor($video->getDuration() / 60) . "m " ?></span></div>
                                    <i class="fa-solid fa-play cyrus-card-center"></i></div>
                                <div class="cyrus-card-body">
                                    <div class="cyrus-card-description">
                                        <div class="cyrus-card-description-info">
                                            <span><?php echo $video?->getAnime()?->getTitle() ?> </span></div>
                                    </div>
                                    <div class="m-0 cyrus-card-title">
                                        <h4 class="cyrus-card-title">
                                            <?php
                                            echo ($video?->getSeason() !== null ? $video?->getSeason()?->getName() . " " : "") . $video?->getVideoType()?->getName() . " " . $video?->getNumeration() . " - " . $video?->getTitle();
                                            ?>
                                        </h4>
                                    </div>
                                    <div class="m-0 cyrus-card-description">
                                        <div class="cyrus-card-description-type">
                                            <span><?php echo $video?->getVideoType()?->getName() ?></span></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper cyrus-carousel-arrow-hidden"
                     data-arrow="next">
                    <div role="button" class="cyrus-carousel-next cyrus-carousel-arrow">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="todayCalendar" class="section content-wrapper">
        <div class="content-wrapper">
            <div class="section-list">
                <div class="section-list-title">
                    <span class="h2">Calendário de Hoje</span>
                </div>
                <div class="cyrus-feed-divider cyrus-feed-divider-1"></div>
            </div>
        </div>
        <div class="cyrus-grid cyrus-grid-3">
            <?php
            $calendar = Animes::getCalendar("today")->getBareReturn();
            foreach ($calendar as $day => $dayInfo)
                foreach ($dayInfo["animes"] as $anime) {
                    ?>
                    <div class="cyrus-card cyrus-card-flex cyrus-carousel-items-card">
                        <a class="cyrus-card-link"
                           href="<?php echo Routing::getRouting("animes") . "?anime=" . $anime->getId() ?>"
                           title="<?php echo $anime?->getTitle() ?>"></a>
                        <div class="cyrus-card-image-flex">
                            <img src="<?php echo $anime->getCape()?->getPath() ?>">
                        </div>
                        <div class="cyrus-card-body">
                            <div class="cyrus-card-title"><h4
                                        class="cyrus-card-title"><?php echo $anime?->getTitle() ?></h4>
                            </div>
                            <div class="cyrus-card-description ">
                                <div class="cyrus-card-description-info"><span>Episódio 255</span></div>
                                <div class="cyrus-card-description-type float-end">
                                    <span><?php echo $anime?->getLaunchTime()?->format("H:i"); ?></span></div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>

</body>
</html>