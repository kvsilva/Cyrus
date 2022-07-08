<?php

require_once(dirname(__DIR__) . '\\..\\..\\resources\\php\\settings.php');
AutoLoader::register();

use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\Season;
use Objects\SeasonsArray;
use Objects\Video;

if (!isset($_SESSION["user"])) {
    header("Location: " . Routing::getRouting("home"));
    exit;
}

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Criar Ticket");
    ?>
    <link href="<?php echo Utils::getDependencies("CreateTicket", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("CreateTicket") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
include(Utils::getDependencies("Cyrus", "alerts", true));
?>
<div id="content">
    <div class="content-wrapper">
        <div class = "ticket-send-information">
            <h1 class = "cyrus-page-title">Enviar uma Solicitação</h1>
            <div>Não importa se é um probleminha técnico ou um surto, estamos aqui para ajudar!</div>
            <div>Envie um ticket! Se ele não sumir por um portal, responderemos em breve.</div>
        </div>
        <div class="ticket-wrapper">
            <div class="cyrus-input-group">
                <input class="cyrus-minimal" type="text" required value=''
                       onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password"
                       id="form0-subject">
                <span class="cyrus-floating-label">Assunto</span>
            </div>
            <div class="cyrus-input-group">
                <textarea class="cyrus-minimal" required value=''
                          onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password"
                          id="form0-description"></textarea>
                <span class="cyrus-floating-label">Descrição</span>

            </div>
            <div>
                <span class="cyrus-floating-label-float">Anexos</span>
                <div class=" cyrus-group-file" data-hc-focus="false">
                    <input type="file" multiple id="form0-attachments">
                    <span>
                    <a>Adicione o arquivo</a> ou solte os arquivos aqui
                </span>
                </div>
            </div>
            <div class = "d-flex justify-content-center">
            <div class = "cyrus-input-group w-25">
                <input id="form0-submit" type = "submit" class = "cyrus-btn cyrus-btn-type2" value = "ENVIAR" disabled>
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