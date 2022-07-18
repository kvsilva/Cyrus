<?php

use Enumerators\TicketStatus;
use Functions\Database;
use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\Season;
use Objects\SeasonsArray;
use Objects\Ticket;
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
    echo getHead(" - Solicitação");
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
            <h1>As minhas solicitações</h1>
        </div>
        <div class="cyrus-feed-divider cyrus-feed-divider-1 mt-3"></div>
        <div class = "d-flex justify-content-end mt-5">
            <a href = "<?php echo Routing::getRouting("createticket")?>" class = " cyrus-btn cyrus-btn-type3">Abrir Ticket</a>
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
                <div class="cyrus-input-group dropdown no-select w-100">
                    <div class="dropdown-toggle float-end w-100 d-flex justify-content-end align-items-center" type="button" id="dropdownMenuButton1"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selected-status" data-selected="0">Todos</span>
                    </div>
                    <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                        <li data-id="0">Todos</li>
                        <?php
                        $entities = TicketStatus::getAllItems();
                        foreach ($entities as $item) {
                            echo '<li data-id = "' . $item->value . '">' . $item->name() . '</li>';
                        }
                        ?>
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
                <tbody id = "tickets-table">

                <!--<tr>
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
                </tr>-->
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