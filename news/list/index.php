<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\News;
use Objects\Season;
use Objects\SeasonsArray;
use Objects\Video;

$news = News::find(flags: [Entity::ALL]);

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Lista de Notícias");
    ?>
    <link href="<?php echo Utils::getDependencies("NewsList", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("NewsList") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="content-wrapper">
        <?php
        foreach($news as $item){
            $lastUpdate = $item->getEditions()[$item->getEditions()->size()-1];
        ?>
        <div class="news-item no-select">
            <a href="?news=9" class="link-nodecoration">
                <div class="news-item-head">
                    <div class="news-item-head-title"><?php echo $lastUpdate->getTitle() ?></div>
                    <div class="news-item-head-subtitle"><?php echo $lastUpdate->getSubtitle()?></div>
                    <div class="news-item-head-author"><?php echo $lastUpdate->getUser()?->getUsername()?></div>
                    <div class="news-item-head-date"><?php
                        $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                        $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy");
                        $formatted = $formatter->format($item->getCreatedAt());
                        $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                        $monthCapitalize = ucfirst($month);
                        $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                        echo $formatted;
                        ?></div>
                </div>
                <div class="news-item-body">
                    <div class="news-item-body-thumbnail">
                        <img src="<?php echo $lastUpdate->getThumbnail()?->getPath()?>">
                    </div>
                    <div class="news-item-body-preview">
                    <span>
                        À medida que o sol se põe atrás das montanhas, uma luz dourada começa a banhar a extensa floresta. Nas tranquilas trilhas, a Crunchyroll-Hime e o Yuzu se deparam com uma bela paisagem de verdes exuberantes e muito espaço para relaxar. Junte-se a Hime enquanto ela levanta acampamento para descansar e curtir todos os animes da temporada de verão 2022 da Crunchyroll!
                    </span>
                    </div>
                </div>
            </a>
        </div>
        <div class="cyrus-feed-divider cyrus-feed-divider-3"></div>
        <?php } ?>
    </div>
</div>

<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>