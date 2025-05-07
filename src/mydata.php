<?php 
    $skip_validation = true;

    include "components/main.php";

    $edit = $data_db->loadGenericObject($_SESSION["user"]["id"], "usuario", true);
?>

<h1 class="weight-300 h4 title">Meus dados</h1><br />

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="mx-5 my-4 px-4 text-center">
                    <span>
                        <?php if($_SESSION["user"]["avatar"]) { ?>
                            <img width="100" height="100" src="<?= $_SESSION["user"]["avatar"]; ?>" alt='Avatar' class="user-avatar-profile rounded-circle" />
                        <?php } else { ?>
                            <img class="user-avatar-profile rounded-circle" width="100" height="100" alt='Avatar' avatar="<?= $_SESSION["user"]["nome"]; ?>" />
                        <?php } ?>
                        
                        <input type="file" name="avatar" class="d-none" accept=".png, .jpg, .jpeg">
                    </span>
                </div>

                <div class="text-center">
                    <h5 class="weight-400">Olá, <?= $_SESSION["user"]["nome"]; ?></h5>
                    <p class=" text-muted">Código: <span class="badge bg-secondary"><?= $_SESSION["user"]["codigo"]; ?></span></p>
                    <button class="btn btn-info px-4 rounded mx-1 change-avatar text-white" title="Trocar Foto">Trocar Foto</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            
            <form action="pages/users/controller?from=mydata" method="post" class="meusdados-form">
                <input type="hidden" name="id" value="<?= $edit["id"]; ?>" />

                <div class="card-body">
                    <div class="tab-content" id="myTabContent">                                        
                        <div class="tab-pane fade show active" id="profile" aria-labelledby="profile-tab">
            
                            <div class="row">
                                <div class="mb-3 col-md-8">
                                    <label class="form-label" for="nome">Nome</label>
                                    <input required type="text" class="form-input form-control" name="nome" id="nome" maxlength="60" value="<?= $edit["nome"]; ?>">
                                </div>
                                
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="codigo">Código</label>
                                    <input required disabled type="text" class="form-input form-control" name="codigo" id="codigo" maxlength="60" value="<?= $edit["codigo"]; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-8">
                                    <label class="form-label" for="email">E-mail</label>
                                    <input required type="email" class="form-input form-control" name="email" id="email" maxlength="60" value="<?= $edit["email"]; ?>">
                                </div>
                            
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="dataNascimento">Data de Nascimento</label>
                                    <div class="input-group">
                                        <input require type="date" class="form-input form-control" name="dataNascimento" id="dataNascimento" maxlength="10" value="<?= $edit["dataNascimento"]; ?>" aria-describedby="basic-addon-calendar">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 dashed">

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="senha">Nova Senha</label>
                                    <input type="password" class="form-input form-control" name="senha" id="senha" maxlength="15" autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="senha2">Confirmação de Senha</label>
                                    <input type="password" class="form-input form-control" name="senha2" id="senha2" maxlength="15" autocomplete="off">
                                </div>
                            </div>

                            <input class="form-check-input slider-round" type="hidden" name="ativo" <?= $edit["ativo"] == "S" ? "checked='checked'" : ""; ?>><span class="slider round"></span>
                        </div>          
                    </div>       
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary text-white" title="Salvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="wrapper">
    <div class="content-area">
        <div class="content-wrapper">
            <div class="row page-tilte align-items-center">

<?php require "components/template/footer.php"; ?>

<script type="text/javascript" src="pages/users/mydatas.js"></script>

<script type="text/javascript">
    $(function() {
        <?php if(isset($_GET["msg"])) { ?>
            toastr.success("Dados alterados com sucesso.");
        <?php }?>
    });
</script>