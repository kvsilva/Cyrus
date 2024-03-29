<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\News;
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
    <div class="content-wrapper">
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
    </div>
    <div class="news-section">
        <div class="background-image">
            <img src="https://i.pinimg.com/originals/90/cd/dc/90cddc7eeddbac6b17b4e25674e9e971.jpg">
        </div>
        <div class="news">
            <div class="news-wrapper">
                <h2 class="news-wrapper-title">
                    <i style="font-size: 15px;" class="fa-solid fa-bullhorn"></i>
                    <div class = "w-100">
                    <span>Cyrus Notícias</span>
                        <div class="float-end cyrus-feed-view-link">
                            <a class="link-nodecoration" href="<?php echo Routing::getRouting("news")?>">Ver Todas <i
                                        class="fa-solid fa-chevron-right"></i></a>
                        </div>
                    </div>
                </h2>
                <div class="cyrus-feed-divider cyrus-feed-divider-3"></div>
                <div class="news-content">
                    <div class="spotlight-news">
                        <h5>Notícias em Destaque</h5>
                        <div class="spotlight-news-wrapper">
                            <?php
                            $spotlight_news = News::find(spotlight: true, limit: 2, flags: [Entity::ALL]);
                            foreach($spotlight_news as $news){
                            $lastUpdate = $news->getEditions()[$news->getEditions()->size()-1];
                            ?>
                            <div class="cyrus-card">
                                <a class="cyrus-card-link" href="<?php echo Routing::getRouting("news") . '?news=' . $lastUpdate->getId()?>"></a>
                                <div class="cyrus-card-image cyrus-card-image-cape">
                                    <img class = "news-cape" src="<?php echo $lastUpdate?->getThumbnail()?->getPath()?>"></div>
                                <div class="cyrus-card-body">
                                    <div class="cyrus-card-title">
                                        <h4 class="cyrus-card-title"><?php echo $lastUpdate->getTitle()?></h4>
                                    </div>
                                    <div class="cyrus-card-description">
                                        <div class="cyrus-card-description-info"><span><?php
                                                $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                                                //$formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy 'às' H:mm");
                                                $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy");
                                                $formatted = $formatter->format($news->getCreatedAt());
                                                $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                                                $monthCapitalize = ucfirst($month);
                                                $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                                                echo $formatted . ", por " . $news->getUser()?->getUsername();

                                                ?></span></div>

                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="latest-news">
                        <h5>Notícias mais Recentes</h5>
                        <?php
                        $recent_news = News::find(spotlight: true, limit: 5, flags: [Entity::ALL]);
                        foreach($recent_news as $news){
                            $lastUpdate = $news->getEditions()[$news->getEditions()->size()-1];
                         ?>
                        <div class="cyrus-card cyrus-card-flex">
                            <a class="cyrus-card-link" href="<?php echo Routing::getRouting("news") . '?news='. $news->getId()?>"></a>
                            <div class="cyrus-card-image cyrus-card-image-cape-flex">
                                <img src="<?php echo $lastUpdate?->getThumbnail()?->getPath()?>"></div>
                            <div class="cyrus-card-body">
                                <div class="cyrus-card-title">
                                    <h4 class="cyrus-card-title"><?php echo $lastUpdate?->getTitle()?></h4>
                                </div>
                                <div class="cyrus-card-description">
                                    <div class="cyrus-card-description-info"><span><?php
                                            $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                                            //$formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy 'às' H:mm");
                                            $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy");
                                            $formatted = $formatter->format($news->getCreatedAt());
                                            $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                                            $monthCapitalize = ucfirst($month);
                                            $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                                            echo $formatted . ", por " . $news->getUser()?->getUsername();

                                            ?></span></div>

                                </div>
                            </div>
                        </div>
                        <?php } ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-wrapper">
        <?php
        if (isset($_SESSION["user"])) {
            $total = 0;
            $videos = Users::getKeepWatchingVideos()->getBareReturn();
            foreach ($videos as $item) {
                if (count($item) === 0) continue;
                ++$total;
            }
            if ($total > 0) {
                ?>
                <div id="keepWatchingVideos" class="section content-wrapper video-carousel">
                    <div class="content-wrapper">
                        <div class="section-list">
                            <div class="section-list-title">
                                <span class="h2">Continue Assistindo</span>
                                <div class="float-end cyrus-feed-view-link">
                                    <a class="link-nodecoration" href="<?php echo Routing::getRouting("history")?>">Ver Histórico <i
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
                                    foreach ($videos[0] as $item) {
                                        if (count($item) === 0) continue;
                                        $video = $item["video"];
                                        $remaining = $item["remaining"];
                                        ?>
                                        <div class="cyrus-card cyrus-carousel-items-card">
                                            <a class="cyrus-card-link"
                                               href="<?php echo Routing::getRouting("episode") . "?episode=" . $video?->getId() ?>"
                                               title="<?php echo $video?->getTitle() ?>"></a>
                                            <div class="cyrus-card-image-cape">
                                                <img class="c-opacity-70"
                                                     src="<?php echo $video?->getThumbnail()?->getPath() ?>">
                                                <div class="cyrus-card-duration">
                                                    <span><?php echo floor($remaining / 60) . "m para terminar" ?></span>
                                                </div>
                                                <i class="fa-solid fa-play cyrus-card-center"></i></div>
                                            <div class="cyrus-card-body">
                                                <div class="cyrus-card-description">
                                                    <div class="cyrus-card-description-info">
                                                        <span><?php echo $video?->getAnime()?->getTitle() ?> </span>
                                                    </div>
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
                                                        <span><?php echo $video?->getVideoType()?->getName() ?></span>
                                                    </div>
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
            }
        } ?>
        <div id="lastReleasedVideos" class="section content-wrapper video-carousel">
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
                                        <img class="c-opacity-70"
                                             src="<?php echo $video?->getThumbnail()?->getPath() ?>">
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
            <?php
            $size = 0;
            $calendar = Animes::getCalendar("today")->getBareReturn();
            foreach ($calendar as $day => $dayInfo) {
                $size = count($dayInfo["animes"]);
            }
            if ($size > 0){
            ?>
            <div class="cyrus-grid cyrus-grid-3">
                <?php
                foreach ($calendar as $day => $dayInfo) {
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
                                    <div class="cyrus-card-description-info">
                                        <span>Episódio <?php echo Animes::getNumberOfNextEpisode($anime->getId())->getBareReturn()["numeration"]; ?></span>
                                    </div>
                                    <div class="cyrus-card-description-type float-end">
                                        <span><?php echo $anime?->getLaunchTime()?->format("H:i"); ?></span></div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                } else {
                    ?>
                    <div class="no-select"
                         style="display: flex; align-items: center; width: 100%; justify-content: center; flex-direction: column; margin-top: 18px;">
                        <img draggable="false" width="200px" height="150px"
                             src="<?php echo Utils::getDependencies("Cyrus", "nothing_to_see") ?>">
                        <span style="font-weight: bold; font-size: 22px; margin-top: 5px;">Não tem nada por aqui hoje o((>ω< ))o</span>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>

</body>
</html>