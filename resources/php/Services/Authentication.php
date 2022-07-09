<?php
namespace Services;

use APIObjects\Status;
use Constants\API_MESSAGES;
use Enumerators\Verification;
use Functions\Routing;
use JetBrains\PhpStorm\Pure;
use League\OAuth2\Client\Provider\Google;
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


class Authentication
{
    /**
     * @throws ReflectionException
     */
    #[Pure]
    public static function login($password, $email = null, $username = null) : Status{
        if(($email === null && $username === null)) return new Status(isError: true, message: API_MESSAGES::MISSING_FIELDS . "username/email.");
        if($password == null) return new Status(isError: true, message: API_MESSAGES::MISSING_FIELDS . "passowrd.");
        $users = User::find(email: $email, username: $username);
        if($users->size() > 0){
            if($users[0]->isPassword($password)){
                $_SESSION["user"] = $users[0]->addFlag(User::ROLES);
                if($_SESSION["user"]?->getProfileImage() === null){

                }
                return new Status(isError: false, message: "Welcome back, " . $users[0]->getUsername());
            } else {
                return new Status(isError: true, message: "The credentials entered do not match.");
            }
        } else {
            return new Status(isError: true, message: "The user could not be found.");
        }
    }

    public static function logout() : Status{
        $_SESSION["user"] = null;
        return new Status(isError: false, message: "We hope to see you soon.");
    }

    public static function register(String $username, String $email, String $password) : Status{
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Status(isError: true, message: "Email inválido.", return: array(), bareReturn: array());
        }
        if(strlen($password) < 8){
            return new Status(isError: true, message: "Palavra-Passe inferior ao tamanho permitido (8 caracteres).", return: array(), bareReturn: array());
        }
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setVerified(Verification::NOT_VERIFIED);
        $user->store();

        $hash = $user->getUsername() . $user->getEmail() . $user->getId();

        $url = Routing::getRouting("verify") . "?hash=" . md5($hash) . "&id=" . $user->getId();

        $mailto = $email;
        $mailSub = "Ativar Conta";
        $mailMsg = '<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Confirmação de Email</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
  @media screen {
    @font-face {
      font-family: \'Source Sans Pro\';
      font-style: normal;
      font-weight: 400;
      src: local(\'Source Sans Pro Regular\'), local(\'SourceSansPro-Regular\'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format(\'woff\');
    }
    @font-face {
      font-family: \'Source Sans Pro\';
      font-style: normal;
      font-weight: 700;
      src: local(\'Source Sans Pro Bold\'), local(\'SourceSansPro-Bold\'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format(\'woff\');
    }
  }
  body,
  table,
  td,
  a {
    -ms-text-size-adjust: 100%; /* 1 */
    -webkit-text-size-adjust: 100%; /* 2 */
  }
  table,
  td {
    mso-table-rspace: 0pt;
    mso-table-lspace: 0pt;
  }
  img {
    -ms-interpolation-mode: bicubic;
  }
  a[x-apple-data-detectors] {
    font-family: inherit !important;
    font-size: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
    color: inherit !important;
    text-decoration: none !important;
  }
  div[style*="margin: 16px 0;"] {
    margin: 0 !important;
  }
  body {
    width: 100% !important;
    height: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  table {
    border-collapse: collapse !important;
  }
  a {
    color: #1a82e2;
  }
  img {
    height: auto;
    line-height: 100%;
    text-decoration: none;
    border: 0;
    outline: none;
  }
  </style>

</head>
<body style="background-color: #e9ecef;">
  <!-- start preheader -->
  <div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    Confirmação do Endereço de Email solicitado na criação de conta.
  </div>
 
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="center" valign="top" style="padding: 36px 24px;">
              <a href="https://localhost" target="_blank" style="display: inline-block;">
                <img src="https://i.imgur.com/c8XEX83.png" alt="Logo" border="0" width="48" style="display: block; width: 150px; max-width: 150px; min-width: 48px;">
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" bgcolor="#e9ecef">
 
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
              <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Confirma o teu endereço de email</h1>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">

          <!-- start copy -->
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin: 0;">Clique no botão abaixo para confirmar o teu endereço de email. Se não criaste uma conta com <a href="' . Routing::getRouting("home") . '">Cyrus</a>, por favor ignore/apague este email.
              </p>
            </td>
          </tr>
          <tr>
            <td align="left" bgcolor="#ffffff">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" bgcolor="#1a82e2" style="border-radius: 6px;">
                          <a href="'. $url .'" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">Ativar Conta</a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin: 0;">Se o botão não funcionar, copia e cola o seguinte endereço no teu navegador: </p>
              <p style="margin: 0;"><a href="'. $url .'" target="_blank">' . $url . '</a></p>
            </td>
          </tr>
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
              <p style="margin: 0;">Cumprimentos,<br> Cyrus</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" bgcolor="#e9ecef" style="padding: 24px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
              <p style="margin: 0;">Recebeste email email pois recebemos um pedido de registo com este email. Se não solicitaste-o, podes apagar este email.</p>
            </td>
          </tr>
          <tr>
            <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
              <p style="margin: 0;">Contacta-nos através do nosso email, <b>cyrus.animation2021@outlook.com</b></p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>';
        $mail = new PHPMailer();
        $mail->IsSmtp();
        $mail->SMTPDebug = 0;

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






        return new Status(isError: false, return: array($user->toArray(false, false)), bareReturn: array($user));
    }


    public static function test()
    {

        $hash = "Kurottebayovesilva24a@gmail.com20";

        $url = Routing::getRouting("verify") . "hash=" . md5($hash);

        $mailto = "vesilva31a@gmail.com";
        $mailSub = "Ativação da Conta";
        $mailMsg = '<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Confirmação de Email</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
  @media screen {
    @font-face {
      font-family: \'Source Sans Pro\';
      font-style: normal;
      font-weight: 400;
      src: local(\'Source Sans Pro Regular\'), local(\'SourceSansPro-Regular\'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format(\'woff\');
    }
    @font-face {
      font-family: \'Source Sans Pro\';
      font-style: normal;
      font-weight: 700;
      src: local(\'Source Sans Pro Bold\'), local(\'SourceSansPro-Bold\'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format(\'woff\');
    }
  }
  body,
  table,
  td,
  a {
    -ms-text-size-adjust: 100%; /* 1 */
    -webkit-text-size-adjust: 100%; /* 2 */
  }
  table,
  td {
    mso-table-rspace: 0pt;
    mso-table-lspace: 0pt;
  }
  img {
    -ms-interpolation-mode: bicubic;
  }
  a[x-apple-data-detectors] {
    font-family: inherit !important;
    font-size: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
    color: inherit !important;
    text-decoration: none !important;
  }
  div[style*="margin: 16px 0;"] {
    margin: 0 !important;
  }
  body {
    width: 100% !important;
    height: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  table {
    border-collapse: collapse !important;
  }
  a {
    color: #1a82e2;
  }
  img {
    height: auto;
    line-height: 100%;
    text-decoration: none;
    border: 0;
    outline: none;
  }
  </style>

</head>
<body style="background-color: #e9ecef;">
  <!-- start preheader -->
  <div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    Confirmação do Endereço de Email solicitado na criação de conta.
  </div>
 
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="center" valign="top" style="padding: 36px 24px;">
              <a href="https://localhost" target="_blank" style="display: inline-block;">
                <img src="https://i.imgur.com/c8XEX83.png" alt="Logo" border="0" width="48" style="display: block; width: 150px; max-width: 150px; min-width: 48px;">
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" bgcolor="#e9ecef">
 
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
              <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Confirma o teu endereço de email</h1>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">

          <!-- start copy -->
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin: 0;">Clique no botão abaixo para confirmar o teu endereço de email. Se não criaste uma conta com <a href="' . Routing::getRouting("home") . '">Cyrus</a>, por favor ignore/apague este email.
              </p>
            </td>
          </tr>
          <tr>
            <td align="left" bgcolor="#ffffff">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" bgcolor="#1a82e2" style="border-radius: 6px;">
                          <a href="'. $url .'" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">Ativar Conta</a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin: 0;">Se o botão não funcionar, copia e cola o seguinte endereço no teu navegador: </p>
              <p style="margin: 0;"><a href="'. $url .'" target="_blank">' . $url . '</a></p>
            </td>
          </tr>
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
              <p style="margin: 0;">Cumprimentos,<br> Cyrus</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" bgcolor="#e9ecef" style="padding: 24px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
              <p style="margin: 0;">Recebeste email email pois recebemos um pedido de registo com este email. Se não solicitaste-o, podes apagar este email.</p>
            </td>
          </tr>
          <tr>
            <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
              <p style="margin: 0;">Contacta-nos através do nosso email, <b>cyrus.animation2021@outlook.com</b></p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>';
        $mail = new PHPMailer();
        $mail->IsSmtp();
        $mail->SMTPDebug = 0;

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

    }

}