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
        <div class="cyrus-page-title">
            <h1>Notícias</h1>
        </div>
        <div class="cyrus-feed-divider cyrus-feed-divider-1 mt-3"></div>
        <div class = "d-flex justify-content-end mt-5">
            <a href = "<?php echo Routing::getRouting("createnews")?>" class = " cyrus-btn cyrus-btn-type3">Criar Notícia</a>
        </div>
        <?php
        foreach($news as $item){

            $lastUpdate = $item->getEditions()[$item->getEditions()->size()-1];
        ?>
        <div class="news-item no-select">
            <a href="?news=<?php echo $item->getId();?>" class="link-nodecoration">
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
                        <?php echo $lastUpdate->getPreview()?>
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