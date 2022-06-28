<?php
require_once(dirname(__DIR__) . '\\resources\\php\\settings.php');

use Functions\Routing;
use Functions\Utils;
use Objects\Language;
use Objects\Permission;

$permissions = Permission::find(tag: "BACKOFFICE_ACCESS");
if(!isset($_SESSION["user"]) || $permissions->size() == 0 || !$_SESSION["user"]->hasPermission($permissions[0])){
    header("Location: " . Routing::getRouting("home"));
    exit;
}

$objects = array(
        "Objects\User" => array(
                "icon" => "fa-solid fa-users",
                "ignoreFields" => array(
                        "update" => array("id"),
                        "insert" => array("id")
                ),
                "forceModel" => array(
                        "status" => "textarea",
                        "about_me" => "textarea",
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
            <div class="col-2">
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
                            <div class = "group-section-items">
                                <div class = "group-section-item">
                                    <div class = "cyrus-input-group group-input-text">
                                        <input type = "text" class = "cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class="cyrus-floating-label">Email</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <input type = "text" class = "cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class="cyrus-floating-label">Username</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <input type = "password" class = "cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                                        <span class="cyrus-floating-label">Password</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-datepicker">
                                        <input type = "date" class = "cyrus-minimal group-input" min="1900-12-31" max = "9999-01-01" autocomplete="new-password">
                                        <span class="cyrus-floating-label">Birthdate</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <div class="dropdown no-select">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span data-selected="null"></span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                                                <?php
                                                $sex = Enumerators\Sex::getAllItems();
                                                foreach($sex as $item){
                                                    echo '<li data-id = "' . $item->value . '">' . $item->name() . '</li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <span class="cyrus-floating-label cyrus-floating-label-float">Sex</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <textarea class = "cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password"></textarea>
                                        <span class="cyrus-floating-label">Status</span>
                                    </div>
                                    <div class="group-section-subitem">
                                        <div class="group-section-subitem-title">Profile Image</div>

                                        <!-- Upload File -->
                                        <div class="group-section-subitem-items">
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Title</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Description</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="file" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                                            </div>
                                        </div>

                                        <!-- Register File -->
                                        <div class="group-section-subitem-items cyrus-item-hidden">
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Title</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Description</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Extension</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Title</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">URL</span>
                                            </div>
                                        </div>

                                        <!-- Upload Anime Video -->
                                        <div class="group-section-subitem-items cyrus-item-hidden">
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="number" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Video ID</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="file" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="group-section-subitem">
                                        <div class="group-section-subitem-title">Profile Background</div>

                                        <!-- Upload File -->
                                        <div class="group-section-subitem-items">
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Title</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Description</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="file" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                                            </div>
                                        </div>

                                        <!-- Register File -->
                                        <div class="group-section-subitem-items cyrus-item-hidden">
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Title</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Description</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Extension</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Title</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="text" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">URL</span>
                                            </div>
                                        </div>

                                        <!-- Upload Anime Video -->
                                        <div class="group-section-subitem-items cyrus-item-hidden">
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="number" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label">Video ID</span>
                                            </div>
                                            <div class="cyrus-input-group group-input-text">
                                                <input type="file" class="cyrus-minimal group-input" value=''
                                                       onkeyup="this.setAttribute('value', this.value);"
                                                       autocomplete="new-password">
                                                <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <textarea class = "cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password"></textarea>
                                        <span class="cyrus-floating-label">About Me</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <div class="dropdown no-select">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span data-selected="null"></span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                                                <?php
                                                $verification = Enumerators\Verification::getAllItems();
                                                foreach($verification as $item){
                                                    echo '<li data-id = "' . $item->value . '">' . $item->name() . '</li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <span class="cyrus-floating-label cyrus-floating-label-float">Verified</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <div class="dropdown no-select">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span data-selected="null"></span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                                                <?php
                                                $languages = Language::find();
                                                foreach($languages as $item){
                                                    echo '<li data-id = "' . $item->getId() . '">' . $item->getOriginalName() . ' (' . $item->getCode() . ')' . '</li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <span class="cyrus-floating-label cyrus-floating-label-float">Display Language</span>
                                    </div>

                                    <div class = "cyrus-input-group group-input-text">
                                        <div class="dropdown no-select">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span data-selected="null"></span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                                                <?php
                                                $languages = Language::find();
                                                foreach($languages as $item){
                                                    echo '<li data-id = "' . $item->getId() . '">' . $item->getOriginalName() . ' (' . $item->getCode() . ')' . '</li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <span class="cyrus-floating-label cyrus-floating-label-float">Email Communication Language</span>
                                    </div>
                                    <div class = "cyrus-input-group group-input-text">
                                        <div class="dropdown no-select">
                                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                 data-bs-toggle="dropdown" aria-expanded="false">
                                                <span data-selected="null"></span>
                                            </div>
                                            <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                                                <?php
                                                $languages = Language::find();
                                                foreach($languages as $item){
                                                    echo '<li data-id = "' . $item->getId() . '">' . $item->getOriginalName() . ' (' . $item->getCode() . ')' . '</li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <span class="cyrus-floating-label cyrus-floating-label-float">Translation Language</span>
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