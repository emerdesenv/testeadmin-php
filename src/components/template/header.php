<header class="header sticky-top d-print-none">
    <nav class="navbar navbar-dark px-sm-4" style="background-color: #<?= getenv("COLOR"); ?> !important;">
        <a class="navbar-brand py-2 d-md-none  m-0 bi bi-list toggle-sidebar" href="#"></a>
        <ul class="navbar-nav flex-row ml-auto">
            <li class="badge-tp-user">
                <h5><span class="badge badge-outline-tp-user"><?= $_SESSION["user"]["nome"]; ?></span></h5>
            </li>

            <li class="nav-item ml-sm-3 user-logedin dropdown">
                <a href="#" id="userLogedinDropdown" data-bs-toggle="dropdown" class="nav-link weight-400 dropdown-toggle" title="Ícone Avatar">
                    <?php if($_SESSION["user"]["avatar"]) { ?>
                        <img class="rounded-circle" width="30" height="30" src="<?= $_SESSION["user"]["avatar"]; ?>" alt='Logo' />
                    <?php } else { ?>
                        <img class="rounded-circle" width="30" height="30" avatar="<?= $_SESSION["user"]["nome"]; ?>" alt='Logo' />
                    <?php } ?>
                </a>
                
                <div class="dropdown-menu dropdown-menu-right-my" aria-labelledby="userLogedinDropdown">
                    <a class="dropdown-item" href="mydata">Meus dados</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout">Sair</a>
                </div>
            </li>
        </ul>
    </nav>
</header>