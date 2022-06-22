<?php
use Functions\Utils;

function getHead($pageTitle): string
{
    return '
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- JQuery -->
    <script src="' . Utils::getDependencies("JQuery") . '"</script>
    <!-- Popper -->
    <script src="' . Utils::getDependencies("Popper") . '"></script>
    <!-- Bootstrap -->
    <link href="' . Utils::getDependencies("Bootstrap", "css") . '" rel="stylesheet">
    <script src="' . Utils::getDependencies("Bootstrap") . '"></script>
    <!-- Font Awesome -->
    <link href="' . Utils::getDependencies("FontAwesome", "css") . '" rel="stylesheet">
    <!-- Cyrus -->
    <script type = "module" src="' . Utils::getDependencies("Cyrus") . '"></script>
    <script type = "module" src="' . Utils::getDependencies("Cyrus", "models") . '"></script>
    <script type = "module" src="' . Utils::getDependencies("Cyrus", "routing") . '"></script>
    <script type = "module" src="' . Utils::getDependencies("Cyrus", "request") . '"></script>
    <link href="' . Utils::getDependencies("Cyrus", "css") . '" rel = "stylesheet">
    <!-- Title -->
    <title>Cyrus' . $pageTitle . '</title>
    <!-- Logo -->
    <link rel = "icon" href ="' . Utils::getDependencies("Cyrus", "icon") . '" type = "image/x-icon">
    <!-- Current Page Imports -->
    ';
}
?>