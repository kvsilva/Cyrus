<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');
require_once(dirname(__DIR__) . '\\backoffice\\assets\\php\\html.models.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Language;
use Objects\Permission;

$permissions = Permission::find(tag: "BACKOFFICE_ACCESS");
if(!isset($_SESSION["user"]) || $permissions->size() == 0 || !$_SESSION["user"]->hasPermission($permissions[0])){
    header("Location: " . Routing::getRouting("home"));
    exit;
}

$models = array(
        "string" => "URL do Modelo string",
        "Resource" => "URL do Modelo Resource",
    // caso o modelo n esteja aqui declarado, deverá entender que é um subitem e buscar ele à mesma pagina index, mas como se fosse permissão
);

$objects = array(
        "Objects\User" => array(
                "icon" => "fa-solid fa-users",
                "ignoreFields" => array(
                        "update" => array("id"),
                        "insert" => array("id")
                ),
                "forceModel" => array(
                        "status" => "text",
                        "about_me" => "text",
                ),
        ),
        "Objects\Language" => array(
            "icon" => "fa-solid fa-users",
            "ignoreFields" => array(
                "update" => array("id"),
                "insert" => array("id")
            ),
            "forceModel" => array(

            ),
        )
)

?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Backoffice");
    ?>
    <link href="<?php echo Utils::getDependencies("Backoffice", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("Backoffice") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">
    <div class="">
        <div class="row" style = "--bs-gutter-x: 0">
            <div class="col-1">
                <div class = "menu">
                    <div class = "menu-section">
                        <div class = "menu-section-title">
                            <span>Entidades</span>
                        </div>
                        <div class = "menu-section-items">
                            <div class = "menu-section-item">
                                <span>User</span>
                            </div>
                            <div class = "menu-section-item">
                                <span>Role</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-10">
                <div class = "group hidden">
                    <div class = "group-wrapper">
                        <div class = "group-section">
                            <div class = "group-section-title">
                                <span>Adicionar</span>
                            </div>

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Launch demo modal
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Adicionar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class = "group-section-items">
                                                <div class = "group-section-item">
                                                    <?php
                                                        $entity_class = "Objects\\User";
                                                        $entity = new ReflectionClass($entity_class);
                                                        $properties = $entity->getProperties();
                                                        foreach($properties as $property){
                                                            if($property->isProtected()){
                                                                $type = $property->getType();
                                                                $isEntity = str_contains($type, "Objects\\");
                                                                $isEnum = str_contains($type, "Enumerators\\");
                                                                $type = str_replace("Objects\\", "", $type);
                                                                $type = str_replace("Enumerators\\", "", $type);
                                                                $type = str_replace("?", "", $type);
                                                                $type = str_replace("DateTime", "date", $type);
                                                                $field_name = $property->getName();
                                                                $display_name = ucwords(str_replace("_", " ", $field_name));
                                                                if(isset($objects[$entity_class])) {
                                                                    if(isset($objects[$entity_class]["forceModel"][$field_name])){
                                                                        $type = $objects[$entity_class]["forceModel"][$field_name];
                                                                    }
                                                                    echoModelFor($type, array("user_add", $field_name, $display_name));
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">

                                            <input class="cyrus-input" type="reset" data-bs-dismiss="modal" value="CANCELAR" data-form="user_add" data-entity = "User">
                                            <input class="cyrus-input" type="submit" data-bs-dismiss="modal" value="ADICIONAR" data-form="user_add" data-entity = "User">

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
</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>