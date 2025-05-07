<?php
    if (isset($_GET["page"]) and $_GET["page"] != "clients") {
        include "../../../functions/main_config.php";
        include "../service.php";
        $page_url = getURL();
    }

    $id_client = isset($_GET["id_client"]) ? $_GET["id_client"] : $id_client;
    
    $ClientService = new ClientService;

    $data_client   = $data_db->loadGenericObject($id_client, "cliente");
    $uf            = is_int($data_client["idUf"]) ? $ClientService->getUfById($data_client["idUf"]) : false;
    
    $status_client = $data_client["ativo"] == "S" ? true : false;
    $uf            = $uf ? "/".$uf["siglaUF"] : "";
    $city          = $data_client["cidade"] ? $data_client["cidade"].$uf : "";
?>

<div class="col-md-12 px-3 py-1">
    <div class="content-title">Perfil do cliente</div>
    <div class="row mb-1 mt-4">
        <div class="col-6 copy-text-info">
            <dt class="font-weight-bold">Código de Usuário</dt>
            <dd><?= $data_client["id"]; ?></dd>
        </div>

        <div class="col-6 copy-text-info">
            <div class="status-content <?= $status_client ? 'active' : 'inactive' ?> float-end">
                <dt class="font-weight-bold"><span class="status-dot <?= $status_client ? 'active' : 'inactive' ?>"></span><?= $status_client ? 'Ativo' : 'Inativo' ?></dt>
            </div>
        </div>
    </div>

    <hr class="dashed">

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">CPF</dt>
            <dd><?= maskGeneric("###.###.###-##", $data_client["CPF"]); ?></dd>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Nome</dt>
            <dd><?= $data_client["nome"]; ?></dd>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Sexo</dt>
            <dd><?= SEXO[$data_client["sexo"]]; ?></dd>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Data de Nascimento</dt>
            <dd><?= $data_client["dataNascimento"]; ?></dd>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Telefone</dt>
            <div class="d-flex">
                <div class="col-4">
                    <dd><?= $data_client["telefone"] ? $data_client["telefone"] : "Sem informação"; ?></dd>
                </div>
            </div>
        </div>

        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Celular</dt>
            <div class="d-flex">
                <div class="col-4">
                    <dd><?= $data_client["celular"] ? $data_client["celular"] : "Sem informação"; ?></dd>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Número Benefício</dt>
            <dd><?= $data_client["numeroBeneficio"]; ?></dd>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Residência Própria</dt>
            <dd><?= $data_client["residenciaPropria"] == 'S' ? 'Sim' : 'Não'; ?></dd>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Email</dt>
            <dd><?= $data_client["email"]; ?></dd>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-12 copy-text-info">
            <dt class="font-weight-bold">Cidade/UF</dt>
            <dd><?= $city; ?></dd>
        </div>
    </div>

    <hr class="dashed">

    <div class="row mb-1">
        <div class="col-12 copy-text-info d-flex">
            <div class="col-md-6">
                <dt class="font-weight-bold">Data de Cadastro</dt>
                <dd><?= ($data_client["dataHoraCadastro"]) == null ? "00/00/0000 00:00" : dateHourBR($data_client["dataHoraCadastro"]); ?></dd>
            </div>
            
            <div class="col-md-6">
                <dt class="font-weight-bold">Última Atualização</dt>
                <dd><?= ($data_client["dataHoraAtualizacao"]) == null ? "00/00/0000 00:00" : dateHourBR($data_client["dataHoraAtualizacao"]); ?></dd>
            </div>
        </div>
    </div>
</div>