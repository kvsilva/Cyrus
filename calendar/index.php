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
        <div class="cyrus-page-title">
            <h1>Calendário</h1>
        </div>
        <div class="cyrus-feed-divider cyrus-feed-divider-1 mt-3"></div>
        <div class = "calendar">
        <?php
        foreach($calendar as $day => $dayInfo){?>
        <div class = "calendar-day">
            <div class = "calendar-day-header">
                <div class = "calendar-day-date">
                    <span><?php echo $dayInfo["day"]->format("d/m");?></span>
                </div>
                <div class = "calendar-day-text">
                    <span><?php echo DayOfWeek::getItemByName($day)->name();?></span>
                </div>
            </div>
            <div class = "calendar-day-body">
                <?php
                foreach($dayInfo["animes"] as $anime){?>
                <div class = "calendar-item">
                    <div class = "calendar-item-hour">
                        <span class = "no-select"><?php echo $anime?->getLaunchTime()?->format("H:i");?></span>
                    </div>
                    <div class = "calendar-item-title">
                        <a href = "<?php echo Routing::getRouting("animes") . "?anime=" . $anime->getId(); ?>"><span><?php echo $anime->getTitle(); ?></span></a>
                    </div>
                    <div class = "calendar-item-episode-info">
                        <?php
                            $today = date('w', (new DateTime())->getTimestamp());
                            $isAvailable = false;
                            if (DayOfWeek::getItemByName($day)->value < $today){
                                $isAvailable = true;
                                echo '<a href = "#">';
                            } else if (DayOfWeek::getItemByName($day)->value == $today){
                                if($anime?->getLaunchTime()?->getTimestamp() <= (new DateTime())->getTimestamp()) {
                                    $isAvailable = true;
                                    echo '<a href = "#">';
                                }
                            }
                            $numeration = Animes::getNumberOfNextEpisode($anime->getId())->getBareReturn()["numeration"];
                        ?><span <?php echo !$isAvailable ? 'class = "not-available"' : ''?>>Episódio <?php echo $numeration?> Disponível <?php echo !$isAvailable ? " em Breve" : ""?></span><?php echo $isAvailable ? "</a>" : ""?>
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