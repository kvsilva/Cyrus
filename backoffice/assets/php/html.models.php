<?php

use Objects\Language;

function echoModelFor(String $model, array $data = array()){
    switch(strtolower($model)){
        case "string":
            // 0: Nome do Formulário
            // 1: Nome do Campo no Formulário
            // 2: Nome do Label
            ?>
            <div class = "cyrus-input-group group-input-text">
                <input type = "text" class = "cyrus-minimal group-input" data-form="<?php echo $data[0];?>" data-name = "<?php echo $data[1]?>" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                <span class="cyrus-floating-label"><?php echo $data[2]?></span>
            </div>
        <?php
            break;
        case "password":
            // 0: Nome do Formulário
            // 1: Nome do Campo no Formulário
            // 2: Nome do Label
            ?>
            <div class = "cyrus-input-group group-input-text">
                <input type = "password" class = "cyrus-minimal group-input" data-form="<?php echo $data[0];?>" data-name = "<?php echo $data[1]?>" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                <span class="cyrus-floating-label"><?php echo $data[2]?></span>
            </div>
            <?php
            break;
        case "text":
            ?>
            <div class = "cyrus-input-group group-input-text">
                <textarea class = "cyrus-minimal group-input" data-form="<?php echo $data[0];?>" data-name = "<?php echo $data[1]?>" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password"></textarea>
                <span class="cyrus-floating-label"><?php echo $data[2]?></span>
            </div>
            <?php
            break;
        case "date":
            $date = new DateTime();
            $date = $date->format("Y-m-d");
            ?>
            <div class = "cyrus-input-group group-input-datepicker">
                <input type = "date" class = "cyrus-minimal group-input" value = "<?php echo $date;?>" data-form="<?php echo $data[0];?>" data-name = "<?php echo $data[1]?>" min="1900-12-31" max = "9999-01-01" autocomplete="new-password">
                <span class="cyrus-floating-label"><?php echo $data[2]?></span>
            </div>
            <?php
            break;
        case "resource":
            ?>
            <div class="group-section-subitem" data-form="<?php echo $data[0];?>" data-isMultiple = "true" data-name = "<?php echo $data[1]?>" data-selectedSection = "1">
                <div class="group-section-subitem-title"><?php echo $data[2]?></div>

                <!-- Upload File -->
                <div class="group-section-subitem-items" data-section = "1">
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "title" data-service = "Resources" data-action = "uploadFile">
                        <span class="cyrus-floating-label">Title</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "description" data-service = "Resources" data-action = "uploadFile">
                        <span class="cyrus-floating-label">Description</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="file" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "file" data-service = "Resources" data-action = "uploadFile">
                        <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                    </div>
                </div>

                <!-- Register File -->
                <div class="group-section-subitem-items cyrus-item-hidden" data-section = "2">
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "title" data-service = "Resources" data-action = "registerFile">
                        <span class="cyrus-floating-label">Title</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password">
                        autocomplete="new-password"
                        data-subitem = "description" data-service = "Resources" data-action = "registerFile">
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "extension" data-service = "Resources" data-action = "registerFile">
                        <span class="cyrus-floating-label">Extension</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "title" data-service = "Resources" data-action = "registerFile">
                        <span class="cyrus-floating-label">Title</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="text" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "url" data-service = "Resources" data-action = "registerFile">
                        <span class="cyrus-floating-label">URL</span>
                    </div>
                </div>

                <!-- Upload Anime Video -->
                <div class="group-section-subitem-items cyrus-item-hidden" data-section = "3">
                    <div class="cyrus-input-group group-input-text">
                        <input type="number" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "videoID" data-service = "Resources" data-action = "uploadAnimeVideo">
                        <span class="cyrus-floating-label">Video ID</span>
                    </div>
                    <div class="cyrus-input-group group-input-text">
                        <input type="file" class="cyrus-minimal group-input" value=''
                               onkeyup="this.setAttribute('value', this.value);"
                               autocomplete="new-password"
                               data-subitem = "file" data-service = "Resources" data-action = "uploadAnimeVideo">
                        <span class="cyrus-floating-label cyrus-floating-label-float">File</span>
                    </div>
                </div>

            </div>

            <?php
            break;
        case "sex":?>
            <div class = "cyrus-input-group group-input-text">
                <div class="dropdown no-select">
                    <div class="dropdown-toggle w-100" type="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown = "true" data-form="<?php echo $data[0];?>" data-name = "<?php echo $data[1]?>" data-selected="null"></span>
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
                <span class="cyrus-floating-label cyrus-floating-label-float"><?php echo $data[2]; ?></span>
            </div>

            <?php
            break;
        case "verification":?>
            <div class = "cyrus-input-group group-input-text">
                <div class="dropdown no-select">
                    <div class="dropdown-toggle w-100" type="button" id="dropdownMenuButton1"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown = "true" data-form="<?php echo $data[0];?>" data-name = "<?php echo $data[1]?>" data-selected="null"></span>
                    </div>
                    <ul class="dropdown-menu no-select " aria-labelledby="dropdownMenuButton1">
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

            <?php
            break;
        case "language":?>
            <div class = "cyrus-input-group group-input-text">
                <div class="dropdown no-select">
                    <div class="dropdown-toggle w-100" type="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-isDropdown = "true" data-form="<?php echo $data[0];?>" data-name = "<?php echo $data[1]?>" data-selected="null"></span>
                    </div>
                    <ul class="dropdown-menu no-select" aria-labelledby="">
                        <?php
                        $languages = Language::find();
                        foreach($languages as $item){
                            echo '<li data-id = "' . $item->getId() . '">' . $item->getOriginalName() . ' (' . $item->getCode() . ')' . '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <span class="cyrus-floating-label cyrus-floating-label-float"><?php echo $data[2]?></span>
            </div>

            <?php
            break;


    }
}


?>