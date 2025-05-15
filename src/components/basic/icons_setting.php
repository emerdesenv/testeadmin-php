<?php 
    $revalidate = isset($_GET["revalidate"]) ? true : false;

    if($revalidate) {
        session_start();
        
        require("../../functions/pages_settings.php");
        require("../../functions/generic_controller.php");

        $page_url = $_GET["url"];
    }

    $agent_id = ($page_url == "teste" and isset($_SESSION["agent_client"])) ? $_SESSION["agent_client"]["id"] : false;
?>

<?php if(count(SETTINGS[$page_url]) > 0) { ?>
    <span class="dropdown dropdown-configs">
        <button type="button" id="drop_setting" data-bs-toggle="dropdown" class="table-setting font-size-22 btn btn-white py-0 px-2" aria-expanded="false" title="Configurações">
            <span class="bi bi-gear align-text-bottom"></span>
        </button>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="drop_setting">
            <?php 
            $cont_permission = 0;

            foreach(SETTINGS[$page_url] as $setting) { ?>
                <?php if(in_array($setting, SKIP_VALIDATION_ACTION) or validatePermissionsIcon($setting, $agent_id)) { $cont_permission++; ?>
                    <a class="dropdown-item" href="#" data-type="<?= $setting; ?>"><?=I18n[$setting]; ?></a>
                <?php } ?>
            <?php } ?>

            <?php if($cont_permission == 0) { ?>
                <a class="dropdown-item not-options-select" href="#">Nenhuma Permissão</a>
            <?php } ?>
        </div>
    </span>
<?php } ?>