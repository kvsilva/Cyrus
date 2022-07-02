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
                "class" => "Objects\Video"
            ),
            "seasons" => array(
                "model" => "relation-full",
                "class" => "Objects\Season"
            ),
            "genders" => array(
                "model" => "default",
                "class" => "Objects\Gender"
            ),
        )
    ),
    "Objects\Gender" => array(
        "icon" => "fa-solid fa-users",
        "update" => true,
        "insert" => true,
        "forceModel" => array(),
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
?>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>
<div class="alerts">
    <div class="alerts-wrapper" id = "alerts">
        <div class="alert alert-danger alert-dismissible fade d-flex align-items-center" data-alert = "danger" role="alert" id = "alert-danger">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                <use xlink:href="#exclamation-triangle-fill"/>
            </svg>
            <span>a</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="alert alert-warning alert-dismissible fade d-flex align-items-center" data-alert = "warning" role="alert"  id = "alert-warning">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                <use xlink:href="#exclamation-triangle-fill"/>
            </svg>
            <span></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="alert alert-success alert-dismissible fade d-flex align-items-center" data-alert = "success" role="alert" id = "alert-success">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                <use xlink:href="#check-circle-fill"/>
            </svg>
            <span></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
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
                                                <button class="cyrus-btn cyrus-btn-type2"
                                                        data-form="<?php echo strtolower($entity_name); ?>_details"
                                                        data-entity="<?php echo $entity_name; ?>" id="btn-details-edit">
                                                    EDITAR
                                                </button>
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
                                                                    echoModelFor(model: $model, formName: $entity_name . "_update_relations", fieldName: $relation, displayName: ucfirst(strtolower($relation)), relationEntity: $entity_class, childEntity: $obj);

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
                                            data-bs-target="#exampleModal">
                                        Adicionar
                                    </button>
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Adicionar</h5>
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
include(Utils::getDependencies("Cyrus", "footer", true));

$entity = new Audience();

?>
</body>
</html>