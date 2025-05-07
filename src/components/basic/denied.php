<?php
    $skip_validation = true;
    $code_activitie  = "DASHBOARD";

    include "components/main.php";
?>

<div class="content text-center">
    <h1 class="position-denied"><span class="nav-icon bi bi-file-lock2 font-denied"></span></h1>
    <p class="lead mb-2">Você não tem permissão para acessar a página</p>
    <p class="text-muted">Você pode solicitar acesso ao administrador do sistema.<br>Para segurança, a tentativa de acesso foi registrada em nosso sistema.</p>
    <a href="/index" class="btn btn-danger text-white px-4 mt-4 rounded">Voltar para home</a>
</div>

<?php require "components/template/footer.php"; ?>