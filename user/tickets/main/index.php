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
    echo getHead(" - Tickets");
    ?>
    <link href="<?php echo Utils::getDependencies("Tickets", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Tickets") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="content-wrapper">
        <div class="cyrus-page-title">
            <h1>Os Meus Tickets</h1>
        </div>
        <div class="tickets-filter">
            <div class="w-25 tickets-filter-search mb-5">
                <div class="cyrus-input-group">
                    <input class="cyrus-minimal" type="text" id="search" value=''
                           onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                    <span class="cyrus-floating-label">Procurar por assunto</span>
                </div>
            </div>
            <div class="w-25 ms-5 float-end tickets-filter-status">
                <div class="cyrus-input-group dropdown no-select">
                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selected-display-language" data-selected="0">Todos</span>
                    </div>
                    <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                        <li data-id="0">Todos</li>
                        <li data-id="1">Aberto</li>
                        <li data-id="2">Esperando a sua Resposta</li>
                        <li data-id="3">Resolvido</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tickets">
            <table>
                <thead>
                <tr>
                    <th class="subject">Assunto</th>
                    <th class="ticket-id">ID do Ticket</th>
                    <th class="last-update">Última Atualização</th>
                    <th class="status">Estado</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><a class="cyrus-feed-view-link" href="c">Não consigo assistir a minha série favorita!</a></td>
                    <td>123</td>
                    <td>07/07/2022</td>
                    <td>Resolvido</td>
                </tr>
                <tr>
                    <td><a class="cyrus-feed-view-link" href="c">Teste</a></td>
                    <td>1243</td>
                    <td>05/07/2022</td>
                    <td>Aguardando a sua Resposta</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>