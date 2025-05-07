<?php
    if(!isset($data_db)) {
        require "functions/data.php";
        
        $data_db = new Data;
        $data_db->conectDataBase(MYSQL_DATABASE);
    }

    require "functions/generic_controller.php";
?>

<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Sistema de Gestão de Contatos - Admin">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= getenv("APP_TITLE"); ?></title>

        <noscript>
            <div class="alert-noscript">
                <span>JavaScript desabilitado, ative-o novamente!</span>
            </div>
        </noscript>

        <link rel="stylesheet" href="assets/css/app/bootstrap.css">
        <link rel="stylesheet" href="assets/css/app/login.css">
        <link rel="shortcut icon" type="image/png" href="assets/images/general/favicon.png" />

        <script src="node_modules/jquery/dist/jquery.min.js"></script>

        <link rel="stylesheet" type="text/css" href="assets/css/select2/select2.min.css" />
        <script src="assets/js/select2/select2.min.js"></script>

        <script src="node_modules/toastr/build/toastr.min.js"></script>
        <link rel="stylesheet" type="text/css" href="node_modules/toastr/build/toastr.min.css" />
    </head>

    <body class="login-page">
        <section class="wrapper">
            <div class="login">
                <div class="image-placeholder"></div>
                
                <div class="form">
                    <div class="text-center mb-4"><img width="50%" src="<?php echo getenv("LOGO_AGENT_GENERAL"); ?>" alt='Logo' /></div>

                        <form method="POST" action="<?= "auth/ajax_login"; ?>" class="element-form form-login" id="form-login" novalidate enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label" for="selector_agent">Agenciador</label>
                                <select name="idAgenciador" id="selector_agent" class="form-control select-agent" required>
                                    <option value="">Selecione o Agenciador</option>
                                    <?php
                                        $sql_data_agents = $GenericService->getAllAgents();

                                        while($row = $data_db->getLine($sql_data_agents)) { ?>
                                            <option <?= (isset($_COOKIE["agenciador"]) and $_COOKIE["agenciador"] == $row["id"]) ? "selected='selected'" : ""; ?> value="<?= $row["id"]; ?>"><?= $row["nome"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="user">Usuário</label>
                                <input type="text" name="user" class="form-control" id="user" aria-describedby="emailHelp" placeholder="Usuário" value="<?= isset($_COOKIE["usuario"]) ? $_COOKIE["usuario"] : ""; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="pass">Senha</label>
                                <input type="password" class="form-control" id="pass" placeholder="Digite sua senha" name="pass" required autocomplete="off">
                            </div>
                            
                            <div class="mb-3 form-check">
                                <?php $checked = isset($_COOKIE["lembrar"]) and $_COOKIE["lembrar"] == "false" ? false : true; ?>

                                <input type="checkbox" class="form-check-input" id="rememberme" value="true" name="lembrar" <?= $checked ? "checked='checked'" : "" ?>>
                                <label class="form-check-label" for="rememberme">Lembrar-me</label>
                            </div>

                            <button type="submit" class="btn mt-4 btn-primary btn-block btn-enter" id="btn_enter">Entrar</button>
                            
                            <hr>
                            
                            <footer class="footer d-print-none">
                                <p class="text-muted m-0">
                                    <small class="float-right"><a target="_blank" class="text-muted" href="https://evolvecap.com.br/">Desenvolvido por Evolve Tecnologia</a> © <?= date("Y"); ?></small>
                                </p>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

<script type="text/javascript" src="auth/actions.js"></script>