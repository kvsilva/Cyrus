<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');

use Enumerators\Month;
use Functions\Routing;
use Functions\Utils;
use Objects\Video;

try {
    $episodes = isset($_GET["episode"]) ? Video::find(id: $_GET["episode"]) : null;
} catch (ReflectionException $e) {
    Utils::goTo("animes");
}
if ($episodes == null || $episodes?->size() == 0) {
    Utils::goTo("animes");
}

$episode = $episodes[0];


?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);

    echo getHead(" - " . $episode->getTitle() . " Episódio " . $episode->getNumeration());
    ?>
    <link href = "<?php echo Utils::getDependencies("Episode", "css", false) ?>" rel = "stylesheet">
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">

    <!-- CONTENT HERE -->
    <div class = "video-player">

        <video id="player0" playsinline="" controls>
            <source src="<?php echo $episode->getPath()?->getPath() ?>" type="video/mp4">

        </video>
    </div>

    <div class = "content-wrapper">
        <div class = "information">
           <div class = "anime-information">
               <span><a href = "<?php echo Routing::getRouting("animes") . "?anime=" . $episode->getAnime()?->getId() ?>"><?php echo $episode->getAnime()?->getTitle() ?></a></span>
               <span>4.8 <i class="fa-solid fa-star"></i> (54.3k) </span>
           </div>
        </div>
        <div class = "episode-information">
            <h3 class = "episode-title">Episódio <?php echo $episode->getNumeration()?> - <?php echo $episode->getTitle() ?></h3>
            <!-- 17 de Junho de 2022 -->
            <span class = "launch-date">Lançado a <?php echo date('d', $episode->getReleaseDate()->getTimestamp()) . " de " . Month::getItem(date('m', $episode->getReleaseDate()->getTimestamp()))->name() . " de " . date('Y', $episode->getReleaseDate()->getTimestamp())?></span>
        </div>
        <div class = "episode-synopsis">
            <p class = "text"><?php echo $episode->getSynopsis(); ?></p>
        </div>
        <div class = "episode-comments">
            <div class="row" id="reviews">
                <div class = "controller">
                    <div class = "reviews-average-rating">
                        <span id="reviews-average-rating_value">12 Comentários </span>
                    </div>
                </div>
                <div class = "reviews-section">
                    <div class = "review-post mt-3">
                        <div class = "row">
                            <div class = "col-2 review-post-user no select">
                                <img draggable="false" class = "img-fluid mx-auto" src = "https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png">
                                <div class = "review-post-username">Kurookami</div>
                            </div>
                            <div class = "col-9">
                                <div class = "review-post-rating">
                                </div>
                                <form class = "cyrus-form">
                                    <div class = "cyrus-form-inputs">
                                        <label class = "cyrus-label">
                                            <textarea class = "cyrus-input reviews-self-textarea" placeholder="Comentário"></textarea>
                                        </label>
                                        <div class = "reviews-self-char-notification"><span>0/200 caracteres</span></div>
                                        <label class = "cyrus-label-checkbox mt-2">
                                        <span class = "cyrus-hover-pointer">
                                            <input class = "cyrus-input-checkbox-null" type = "checkbox">
                                            <span class="cyrus-input-checkbox-checkmark"></span>
                                            <span>Marcar como Spoiler</span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class = "cyrus-form-buttons">
                                        <input data-toggle="tooltip" data-placement="top" title="Tooltip on top" class = "cyrus-input" type = "reset" value="CANCELAR">
                                        <input class = "cyrus-input" type = "submit" value = "PUBLICAR">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id = "reviews-list" class = "mt-3">
                        <?php
                        for($x = 0; $x < 5; $x++){

                            ?>
                            <div class = "review">
                                <div class = "row">
                                    <div class = "col-2 review-post-user no-select">
                                        <img draggable="false" class = "mx-auto" src = "https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png">
                                    </div>
                                    <div class = "col-9">
                                <span>
                                    <span class = "review-username">Kurookami</span>
                                    <span class = "review-date float-right">10 de Janeiro de 2021</span>
                                </span>
                                        <span class = "review-options">
                                    <button class = "cyrus-btn cyrus-btn-simple"><i class="fa-solid fa-flag"></i></button>
                                    <button class = "cyrus-btn cyrus-btn-simple"><i class="fa-solid fa-share-nodes"></i></button>
                                </span>
                                        <div class = "mt-3">
                                            <h3 class = "review-title">Lorem ipsum dolor sit amet</h3>
                                            <div class = "review-description ">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris fringilla nunc at arcu rhoncus facilisis. Donec at justo eget eros auctor porttitor ut in magna. Etiam porta commodo dolor. Sed a enim dapibus, placerat erat sit amet, rhoncus ipsum. Fusce ut lobortis turpis, a hendrerit leo. Vivamus dui ipsum, tristique vulputate vulputate nec, cursus non enim. Proin molestie ante a lorem congue, quis tincidunt ligula consectetur. Sed id tempus mi, sed finibus nulla.
                                                    Curabitur sodales viverra dapibus. Aenean fermentum dui turpis, non consectetur sapien posuere in. Duis gravida vitae arcu sed rhoncus. Integer vel ex dapibus, dapibus dolor vel, tincidunt mi. Nullam eget suscipit lorem. Integer a nibh non purus aliquam efficitur. Nullam consequat condimentum nulla, vitae mollis ipsum dignissim sit amet. Suspendisse potenti. Praesent tristique dolor mauris, a suscipit sem ultricies ut.
                                                    Suspendisse fermentum erat nunc, consequat mattis dolor posuere nec. Vivamus pretium in ligula in dapibus. Nulla facilisi. Donec lectus ligula, sagittis eu tincidunt eget, aliquam a mauris. Maecenas et purus luctus, pretium tellus ac, aliquet augue. Phasellus sollicitudin justo sit amet ligula vulputate, eget vehicula orci rutrum. Phasellus placerat rhoncus convallis. Curabitur eleifend, justo sed tempus finibus, neque nulla varius urna, sit amet ultrices urna metus in nibh. Sed sed sodales urna, nec pretium orci. Phasellus rhoncus ac nisl id lobortis. Morbi sit amet elit laoreet, viverra dui sit amet, efficitur nunc. Nulla cursus ante id tempor sodales.
                                                    Fusce luctus lacus libero. Integer bibendum lacinia urna, id faucibus ipsum hendrerit ut. Fusce bibendum tellus sit amet accumsan malesuada. Nam facilisis nibh vestibulum ex condimentum, ut lobortis ex pharetra. Mauris porta tristique cursus. Duis cursus magna id iaculis ornare. Duis ultrices nunc nisl, nec porttitor est volutpat ut. Vestibulum non congue metus, tempor consequat tellus. Proin vitae ex a nisi volutpat fringilla. Vivamus sit amet consequat ante, in dignissim magna. Nullam vitae lobortis ligula, a sodales tellus. Sed luctus risus id interdum efficitur.
                                                    Vivamus euismod ipsum quis facilisis congue. Proin et tincidunt velit. Quisque sit amet porta metus. Sed non eros ut diam consectetur rhoncus. Nam at dui lacus. Quisque nibh mi, bibendum sit amet nunc nec, imperdiet euismod leo. Mauris blandit odio eleifend nisi aliquet maximus laoreet non arcu.
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <button class = "cyrus-btn cyrus-btn-simple">MOSTRAR MAIS</button>
                                        </div>
                                        <!--<div class ="evaluate-review mt-2">
                                            <span data-positive="86">86</span> de <span data-total = "100">100</span> pessoas consideraram esta crítica útil. É útil para si? <button class = "cyrus-btn cyrus-btn-simple evaluate-review-button">SIM</button> | <button class = "cyrus-btn cyrus-btn-simple">NÃO</button>
                                        </div>-->
                                    </div>
                                </div>
                                <!--<hr class = "w-25 mx-auto">-->
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>