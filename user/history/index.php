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
use Services\Users;

if (!isset($_SESSION["user"])) {
    header("Location: " . Routing::getRouting("home"));
    exit;
}

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Histórico");
    ?>
    <link href="<?php echo Utils::getDependencies("History", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("History") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="content-wrapper">
        <div class="cyrus-page-title">
            <h1>Histórico</h1>
        </div>
        <div class="cyrus-feed-divider cyrus-feed-divider-1 mt-3"></div>

        <?php
        if (isset($_SESSION["user"])) {
            $total = 0;
            $videos = Users::getKeepWatchingVideos(max: -1)->getBareReturn();
            foreach ($videos as $item) {
                if (count($item) === 0) continue;
                ++$total;
            }
            if ($total > 0) {
                ?>
                <div id="keepWatchingVideos" class="section content-wrapper video-carousel">
                    <div class="history-wrapper">
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
                <?php
            }
        } ?>

    </div>

    <?php
    include(Utils::getDependencies("Cyrus", "footer", true));
    ?>
</body>
</html>