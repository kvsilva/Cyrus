<?php
function echoModelFor(String $model, array $data = array()){
    switch($model){
        case "STRING":?>
            <div class = "cyrus-input-group group-input-text">
                <input type = "text" class = "cyrus-minimal group-input" value='' onkeyup="this.setAttribute('value', this.value);" autocomplete="new-password">
                <span class="cyrus-floating-label"><?php echo $data[0]?></span>
            </div>
        <?php
            break;



    }
}


?>