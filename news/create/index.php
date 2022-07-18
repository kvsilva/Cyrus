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


if(!isset($_SESSION["user"])){
    header("Location: " . Routing::getRouting("home"));
    exit;
}

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Criar Notícia");
    ?>
    <link href="<?php echo Utils::getDependencies("NewsCreate", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("NewsCreate") ?>"></script>
    <link href="<?php echo Utils::getDependencies("SummerNote", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("SummerNote") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
include(Utils::getDependencies("Cyrus", "alerts", true));
?>

<div id="content">
    <div class="content-wrapper">
        <div class = "cyrus-input-group">
            <input type="text" class="cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password" id = "form0-title">
            <span class="cyrus-floating-label">Título</span>
        </div>
        <div class = "cyrus-input-group">
            <input type="text" class="cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password" id = "form0-subtitle">
            <span class="cyrus-floating-label">Subtítulo</span>
        </div>

        <div class = "cyrus-input-group">
            <textarea class="cyrus-minimal group-input" style = "height: 150px;" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password" id = "form0-preview"></textarea>
            <span class="cyrus-floating-label">Resumo</span>
        </div>
        <div>
            <label class="cyrus-label-checkbox mt-2">
                                        <span class="cyrus-hover-pointer">
                                            <input class="cyrus-input-checkbox-null" type="checkbox" id="form0-spotlight">
                                            <span class="cyrus-input-checkbox-checkmark"></span>
                                            <span>Notícia de Destaque</span>
                                        </span>
            </label>
        </div>
        <div class = "mt-4 mb-4">
            <span class="cyrus-floating-label-float">Anexos</span>
            <div class=" cyrus-group-file" data-hc-focus="false">
                <input data-cyrus="true" type="file" id="form0-thumbnail">
                <span data-dragged = "false">Adicione o arquivo ou solte os arquivos aqui</span>
                <span class = "cyrus-item-hidden" data-dragged = "true">Solte o arquivo</span>
            </div>
            <ul class="cyrus-group-attachments" data-for="form0-thumbnail">
            </ul>
        </div>
        <div class = "body">
            <div id="form0-body"></div>
        </div>
        <div class = "cyrus-input-group">
            <input class = "cyrus-input" type = "submit" value = "Submeter" id  ="form0-submit">
        </div>
    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>