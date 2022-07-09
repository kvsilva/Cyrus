<?php


use Enumerators\TicketStatus;
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

if (!isset($_GET["ticket"])) {
    header("Location: " . Routing::getRouting("tickets"));
    exit;
}

$tickets = Ticket::find(id: $_GET["ticket"], flags: [Entity::ALL]);
if ($tickets->size() === 0) {
    header("Location: " . Routing::getRouting("tickets"));
    exit;
}

$ticket = $tickets[0];


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
                    <span class="ticket-information-value">#<?php echo $ticket->getId() ?></span>
                </div>
                <div class="ticket-information-createddate">
                    <div class="ticket-information-title">Data de Criação</div>
                    <span class="ticket-information-value"><?php
                        $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                        $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy 'às' H:mm");
                        $formatted = $formatter->format($ticket->getCreatedAt());
                        $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                        $monthCapitalize = ucfirst($month);
                        $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                        echo $formatted;
                        ?></span>
                </div>
                <div class="ticket-information-lastactivity">
                    <div class="ticket-information-title">Última Atualição</div>
                    <span class="ticket-information-value"><?php
                        $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                        $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy 'às' H:mm");
                        $formatted = $formatter->format($ticket->getMessages()[$ticket->getMessages()->size() - 1]->getSentAt());
                        $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                        $monthCapitalize = ucfirst($month);
                        $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                        echo $formatted;
                        ?></span>
                </div>
                <div class="ticket-information-status">
                    <div class="ticket-information-title">Estado</div>
                    <span class="ticket-information-value"><?php echo $ticket->getStatus()?->name(); ?></span>
                </div>
                <?php
                if($ticket->getResponsible() !== null){
                ?>
                    <div class="ticket-information-status">
                        <div class="ticket-information-title">Responsável</div>
                        <span class="ticket-information-value"><?php echo $ticket->getResponsible()?->getUsername(); ?></span>
                    </div>
                <?php
                }
                if($ticket->getClosedBy() !== null){
                    ?>
                    <div class="ticket-information-status">
                        <div class="ticket-information-title">Fechado por</div>
                        <span class="ticket-information-value"><?php echo $ticket->getClosedBy()?->getUsername(); ?></span>
                    </div>
                <?php
                }
                if ($_SESSION["user"]->hasPermission(tag: "TICKETS_ACCESS_OTHERS")) {
                    ?>
                    <div class="ticket-information-status">
                        <div class="ticket-information-title">Opções</div>
                        <div class="ticket-information-value">
                            <div class="cyrus-input-group dropdown no-select w-100 align-items-start mt-5 ms-2">
                                <span class="cyrus-floating-label cyrus-floating-label-float" value=''
                                      onkeyup="this.setAttribute('value', this.value);">Alterar o Estado</span>
                                <div class="dropdown-toggle w-100" type="button" id="dropdownMenuButton1"
                                     data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="selected-status" data-selected="0">Todos</span>
                                </div>
                                <ul class="dropdown-menu no-select w-100" aria-labelledby="dropdownMenuButton1">
                                    <?php
                                    $entities = TicketStatus::getAllItems();
                                    foreach ($entities as $item) {
                                        if ($ticket->getStatus()->value === $item->value) {
                                            echo '<li class = "selected" data-id = "' . $item->value . '">' . $item->name() . '</li>';
                                        } else echo '<li data-id = "' . $item->value . '">' . $item->name() . '</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php if($ticket->getResponsible() === null){?>
                                <div class="cyrus-input-group no-select w-100 align-items-start mt-5 ms-2">
                                    <button class="cyrus-btn cyrus-btn-type2" id = "responsible">Assumir</button>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="ticket-conversation">
                <div class="ticket-explanation">
                    <div class="ticket-explanation-title">
                        <h3><?php echo $ticket->getSubject() ?></h3>
                    </div>
                    <div class="ticket-explanation-body">
                        <p>
                            <?php echo str_replace("\n", "<br>", $ticket->getMessages()[0]->getContent()); ?>
                        </p>
                    </div>
                    <div class="attachments">
                        <span>Anexos (<?php echo $ticket->getMessages()[0]->getAttachments()->size() ?>):</span>
                        <?php
                        if ($ticket->getMessages()[0]->getAttachments()->size() > 0) {
                            ?>
                            <ul class="cyrus-group-attachments">
                                <?php
                                foreach ($ticket->getMessages()[0]->getAttachments() as $attachment) {

                                    ?>
                                    <li class="cyrus-attachment" data-download="true"
                                        data-filename="<?php echo $attachment->getOriginalName() ?>"
                                        data-href="<?php echo $attachment->getPath() ?>">
                                        <i class="fa-solid fa-paperclip"></i>
                                        <span class="cyrus-attachment-link"><?php echo $attachment->getOriginalName() ?></span>
                                    </li>
                                    <?php
                                } ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="ticket-thread" id="thread">
                    <?php
                    for ($i = 1; $i < $ticket->getMessages()->size(); $i++) {
                        $message = $ticket->getMessages()[$i];
                        ?>
                        <div class="ticket-message">
                            <div class="ticket-message-header">
                                <div class="ticket-user-icon">
                                    <img src="<?php echo $message->getAuthor()?->getProfileImage()?->getPath() ?>">
                                </div>
                                <div class="ticket-user-info">
                                    <div class="ticket-user-info-wrapper">
                                        <div class="ticket-user-info-username"><?php echo $message->getAuthor()?->getUsername() ?></div>
                                        <div class="ticket-user-info-postdate"><?php
                                            $formatter = new IntlDateFormatter('pt_PT', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
                                            $formatter->setPattern("d 'de' '['MMMM']' 'de' yyyy 'às' H:mm");
                                            $formatted = $formatter->format($ticket->getMessages()[$ticket->getMessages()->size() - 1]->getSentAt());
                                            $month = substr($formatted, strpos($formatted, "[") + 1, strpos($formatted, "]") - strlen($formatted));
                                            $monthCapitalize = ucfirst($month);
                                            $formatted = str_replace("[" . $month . "]", $monthCapitalize, $formatted);
                                            echo $formatted;
                                            ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ticket-message-body">
                                <p>
                                    <?php echo str_replace("\n", "<br>", $message->getContent()); ?>
                                </p>
                            </div>
                            <div class="attachments">
                                <span>Anexos (<?php echo $message->getAttachments()->size() ?>):</span>
                                <?php
                                if ($message->getAttachments()->size() > 0) {
                                    ?>
                                    <ul class="cyrus-group-attachments">
                                        <?php
                                        foreach ($message->getAttachments() as $attachment) {

                                            ?>
                                            <li class="cyrus-attachment" data-download="true"
                                                data-filename="<?php echo $attachment->getOriginalName() ?>"
                                                data-href="<?php echo $attachment->getPath() ?>">
                                                <i class="fa-solid fa-paperclip"></i>
                                                <span class="cyrus-attachment-link"><?php echo $attachment->getOriginalName() ?></span>
                                            </li>
                                            <?php
                                        } ?>
                                    </ul>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="ticket-answer">
                    <?php
                    if ($ticket->getStatus()->value !== TicketStatus::CLOSED->value) {
                        ?>
                        <h5>Algo a adicionar? Conte-nos!</h5>
                        <div class="ticket-answer-wrapper">
                            <textarea id="form0-textarea" class="cyrus-input" placeholder="Sua resposta"></textarea>
                            <div>
                                <span class="cyrus-floating-label-float">Anexos</span>
                                <div class=" cyrus-group-file" data-hc-focus="false">
                                    <input data-cyrus="true" type="file" multiple id="form0-attachments">
                                    <span data-dragged="false">Adicione o arquivo ou solte os arquivos aqui</span>
                                    <span class="cyrus-item-hidden" data-dragged="true">Solte o arquivo</span>
                                </div>
                                <ul class="cyrus-group-attachments" data-for="form0-attachments">
                                </ul>
                            </div>
                            <input id="form0-submit" type="submit" class="cyrus-input" value="Enviar" disabled>
                        </div>
                        <?php
                    } else {
                        ?>
                        <h5 class="text-center">Este ticket foi fechado. Se precisares de ajuda abre um novo!</h5>
                        <?php
                    }
                    ?>
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