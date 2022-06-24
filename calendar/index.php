<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');

use Enumerators\DayOfWeek;
use Functions\Routing;
use Functions\Utils;
use Services\Animes;

$calendar = Animes::getCalendar();
if($calendar->isError()){
    // import página de erro;
    exit;
}
$calendar = Animes::getCalendar()->getBareReturn();

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);

    echo getHead(" - Calendário de Lançamentos");
    ?>
    <script type="module" src="<?php echo Utils::getDependencies("Calendar", "js", false) ?>"></script>
    <link href="<?php echo Utils::getDependencies("Calendar", "css", false) ?>" rel="stylesheet">
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <!-- CONTENT HERE -->
    <div class = "content-wrapper">
        <div class = "calendar">
        <?php
        foreach($calendar as $day => $dayInfo){?>
        <div class = "calendar-day">
            <div class = "calendar-day-header">
                <div class = "calendar-day-date">
                    <span><?php echo $dayInfo["day"]->format("d/m");?></span>
                </div>
                <div class = "calendar-day-text">
                    <span><?php echo DayOfWeek::getItem($day)->name();?></span>
                </div>
            </div>
            <div class = "calendar-day-body">
                <?php
                if($dayInfo["animes"]?->size() == 0){?>
                    <!--<div class = "calendar-day-body-empty">
                        <span>Nada encontrado</span>
                        <span>￣へ￣</span>
                    </div>-->
                <?php
                } else
                for($m = 0; $m < 12; $m++)
                    foreach($dayInfo["animes"] as $anime){?>
                <div class = "calendar-item">
                    <div class = "calendar-item-hour">
                        <span><?php echo $anime?->getLaunchTime()?->format("H:i");?></span>
                    </div>
                    <div class = "calendar-item-title">
                        <a href = "<?php echo Routing::getRouting("animes") . "?anime=" . $anime->getId(); ?>"><span><?php echo $anime->getTitle(); ?></span></a>
                    </div>
                    <div class = "calendar-item-episode-info">
                        <?php
                            $today = date('w', (new DateTime())->getTimestamp());
                            $isAvailable = false;
                            if ($day < $today){
                                $isAvailable = true;
                                echo '<a href = "#">';
                            } else if ($day == $today){
                                if($anime?->getLaunchTime()?->getTimestamp() <= (new DateTime())->getTimestamp()) {
                                    $isAvailable = true;
                                    echo '<a href = "#">';
                                }
                            }
                        ?><span <?php echo !$isAvailable ? 'class = "not-available"' : ''?>>Episódio 12 Disponível <?php echo !$isAvailable ? " em Breve" : ""?></span><?php echo $isAvailable ? "</a>" : ""?>
                    </div>
                    <div class = "calendar-item-anime-cape">
                        <img src = "<?php echo $anime->getProfile()?->getPath();?>">
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>