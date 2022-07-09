<?php

require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');
AutoLoader::register();

use Enumerators\TicketStatus;
use Functions\Database;
use Functions\Routing;
use Functions\Utils;
use Objects\Entity;
use Objects\Ticket;

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
            <h1>Tickets</h1>
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
                        <span id="selected-display-language" data-selected="0">Todos</span>
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
                    <th class="ticket-id">Utilizador</th>
                    <th class="last-update">Última Atualização</th>
                    <th class="status">Estado</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $tickets = Ticket::find(flags: [Entity::ALL]);
                foreach($tickets as $ticket){
                    ?>
                    <tr>
                        <td><a class="cyrus-feed-view-link" href="<?php echo Routing::getRouting("tickets") . "?ticket=" . $ticket->getId(); ?>"><?php echo $ticket->getSubject(); ?></a></td>
                        <td><?php echo $ticket->getId(); ?></td>
                        <td><?php echo $ticket->getUser()?->getUsername(); ?></td>
                        <td><?php echo $ticket->getMessages()[$ticket->getMessages()->size()-1]->getSentAt()?->format(Database::DateFormat); ?></td>
                        <td><?php echo $ticket->getStatus()->name(); ?></td>
                    </tr>
                    <?php
                }
                ?>
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