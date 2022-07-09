<?php
namespace Services;

use APIObjects\Status;
use Constants\API_MESSAGES;
use Enumerators\Verification;
use Functions\Database;
use Functions\Routing;
use JetBrains\PhpStorm\Pure;
use League\OAuth2\Client\Provider\Google;
use Objects\Entity;
use Objects\Ticket;
use Objects\User;
use Functions\Utils;
use ReflectionException;


require(Utils::getBasePath() . "/resources/dependencies/PHPMailer/phpmailer/src/PHPMailer.php");
require(Utils::getBasePath() . "/resources/dependencies/PHPMailer/phpmailer/src/OAuthTokenProvider.php");
require(Utils::getBasePath() . "/resources/dependencies/PHPMailer/phpmailer/src/OAuth.php");
require(Utils::getBasePath() . "/resources/dependencies/PHPMailer/phpmailer/src/SMTP.php");
require(Utils::getBasePath() . "/resources/dependencies/PHPMailer/phpmailer/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Tickets
{
    public static function ticketCreated(int $id) : Status{
        $tickets = Ticket::find(id :$id);
        if($tickets->size() === 0){
            return new Status(isError: true, message: "Ticket não encontrado.");
        }
        $ticket = $tickets[0];
        $mailto = $ticket->getUser()?->getEmail();
        $mailSub = "[Cyrus Suporte] Ticket #" . $ticket->getId();
        $mailMsg = '
        <!DOCTYPE html>
<html lang="pt_PT">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Confirmação de Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        #service-info{
            color: #4a4e58;
            margin-top: 50px;
        }
        #thanks{
            margin-top: 25px;
        }
    </style>
</head>
<body>
<div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    [Cyrus Suporte] Ticket #' . $ticket->getId() .' - '. $ticket->getSubject() .'.
</div>
<div>
    <p>Obrigado por contactares a nossa Equipa de Suporte! Nós recebemos o teu pedido (#'. $ticket->getId() .') com o assunto "'. $ticket->getSubject() .'".</p>
    <p>Receberás atualizações automáticas no teu pedido, fica atento!</p>
    <p>Caso tenhas mais informação adicional, ou anexos que ajudariam a explicar melhor o problema, compartilha no feed do ticket!</p>
    <p>Se estiveres logado no site, podes aceder ao feed do ticket diretamente com o link abaixo:</p>
    <p>' . Routing::getRouting("tickets") . '?ticket=' . $ticket->getId() .'</p>
</div>
<div id = "thanks">
    <p>Cumprimentos,</p>
    <p>Cyrus - Equipa de Suporte</p>
</div>
<div id="service-info"><span>This email is a service from Cyrus - Support. Sent by Cyrus.</span></div>
</body>
</html>
        ';
        $mail = new PHPMailer();
        $mail->IsSmtp();
        $mail->SMTPDebug = 0;
        define("ENCODING", "UTF-8");
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.office365.com";
        $mail->Port = 587;

        $mail->IsHTML(true);
        $mail->Username = "cyrus.animation2021@outlook.com";
        $mail->Password = "PAP_1234";
        $mail->setFrom("cyrus.animation2021@outlook.com");
        $mail->Subject = $mailSub;
        $mail->Body = $mailMsg;
        $mail->AddAddress($mailto);
        $mail->Send();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';



        return new Status(isError: false, return: array(), bareReturn: array());
    }

    public static function ticketUpdated(int $id) : Status{
        $tickets = Ticket::find(id :$id, flags: [Entity::ALL]);
        if($tickets->size() === 0){
            return new Status(isError: true, message: "Ticket não encontrado.");
        }
        $ticket = $tickets[0];


        $mailto = $ticket->getUser()?->getEmail();
        $mailSub = "[Cyrus Suporte] Ticket #" . $ticket->getId();
        $mailMsg = '<!DOCTYPE html>
<html lang="pt_PT">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Confirmação de Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        #service-info {
            color: #4a4e58;
            margin-top: 50px;
        }

        #thanks {
            margin-top: 25px;
        }

        #answer {
            padding-top: 30px;
            padding-bottom: 30px;
            width: 100%;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        #answer-wrapper {
            margin-left: 35px;
        }

        #message {
            font-size: 18px;
            line-height: 45px;
        }
        #author-wrapper{
            display: flex;
            align-items: center;
        }
        #author-wrapper > div {
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="preheader"
     style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    [Cyrus Suporte] Ticket #20 - Não consigo Comentar
</div>
<div>
    <p>Olá '. $ticket->getUser()?->getUsername() .',</p>
    <p>Apenas gostaríamos de te informar que o teu pedido (#'.$ticket->getId().') foi atualizado. Acede ao teu ticket através do seguinte
        link:</p>
    <p>'.Routing::getRouting("tickets") . '?ticket=' . $ticket->getId() .'</p>
</div>
<div id="answer">
    <div id="author-wrapper" style="">
        <div>
            <img width="40px" height="40px" src = "'. Utils::makeItPublic($ticket->getMessages()[$ticket->getMessages()->size()-1]->getAuthor()?->getProfileImage()?->getPath()).'">
        </div>
        <div style = "margin-left: 10px;">
            <div id="username">'.$ticket->getMessages()[$ticket->getMessages()->size()-1]->getAuthor()?->getUsername().'</div>
            <div id="timestamp">' . $ticket->getMessages()[$ticket->getMessages()->size()-1]->getSentAt()?->format(Database::DateFormat) .'</div>
        </div>
    </div>
    <div id="answer-wrapper">
        <div id="message">
            '.str_replace("\n", "<br>", $ticket->getMessages()[$ticket->getMessages()->size()-1]->getContent()).'
        </div>
    </div>
</div>
<div id="thanks">
    <p>Cumprimentos,</p>
    <p>Cyrus - Equipa de Suporte</p>
</div>
<div id="service-info"><span>This email is a service from Cyrus - Support. Sent by Cyrus.</span></div>
</body>
</html>';
        $mail = new PHPMailer();
        $mail->IsSmtp();
        $mail->SMTPDebug = 0;
        define("ENCODING", "UTF-8");
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.office365.com";
        $mail->Port = 587;

        $mail->IsHTML(true);
        $mail->Username = "cyrus.animation2021@outlook.com";
        $mail->Password = "PAP_1234";
        $mail->setFrom("cyrus.animation2021@outlook.com");
        $mail->Subject = $mailSub;
        $mail->Body = $mailMsg;
        $mail->AddAddress($mailto);
        $mail->Send();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';



        return new Status(isError: false, return: array(), bareReturn: array());
    }

    public static function ticketStatusUpdated(int $id) : Status{
        $tickets = Ticket::find(id :$id, flags: [Entity::ALL]);
        if($tickets->size() === 0){
            return new Status(isError: true, message: "Ticket não encontrado.");
        }
        $ticket = $tickets[0];

        $mailto = $ticket->getUser()?->getEmail();
        $mailSub = "[Cyrus Suporte] Ticket #" . $ticket->getId();
        $mailMsg = '<!DOCTYPE html>
<html lang="pt_PT">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Confirmação de Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        #service-info {
            color: #4a4e58;
            margin-top: 50px;
        }

        #thanks {
            margin-top: 25px;
        }
    </style>
</head>
<body>
<div class="preheader"
     style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    [Cyrus Suporte] Ticket #20 - Não consigo Comentar
</div>
<div>
    <p>Olá '. $ticket->getUser()?->getUsername() .',</p>
    <p>Apenas gostaríamos de te informar que o estado do teu pedido (#'.$ticket->getId().') foi atualizado. Acede ao teu ticket através do seguinte
        link:</p>
    <p>'.Routing::getRouting("tickets") . '?ticket=' . $ticket->getId() .'</p>
</div>
<div id="thanks">
    <p>Cumprimentos,</p>
    <p>Cyrus - Equipa de Suporte</p>
</div>
<div id="service-info"><span>This email is a service from Cyrus - Support. Sent by Cyrus.</span></div>
</body>
</html>';
        $mail = new PHPMailer();
        $mail->IsSmtp();
        $mail->SMTPDebug = 0;
        define("ENCODING", "UTF-8");
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.office365.com";
        $mail->Port = 587;

        $mail->IsHTML(true);
        $mail->Username = "cyrus.animation2021@outlook.com";
        $mail->Password = "PAP_1234";
        $mail->setFrom("cyrus.animation2021@outlook.com");
        $mail->Subject = $mailSub;
        $mail->Body = $mailMsg;
        $mail->AddAddress($mailto);
        $mail->Send();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';



        return new Status(isError: false, return: array(), bareReturn: array());
    }

    public static function ticketAssumed(int $id) : Status{
        $tickets = Ticket::find(id :$id, flags: [Entity::ALL]);
        if($tickets->size() === 0){
            return new Status(isError: true, message: "Ticket não encontrado.");
        }
        $ticket = $tickets[0];

        $mailto = $ticket->getUser()?->getEmail();
        $mailSub = "[Cyrus Suporte] Ticket #" . $ticket->getId();
        $mailMsg = '<!DOCTYPE html>
<html lang="pt_PT">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Confirmação de Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        #service-info {
            color: #4a4e58;
            margin-top: 50px;
        }

        #thanks {
            margin-top: 25px;
        }
    </style>
</head>
<body>
<div class="preheader"
     style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    [Cyrus Suporte] Ticket #20 - Não consigo Comentar
</div>
<div>
    <p>Olá '. $ticket->getUser()?->getUsername() .',</p>
    <p>Gostaríamos de te informar que o teu pedido (#'.$ticket->getId().') foi assumido e está sob análise. Acede ao teu ticket através do seguinte
        link:</p>
    <p>'.Routing::getRouting("tickets") . '?ticket=' . $ticket->getId() .'</p>
</div>
<div id="thanks">
    <p>Cumprimentos,</p>
    <p>Cyrus - Equipa de Suporte</p>
</div>
<div id="service-info"><span>This email is a service from Cyrus - Support. Sent by Cyrus.</span></div>
</body>
</html>';
        $mail = new PHPMailer();
        $mail->IsSmtp();
        $mail->SMTPDebug = 0;
        define("ENCODING", "UTF-8");
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.office365.com";
        $mail->Port = 587;

        $mail->IsHTML(true);
        $mail->Username = "cyrus.animation2021@outlook.com";
        $mail->Password = "PAP_1234";
        $mail->setFrom("cyrus.animation2021@outlook.com");
        $mail->Subject = $mailSub;
        $mail->Body = $mailMsg;
        $mail->AddAddress($mailto);
        $mail->Send();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';



        return new Status(isError: false, return: array(), bareReturn: array());
    }
}