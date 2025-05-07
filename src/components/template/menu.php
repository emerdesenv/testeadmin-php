<aside class="sidebar sidebar-light d-print-none">
    <nav class="navbar navbar-dark" style="background-color: #<?= getenv("COLOR"); ?> !important;">
        <a class="navbar-brand m-0 py-0 brand-title" href="index"><img class="image-link" src="<?php echo getenv("LOGO_AGENT_INTERNAL"); ?>" alt='Logo' height="46" /></a>
        <a class="navbar-brand py-2 bi bi-list font-size-20 toggle-sidebar" title="Menu" href="#"></a>
    </nav>

    <nav class="navigation">
        <ul>
            <?php

                $data_menus = $ComponentService->listMenu();

                while($row_menu = $data_db->getLine($data_menus)) { ?>
                    <li class="<?= (isset($info["idMenu"]) and $info["idMenu"] == $row_menu["id"]) ? "active open" : ""; ?>">
                        <a href="<?= $row_menu["link"]; ?>" title="<?= $row_menu["descricao"]; ?>" <?= (isset($info["idMenu"]) and $info["idMenu"] == $row_menu["id"]) ? "class='menu-selected' style='box-shadow:inset 3px 0px 0px #006d00 !important;'" : ""; ?>>
                            <span class="nav-icon bi font-size-18 <?= $row_menu["icon"]; ?>"></span>
                            <?= $row_menu["descricao"]; ?><span class="toogle-sub-nav bi bi-chevron-right"></span>
                        </a>

                        <?php
                            $data_submenus = $ComponentService->listSubMenu($row_menu["id"]);
                            $cont = 0;

                            while($row_submenu = $data_db->getLine($data_submenus)) {
                                
                                if(validateAccess($row_submenu["codigo"])) {
                                    if($cont == 0) { echo '<ul class="sub-nav">'; } ?>

                                    <li class="<?= (isset($info["link"]) and $info["link"] == $row_submenu["link"]) ? "active" : ""; ?>">
                                        <a href="<?= $row_submenu["link"]; ?>" title="<?= $row_submenu["nome"]; ?>"><?= $row_submenu["nome"]; ?></a>
                                    </li> 
                                    
                                    <?php $cont++; 
                                }
                            }
                            
                            if($cont > 0) { echo "</ul>"; }
                        ?>
                    </li>
            <?php } ?>
        </ul>
    </nav>
</aside>