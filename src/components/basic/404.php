<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="<?=getenv("APP_TITLE"); ?>">
        <link rel="icon" href="../../assets/images/general/favicon.png">
        <title><?= getenv("APP_TITLE"); ?></title>

        <noscript>
            <div class="alert-noscript">
                <span>JavaScript desabilitado, ative-o novamente!</span>
            </div>
        </noscript>

        <link rel="stylesheet" href="../../assets/css/app/style.css">
        <link rel="shortcut icon" type="image/png" href="../../assets/images/general/favicon.png" />
    </head>

    <body>
        <div class="content text-center">
            <h1 class="position-404"><span class="badge badge-pill bg-secondary">4</span>&nbsp;<span class="badge badge-pill bg-secondary">0</span>&nbsp;<span class="badge badge-pill bg-secondary">4</span></h1>
            <p class="lead mb-2">Ops!!!</p>
            <p class="text-muted">Página não encontrada.</p>
            <a href="/index" class="btn btn-danger text-white px-4 mt-4 rounded">Voltar para home</a>
        </div>

        <div class="content-wrapper">
            <footer class="footer d-print-none">
                <p class="text-muted m-0">
                <small class="float-right"><a target="_blank" class="text-muted" href="//objetiva.digital">Desenvolvido por Objetiva Digital</a> © <?= date("Y"); ?></small>
                </p>
            </footer>
        </div>
    </body>
</html>