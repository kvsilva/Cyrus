<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');
use Functions\Utils;
?>
<html lang="pt_PT">
<head>
    <?php
    include Utils::getDependencies("Cyrus", "head", true);
    echo getHead(" - Attack on Titan");
    ?>
    <link href="<?php echo Utils::getDependencies("Personal", "css")?>" rel="stylesheet">
    <script src="<?php echo Utils::getDependencies("Personal")?>"></script>
</head>
<body>
<?php
include(Utils::getDependencies("Cyrus", "header", true));
?>
<div id="content">

    <!-- CONTENT HERE -->

</div>
<?php
include(Utils::getDependencies("Cyrus", "footer", true));
?>
</body>
</html>