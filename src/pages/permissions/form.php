<?php
    include "../../functions/main_config.php";

    $id_form = isset($_GET["id"]) ? $_GET["id"] : false;
    $edit    = $data_db->loadGenericObject($id_form, "permissao");

    if($edit) {
        echo "<input type='hidden' name='id' value='{$edit["id"]}' />";
        include "tabs/unit.php"; exit();
    }
?>

<ul class="nav nav-tabs">
    <li class="nav-item tab-modal-permission">
        <a class="nav-link active" data-bs-toggle="tab" id="individual" href="#unit">
            <span class="bi bi-person-plus-fill align-text-top"></span>
            <span class="d-md-inline-block label-tab-permission">Individual<span>
        </a>
    </li>
    <li class="nav-item tab-modal-permission">
        <a class="nav-link" data-bs-toggle="tab" id="modelo" href="#model">
            <span class="bi bi-people-fill align-text-top"></span>
            <span class="d-md-inline-block label-tab-permission">Modelo<span>
        </a>
    </li>
</ul>

<br />

<div class="tab-content">
    <div id="unit" class="tab-pane active show" id="home4" role="tabpanel" aria-labelledby="individual">
        <?php include "tabs/unit.php"; ?>
    </div>

    <div id="model" class="tab-pane" id="profile4" role="tabpanel" aria-labelledby="modelo">
        <?php include "tabs/model.php"; ?>
    </div>
</div>