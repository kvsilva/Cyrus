<?php
use Functions\Utils;
use Functions\Routing;

?>
<div class = "page-overlay"></div>
<div id = "header">
    <div class = "header-wrapper">
        <div class = "header-content">
        <div class = "header-left">
            <div class = "logo">
                <img src = "<?php echo Utils::getDependencies("Cyrus", "logo"); ?>" alt="icon">
            </div>
        </div>
        <div class = "header-center">
            <div class = "header-btn">
                <a class = "link-nodecoration header-link" href = "<?php echo Routing::getRouting("animes"); ?>">Animes</a>
            </div> <!-- Depois mostrar Popular, Lançamentos quando passar por cima, se pressionar vai pra o /animes-->
            <div class = "header-btn">
                <a class = "link-nodecoration header-link" href = "<?php echo Routing::getRouting("calendar"); ?>">Calendário</a>
            </div>
        </div>
        <div class = "header-right">
            <div class = "header-btn">
                <a class = "link-nodecoration header-link" href = "<?php echo Routing::getRouting("search"); ?>"> <i class="fa-solid fa-magnifying-glass"></i>
                </a>
            </div>
            <div class = "header-btn">
                <i class="fa-solid fa-bookmark"></i>
            </div>
            <div class = "header-btn" id = "user-menu-btn">
                <div role="button" class="header-user dropdown-arrow">
                    <img draggable="false" class = "header-user-avatar" src = "https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png" alt="Icon">
                </div>
                <div class = "list-menu" id = "user-menu-list">
                    <div class = "list-menu-scrollable">
                        <div class = "list-menu-section">
                            <div class = "list-menu-section-item">
                                <div class = "col-3">
                                    <div class = "pe-1">
                                        <img draggable="false" class = "list-avatar" src = "https://static.crunchyroll.com/assets/avatar/170x170/1044-jujutsu-kaisen-satoru-gojo.png" alt="Icon">
                                    </div>
                                </div>
                                <div class = "col-9">
                                    <div>Kurookami</div>
                                    <div class = "user-role-text"><i class="fa-solid fa-user-group"></i><span class = "ps-2">Membro Premium</span></div>
                                </div>
                            </div>
                        </div>
                        <div class = "list-menu-section">
                            <div class = "list-menu-section-item list-menu-btn">
                                <div>
                                    <i class="list-icon fa-solid fa-bookmark"></i>
                                    <span>Lista de Visionamento</span>
                                </div>
                            </div>
                            <div class = "list-menu-section-item list-menu-btn">
                                <div>
                                    <div>
                                        <i class="list-icon fa-solid fa-clock-rotate-left"></i>
                                        <span>Histórico</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class = "list-menu-section">
                            <div class = "list-menu-section-item list-menu-btn">
                                <a href = "<?php echo Routing::getRouting("account"); ?>" class = "link-nodecoration">
                                    <div>
                                        <i class="list-icon fa-solid fa-user"></i>
                                        <span>A minha conta</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class = "list-menu-section">
                            <div class = "list-menu-section-item list-menu-btn">
                                <div>
                                    <i class="list-icon fa-solid fa-right-from-bracket"></i>
                                    <span>Encerrar Sessão</span>
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
?>