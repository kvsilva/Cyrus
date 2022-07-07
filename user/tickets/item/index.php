<?php


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
    echo getHead(" - Ticket");
    ?>
    <link href="<?php echo Utils::getDependencies("Ticket", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Ticket") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="content-wrapper">
        <div class="ticket-wrapper">
            <div class="ticket-information">
                <div class="ticket-information-id">
                    <div class="ticket-information-title">ID do Ticket</div>
                    <span class="ticket-information-value">#15</span>
                </div>
                <div class="ticket-information-createddate">
                    <div class="ticket-information-title">Data de Criação</div>
                    <span class="ticket-information-value">25 de outubro de 2020 19:48</span>
                </div>
                <div class="ticket-information-lastactivity">
                    <div class="ticket-information-title">Última Atualição</div>
                    <span class="ticket-information-value">07/07/2022</span>
                </div>
                <div class="ticket-information-status">
                    <div class="ticket-information-title">Estado</div>
                    <span class="ticket-information-value">Resolvido</span>
                </div>
            </div>
            <div class="ticket-conversation">
                <div class="ticket-explanation">
                    <div class="ticket-explanation-title">
                        <h3>Reembolso</h3>
                    </div>
                    <div class="ticket-explanation-body">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pellentesque arcu vel orci
                            mollis laoreet. Nunc gravida, tortor id porta mattis, turpis est varius urna, sed interdum
                            enim magna eu felis. Aenean vitae vulputate velit, non pulvinar justo. Nunc nulla lorem,
                            mattis et eros sed, viverra aliquet neque. Praesent eleifend non eros non iaculis. Maecenas
                            semper interdum justo in pharetra. Cras rhoncus augue leo, eu dictum risus luctus ac. Sed a
                            tellus ornare, semper massa vehicula, tempus justo.
                        </p>
                    </div>
                </div>

                <div class="ticket-thread" id="thread">

                    <div class="ticket-message">
                        <div class="ticket-message-header">
                            <div class="ticket-user-icon">
                                <img src="http://localhost/Cyrus/resources/site/resources/39.jpg">
                            </div>
                            <div class="ticket-user-info">
                                <div class="ticket-user-info-wrapper">
                                    <div class="ticket-user-info-username">Kurookami</div>
                                    <div class="ticket-user-info-postdate">26 de outubro de 2020 13:52</div>
                                </div>
                            </div>
                        </div>
                        <div class="ticket-message-body">
                            <p>
                                Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                Suspendisse potenti. Praesent sollicitudin mi leo, id eleifend sem euismod et.
                                Pellentesque id magna bibendum, pretium nunc quis, molestie neque. Sed nec cursus
                                mauris, quis consectetur ante. Maecenas nulla risus, dignissim eget facilisis non,
                                elementum vel velit. Proin eu justo non sem facilisis dapibus. Suspendisse maximus neque
                                non scelerisque pulvinar.
                            </p>
                        </div>
                    </div>

                </div>

                <div class="ticket-answer">
                    <textarea id="form0-textarea" class="cyrus-input" placeholder="Sua resposta"></textarea>
                    <input id="form0-submit" type="submit" class="cyrus-input" value="Enviar">
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