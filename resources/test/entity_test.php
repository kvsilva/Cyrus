<?php

use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Objects\Ticket;
use Objects\TicketStatus;

session_start();
require_once(dirname(__FILE__) . '/../php/AutoLoader.php');
AutoLoader::register();
$var = new \Objects\Dubbing();
$var->setAvailable(\Enumerators\Availability::AVAILABLE);
$var->setLanguage(new \Objects\Language(1));
$var->setPath("this space");
//$var = new Ticket();
/*$var->setStatus(new TicketStatus(id: 1));
$var->setAttendedBy(new \Objects_old\User(10));
$var->setTitle("teste de título");*/
try {
    $var->store();
} catch (ColumnNotFound|IOException|NotNullable|UniqueKey|TableNotFound|InvalidSize $e) {
    echo $e;
}

?>

