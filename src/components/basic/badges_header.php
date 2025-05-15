<?php if(isset(FILTER_PAGES[$page_url])) { ?>
    <?php if(in_array("agent", FILTER_PAGES[$page_url])) { ?>
        <span id="badge-agent" class="badge bg-primary">Agenciador: <span class="label-badge-agent"><?=  $_SESSION["agent"]["nome"]; ?></span></span>
    <?php } ?>

    <?php if(in_array("teste", FILTER_PAGES[$page_url])) { ?>
        <span id="badge-user-dash" style="display:<?=isset($_SESSION['user_client']) ? '' : 'none'?>;" class="badge bg-primary">Usuário: <span class="label-badge-user"><?= isset($_SESSION["user_client"]) ? $_SESSION["user_client"]["nome"] : ""; ?></span></span>

        <span id="badge_status_client" class="badge bg-primary" style="display:<?= isset($_SESSION['status_client_active']) && $_SESSION['status_client_active'] == 'S' || isset($_SESSION['status_client_inactive']) && $_SESSION['status_client_inactive'] == 'N' ? '' : 'none'; ?>;">
            <span class="font-weight-bold">Status:</span>
            <span class="badge-status-client">
                <?php 
                    if(isset($_SESSION['status_client_active']) && $_SESSION['status_client_active'] == 'S' && isset($_SESSION['status_client_inactive']) && $_SESSION['status_client_inactive'] == 'N') {
                        echo 'Todos';
                    } else if(isset($_SESSION['status_client_inactive']) && $_SESSION['status_client_inactive'] == 'N') {
                        echo 'Inativo';
                    } else {
                        echo 'Ativo';
                    }
                ?>
            </span>
        </span>
    <?php } ?>
<?php } ?>