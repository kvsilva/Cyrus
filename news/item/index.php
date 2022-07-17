<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\News;
use Objects\NewsBody;
use Objects\Season;
use Objects\SeasonsArray;
use Objects\Video;

try {
    $news = isset($_GET["news"]) ? News::find(id: intval($_GET["news"]), flags: [Entity::ALL]) : null;
} catch (ReflectionException $e) {
    Utils::goTo("news");
}
if ($news == null || $news?->size() == 0) {
    Utils::goTo("news");
}
$news = $news[0];
$currentEdition = $news->getEditions()[0];



?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - " . $currentEdition->getTitle());
    ?>
    <link href="<?php echo Utils::getDependencies("NewsItem", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("NewsItem") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
include(Utils::getDependencies("Cyrus", "alerts", true));
?>

<div id="content">
    <div class="content-wrapper">
        <div class="news-title">
            <span><?php echo $currentEdition?->getTitle() ?></span>
        </div>
        <div class="news-subtitle">
            <span><?php echo $currentEdition?->getSubTitle() ?></span>
        </div>
        <div class="news-author">
            <span><?php echo $currentEdition?->getUser()?->getUsername() ?></span>
        </div>
        <div class="news-date">
            <span><?php
                $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                //$formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy 'às' H:mm");
                $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy");
                $formatted = $formatter->format($news->getCreatedAt());
                $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                $monthCapitalize = ucfirst($month);
                $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                echo $formatted;

                ?></span>
            <?php
            if($news->getEditions()->size() > 0){
            ?>
            <span class = "news-date-updated">(atualizado a <?php
                $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy 'às' H:mm");
                $formatted = $formatter->format($currentEdition->getEditedAt());
                $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                $monthCapitalize = ucfirst($month);
                $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                echo $formatted;

                ?>)</span>
            <?php }?>
        </div>
        <div class = "cyrus-feed-divider cyrus-feed-divider-3 mt-4"></div>
        <div class="news-body">
            <div class="news-body-wrapper">
                <?php echo $currentEdition?->getContent() ?>
            </div>
        </div>
        <div class = "cyrus-feed-divider cyrus-feed-divider-2 mt-5"></div>
        <div class="reviews-section">
            <?php
            if (isset($_SESSION["user"])) {
                ?>
                <div class="review-post mt-3">
                    <div class="row">
                        <div class="col-2 review-post-user no select">
                            <img draggable="false" class="img-fluid mx-auto"
                                 src="<?php echo $_SESSION["user"]?->getProfileImage()?->getPath() ?>">
                            <div class="review-post-username"><?php echo $_SESSION["user"]?->getUsername() ?></div>
                        </div>
                        <div class="col-9">
                            <div class="review-post-rating">
                            </div>
                            <form class="cyrus-form" id="form0">
                                <div class="cyrus-form-inputs">
                                    <label class="cyrus-label">
                                                <textarea id="form0-description"
                                                          class="cyrus-input reviews-self-textarea"
                                                          placeholder="Comentário" maxlength="500"></textarea>
                                    </label>
                                    <div class="reviews-self-char-notification"><span>0/500 caracteres</span>
                                    </div>
                                </div>
                                <div class="cyrus-form-buttons">
                                    <input data-toggle="tooltip"
                                           class="cyrus-input" type="reset" value="CANCELAR">
                                    <input class="cyrus-input" type="submit" id="form0-submit" value="PUBLICAR">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div id="reviews-list" class="mt-3">

            </div>
        </div>
    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>