<?php 
    $revalidate = isset($_GET["revalidate"]) ? true : false;

    if($revalidate) {
        session_start();
        
        require("../../functions/pages_settings.php");
        require("../../functions/generic_controller.php");

        $page_url = $_GET["url"];
    }

    $agent_id = false; 
?>

<?php if(count(REPORTS[$page_url]) > 0) { ?>
    <span class="dropdown dropdown-reports">
        <button type="button" id="drop_report" data-bs-toggle="dropdown" class="table-report btn font-size-20 btn-white py-0 px-2" aria-expanded="false" title="Relatórios">
            <span class="bi bi-printer-fill align-text-bottom"></span>
        </button>

        <div class="dropdown-menu dropdown-menu-end icons-reports" aria-labelledby="drop_report">
            <?php 
            $cont_permission = 0;

            foreach(REPORTS[$page_url] as $report) { ?>
                <?php if($report != "print") { ?>
                    <?php if(in_array($report, SKIP_VALIDATION_ACTION) or validatePermissionsIcon($report, $agent_id)) { $cont_permission++; ?>
                        <a class="dropdown-item" href="#" data-type="<?= $report; ?>"><?= I18n[$report]; ?></a>
                    <?php } ?>
            <?php } } ?>

            <?php if($cont_permission == 0) { ?>
                <a class="dropdown-item not-options-select" href="#">Nenhuma Permissão</a>
            <?php } ?>

            <?php if(count(REPORTS[$page_url]) > 1 and in_array("print", REPORTS[$page_url])) { ?>
                <div class="dropdown-divider"></div>
            <?php } ?>

            <?php if(in_array("print", REPORTS[$page_url])) { ?>
                <a class="dropdown-item" href="#" data-type="print">Imprimir Página</a>
            <?php } ?>
        </div>
    </span>
<?php } ?>