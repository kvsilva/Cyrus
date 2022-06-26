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
        <div class="section-list">
            <div class="section-list-title">
                <h2>Recomendações para Ti</h2>
            </div>
            <div class = "cyrus-feed-divider cyrus-feed-divider-2"></div>
        </div>
    </div>
    <div class="cyrus-carousel cyrus-carousel-cards">
        <div class="content-wrapper cyrus-carousel-wrapper">
            <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper" data-arrow="previous">
                <div role="button" class="cyrus-carousel-previous cyrus-carousel-arrow"><i
                            class="fa-solid fa-chevron-left"></i></div>
            </div>
            <div class="cyrus-carousel-items">
                <div class="cyrus-carousel-items-wrapper">
                    <?php
                    for ($i = 0; $i < 22; $i++) {
                        ?>
                        <div class="cyrus-card cyrus-carousel-items-card"><a class="cyrus-card-link"
                                                                             href="https://localhost/Cyrus/animes/?anime=1"
                                                                             title="Attack on Titan"></a>
                            <div class="cyrus-card-image-profile"><img
                                        src="https://i1.wp.com/animesonlinegames.com/wp-content/uploads/2021/12/shingeki-no-kyojin-4-part-2-todos-os-episodios.jpg">
                            </div>
                            <div class="cyrus-card-body">
                                <div class="cyrus-card-title"><h4
                                            class="cyrus-card-title"><?php echo "Item: " . $i . "  "; ?>Attack on
                                        Titan</h4></div>
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
            <div class="cyrus-carousel-arrow-wrapper cyrus-carousel-cards-arrow-wrapper" data-arrow="next">
                <div role="button" class="cyrus-carousel-next cyrus-carousel-arrow"><i
                            class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>
    </div>

    <?php
    include(Utils::getDependencies("Cyrus", "footer", true));
    ?>
</div>
</body>
</html>