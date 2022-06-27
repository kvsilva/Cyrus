<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\Season;
use Objects\SeasonsArray;
use Objects\Video;
use Services\Animes;
use Services\Users;


?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Minha Conta");
    ?>
    <link href="<?php echo Utils::getDependencies("Account", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Account") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="content-wrapper">
        <div class="user-info row">
            <div class="col-2">
                <div class="pe-1">
                    <img draggable="false" class="user-info-avatar"
                         src="https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png"
                         alt="Icon">
                </div>
            </div>
            <div class="col-8 user-info-data">
                <div class="user-info-username">Kurookami</div>
                <div class="user-info-role-text"><i class="fa-solid fa-user-group"></i><span
                            class="ps-2">Administrador</span></div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="user-options">
                    <div class="user-options-section">
                        <div class="user-options-section-title">
                            <span>Geral</span>
                        </div>
                        <div class="user-options-section-items">
                            <div class="user-options-section-item">
                                <span>Plano de Subscrição</span>
                            </div>
                            <div class="user-options-section-item user-options-section-selected" role = "button" data-option = "preferences">
                                <span>Preferências</span>
                            </div>
                            <div class="user-options-section-item" role = "button" data-option = "change-email">
                                <span>Alterar e-mail</span>
                            </div>
                            <div class="user-options-section-item" role = "button" data-option = "change-password">
                                <span>Alterar palavra-passe</span>
                            </div>
                        </div>
                    </div>
                    <div class="user-options-section">
                        <div class="user-options-section-title">
                            <span>Compras</span>
                        </div>
                        <div class="user-options-section-items">
                            <div class="user-options-section-item">
                                <span>Histórico de Pedidos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="user-options-body" data-setting = "preferences">
                    <div class="user-options-body-wrapper">
                        <div class="user-options-body-title">
                            <span>Preferências</span>
                        </div>
                        <div class="user-options-body-description">
                            <span>Define as tuas preferências relativamente a idioma e vídeos</span>
                        </div>
                        <div class="user-options-body-cfg">
                            <div class="option-cfg-item">
                                <div class="option-cfg-item-title">
                                    <span>Idioma</span>
                                </div>
                                <div class="option-cfg-item-subitem">
                                    <label class="option-cfg-item-subitem-subtitle">
                                        <span>Idioma de Apresentação</span>
                                        <div class="dropdown">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span id="selected-display-language" data-selected="0">Português (Portugal)</span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1"
                                                id="availableSeasons">
                                                <li data-id="1">Português (Portugal)</li>
                                                <li data-id="2">English (US)</li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                                <div class="option-cfg-item-subitem">
                                    <label class="option-cfg-item-subitem-subtitle">
                                        <span>Idioma de Comunicação via e-mail</span>
                                        <div class="dropdown">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span id="selected-email-communication-language" data-selected="0"></span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1"
                                                id="availableSeasons">
                                                <li data-id="1">Português (Portugal)</li>
                                                <li data-id="2">English (US)</li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="option-cfg-item">
                                <div class="option-cfg-item-title">
                                    <span>Video</span>
                                </div>
                                <div class="option-cfg-item-subitem">
                                    <label class="option-cfg-item-subitem-subtitle">
                                        <span>Idioma das Legendas</span>
                                        <div class="dropdown">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span id="selected-translation-language"
                                                      data-selected="2"></span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1"
                                                id="availableSeasons">
                                                <li  data-id="1">Português (Portugal)</li>
                                                <li data-id="2">English (US)</li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-options-body user-options-body-hidden" data-setting = "change-email">
                    <div class="user-options-body-wrapper">
                        <div class="user-options-body-title">
                            <span>Alterar e-mail</span>
                        </div>
                        <div class="user-options-body-description">
                            <span>Escolhe um endereço de e-mail para iniciares sessão e receberes comunicações</span>
                        </div>
                        <div class="user-options-body-cfg">
                            <div class="option-cfg-item">
                                <div class="option-cfg-item-subitem">
                                    <div class="cyrus-input-group disable-after">
                                        <span class="option-cfg-item-subitem-title">E-mail Atual</span>
                                        <span class="option-cfg-item-subitem-text">vesilva24a@gmail.com</span>
                                    </div>
                                </div>
                                <div class="option-cfg-item-subitem">
                                    <div class="cyrus-input-group option-cfg-item-subitem-text">
                                        <input class="w-100 cyrus-minimal" type="text" value=''
                                               onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class="cyrus-floating-label">Novo e-mail</span>
                                    </div>
                                    <div class = "cyrus-input-group option-cfg-item-subitem-text">
                                        <input class = "w-100 cyrus-minimal" type = "password" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class = "cyrus-floating-label">Palavra-Passe Atual</span>
                                    </div>
                                    <div class = "d-flex justify-content-center mt-4">
                                        <input class="cyrus-input" type="submit" value="ALTERAR EMAIL" id = "change-email_submit" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-options-body user-options-body-hidden" data-setting = "change-password">
                    <div class="user-options-body-wrapper">
                        <div class="user-options-body-title">
                            <span>Alterar palavra-passe</span>
                        </div>
                        <div class="user-options-body-description">
                            <span>Escolhe uma palavra-passe única para manter a tua conta em segurança</span>
                        </div>
                        <div class="user-options-body-cfg">
                            <div class="option-cfg-item">
                                <div class="option-cfg-item-subitem">
                                    <div class="cyrus-input-group option-cfg-item-subitem-text">
                                        <input id = "change-password_current-password" class="w-100 cyrus-minimal" type="password" value=''
                                               onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class="cyrus-floating-label">Palavra-passe atual</span>
                                    </div>
                                    <div class="cyrus-input-group option-cfg-item-subitem-text">
                                        <input id = "change-password_new-password" class="w-100 cyrus-minimal" type="password" value=''
                                               onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class="cyrus-floating-label">Nova palavra-passe</span>
                                    </div>
                                    <div class = "cyrus-input-group option-cfg-item-subitem-text">
                                        <input id = "change-password_repeat-password" class = "w-100 cyrus-minimal" type = "password" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class = "cyrus-floating-label">Repete a nova palavra-passe</span>
                                    </div>
                                    <div class = "cyrus-alert-info">
                                        <i class="fa-solid fa-circle-info"></i>
                                        <span class = "cyrus-alert-info-text">
                                            <span>Mudar a tua palavra-passe terminará a tua sessão. Precisarás de inserir a nova palavra-passe quando voltares a iniciar a sessão.</span>
                                        </span>
                                    </div>
                                    <div class = "d-flex justify-content-center mt-4">
                                        <input class="cyrus-input" type="submit" value="ALTERAR PALAVRA-PASSE" id = "change-password_submit" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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