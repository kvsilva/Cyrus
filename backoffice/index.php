<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');
require_once(dirname(__DIR__) . '\\backoffice\\assets\\php\\html.models.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Audience;
use Objects\Entity;
use Objects\Language;
use Objects\Permission;
use Objects\User;

$permissions = Permission::find(tag: "BACKOFFICE_ACCESS");
if (!isset($_SESSION["user"]) || $permissions->size() == 0 || !$_SESSION["user"]->hasPermission($permissions[0])) {
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
            "password" => "password"
        ),
        "update" => true,
        "insert" => true,
        "ignoreUpdate" => array(
            "creation_date"
        ),
        "forceModelOnUpdate" => array(
            "profile_image" => "resource_update",
            "profile_background" => "resource_update",
        ),
        "relations" => array(
            "roles" => array(
                "model" => "relation-dropdown",
                "class" => "Objects\Role",
                "ignore" => array()
            ),
            "punishments" => array(
                "model" => "relation-full",
                "class" => "Objects\Punishment",
                "ignore" => array()
            ),
        )
    ),
    "Objects\Role" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "ignoreUpdate" => array(

        ),
        "forceModel" => array(

        ),
        "forceModelOnUpdate" => array(

        ),
        "relations" => array(
            "permissions" => array(
                "model" => "default",
                "class" => "Objects\Permission",
                "ignore" => array()
            )
        )
    ),
    "Objects\Permission" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(),
        "relations" => null
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
    "Objects\PunishmentType" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(),
        "relations" => null
    ),
    "Objects\Anime" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(
                "synopsis" => "text"
        ),
        "forceModelOnUpdate" => array(
            "cape" => "resource_update",
            "profile" => "resource_update",
        ),
        "relations" => array(
            "videos" => array(
                "model" => "relation-full",
                "class" => "Objects\Video",
                "ignore" => array(
                        "anime", "season"
                )
            ),
            "seasons" => array(
                "model" => "relation-full",
                "class" => "Objects\Season",
                "ignore" => array("anime")
            ),
            "genders" => array(
                "model" => "default",
                "class" => "Objects\Gender",
                "ignore" => array()
            ),
        )
    ),
    "Objects\Season" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => false,
        "forceModel" => array(
            "synopsis" => "text"
        ),
        "forceModelOnUpdate" => array(

        ),
        "relations" => array(
            "videos" => array(
                "model" => "relation-full",
                "class" => "Objects\Video",
                "ignore" => array(
                    "anime", "season"
                )
            )
        )
    ),
    "Objects\Video" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => false,
        "ignoreUpdate" => array(
            "anime", "season", "release_date"
        ),
        "forceModel" => array(
            "synopsis" => "text"
        ),
        "forceModelOnUpdate" => array(
            "thumbnail" => "resource_update",
            "path" => "resource_update",
        ),
        "relations" => array(
            "subtitles" => array(
                "model" => "relation-full",
                "class" => "Objects\Subtitle",
                "ignore" => array(
                    "anime", "season"
                )
            )
        )
    ),
    "Objects\VideoType" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(),
        "relations" => null
    ),
    "Objects\Subtitle" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => false,
        "forceModel" => array(

        ),
        "relations" => null
    ),
    "Objects\Gender" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(),
        "relations" => null
    ),
    "Objects\Audience" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(),
        "relations" => null
    ),
    "Objects\Resource" => array(
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
    ),
);

$entity_name = $_GET["entity"] ?? "User";
$entity_class = "Objects\\" . $entity_name;
if (!isset($objects[$entity_class])) {
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
    <link href="<?php echo Utils::getDependencies("DataTables", "css") ?>" rel="stylesheet">
    <link href="<?php echo Utils::getDependencies("Backoffice", "css") ?>" rel="stylesheet">
    <script type="module" src="<?php echo Utils::getDependencies("DataTables") ?>"></script>
    <script type="module" src="<?php echo Utils::getDependencies("Backoffice") ?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
include(Utils::getDependencies("Cyrus", "alerts", true));
?>


<div id="content">
    <div class="">
        <div class="row" style="--bs-gutter-x: 0">
            <div class="col-1">
                <div class="menu">
                    <div class="menu-section">
                        <div class="menu-section-title">
                            <span>Entidades</span>
                        </div>
                        <div class="menu-section-items">
                            <?php
                            foreach ($objects as $obj => $properties) {
                                ?>
                                <a class = "<?php echo $entity_name == str_replace("Objects\\", "", $obj) ? 'menu-section-item-selected ' : ''?>link-nodecoration" href = "?entity=<?php echo str_replace("Objects\\", "", $obj) ?>">
                                <div class="menu-section-item">
                                    <span><?php echo str_replace("Objects\\", "", $obj); ?></span>
                                </div>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-10 me-auto ms-auto">
                <span class="cyrus-item-hidden" id="entity-data" data-entity="<?php echo $entity_name ?>"></span>
                <div class="group">
                    <div class="group-wrapper">
                        <div class="group-section">
                            <div id="query" class="cyrus-scrollbar">

                                <table class="table align-middle table-striped table-dark table-hover cyrus-scrollbar" id = "query-table">
                                    <thead>
                                    <tr class="tr">
                                        <?php
                                        $entity = new ReflectionClass($entity_class);
                                        $array = $entity->getMethod("toOriginalArray")->invokeArgs(object: $entity->newInstanceWithoutConstructor(), args: array(true));
                                        foreach ($array as $column => $value) {
                                            if (!is_array($value)) {
                                                $field_name = str_replace("_", " ", $column);
                                                $field_name = ucwords($field_name);
                                                echo "<th class = 'backoffice-th' scope='col'>" . $field_name . "</th>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody id="query-body">

                                    </tbody>
                                </table>
                            </div>

                            <div id="modals">
                                <!-- Modals -->
                                <!-- Details Modal -->

                                <div class="modal fade" id="detailsModal" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detalhes</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="group-section-items" id="details-body">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="cyrus-btn cyrus-btn-type3" data-bs-dismiss="modal"
                                                        data-form="<?php echo strtolower($entity_name); ?>_details"
                                                        data-entity="<?php echo $entity_name; ?>">CANCELAR
                                                </button>
                                                <button class="cyrus-btn cyrus-btn-type2"
                                                        data-form="<?php echo strtolower($entity_name); ?>_details"
                                                        data-entity="<?php echo $entity_name; ?>"
                                                        id="btn-details-remove">REMOVER
                                                </button>
                                                <?php
                                                if (isset($objects[$entity_class]) && $objects[$entity_class]["relations"] !== null){
                                                ?>
                                                <button class="cyrus-btn cyrus-btn-type2"
                                                        data-form="<?php echo strtolower($entity_name); ?>_details"
                                                        data-entity="<?php echo $entity_name; ?>" id="btn-details-relations">
                                                    RELAÇÕES
                                                </button>
                                                <?php } ?>

                                                <?php if (isset($objects[$entity_class]) && (!isset($objects[$entity_class]["update"]) || $objects[$entity_class]["update"])){
                                                ?>
                                                <button class="cyrus-btn cyrus-btn-type2"
                                                        data-form="<?php echo strtolower($entity_name); ?>_details"
                                                        data-entity="<?php echo $entity_name; ?>" id="btn-details-edit">
                                                    EDITAR
                                                </button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Relations Modal-->
                                <?php
                                if (isset($objects[$entity_class]) && $objects[$entity_class]["relations"] !== null) {
                                    ?>

                                    <div class="modal fade" id="relationsModal" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Relações</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="group-section-items">
                                                        <div class="group-section-item">
                                                            <?php
                                                            $entity_class = "Objects\\" . $entity_name;
                                                            if (isset($objects[$entity_class])) {
                                                                foreach ($objects[$entity_class]["relations"] as $relation => $data) {
                                                                    $model = $data["model"];
                                                                    $obj = $data["class"];
                                                                    //echoModelFor($model, array(strtolower($entity_name) . "_update_relations", "teste", ucfirst(strtolower($relation)), $obj));
                                                                    echoModelFor(model: $model, formName: strtolower($entity_name) . "_update_relations", fieldName: $relation, displayName: ucfirst(strtolower($relation)), relationEntity: $entity_class, childEntity: $obj, objects: $objects);

                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input class="cyrus-input" type="reset" data-bs-dismiss="modal"
                                                           value="FECHAR"
                                                           data-form="<?php echo strtolower($entity_name); ?>_update_relations"
                                                           data-entity="<?php echo $entity_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php
                                if (isset($objects[$entity_class]) && $objects[$entity_class]["insert"]) {
                                    ?>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#insertModal">
                                        Adicionar
                                    </button>
                                    <div class="modal fade" id="insertModal" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Adicionar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="group-section-items">
                                                        <div class="group-section-item">
                                                            <?php
                                                            $entity = new ReflectionClass($entity_class);
                                                            $properties = $entity->getProperties();
                                                            foreach ($properties as $property) {
                                                                if ($property->isProtected()) {
                                                                    $type = $property->getType();
                                                                    $isEntity = str_contains($type, "Objects\\");
                                                                    $isEnum = str_contains($type, "Enumerators\\");
                                                                    $type = str_replace("Objects\\", "", $type);
                                                                    $type = str_replace("Enumerators\\", "", $type);
                                                                    $type = str_replace("?", "", $type);
                                                                    $type = str_replace("DateTime", "date", $type);
                                                                    $field_name = $property->getName();
                                                                    $display_name = ucwords(str_replace("_", " ", $field_name));
                                                                    if (isset($objects[$entity_class])) {
                                                                        if (isset($objects[$entity_class]["forceModel"][$field_name])) {
                                                                            $type = $objects[$entity_class]["forceModel"][$field_name];
                                                                        }
                                                                        //echoModelFor($type, array(strtolower($entity_name) . "_insert", $field_name, $display_name));
                                                                        echoModelFor(model: $type, formName: strtolower($entity_name) . "_insert", fieldName: $field_name, displayName: $display_name);
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">

                                                    <input class="cyrus-input" type="reset" data-bs-dismiss="modal"
                                                           value="CANCELAR"
                                                           data-form="<?php echo strtolower($entity_name); ?>_insert"
                                                           data-entity="<?php echo $entity_name; ?>">
                                                    <input class="cyrus-input" type="submit" data-bs-dismiss="modal"
                                                           value="ADICIONAR"
                                                           data-form="<?php echo strtolower($entity_name); ?>_insert"
                                                           data-action="insert"
                                                           data-entity="<?php echo $entity_name; ?>">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                } ?>

                                <!-- Update Modal-->

                                <?php
                                if (isset($objects[$entity_class]) && $objects[$entity_class]["update"]) {
                                    ?>
                                    <div class="modal fade" id="updateModal" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Atualizar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="group-section-items">
                                                        <div class="group-section-item">
                                                            <?php
                                                            $entity = new ReflectionClass($entity_class);
                                                            $properties = $entity->getProperties();
                                                            foreach ($properties as $property) {
                                                                if ($property->isProtected()) {
                                                                    $type = $property->getType();
                                                                    $isEntity = str_contains($type, "Objects\\");
                                                                    $isEnum = str_contains($type, "Enumerators\\");
                                                                    $type = str_replace("Objects\\", "", $type);
                                                                    $type = str_replace("Enumerators\\", "", $type);
                                                                    $type = str_replace("?", "", $type);
                                                                    $type = str_replace("DateTime", "date", $type);
                                                                    $field_name = $property->getName();
                                                                    $display_name = ucwords(str_replace("_", " ", $field_name));
                                                                    $ignore = false;
                                                                    if (isset($objects[$entity_class])) {
                                                                        if (isset($objects[$entity_class]["ignoreUpdate"])) {
                                                                            foreach ($objects[$entity_class]["ignoreUpdate"] as $field) {
                                                                                if ($field == $field_name) $ignore = true;
                                                                            }
                                                                        }
                                                                        if (isset($objects[$entity_class]["forceModel"][$field_name])) {
                                                                            $type = $objects[$entity_class]["forceModel"][$field_name];
                                                                        }
                                                                        if (isset($objects[$entity_class]["forceModelOnUpdate"][$field_name])) {
                                                                            $type = $objects[$entity_class]["forceModelOnUpdate"][$field_name];
                                                                        }
                                                                        if (!$ignore) echoModelFor(model: $type, formName: strtolower($entity_name) . "_update", fieldName: $field_name, displayName: $display_name, relationEntity: $entity_class);
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">

                                                    <input class="cyrus-input" type="reset" data-bs-dismiss="modal"
                                                           value="CANCELAR"
                                                           data-form="<?php echo strtolower($entity_name); ?>_update"
                                                           data-entity="<?php echo $entity_name; ?>">
                                                    <input class="cyrus-input" type="submit" data-bs-dismiss="modal"
                                                           value="ATUALIZAR"
                                                           data-form="<?php echo strtolower($entity_name); ?>_update"
                                                           data-entity="<?php echo $entity_name; ?>" id="btn-update"
                                                           data-action="update">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                } ?>

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
</body>
</html>