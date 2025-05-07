<?php
    $page_url = getURL();
?>


<div class="justify-content-between width-header">
    <img class="image-link" src="<?php echo getenv("LOGO_AGENT_GENERAL"); ?>" alt='Logo' height="40">
</div>

<div class="flex-grow-1 text-left">
    <span class="font-weight-bold"><?= $report_name; ?></span><br />
    <span class="font-weight-bold">Agenciador:</span> <?= $_SESSION["agent"]["nome"]; ?> <br />

    <span class="badge bg-primary" id="badge-user-dash" style="display:<?=isset($_SESSION['user_client']) ? '' : 'none'?>;">
        <span class="font-weight-bold">Usuário:</span>
        <span class="label-badge-user"><?= $_SESSION["user_client"]["nome"] == 'ADM' ? strtoupper($nome_usuario) : ucfirst(strtolower($_SESSION["user_client"]["nome"])); ?></span>
    </span>
    
    <span class="badge bg-primary" id="badge-period-dash" style="display:<?=isset($_SESSION['schedule_period']) ? '' : 'none'?>;">
        <span class="font-weight-bold">Período:</span>
        <span class="label-badge-period"><?= isset($_SESSION["schedule_period"]) ? $_SESSION["schedule_period"] : ""; ?></span>
    </span>
        
    <span class="badge bg-primary" id="badge_status_schedule" style="display:<?= isset($_SESSION['status_schedule_served']) && $_SESSION['status_schedule_served'] == 'S' || isset($_SESSION['status_schedule_pending']) && $_SESSION['status_schedule_pending'] == 'N' ? '' : 'none'; ?>;">
        <span class="font-weight-bold">Status:</span>
        <span class="badge-status-schedule">
            <?php 
                if(isset($_SESSION['status_schedule_served']) && $_SESSION['status_schedule_served'] == 'S' && isset($_SESSION['status_schedule_pending']) && $_SESSION['status_schedule_pending'] == 'N') {
                    echo 'Todos';
                } else if(isset($_SESSION['status_schedule_pending']) && $_SESSION['status_schedule_pending'] == 'N') {
                    echo 'Pendente';
                } else {
                    echo 'Atendido';
                }
            ?>
        </span>
    </span>
</div>
