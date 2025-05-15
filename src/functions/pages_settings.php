<?php
    // Telas que possuem listagem
    CONST TABLE_LIST_PAGES = [
        "users", "permissions", "activities", "agents", "texts"
    ];

    // Telas que é permitido adicionar
    CONST BT_ADD = [
        "users", "permissions", "activities", "agents", "texts", "index"
    ];

    // Telas que possuem algum tipo de filtro
    CONST FILTER_PAGES = [
        ""      => ["agent"],
        "index" => ["agent"]
    ];

    //Status para badge do Uusário
    CONST TYPE_USER = array(
        "0" => ["badge" => "badge bg-secondary text-white", "texto" => "Admin"],
        "1" => ["badge" => "badge bg-info text-white", "texto" => "Normal"]
    );

    CONST TYPE_SERVED = array(
        "S" => ["badge" => "badge bg-success text-white", "texto" => "Sim"],
        "N" => ["badge" => "badge bg-secondary text-white", "texto" => "Não"]
    );

    //Nome das ações que aparecem nos icones (Impressões e Configurações)
    CONST I18n = array(
        "" => ""
    );

    //Paginas que vão utilizar gráficos
    CONST GRAPHICS = ["index", ""];

    // Telas que possuem algum tipo de filtro
    CONST FILTERS = [
        ""
    ];

    // Padrão de extensões aceitas
    CONST ALLOWED_FILETYPES = [
        "default" => array("png", "jpg", "jpeg", "pdf")
    ];

    CONST MODAL = ["index"];
    CONST TOTALS = [];

    //Telas que possuem o Dropdown de Impressões
    CONST REPORTS = [
        "" => [""]
    ];

    //Ações que não precisam de permissão (Somente nome das funções)
    CONST SKIP_VALIDATION_ACTION = [""];

    //Telas que possuem o Dropdown de Configurações
    CONST SETTINGS = [ 
        "clients"  => []
    ];

    //ENUM para os tipos de sexo
    CONST SEXO = [
        "M" => "Masculino",
        "F" => "Feminino",
        "O" => "Outro",
        "P" => "Prefiro não dizer"
    ];
?>