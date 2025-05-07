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
    
    $status_client = $data_client["ativo"] == "N" ? true : false;
    $uf            = $uf ? "/".$uf["siglaUF"] : "";
    $city          = $data_client["cidade"] ? $data_client["cidade"].$uf : "";
?>

<div class="row py-4">
    <div class="col-md-12">
        <h5 class="weight-400">Dados Pessoais</h5>

        <br>

        <div class="row mb-2">
            <div class="col-md-3 copy-text-info">
                <dt class="font-weight-bold">Código</dt>
                <dd>
                    <?= $data_client["id"]; ?>
                </dd>
            </div>

            <div class="col-md-3 copy-text-info">
                <dt class="font-weight-bold">CPF</dt>
                <dd>
                    <?= maskGeneric("###.###.###-##", $data_client["CPF"]); ?>
                </dd>
            </div>

            <div class="col-md-4 copy-text-info">
                <dt class="font-weight-bold">Nome</dt>
                <dd>
                    <?= $data_client["nome"]; ?>
                </dd>
            </div>

            <div class="col-md-2 copy-text-info">
                <dt class="font-weight-bold">Sexo</dt>
                <dd>
                    <?= SEXO[$data_client["sexo"]]; ?>
                </dd>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-3 copy-text-info">
                <dt class="font-weight-bold">Data Nascimento</dt>
                <dd>
                    <?= dateCompleteBR($data_client["dataNascimento"]); ?>
                </dd>
            </div>

            <div class="col-md-3 copy-text-info">
                <dt class="font-weight-bold">Telefone</dt>
                <dd>
                    <?= $data_client["telefone"]; ?>
                </dd>
            </div>

            <div class="col-md-4 copy-text-info">
                <dt class="font-weight-bold">Celular</dt>
                <dd>
                    <?= $data_client["celular"]; ?>
                </dd>
            </div>

            <div class="col-md-2 copy-text-info">
                <dt class="font-weight-bold">Ativo</dt>
                <dd>
                    <?= ($data_client["ativo"] and $data_client["ativo"] == "S") ? "Sim" : "Não"; ?>
                </dd>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-3 copy-text-info">
                <dt class="font-weight-bold">Número Benefício</dt>
                <dd>
                    <?= $data_client["numeroBeneficio"]; ?>
                </dd>
            </div>

            <div class="col-md-3 copy-text-info">
                <dt class="font-weight-bold">Residência Própria</dt>
                <dd>
                    <?= ($data_client["residenciaPropria"] and $data_client["residenciaPropria"] == "S") ? "Sim" : "Não"; ?>
                </dd>
            </div>

            
            <div class="col-md-4 copy-text-info">
                <dt class="font-weight-bold">Email</dt>
                <dd>
                    <?= $data_client["email"]; ?>
                </dd>
            </div> 
        </div>

        <hr class="dashed">

        <h5 class="weight-400">Localização</h5>
        <br>

        <div class="row mb-2">
            <div class="col-md-3 copy-text-info">
                <dt class="font-weight-bold">Cidade/UF</dt>
                <dd>
                    <?= $city; ?>
                </dd>
            </div>
        </div>

        <hr class="dashed">
        <h5 class="weight-400">Últimas Ações</h5>
        <br>
        
        <div class="row mb-2">
            <div class="col-md-3">
                <dt class="font-weight-bold">Data de Cadastro</dt>
                <dd>
                    <?= dateHourBR($data_client["dataHoraCadastro"]); ?>
                </dd>
            </div>
            <div class="col-md-3">
                <dt class="font-weight-bold">Última Atualização</dt>
                <dd>
                    <?= (($data_client["dataHoraAtualizacao"]) == null) ? "00/00/0000 00:00" : dateHourBR($data_client["dataHoraAtualizacao"]) ?>
                </dd>
            </div>
        </div>
    </div>
</div>