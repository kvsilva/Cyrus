<?php

require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');
AutoLoader::register();

use Enumerators\TicketStatus;
use Functions\Database;
use Functions\Routing;
use Functions\Utils;
use Objects\Entity;
use Objects\Ticket;
use Objects\User;

if (!isset($_SESSION["user"])) {
    header("Location: " . Routing::getRouting("home"));
    exit;
}

if (!$_SESSION["user"]->hasPermission(tag: "TICKETS_ACCESS_OTHERS")) {
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
    <link href="<?php echo Utils::getDependencies("AdmTickets", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("AdmTickets") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="content-wrapper">
        <div class="cyrus-page-title">
            <h1>Tickets</h1>
        </div>
        <div class="tickets-filter">
            <div class="w-15 tickets-filter-search mb-5">
                <div class="cyrus-input-group" style = "margin-top: 0 !important;">
                    <input class="cyrus-minimal" type="text" id="search" value=''
                           onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                    <span class="cyrus-floating-label">Procurar por assunto</span>
                </div>
            </div>
            <div class="w-15 ms-5 tickets-filter-status">
                <div class="cyrus-input-group dropdown no-select w-100">
                    <div class="dropdown-toggle float-end w-100 d-flex justify-content-end align-items-center" type="button" id="dropdownMenuButton1"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selected-user" data-selected="0">Utilizador</span>
                    </div>
                    <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                        <li data-id="0">Todos Utilizadores</li>
                        <?php
                        $entities = User::find();
                        foreach ($entities as $item) {
                            echo '<li data-id = "' . $item->getId() . '">' . $item->getUsername() . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="w-15 ms-5 float-end tickets-filter-status">
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
                    <th class="ticket-user">Utilizador</th>
                    <th class="ticket-responsible">Responsável</th>
                    <th class="last-update">Última Atualização</th>
                    <th class="status">Estado</th>
                </tr>
                </thead>
                <tbody id = "tickets-table">
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