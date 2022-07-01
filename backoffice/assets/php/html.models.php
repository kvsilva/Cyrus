<?php

use Objects\Language;
use Objects\Punishment;
use Objects\Role;

function echoModelFor(string $model, string $formName, string $fieldName, string $displayName, ?string $relationEntity = null, ?string $childEntity = null, $objects = array())
{
    switch (strtolower($model)) {
        case "string":
            // 0: Nome do Formulário
            // 1: Nome do Campo no Formulário
            // 2: Nome do Label
            ?>
            <div class="cyrus-input-group group-input-text">
                <input type="text" class="cyrus-minimal group-input" data-form="<?php echo $formName; ?>"
                       data-name="<?php echo $fieldName ?>" value='' onkeyup="this.setAttribute('value', this.value);"
                       autocomplete="new-password">
                <span class="cyrus-floating-label"><?php echo $displayName ?></span>
            </div>
            <?php
            break;
        case "password":
            // 0: Nome do Formulário
            // 1: Nome do Campo no Formulário
            // 2: Nome do Label
            ?>
            <div class="cyrus-input-group group-input-text">
                <input type="password" class="cyrus-minimal group-input" data-form="<?php echo $formName; ?>"
                       data-name="<?php echo $fieldName ?>" value='' onkeyup="this.setAttribute('value', this.value);"
                       autocomplete="new-password">
                <span class="cyrus-floating-label"><?php echo $displayName ?></span>
            </div>
            <?php
            break;
        case "text":
            ?>
            <div class="cyrus-input-group group-input-text">
                <textarea class="cyrus-minimal group-input" data-form="<?php echo $formName; ?>"
                          data-name="<?php echo $fieldName ?>" value='' onkeyup="this.setAttribute('value', this.value);"
                          autocomplete="new-password"></textarea>
                <span class="cyrus-floating-label"><?php echo $displayName ?></span>
            </div>
            <?php
            break;
        case "date":
            $date = new DateTime();
            $date = $date->format("Y-m-d");
            ?>
            <div class="cyrus-input-group group-input-datepicker">
                <input type="date" class="cyrus-minimal group-input" value="<?php echo $date; ?>"
                       data-form="<?php echo $formName; ?>" data-name="<?php echo $fieldName ?>" min="1900-12-31"
                       max="9999-01-01" autocomplete="new-password">
                <span class="cyrus-floating-label"><?php echo $displayName ?></span>
            </div>
            <?php
            break;
        case "resource_update":
            ?>
            <div class="cyrus-input-group" data-form="<?php echo $formName; ?>" data-isDetailed="true"
                 data-name="<?php echo $fieldName ?>" data-object="null">
                <div class="model-update-details-removed cyrus-item-hidden">
                    <div class="w-100 text-center p-3">Removido</div>
                </div>
                <div class="model-update-details model-update-details-items" title="Apagar">
                    <div><b>Title</b>:<span class="ps-2" data-item="title">Attack On Titan</span></div>
                    <div><b>Description</b>:<span class="ps-2" data-item="description">Cape Image</span></div>
                    <div><b>URL</b>:<span class="ps-2" data-item="path">https://i.pinimg.com/originals/13/a1/01/13a10172127bbf9da50b8ce6db35eeaa.png</span>
                    </div>
                </div>
                <span class="cyrus-floating-label cyrus-floating-label-float-textarea"><?php echo $displayName ?></span>
            </div>
            <?php
            break;
        case "resource":
            ?>
            <div class="group-section-subitem" data-form="<?php echo $formName; ?>" data-isMultiple="true"
                 data-name="<?php echo $fieldName ?>" data-selectedSection="1">
                <div class="group-section-subitem-title"><?php echo $displayName ?></div>

                <!-- Upload File -->
                <div class="group-section-subitem-items" data-section="1" data-service="Resources"
                     data-action="uploadFile">

                    <input type="text" class="cyrus-item-hidden" value=''
                           onkeyup="this.setAttribute('value', this.value);"
                           autocomplete="new-password"
                           data-subitem="id">

                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="title">
                        <span class="cyrus-floating-label">Title</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="description" data-service="Resources" data-action="uploadFile">
                        <span class="cyrus-floating-label">Description</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="file" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="file" data-service="Resources" data-action="uploadFile">
                        <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                    </div>
                </div>

                <!-- Register File -->
                <div class="group-section-subitem-items cyrus-item-hidden" data-section="2">
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="title" data-service="Resources" data-action="registerFile">
                        <span class="cyrus-floating-label">Title</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="description" data-service="Resources" data-action="registerFile">
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="extension" data-service="Resources" data-action="registerFile">
                        <span class="cyrus-floating-label">Extension</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="title" data-service="Resources" data-action="registerFile">
                        <span class="cyrus-floating-label">Title</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="url" data-service="Resources" data-action="registerFile">
                        <span class="cyrus-floating-label">URL</span>
                    </div>
                </div>

                <!-- Upload Anime Video -->
                <div class="group-section-subitem-items cyrus-item-hidden" data-section="3">
                    <div class="cyrus-input-group group-input-text">
                        <input type="number" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="videoID" data-service="Resources" data-action="uploadAnimeVideo">
                        <span class="cyrus-floating-label">Video ID</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="file" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem="file" data-service="Resources" data-action="uploadAnimeVideo">
                        <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                    </div>
                </div>

            </div>

            <?php
            break;
        case "sex":
            ?>
            <div class="cyrus-input-group group-input-text">
                <div class="dropdown no-select">
                    <div class="dropdown-toggle w-100" type="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown="true" data-form="<?php echo $formName; ?>"
                              data-name="<?php echo $fieldName ?>" data-selected="null"></span>
                    </div>
                    <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                        <?php
                        $sex = Enumerators\Sex::getAllItems();
                        foreach ($sex as $item) {
                            echo '<li data-id = "' . $item->value . '">' . $item->name() . '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <span class="cyrus-floating-label cyrus-floating-label-float"><?php echo $displayName; ?></span>
            </div>

            <?php
            break;
        case "availability":
            ?>
            <div class="cyrus-input-group group-input-text">
                <div class="dropdown no-select">
                    <div class="dropdown-toggle w-100" type="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown="true" data-form="<?php echo $formName; ?>"
                              data-name="<?php echo $fieldName ?>" data-selected="null"></span>
                    </div>
                    <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                        <?php
                        $sex = Enumerators\Availability::getAllItems();
                        foreach ($sex as $item) {
                            echo '<li data-id = "' . $item->value . '">' . $item->name() . '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <span class="cyrus-floating-label cyrus-floating-label-float"><?php echo $displayName; ?></span>
            </div>

            <?php
            break;
        /*case "verification":
            ?>
            "<div class="cyrus-input-group group-input-text">
                <div class="dropdown no-select">
                    <div class="dropdown-toggle w-100" type="button" id="dropdownMenuButton1"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown="true" data-form="<?php echo $formName; ?>"
                              data-name="<?php echo $fieldName ?>" data-selected="null"></span>
                    </div>
                    <ul class="dropdown-menu no-select " aria-labelledby="dropdownMenuButton1">
                        <?php
                        $verification = Enumerators\Verification::getAllItems();
                        foreach ($verification as $item) {
                            echo '<li data-id = "' . $item->value . '">' . $item->name() . '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <span class="cyrus-floating-label cyrus-floating-label-float">Verified</span>
            </div>"

            <?php
            break;*/
        /*case "language":
            ?>
            <div class="cyrus-input-group group-input-text">
                <div class="dropdown no-select">
                    <div class="dropdown-toggle w-100" type="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown="true" data-form="<?php echo $formName; ?>"
                              data-name="<?php echo $fieldName ?>" data-selected="null"></span>
                    </div>
                    <ul class="dropdown-menu no-select" aria-labelledby="">
                        <?php
                        $languages = Language::find();
                        foreach ($languages as $item) {
                            echo '<li data-id = "' . $item->getId() . '">' . $item->getOriginalName() . ' (' . $item->getCode() . ')' . '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <span class="cyrus-floating-label cyrus-floating-label-float"><?php echo $displayName ?></span>
            </div>

            <?php
            break;*/
        /*case "relation-dropdown":
            ?>
            <div class="group-section-subitem" data-form="<?php echo $formName; ?>" data-isMultiple="true"
                 data-name="<?php echo $fieldName ?>" data-selectedSection="1">
                <div class="group-section-subitem-header">
                    <span class="group-section-subitem-title"><?php echo $displayName ?></span>
                    <button class="cyrus-btn cyrus-btn-type2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#relations_<?php echo $displayName ?>">Expandir
                    </button>
                </div>
                <div class="model" data-model="relation-full">
                    <div class="collapse" id="relations_<?php echo $displayName ?>">
                        <div class="group-section-subitem-items d-flex flex-wrap flex-column">
                            <div class="dropdown no-select pt-2">
                                <div class="dropdown-toggle w-50" type="button"
                                     data-bs-toggle="dropdown" aria-expanded="false">
                                <span data-isDropdown="true" data-form="<?php echo $formName; ?>"
                                      data-name="<?php echo $fieldName ?>" data-selected="null">Nenhum</span>
                                </div>
                                <ul class="dropdown-menu no-select" aria-labelledby="dropdownMenuButton1">
                                    <?php
                                    $entity_name = str_replace("Objects\\", "", $relationEntity);
                                    echo $relationEntity;
                                    $entity_class = $relationEntity;
                                    $entity = new ReflectionClass($entity_class);
                                    $roles = $entity->getMethod("find")->invoke(null);
                                    foreach ($roles as $item) {
                                        echo '<li data-id = "' . $item->getId() . '">' . $item->getName() . '</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <span class="cyrus-floating-label cyrus-floating-label-float"
                                  style="transform: translateY(-225%)"><?php echo $entity_name ?></span>
                            <?php
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            break;*/
        case "relation-full":
            ?>
            <div class="group-section-subitem" data-form="<?php echo $formName; ?>" data-isMultiple="true"
                 data-name="<?php echo $fieldName ?>" data-selectedSection="1">
                <div class="group-section-subitem-header">
                    <span class="group-section-subitem-title"><?php echo $displayName ?></span>
                    <button class="cyrus-btn cyrus-btn-type2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#relations_<?php echo $displayName ?>">Expandir
                    </button>
                </div>
                <div class="collapse" id="relations_<?php echo $displayName ?>">
                    <div class = "model-existingRelations mb-4">
                        <div class="cyrus-input-group" data-form="<?php echo $formName; ?>" data-isDetailed="true"
                             data-name="<?php echo $fieldName ?>" data-object="null">
                            <div class="model-update-details-removed cyrus-item-hidden">
                                <div class="w-100 text-center p-3">Removido</div>
                            </div>
                            <div class="model-update-details-items" title="Apagar" data-childentity="<?php echo $fieldName?>">
                                <div class = "model-update-details">
                                    <b>ID</b>: 54; <b>Name</b>: Attack on Titan
                                </div>
                                <div class = "model-update-details">
                                    <b>ID</b>: 54; <b>Name</b>: Attack on Titan
                                </div>
                            </div>
                            <span class="cyrus-floating-label cyrus-floating-label-float-textarea">Relações Existentes</span>
                        </div>
                    </div>
                    <div class="model" data-model="relation-full">
                        <span class="cyrus-floating-label cyrus-floating-label-float-textarea">Adicionar Relação</span>
                        <?php
                        $entity_name = str_replace("Objects\\", "", $childEntity);
                        $entity_class = "Objects\\" . $entity_name;
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
                                }
                                //echoModelFor($type, array(strtolower($entity_name) . "_update_relations", $field_name, $display_name));
                                echoModelFor(model: $type, formName: strtolower($entity_name . "_update_relations"), fieldName: $field_name, displayName: $display_name);
                            }
                        }
                        ?>
                    </div>
                    <button type = "button" class = "mt-4 float-end cyrus-btn cyrus-btn-type2" data-entity="<?php echo strtolower(str_replace("Objects\\", "", $relationEntity))?>" data-entityChild = "<?php echo strtolower($entity_name); ?>" data-relation = "<?php echo strtolower($displayName);?>" data-form = "<?php echo strtolower($entity_name) . "_update_relations";?>">Guardar Relação</button>
                </div>
            </div>


            <?php
            break;
        default:
            if(class_exists("Enumerators\\" . $model)){
                ?>

                <div class="cyrus-input-group group-input-text">
                    <div class="dropdown no-select">
                        <div class="dropdown-toggle w-100" type="button" id="dropdownMenuButton1"
                             data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown="true" data-form="<?php echo $formName; ?>"
                              data-name="<?php echo $fieldName ?>" data-selected="null"></span>
                        </div>
                        <ul class="dropdown-menu no-select " aria-labelledby="dropdownMenuButton1">
                            <?php


                            $entity_name = $model;
                            $entity_enum = "Enumerators\\" . $model;
                            $entity = new ReflectionEnum($entity_enum);
                            $cases = $entity->getCases();
                            foreach ($cases as $item) {

                                $case = $entity->getMethod("getItem")->invokeArgs(null, array($item->getBackingValue()));


                                echo '<li data-id = "' . $case->value . '">';
                                echo $case->name();
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <span class="cyrus-floating-label cyrus-floating-label-float">Verified</span>
                </div>

                <?php
            } else if(class_exists("Objects\\" . $model)){
                ?>
                <div class="cyrus-input-group group-input-text">
                    <div class="dropdown no-select">
                        <div class="dropdown-toggle w-100" type="button"
                             data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown="true" data-form="<?php echo $formName; ?>"
                              data-name="<?php echo $fieldName ?>" data-selected="null"></span>
                        </div>
                        <ul class="dropdown-menu no-select" aria-labelledby="">
                            <?php
                            $entity_name = $model;
                            $entity_class = "Objects\\" . $model;
                            $entity = new ReflectionClass($entity_class);
                            $entities = $entity->getMethod("find")->invoke(null);
                            foreach ($entities as $item) {
                                echo '<li data-id = "' . $item->getId() . '">';
                                if(strtolower($entity_name) === "language") {
                                    echo $item->getOriginalName() . " (" . $item->getCode() . ")";
                                } else if($entity->hasMethod("getName")) {
                                    echo $item->getName();
                                } else if ($entity->hasMethod("getUsername")){
                                    echo $item->getUsername();
                                } else if ($entity->hasMethod("getTitle")){
                                    echo $item->getTitle();
                                }
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <span class="cyrus-floating-label cyrus-floating-label-float"><?php echo $displayName ?></span>
                </div>

                <?php
            }
            break;
    }

}


?>