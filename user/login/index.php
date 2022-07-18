<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Language;


if (isset($_SESSION["user"])) {
    header("Location: " . Routing::getRouting("home"));
    exit;
}

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Login");
    ?>
    <link href="<?php echo Utils::getDependencies("Login", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Login") ?>"></script>
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
        <div class="login">
            <img class = "logo-img" src="<?php echo Utils::getDependencies("Cyrus", "logo_slogan"); ?>">
            <div class="login-wrapper">
                <div class="title">Entrar</div>
                <div class="description">Entre com a sua conta para continuar!</div>

                <div class="form">
                    <div class="cyrus-input-group">
                        <input type="text" id="username-email" class="cyrus-minimal" value=''
                               onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                        <span class="cyrus-floating-label">Endereço de Email ou Nome de Utilizador</span>
                    </div>

                    <div class="cyrus-input-group">
                        <input type="password" id="password" class="cyrus-minimal" value=''
                               onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                        <span class="cyrus-floating-label">Palavra-Passe</span>
                    </div>

                    <input type = "submit" id = "execute" class="cyrus-input cyrus-btn" disabled value = "Entrar">
                </div>
                <div class="redirect-page">
                    <span>Não registado? <a
                                href="<?php echo Routing::getRouting("register") ?>">Crie uma conta</a></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
?>
</body>
</html>