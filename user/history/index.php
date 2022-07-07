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

if(!isset($_SESSION["user"])){
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
    <script type = "module" src="<?php echo Utils::getDependencies("History") ?>"></script>
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

    </div>

    <?php
    include(Utils::getDependencies("Cyrus", "footer", true));
    ?>
</body>
</html>