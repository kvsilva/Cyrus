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
                        <li class="letter-item  <?php echo $isFirst ? 'letter-item-selected' : '';?>" data-key = "<?php echo $letter?>"><?php echo $letter?></li>
                    <?php
                        $isFirst = false;
                    }?>
                </ul>
        </div>
        <div class="anime-full-list" id = "anime-full-list">
            <?php
            for($x = 0; $x < 5; $x++){
            ?>
            <div class="anime-list">
                <div class="anime-letter">
                    <span>#</span>
                </div>
                <div class = "anime-letter-separator"></div>
                <div class="animes">
                    <?php
                    for($i = 0; $i < 15; $i++){?>
                    <div class="cyrus-card cyrus-card-flex">
                        <a class="cyrus-card-link"
                           href="http://localhost/Cyrus/animes/?anime=1"
                           title="Attack on Titan"></a>
                        <div class="cyrus-card-image-cape">
                            <img src="https://i.pinimg.com/originals/13/a1/01/13a10172127bbf9da50b8ce6db35eeaa.png">
                        </div>
                        <div class="cyrus-card-body">
                            <div class="cyrus-card-title"><h4 class="cyrus-card-title">Attack on Titan</h4></div>
                            <div class="cyrus-card-description">
                                <div class="cyrus-card-description-info">
                                    <div class = "cyrus-card-description-text">
                                        <span>
                                            Eren Jaeger jurou eliminar todos os Titãs, mas em uma batalha desesperada ele se torna aquilo que mais odeia. Com seus novos poderes, ele luta pela liberdade da humanidade, combatendo os monstros que ameaçam seu lar. Mesmo depois de derrotar a Titã Fêmea, Eren não consegue descansar - uma horda de Titãs se aproximam da Muralha Rose e a batalha em nome da humanidade continua!
                                        </span>
                                    </div>
                                </div>
                                <div class="cyrus-card-description-type"><span>Série</span></div>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
            <?php }?>
        </div>
    </div>

    <?php
    include(Utils::getDependencies("Cyrus", "footer", true));
    ?>
</body>
</html>