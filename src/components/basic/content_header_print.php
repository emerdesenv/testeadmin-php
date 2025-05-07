<div class="report-header mb-3 d-none d-print-flex">
    <div class="justify-content-between width-badges">
        <img class="image-link" src="<?php echo getenv("LOGO_AGENT_GENERAL"); ?>" alt='Logo' height="40">
    </div>

    <div class="flex-grow-1 print-description">
        <span class="font-weight-bold">Página: </span><?=$info["nome"]; ?><br />

        <?php if(in_array("agent", FILTER_PAGES[$page_url])) { ?>
            <span class="font-weight-bold">Agenciador:</span> <span class="header-agent"><?= $_SESSION["agent"]["nome"]; ?></span>
        <?php } ?>  

        <span class="font-weight-bold">Gerado em:</span> <?=dateHourBR(date("Y-m-d H:i")); ?>

        <div style="display: none;"><span class="font-weight-bold">Busca por texto: </span><span class="search-query"></span></div>
    </div>
</div>