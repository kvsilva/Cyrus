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


$letters_list = array(
    "#",
    "A",
    "B",
    "C",
    "D",
    "E",
    "F",
    "G",
    "H",
    "I",
    "J",
    "K",
    "L",
    "M",
    "N",
    "O",
    "P",
    "Q",
    "R",
    "S",
    "T",
    "U",
    "V",
    "W",
    "X",
    "Y",
    "Z",
);

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Lista de Animes");
    ?>
    <link href="<?php echo Utils::getDependencies("List", "css") ?>" rel="stylesheet">
    <script type = "module" src="<?php echo Utils::getDependencies("List") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="content-wrapper">
        <div class="cyrus-page-title">
            <h1>Procurar Animes</h1>
        </div>
        <div class="letters-list" id = "letters-list">
                <ul class="letter-full-list content-wrapper">
                    <?php
                    $isFirst = true;
                    foreach($letters_list as $letter){?>
                        <li class="letter-item" data-key = "<?php echo $letter?>"><?php echo $letter?></li>
                    <?php
                        $isFirst = false;
                    }?>
                </ul>
        </div>
        <div class="anime-full-list" id = "anime-full-list">
        </div>
    </div>

    <?php
    include(Utils::getDependencies("Cyrus", "footer", true));
    ?>
</body>
</html>