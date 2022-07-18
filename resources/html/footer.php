<?php
use Objects\GlobalSetting;
use Functions\Routing;
?>
<div id = "footer">
    <div class = "content-wrapper sections">
        <div class = "section">
            <h4 class = "section-title">Navegação</h4>
            <ul class = "list">
                <li>
                    <a href = "<?php echo Routing::getRouting("animes"); ?>">
                        <span>Procurar animes</span>
                    </a>
                </li>
                <li>
                    <a href = "<?php echo Routing::getRouting("calendar"); ?>">
                        <span>Calendário</span>
                    </a>
                </li>
                <li>
                    <a href = "<?php echo Routing::getRouting("news"); ?>">
                        <span>Notícias</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class = "section">
            <h4 class = "section-title">Conecte-se connosco</h4>
            <ul class = "list">
                <?php
                try {
                    $array = GlobalSetting::find(category: "SocialMedia");
                    foreach ($array as $key){?>
                        <li>
                            <a href = "<?php echo $key->getValue();?>">
                                <span><?php echo $key->getName();?></span>
                            </a>
                        </li>
                    <?php }

                } catch (ReflectionException $e) {
                    echo $e;
                }

                ?>
            </ul>
        </div>
        <?php
        if(false){?>
        <div class = "section">
            <h4 class = "section-title">Cyrus</h4>
            <ul class = "list">
                <li>
                    <a href = "<?php echo Routing::getRouting("about"); ?>">
                        <span>Sobre</span>
                    </a>
                </li>
                <li>
                    <a href = "<?php echo Routing::getRouting("faq"); ?>">
                        <span>Ajuda/FAQ</span>
                    </a>
                </li>
                <li>
                    <a href = "<?php echo Routing::getRouting("termsOfUse"); ?>">
                        <span>Termos de Uso</span>
                    </a>
                </li>
                <li>
                    <a href = "<?php echo Routing::getRouting("privacyPolicy"); ?>">
                        <span>Política de Privacidade</span>
                    </a>
                </li>
                <li>
                    <a href = "<?php echo Routing::getRouting("cookiesPolicy"); ?>">
                        <span>Política de Cookies</span>
                    </a>
                </li>
            </ul>
        </div>
        <?php }?>
    </div>
    <div class = "content-wrapper ">
        <hr>
        <div class = "copyright">
            <?php
            $projectStartDate = GlobalSetting::find(name: "ProjectStartDate")[0]?->getValue();
            ?>
            <span>© Cyrus <?php echo date("Y") == $projectStartDate ? $projectStartDate : $projectStartDate . " - " . date('Y');?></span>
        </div>
    </div>
</div>