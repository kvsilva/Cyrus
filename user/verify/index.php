<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');

use Enumerators\Verification;
use Functions\Routing;
use Functions\Utils;
use Objects\Language;
use Objects\User;


if (!isset($_GET["hash"]) || !isset($_GET["id"])) {
    header("Location: " . Routing::getRouting("home"));
    exit;
}

$error = false;
$id = $_GET["id"];
$hashReceived = $_GET["hash"];

$users = User::find(id: $id, verification: Verification::NOT_VERIFIED);
if($users->size() === 0){
    $error = true;
} else {
    $hashUser = md5($users[0]->getUsername() . $users[0]->getEmail() . $users[0]->getId());
    if($hashUser != $hashReceived) {
        $error = true;
    } else {
        $users[0]->setVerified(Verification::VERIFIED);
        $users[0]->store();
    }
}

if(!$error){
    header( "refresh:5;url=" . Routing::getRouting("login"));
}


?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Verificação");
    ?>
    <link href="<?php echo Utils::getDependencies("Verify", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Verify") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
include(Utils::getDependencies("Cyrus", "alerts", true));
?>
<div id="content">
    <div class="page-wrapper">
        <div class="logo-hero">
            <div class="logo-hero-wrapper">
                <video autoplay muted loop id="myVideo">
                    <source src="<?php echo Routing::getRouting("home") . "video_back.mp4"?>" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
            </div>
        </div>
        <div class="verify">
            <img class = "logo-img" src="<?php echo Utils::getDependencies("Cyrus", "logo_slogan"); ?>">
            <div class="verify-wrapper">
                <span style = "font-size: 30px;">
                        <?php
                            if(!$error){
                        ?>
                                Conta Verificada com sucesso. Redirecionando...
                        <?php } else { ?>
                                Não foi possível encontrar esta conta.
                        <?php } ?>
                    </span>
            </div>
        </div>
    </div>
</div>

<?php
?>
</body>
</html>