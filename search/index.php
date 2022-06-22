<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');

use Enumerators\Month;
use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\EntityArray;
use Objects\Video;
use Objects\VideoType;

//Sort Array by a property: https://stackoverflow.com/questions/4282413/sort-array-of-objects-by-object-fields

$entities = null;

if (isset($_GET["query"])) {
    $animes = Anime::find(title: $_GET["query"]);
    $videos = Video::find(title: $_GET["query"]);
    $entities = $animes;
    $entities->addAll($videos);
    $entities->sort(fn($a, $b) => strcmp($a->getTitle(), $b->getTitle()));
}

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);

    echo getHead(" - Procurar");
    ?>
    <script type="module" src="<?php echo Utils::getDependencies("Search", "js", false) ?>"></script>
    <link href="<?php echo Utils::getDependencies("Search", "css", false) ?>" rel="stylesheet">
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <!-- CONTENT HERE -->
    <div class="search">
        <div class="content-wrapper">
            <form id="form-query" class="d-flex justify-content-center">
                <label class="cyrus-label-noborder">
                    <input id="field-query" class="cyrus-input-noborder" type="text" placeholder="Procurar...">
                    <div class="reset" id="reset-query-form">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </label>
            </form>
        </div>
    </div>
    <div class="content-wrapper" id="content-results">

    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>