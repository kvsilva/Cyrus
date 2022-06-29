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
        "forceModel" => array(
            "status" => "text",
            "about_me" => "text",
        ),
        "update" => true,
        "insert" => true,
        "relations" => array(
            "roles" => array(
                "model" => "relation-dropdown",
                "class" => "Objects\Role"
            ),
            "punishments" => array(
                "model" => "relation-full",
                "class" => "Objects\Punishment"
            ),
        )
    ),
    "Objects\Language" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(),
        "relations" => null
    ),
    "Objects\Punishment" => array(
         "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => false,
        "forceModel" => array(),
        "relations" => null
    ),
    "Objects\GlobalSetting" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(
                "value_binary" => "text"
        ),
        "relations" => null
    )
);

$entity_name = $_GET["entity"] ?? "User";
$entity_class = "Objects\\" . $entity_name;
if(!isset($objects[$entity_class])) {
    $entity_name = "User";
    $entity_class = "Objects\\" . $entity_name;
}
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

                            <?php
                            if(isset($objects[$entity_class]) && $objects[$entity_class]["relations"] !== null){
                            ?>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#relationModal">
                                    Relações
                                </button>

                                <div class="modal fade" id="relationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Relações</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class = "group-section-items">
                                                    <div class = "group-section-item">
                                                        <?php
                                                        $entity_class = "Objects\\" . $entity_name;
                                                        if(isset($objects[$entity_class])) {
                                                            foreach ($objects[$entity_class]["relations"] as $relation => $data){
                                                                $model = $data["model"];
                                                                $obj = $data["class"];
                                                                echoModelFor($model, array(strtolower($entity_name) . "_update_relations", "punishments", ucfirst(strtolower($relation)), $obj));
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input class="cyrus-input" type="reset" data-bs-dismiss="modal" value="CANCELAR" data-form="<?php echo strtolower($entity_name); ?>_update" data-entity = "<?php echo $entity_name; ?>">
                                                <input class="cyrus-input" type="submit" data-bs-dismiss="modal" value="ATUALIZAR" data-form="<?php echo strtolower($entity_name); ?>_update" data-entity = "<?php echo $entity_name; ?>">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if(isset($objects[$entity_class]) && $objects[$entity_class]["insert"]){?>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Adicionar
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
                                                                        echoModelFor($type, array(strtolower($entity_name) . "_insert", $field_name, $display_name));
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">

                                                <input class="cyrus-input" type="reset" data-bs-dismiss="modal" value="CANCELAR" data-form="<?php echo strtolower($entity_name); ?>_insert" data-entity = "<?php echo $entity_name; ?>">
                                                <input class="cyrus-input" type="submit" data-bs-dismiss="modal" value="ADICIONAR" data-form="<?php echo strtolower($entity_name); ?>_insert" data-entity = "<?php echo $entity_name; ?>">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            }?>


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