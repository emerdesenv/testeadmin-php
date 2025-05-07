<?php
    include("functions/main_config.php");
    include("components/controller.php");
    
    $page_url = getURL();

    $validation = explode("/", $_SERVER["REQUEST_URI"]);

    $uri      = end($validation);

    $uri      = $uri ? $uri : "index";
    $page_url = $page_url ? $page_url : "index";
    
    $body     = array($uri, getOS(), getBrowser());

    if(!isset($skip_validation) or !$skip_validation) {
        $return_validation = skipValidation(isset($code_activitie) ? $code_activitie : false, $page_url);
        
        $page           = $return_validation["page"];
        $info           = $return_validation["info"];
        $code_activitie = $return_validation["code_activitie"];
    }

    $is_table = vTable();
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?=getenv("APP_TITLE"); ?>">
    <link rel="icon" href="assets/images/general/favicon.png">
    <title><?=getenv("APP_TITLE"); ?></title>

    <noscript>
        <div class="alert-noscript">
            <span>JavaScript desabilitado, ative-o novamente!</span>
        </div>
    </noscript>

    <link rel="stylesheet" href="assets/css/app/style.css">
    <link rel="shortcut icon" type="image/png" href="assets/images/general/favicon.png" />

    <script>
        localStorage.setItem("idAgent", <?= $_SESSION["id_agent"]; ?>);
        localStorage.setItem("idUserSession", <?= $_SESSION["user"]["id"]; ?>);
    </script>
</head>

<body class="<?=implode(" ", $body); ?>">
    <section class="wrapper">

        <?php include "components/template/menu.php"; ?>

        <div class="content-area">
            <?php include "components/template/header.php"; ?>

            <div class="content-wrapper">
                <div class="row page-tilte">
                    <div class="col-md-auto">
                        <a href="#" class="mt-3 d-md-none float-right toggle-controls icon-show-opt"><span class="bi bi-caret-down-fill"></span></a>
                        
                        <?php if(isset($page)) { ?>
                            <h1 class="weight-300 h4 title"><?= $info["nome"]; ?></h1>
                            <?php include "components/basic/badges_header.php"; ?>
                        <?php } ?>
                    </div>

                    <div class="col controls-wrapper mt-3 mt-md-0 d-none d-md-block">
                        <div class="controls d-flex justify-content-center justify-content-md-end">
                            <?php if(vTable()) { ?>
                                <?php include "components/basic/search_table.php"; ?>
                            <?php } ?>

                            <?php if(isset(SETTINGS[$page_url])) { ?>
                                <?php include "components/basic/icons_setting.php"; ?>
                            <?php } ?>

                            <?php if(isset(REPORTS[$page_url])) { ?>
                                <?php include "components/basic/icons_report.php"; ?>
                            <?php } ?>

                            <?php if(vFilters($page_url)) { ?>
                                <div id="button_filters" class="modal-filter">
                                    <button type="button" class="btn btn-white font-size-22 py-0 px-2" data-bs-toggle="modal" data-bs-target="#filterModal" title="Filtro">
                                        <span class="bi bi-filter align-text-bottom"></span>
                                        <span class="badge bg-warning"></span>
                                    </button>

                                    <?php include "components/basic/filters_modal.php"; ?>
                                </div>
                            <?php } ?>

                            <?php if(vBttAdd()) { ?>
                                <?php if(validateAccess($page_url, "inclusao")) { ?>
                                    <button type="button" class="btn btn-danger <?= $page_url == 'index' ? 'btn-add-index' : 'btn-add-general'; ?> text-white" title="Adicionar" data-bs-target="#addGrid">Adicionar</button>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="content <?= $is_table ? "loading" : ""; ?>">

                <?php if(vFilters($page_url)) { ?>
                    <?php include "components/basic/content_header_print.php"; ?>
                <?php } ?>